<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Entity\Order;
use Tests\TestCase;

class OrderExceptionsTest extends TestCase
{
    /**
     * Готовит массив доступных методом класса Order и сообщений исключений.
     *
     * @return array
     */
    public function dataProvider_orderMethods(): array
    {
        return [
            'getId' => ['getId', 'id is unset'],
            'getEventId' => ['getEventId', 'eventId is unset'],
            'getEventDate' => ['getEventDate', 'eventDate is unset'],
            'getTicketAdultPrice' => ['getTicketAdultPrice', 'ticketAdultPrice is unset'],
            'getTicketAdultQuantity' => ['getTicketAdultQuantity', 'ticketAdultQuantity is unset'],
            'getTicketKidPrice' => ['getTicketKidPrice', 'ticketKidPrice is unset'],
            'getTicketKidQuantity' => ['getTicketKidQuantity', 'ticketKidQuantity is unset'],
            'getBarcode' => ['getBarcode', 'barcode is unset'],
            'getUserId' => ['getUserId', 'userId is unset'],
            'getEqualPrice' => ['getEqualPrice', 'equalPrice is unset'],
        ];
    }

    /**
     * Проверка исключений и их сообщений.
     *
     * @dataProvider dataProvider_orderMethods
     */
    public function test_exception($method, $expectedMessage)
    {
        $order = new Order();

        $actual = '';

        try {
            $order->$method();
        } catch (\RuntimeException $exception) {
            $actual = $exception->getMessage();
        }

        $this->assertEquals($expectedMessage, $actual);
    }
}