<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;

/**
 * Класс осуществляющий взаимодействие с API.
 * В текущей реализации - имитатор.
 */
class Api
{
    /**
     * Метод заглушка имитирующий вызов API для бронирования заказа.
     *
     * @param Order $order
     * @return string[]
     */
    public static function bookOrder(Order $order): array
    {
        $responses = [
            ['message' => 'order successfully booked'],
            ['error' => 'barcode already exists'],
        ];

        /**
         * Случайным образом возвращает один из возможных ответов.
         */
        return $responses[rand(0, count($responses) - 1)];
    }

    /**
     * Метод заглушка имитирующий вызов API для подтверждения брони.
     *
     * @param string $barcode
     * @return string[]
     */
    public static function approveOrder(string $barcode): array
    {
        $responses = [
            ['message' => 'order successfully approved'],
            ['error' => 'event cancelled'],
            ['error' => 'no tickets'],
            ['error' => 'no seats'],
            ['error' => 'fan removed'],
        ];

        /**
         * Случайным образом возвращает один из возможных ответов.
         */
        return $responses[rand(0, count($responses) - 1)];
    }
}