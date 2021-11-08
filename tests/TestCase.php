<?php

declare(strict_types=1);

namespace Tests;

use App\Entity\BarcodeGenerator;
use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Faker\Factory;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Возвращает объект управления жизненным циклом сущностей Doctrine ORM.
     *
     * @throws ORMException
     */
    public static function getEntityManager(): EntityManager
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/../../src'], true);

        $conn = [
            'driver' => getenv('DB_DRIVER'),
            'path' => getenv('DB_PATH'),
        ];

        return EntityManager::create($conn, $config);
    }

    /**
     * Возвращает случайного пользователя.
     *
     * @return User
     */
    public function makeRandomUser(): User
    {
        $faker = Factory::create();

        $name = $faker->firstName;

        return (new User())
            ->setName($name)
            ->setCreated();
    }

    /**
     * Возвращает случайный заказ.
     *
     * @return Order
     */
    public static function makeRandomOrder(): Order
    {
        $faker = Factory::create();

        return (new Order())
            ->setUserId($faker->numberBetween(100, 900))
            ->setEventId($faker->numberBetween(1, 900))
            ->setEventDate($faker->dateTime)
            ->setTicketAdultPrice($faker->numberBetween(500, 900))
            ->setTicketAdultQuantity($faker->numberBetween(0, 10))
            ->setTicketKidPrice($faker->numberBetween(300, 500))
            ->setTicketKidQuantity($faker->numberBetween(0, 10))
            ->setEqualPrice();
    }
}