<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\BarcodeGenerator;
use App\Entity\Order;
use App\Entity\OrderProcessingReport;
use App\Repository\Api;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class OrderProcessingController
{
    /**
     * Объект отчет.
     *
     * @var OrderProcessingReport
     */
    private OrderProcessingReport $report;

    /**
     * Переменная итератор.
     */
    private int $iteration = 1;

    public function __construct()
    {
        $this->report = new OrderProcessingReport();
    }

    public function __invoke(EntityManager $entityManager, Order $order): OrderProcessingReport
    {
        /**
         * Создание баркода для заказа.
         */
        $order->setBarcode(new BarcodeGenerator());

        /**
         * Цикл обработки заказа формирующий проверяющий уникальность баркода
         * в базе данных сервиса, и во внешнем сервисе вызываемом по API.
         */
        while (true) {
            try {
                if (1 === $this->iteration) {
                    /**
                     * Заказ не существует в базе, т.е. выполняется первый проход.
                     */
                    $this->report->push(
                        $this->iteration,
                        1,
                        'Title',
                        'Preserve order in database with barcode: ' . $order->getBarcode()
                    );
                } else {
                    /**
                     * Заказ ранее сохранен в базу, но требуется обновление баркода.
                     */
                    $order->setBarcode(new BarcodeGenerator());
                    $this->report->push(
                        $this->iteration,
                        1,
                        'Title',
                        'Update order in database with barcode: ' . $order->getBarcode()
                    );
                }

                /**
                 * Сохранение/обновление заказа в базе данных сервиса.
                 */
                $entityManager->persist($order);
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $exception) {
                /**
                 * Инкрементальной увеличение маркера итератора.
                 */
                $this->iteration++;

                /**
                 * Переход на следующую итерацию цикла, т.к. переданный баркод существует в базе данных сервиса.
                 */
                continue;
            }
            $this->report->push($this->iteration, 1, 'Result', 'orderId: ' . $order->getId());

            /**
             * Запрос брони заказа.
             */
            $isReservationSuccessful = $this->requestReservation($order);

            /**
             * Если заказ успешно забронирован - выход из цикла, в противном случае - новый проход цикла.
             */
            if ($isReservationSuccessful) {
                /**
                 * Заказ успешно зарезервирован.
                 */
                break;
            } else {
                /**
                 * Условие контролирующее количество попыток получения подтверждения заказа.
                 */
                if ($this->iteration >= 5) {
                    /**
                     * Удаление заказа из базы данных сервиса как неподтвержденного.
                     */
                    $entityManager->remove($order);
                    $entityManager->flush();

                    $this->report->setStatusRemoved();

                    throw new \RuntimeException('Failed barcode validation in external API');
                }

                /**
                 * Инкрементальное увеличение маркера итератора.
                 */
                $this->iteration++;
            }
        }

        /**
         * Запрос на подтверждение брони.
         */
        $isBookingSuccessful = $this->requestForApproving($order, $this->iteration);

        /**
         * Работа с заказом в базе данных.
         */
        $this->report->push($this->iteration, 4, 'Title', 'Processing order in database');
        /**
         * Условие контролирующее успешность подтверждения брони.
         */
        if ($isBookingSuccessful) {
            /**
             * Маркировка заказа в базе данных сервиса как успешно подтвержденного.
             */
            $entityManager->persist($order->setCreated());
            $this->report->push(
                $this->iteration,
                4,
                'Result',
                'Order stored successfully with barcode: ' . $order->getBarcode()
            );
            $this->report->setStatusStored();
        } else {
            /**
             * Удаление заказа из базы данных сервиса как неподтвержденного.
             */
            $entityManager->remove($order);
            $this->report->push($this->iteration, 4, 'Result', 'Removed');
            $this->report->setStatusRemoved();
        }

        $entityManager->flush();

        return $this->report;
    }

    /**
     * Запрос брони заказа.
     *
     * @param Order $order
     * @return bool
     */
    private function requestReservation(Order $order): bool
    {
        $this->report->push($this->iteration, 2, 'Title', 'Make booking API request');
        $responseBooking = Api::bookOrder($order);
        $this->report->push($this->iteration, 2, 'Result', json_encode($responseBooking));

        return key_exists('message', $responseBooking);
    }

    /**
     * Запрос на подтверждение брони.
     *
     * @param Order $order
     * @return bool
     */
    private function requestForApproving(Order $order): bool
    {
        $this->report->push($this->iteration, 3, 'Title', 'Make approve API request');
        $responseApprove = Api::approveOrder($order->getBarcode());
        $this->report->push($this->iteration, 3, 'Result', json_encode($responseApprove));

        return key_exists('message', $responseApprove);
    }
}