<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Entity\OrderProcessingReport;
use PHPUnit\Framework\TestCase;

class OrderProcessingReportTest extends TestCase
{
    /**
     * Проверка создания объекта отчета.
     *
     * @return OrderProcessingReport
     */
    public function test_createReport(): OrderProcessingReport
    {
        $report = new OrderProcessingReport();

        $this->assertInstanceOf(OrderProcessingReport::class, $report);

        return $report;
    }

    /**
     * Проверка исключения при попытке получения пустого отчета.
     *
     * @depends test_createReport
     */
    public function test_getException(OrderProcessingReport $report)
    {
        $this->expectException(\RuntimeException::class);

        $report->getArray();
    }

    /**
     * Проверка внесения записи в отчет.
     *
     * @depends test_createReport
     */
    public function test_push(OrderProcessingReport $report): OrderProcessingReport
    {
        $report->push(1,1,'test title', 'test message');

        $this->assertInstanceOf(OrderProcessingReport::class, $report);

        return $report;
    }

    /**
     * Проверка получения массива содержащего отчет.
     *
     * @depends test_push
     */
    public function test_getArray(OrderProcessingReport $report): OrderProcessingReport
    {
        $expected = [
            1 => [
                1 => ['test title' => 'test message']
            ]
        ];

        $this->assertEquals($expected, $report->getArray());

        return $report;
    }

    /**
     * Проверка корректности установки статуса stored.
     */
    public function test_setStatusStored()
    {
        $report = new OrderProcessingReport();
        $report->setStatusStored();

        $this->assertEquals('stored', $report->getStatus());
    }

    /**
     * Проверка корректности установки статуса removed.
     */
    public function test_setStatusRemoved()
    {
        $report = new OrderProcessingReport();
        $report->setStatusRemoved();

        $this->assertEquals('removed', $report->getStatus());
    }

    /**
     * Проверка получения json строки содержащей отчет.
     *
     * @depends test_getArray
     */
    public function test_getJson(OrderProcessingReport $report)
    {
        $expected = '{"1":{"1":{"test title":"test message"}}}';
        $actual = $report->getJson();

        $this->assertJson($actual);
        $this->assertEquals($expected, $actual);
    }
}