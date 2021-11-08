<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Helper\HelperSet;

require_once __DIR__ . '/bootstrap.php';

/** @var EntityManager $entityManager */
return new HelperSet([
    'em' => new EntityManagerHelper($entityManager)
]);