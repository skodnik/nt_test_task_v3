<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Tests\TestCase;

class UserTest extends TestCase
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
            $entityManager->getClassMetadata(User::class),
        ];
        $schema->dropSchema($classes);
        $schema->createSchema($classes);
    }

    /**
     * Заполняет таблицу пользователей и проверяет количество сохраненных элементов.
     *
     * @throws OptimisticLockException|ORMException
     */
    public function test_fillUsersTable()
    {
        $entityManager = self::getEntityManager();

        $usersQuantity = 50;

        for ($iterator = 1; $iterator <= $usersQuantity; $iterator++) {
            $entityManager->persist(self::makeRandomUser());

            try {
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $exception) {
                $entityManager = self::getEntityManager();
                $iterator--;

                continue;
            }
        }

        $users = $entityManager->getRepository(User::class)->findAll();

        $this->assertCount($usersQuantity, $users);
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
                $entityManager->getClassMetadata(User::class),
            ]);
    }
}