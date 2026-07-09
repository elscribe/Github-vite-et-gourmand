<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Classe parente pour les futurs modeles SQL.
 */
abstract class BaseModel
{
    public function __construct(private ?Database $database = null)
    {
    }

    protected function pdo(): PDO
    {
        $this->database ??= new Database();

        return $this->database->getConnection();
    }
}
