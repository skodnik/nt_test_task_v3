<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Entity\BarcodeGenerator;
use Tests\TestCase;

class BarcodeTest extends TestCase
{
    /**
     * Генерация баркода и проверка его структуры.
     */
    public function test_getNewBarcode(): void
    {
        $userId = 451;
        $barcode = (new BarcodeGenerator)->getNew($userId);

        $this->assertStringStartsWith((string)$userId, $barcode);
    }
}