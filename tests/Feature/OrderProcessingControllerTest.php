<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Controller\OrderProcessingController;
use App\Entity\Order;
use App\Entity\OrderProcessingReport;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Tests\TestCase;

class OrderProcessingControllerTest extends TestCase
{
    /**
     * Подготовка базы данных перед запуском тестов текущего класса.
     *
     * @throws ToolsException
     * @throws ORMException
     */
    public static function setUpBeforeClass(): void
    {
        $entityManager = self::getEntityManager();

        $schema = new SchemaTool($entityManager);
        $classes = [
            $entityManager->getClassMetadata(Order::class),
        ];
        $schema->dropSchema($classes);
        $schema->createSchema($classes);
    }

    /**
     * Проверка обработчика заказов. Формируется $ordersQuantity произвольного содержания заказов,
     * каждый из которых передается в обработчик. Контролируется тип возвращаемого объекта или исключения.
     * Сравнивается количество обработанных заказов.
     *
     * @throws ORMException
     */
    public function test_orderProcessing()
    {
        $ordersQuantity = 500;
        $reports = [];
        $storedCounter = 0;
        $exceptions = 0;

        for ($iterator = 1; $iterator <= $ordersQuantity; $iterator++) {
            $orderProcessingController = new OrderProcessingController();

            try {
                /**
                 * Успешная генерация уникального баркода.
                 */
                $report = $orderProcessingController(self::getEntityManager(), self::makeRandomOrder());
                $reports[] = $report;

                $this->assertInstanceOf(OrderProcessingReport::class, $report);

                $report->getStatus() === 'stored' ? $storedCounter++ : false;
            } catch (\RuntimeException $exception) {
                /**
                 * Исчерпан лимит попыток перегенерации баркода.
                 */
                $this->assertTrue(true);

                $exceptions++;
            }
        }

        $this->assertEquals($ordersQuantity, count($reports) + $exceptions);

        return $storedCounter;
    }

    /**
     * Проверка количества сохраненных в базе данных сервиса заказов и сравнение с
     * количеством сохраненных заказов согласно отчету.
     *
     * @depends test_orderProcessing
     * @param int $storedCounter
     * @throws ORMException
     */
    public function test_countStoredOrders(int $storedCounter)
    {
        $orders = self::getEntityManager()->getRepository(Order::class)->findAll();

        $this->assertCount($storedCounter, $orders);
    }

    /**
     * Очистка таблицы от внесенных изменений.
     *
     * @throws ORMException
     */
    public static function tearDownAfterClass(): void
    {
        $entityManager = self::getEntityManager();

        (new SchemaTool($entityManager))
            ->dropSchema([
                $entityManager->getClassMetadata(Order::class),
            ]);
    }
}