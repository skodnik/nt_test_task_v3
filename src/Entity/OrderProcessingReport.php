<?php

declare(strict_types=1);

namespace App\Entity;

class OrderProcessingReport
{
    /**
     * Отчет об этапах процессинга.
     *
     * @var array
     */
    private array $report;

    /**
     * Финальное состояние заказа.
     *
     * @var string
     */
    private string $status;

    /**
     * Добавляет запись в массив отчета.
     *
     * @param int $iteration
     * @param int $step
     * @param string $title
     * @param string $message
     * @return $this
     */
    public function push(int $iteration, int $step, string $title, string $message): OrderProcessingReport
    {
        $this->report[$iteration][$step][$title] = $message;

        return $this;
    }

    /**
     * Возвращает отчет в формате массива.
     *
     * @return array
     */
    public function getArray(): array
    {
        if (!isset($this->report)) {
            throw new \RuntimeException('Empty report');
        }

        return $this->report;
    }

    /**
     * Возвращает отчет в формате json.
     *
     * @param bool $pretty
     * @param bool $unescaped
     * @return string
     */
    public function getJson(bool $pretty = false, bool $unescaped = false): string
    {
        $flags = ($pretty ? JSON_PRETTY_PRINT : 0) | ($unescaped ? JSON_UNESCAPED_UNICODE : 0);

        return json_encode($this->getArray(), $flags);
    }

    /**
     * Устанавливает значение статуса заказа - сохранен в базе данных.
     *
     * @return $this
     */
    public function setStatusStored(): static
    {
        $this->status = 'stored';

        return $this;
    }

    /**
     * Устанавливает значение статуса заказа - удален из базы данных.
     *
     * @return $this
     */
    public function setStatusRemoved(): static
    {
        $this->status = 'removed';

        return $this;
    }


    public function getStatus(): string
    {
        return $this->status ?? throw new \RuntimeException('Status is unset');
    }
}