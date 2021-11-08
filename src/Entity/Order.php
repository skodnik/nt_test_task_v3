<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity @Table(name="orders")
 */
class Order
{
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer", name="id")
     */
    private int $id;

    /**
     * @Column(type="integer", name="event_id", unique=false, options={"unsigned":true})
     */
    private int $eventId;

    /**
     * @Column(type="datetime", name="event_date")
     */
    private DateTime $eventDate;

    /**
     * @Column(type="integer", name="ticket_adult_price", unique=false, options={"unsigned":true})
     */
    private int $ticketAdultPrice;

    /**
     * @Column(type="integer", name="ticket_adult_quantity", unique=false, options={"unsigned":true})
     */
    private int $ticketAdultQuantity;

    /**
     * @Column(type="integer", name="ticket_kid_price", unique=false, options={"unsigned":true})
     */
    private int $ticketKidPrice;

    /**
     * @Column(type="integer", name="ticket_kid_quantity", unique=false, options={"unsigned":true})
     */
    private int $ticketKidQuantity;

    /**
     * @Column(type="string", name="barcode", unique=true, nullable=false, columnDefinition="VARCHAR(120)")
     */
    private string $barcode;

    /**
     * @Column(type="integer", name="user_id")
     */
    private int $userId;

    /**
     * @Column(type="integer", name="equal_price", unique=false, options={"unsigned":true})
     */
    private int $equalPrice;

    /**
     * @Column(type="datetime", name="created", nullable=true)
     */
    private DateTime $created;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id ?? throw new \RuntimeException('id is unset');
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId ?? throw new \RuntimeException('eventId is unset');
    }

    /**
     * @param int $eventId
     * @return Order
     */
    public function setEventId(int $eventId): Order
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEventDate(): DateTime
    {
        return $this->eventDate ?? throw new \RuntimeException('eventDate is unset');
    }

    /**
     * @param DateTime $eventDate
     * @return Order
     */
    public function setEventDate(DateTime $eventDate): Order
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getTicketAdultPrice(): int
    {
        return $this->ticketAdultPrice ?? throw new \RuntimeException('ticketAdultPrice is unset');
    }

    /**
     * @param int $ticketAdultPrice
     * @return Order
     */
    public function setTicketAdultPrice(int $ticketAdultPrice): Order
    {
        $this->ticketAdultPrice = $ticketAdultPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getTicketAdultQuantity(): int
    {
        return $this->ticketAdultQuantity ?? throw new \RuntimeException('ticketAdultQuantity is unset');
    }

    /**
     * @param int $ticketAdultQuantity
     * @return Order
     */
    public function setTicketAdultQuantity(int $ticketAdultQuantity): Order
    {
        $this->ticketAdultQuantity = $ticketAdultQuantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getTicketKidPrice(): int
    {
        return $this->ticketKidPrice ?? throw new \RuntimeException('ticketKidPrice is unset');
    }

    /**
     * @param int $ticketKidPrice
     * @return Order
     */
    public function setTicketKidPrice(int $ticketKidPrice): Order
    {
        $this->ticketKidPrice = $ticketKidPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getTicketKidQuantity(): int
    {
        return $this->ticketKidQuantity ?? throw new \RuntimeException('ticketKidQuantity is unset');
    }

    /**
     * @param int $ticketKidQuantity
     * @return Order
     */
    public function setTicketKidQuantity(int $ticketKidQuantity): Order
    {
        $this->ticketKidQuantity = $ticketKidQuantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getBarcode(): string
    {
        return $this->barcode ?? throw new \RuntimeException('barcode is unset');
    }

    /**
     * @param BarcodeGenerator $barcode
     * @return Order
     */
    public function setBarcode(BarcodeGenerator $barcode): Order
    {
        $this->barcode = $barcode->getNew($this->getUserId());

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId ?? throw new \RuntimeException('userId is unset');
    }

    /**
     * @param int $userId
     * @return Order
     */
    public function setUserId(int $userId): Order
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return int
     */
    public function getEqualPrice(): int
    {
        return $this->equalPrice ?? throw new \RuntimeException('equalPrice is unset');
    }

    /**
     * @return Order
     */
    public function setEqualPrice(): Order
    {
        $this->equalPrice = ($this->getTicketAdultQuantity() * $this->getTicketAdultPrice()) +
            ($this->getTicketKidQuantity() * $this->getTicketKidPrice());

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @return Order
     */
    public function setCreated(): Order
    {
        $this->created = new DateTime('now');

        return $this;
    }
}