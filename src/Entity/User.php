<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity @Table(name="users")
 */
class User
{
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer", name="id")
     */
    protected int $id;

    /**
     * @Column(type="string", name="name", unique=false)
     * @var string
     */
    protected string $name;

    /**
     * @Column(type="datetime", name="created")
     */
    private DateTime $created;

    public function getId(): int
    {
        return $this->id ?? throw new \RuntimeException('id is unset');
    }

    public function getName(): string
    {
        return $this->name ?? throw new \RuntimeException('name is unset');
    }

    public function setName($name): User
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created ?? throw new \RuntimeException('created is unset');
    }

    /**
     * @return User
     */
    public function setCreated(): User
    {
        $this->created = new DateTime('now');

        return $this;
    }
}