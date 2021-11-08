<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Repository\Api;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * Проверка бронирования заказа.
     */
    public function test_bookOrder(): void
    {
        $responseBooking = Api::bookOrder(self::makeRandomOrder());

        $this->assertIsArray($responseBooking);
    }

    /**
     * Проверка подтверждения заказа.
     */
    public function test_approveOrder(): void
    {
        $responseApprove = Api::approveOrder('4551636043593409');

        $this->assertIsArray($responseApprove);
    }
}