<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Entity\User;
use Tests\TestCase;

class UserExceptionsTest extends TestCase
{
    /**
     * Готовит массив доступных методом класса User и сообщений исключений.
     *
     * @return array
     */
    public function dataProvider_userMethods(): array
    {
        return [
            'getId' => ['getId', 'id is unset'],
            'getName' => ['getName', 'name is unset'],
            'getCreated' => ['getCreated', 'created is unset'],
        ];
    }

    /**
     * Проверка исключений и их сообщений.
     *
     * @dataProvider dataProvider_userMethods
     */
    public function test_exception($method, $expectedMessage)
    {
        $order = new User();

        $actual = '';

        try {
            $order->$method();
        } catch (\RuntimeException $exception) {
            $actual = $exception->getMessage();
        }

        $this->assertEquals($expectedMessage, $actual);
    }
}