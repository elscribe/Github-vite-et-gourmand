<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

/**
 * Gestion simple des plats reutilisables dans les menus.
 */
final class DishModel extends BaseModel
{
    /**
     * @return list<array<string, mixed>>
     */
    public function findAll(): array
    {
        $statement = $this->pdo()->query('SELECT * FROM plats ORDER BY type_plat ASC, titre_plat ASC');

        return $statement->fetchAll();
    }

    /**
     * @param array{titre_plat: string, type_plat: string, description: string} $data
     */
    public function create(array $data): int
    {
        $statement = $this->pdo()->prepare(
            'INSERT INTO plats (titre_plat, type_plat, description) VALUES (:titre_plat, :type_plat, :description)'
        );
        $statement->execute($data);

        return (int) $this->pdo()->lastInsertId();
    }

    /**
     * @param array{titre_plat: string, type_plat: string, description: string} $data
     */
    public function update(int $dishId, array $data): bool
    {
        $statement = $this->pdo()->prepare(
            'UPDATE plats SET titre_plat = :titre_plat, type_plat = :type_plat, description = :description WHERE id_plat = :id_plat'
        );
        $statement->execute($data + ['id_plat' => $dishId]);

        return $statement->rowCount() === 1;
    }
}
