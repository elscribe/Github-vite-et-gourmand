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
        $sql = <<<SQL
            SELECT
                p.id_plat,
                p.titre_plat,
                p.type_plat,
                p.description,
                GROUP_CONCAT(a.libelle ORDER BY a.libelle ASC SEPARATOR ', ') AS allergenes
            FROM plats p
            LEFT JOIN plat_allergenes pa ON pa.id_plat = p.id_plat
            LEFT JOIN allergenes a ON a.id_allergene = pa.id_allergene
            GROUP BY p.id_plat, p.titre_plat, p.type_plat, p.description
            ORDER BY p.type_plat ASC, p.titre_plat ASC
        SQL;

        $statement = $this->pdo()->query($sql);

        return $statement->fetchAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findAllergens(): array
    {
        $statement = $this->pdo()->query('SELECT id_allergene, libelle FROM allergenes ORDER BY libelle ASC');

        return $statement->fetchAll();
    }

    /**
     * @return array<int, list<int>>
     */
    public function findAllergenIdsByDish(): array
    {
        $statement = $this->pdo()->query(
            'SELECT id_plat, id_allergene FROM plat_allergenes ORDER BY id_plat ASC, id_allergene ASC'
        );

        $allergenIdsByDish = [];

        foreach ($statement->fetchAll() as $row) {
            $dishId = (int) $row['id_plat'];
            $allergenIdsByDish[$dishId] ??= [];
            $allergenIdsByDish[$dishId][] = (int) $row['id_allergene'];
        }

        return $allergenIdsByDish;
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

    /**
     * @param list<int> $allergenIds
     */
    public function syncAllergens(int $dishId, array $allergenIds): void
    {
        $allergenIds = $this->normalizeIds($allergenIds);

        $this->pdo()->beginTransaction();

        try {
            $delete = $this->pdo()->prepare('DELETE FROM plat_allergenes WHERE id_plat = :dish_id');
            $delete->execute(['dish_id' => $dishId]);

            $insert = $this->pdo()->prepare(
                'INSERT INTO plat_allergenes (id_plat, id_allergene) VALUES (:dish_id, :allergen_id)'
            );

            foreach ($allergenIds as $allergenId) {
                $insert->execute([
                    'dish_id' => $dishId,
                    'allergen_id' => $allergenId,
                ]);
            }

            $this->pdo()->commit();
        } catch (\Throwable $exception) {
            $this->pdo()->rollBack();
            throw $exception;
        }
    }

    /**
     * @param list<int> $ids
     *
     * @return list<int>
     */
    private function normalizeIds(array $ids): array
    {
        $normalized = [];

        foreach ($ids as $id) {
            $id = (int) $id;

            if ($id > 0 && !in_array($id, $normalized, true)) {
                $normalized[] = $id;
            }
        }

        return $normalized;
    }
}
