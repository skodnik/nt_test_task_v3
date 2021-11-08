<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . '/vendor/autoload.php';

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/src'], $isDevMode);

$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/data/database/database.sqlite',
);

try {
    $entityManager = EntityManager::create($conn, $config);
} catch (\Exception $exception) {
    dd($exception);
}
