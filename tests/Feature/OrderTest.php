<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Entity\Order;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Tests\TestCase;

class OrderTest extends TestCase
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
     * Заполняет таблицу заказов и проверяет количество сохраненных элементов.
     *
     * @throws OptimisticLockException|ORMException
     */
    public function test_fillOrdersTable()
    {
        $entityManager = self::getEntityManager();

        $ordersQuantity = 50;

        for ($iterator = 1; $iterator <= $ordersQuantity; $iterator++) {
            $entityManager->persist(self::makeRandomOrder());

            try {
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $exception) {
                $entityManager = self::getEntityManager();
                $iterator--;

                continue;
            }
        }

        $orders = $entityManager->getRepository(Order::class)->findAll();

        $this->assertCount($ordersQuantity, $orders);
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