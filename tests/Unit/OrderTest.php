<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Entity\BarcodeGenerator;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * Проверяет корректность заполнения заказа.
     */
    public function test_orderGetters()
    {
        $order = self::makeRandomOrder();

        $ticketAdultPrice = $order->getTicketAdultPrice();
        $ticketAdultQuantity = $order->getTicketAdultQuantity();
        $ticketKidPrice = $order->getTicketKidPrice();
        $ticketKidQuantity = $order->getTicketKidQuantity();

        $order->setCreated();
        $order->setBarcode(new BarcodeGenerator());

        $equalPrice = ($ticketAdultQuantity * $ticketAdultPrice) + ($ticketKidQuantity * $ticketKidPrice);

        $this->assertInstanceOf(\App\Entity\Order::class, $order);
        $this->assertIsInt($order->getUserId());
        $this->assertIsInt($ticketAdultPrice);
        $this->assertIsInt($ticketAdultQuantity);
        $this->assertIsInt($ticketKidPrice);
        $this->assertIsInt($ticketKidQuantity);
        $this->assertEquals($equalPrice, $order->getEqualPrice());
        $this->assertInstanceOf(\DateTime::class, $order->getEventDate());
        $this->assertInstanceOf(\DateTime::class, $order->getCreated());
        $this->assertIsString($order->getBarcode());
    }
}