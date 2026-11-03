<?php

declare(strict_types=1);

/**
 * Amorce des tests PHPUnit.
 *
 * Les variables de connexion sont lues depuis .env, mais les tests
 * d'integration remplacent DB_NAME par une base temporaire `vg_test_*`
 * creee puis supprimee par Tests\Support\TestDatabase.
 */

require dirname(__DIR__) . '/vendor/autoload.php';

App\Core\Env::load(dirname(__DIR__) . '/.env');
date_default_timezone_set('Europe/Paris');
