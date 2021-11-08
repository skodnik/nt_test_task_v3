<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Класс модели баркод.
 */
class BarcodeGenerator
{
    /**
     * Создание баркода.
     *
     * @param int $prefix
     * @return string
     */
    public function getNew(int $prefix): string
    {
        /**
         * Формирование баркода.
         */
        return $prefix . time() . rand(100, 999);
    }
}