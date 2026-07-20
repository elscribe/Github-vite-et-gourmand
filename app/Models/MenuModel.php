<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

/**
 * Acces aux donnees publiques du catalogue de menus.
 */
final class MenuModel extends BaseModel
{
    /**
     * @return list<array<string, mixed>>
     */
    public function findAllForAdmin(): array
    {
        $sql = <<<SQL
            SELECT
                m.*,
                r.libelle AS regime,
                t.libelle AS theme,
                (
                    SELECT COUNT(*)
                    FROM menu_plats mp_count
                    WHERE mp_count.id_menu = m.id_menu
                ) AS plats_count,
                (
                    SELECT COUNT(*)
                    FROM menu_images mi_count
                    WHERE mi_count.id_menu = m.id_menu
                ) AS images_count
            FROM menus m
            INNER JOIN regimes r ON r.id_regime = m.id_regime
            INNER JOIN themes t ON t.id_theme = m.id_theme
            ORDER BY m.actif DESC, m.titre ASC
        SQL;

        $statement = $this->pdo()->query($sql);

        return $statement->fetchAll();
    }

    /**
     * @return array<int, list<int>>
     */
    public function findDishIdsByMenu(): array
    {
        $statement = $this->pdo()->query(
            'SELECT id_menu, id_plat FROM menu_plats ORDER BY id_menu ASC, position ASC, id_plat ASC'
        );

        $dishIdsByMenu = [];

        foreach ($statement->fetchAll() as $row) {
            $menuId = (int) $row['id_menu'];
            $dishIdsByMenu[$menuId] ??= [];
            $dishIdsByMenu[$menuId][] = (int) $row['id_plat'];
        }

        return $dishIdsByMenu;
    }

    /**
     * @param array{theme?: int, regime?: int, max_price?: float, people?: int} $filters
     *
     * @return list<array<string, mixed>>
     */
    public function findActiveMenus(array $filters = []): array
    {
        $conditions = ['m.actif = 1'];
        $parameters = [];

        if (!empty($filters['theme'])) {
            $conditions[] = 'm.id_theme = :theme';
            $parameters['theme'] = $filters['theme'];
        }

        if (!empty($filters['regime'])) {
            $conditions[] = 'm.id_regime = :regime';
            $parameters['regime'] = $filters['regime'];
        }

        if (!empty($filters['max_price'])) {
            $conditions[] = 'm.prix_minimum <= :max_price';
            $parameters['max_price'] = $filters['max_price'];
        }

        if (!empty($filters['people'])) {
            $conditions[] = 'm.nombre_personnes_minimum <= :people';
            $parameters['people'] = $filters['people'];
        }

        $whereClause = implode(' AND ', $conditions);

        $sql = <<<SQL
            SELECT
                m.id_menu,
                m.id_regime,
                m.id_theme,
                m.titre,
                m.description,
                m.nombre_personnes_minimum,
                m.prix_minimum,
                m.stock_disponible,
                r.libelle AS regime,
                t.libelle AS theme
            FROM menus m
            INNER JOIN regimes r ON r.id_regime = m.id_regime
            INNER JOIN themes t ON t.id_theme = m.id_theme
            WHERE {$whereClause}
            ORDER BY m.titre ASC
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findThemes(): array
    {
        $statement = $this->pdo()->query(
            'SELECT id_theme, libelle FROM themes ORDER BY libelle ASC'
        );

        return $statement->fetchAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findRegimes(): array
    {
        $statement = $this->pdo()->query(
            'SELECT id_regime, libelle FROM regimes ORDER BY libelle ASC'
        );

        return $statement->fetchAll();
    }
    /**
     * @return array<string, mixed>|null
     */
    public function findActiveMenuById(int $id): ?array
    {
        $sql = <<<SQL
            SELECT
                m.id_menu,
                m.id_regime,
                m.id_theme,
                m.titre,
                m.description,
                m.conditions,
                m.nombre_personnes_minimum,
                m.prix_minimum,
                m.stock_disponible,
                r.libelle AS regime,
                t.libelle AS theme
            FROM menus m
            INNER JOIN regimes r ON r.id_regime = m.id_regime
            INNER JOIN themes t ON t.id_theme = m.id_theme
            WHERE m.actif = 1
              AND m.id_menu = :id
            LIMIT 1
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(['id' => $id]);

        $menu = $statement->fetch();

        return $menu === false ? null : $menu;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findImagesByMenuId(int $menuId): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT url, texte_alternatif FROM menu_images WHERE id_menu = :menu_id ORDER BY position ASC, id_image ASC'
        );
        $statement->execute(['menu_id' => $menuId]);

        return $statement->fetchAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findDishesByMenuId(int $menuId): array
    {
        $sql = <<<SQL
            SELECT
                p.id_plat,
                p.titre_plat,
                p.type_plat,
                p.description
            FROM menu_plats mp
            INNER JOIN plats p ON p.id_plat = mp.id_plat
            WHERE mp.id_menu = :menu_id
            ORDER BY mp.position ASC, p.titre_plat ASC
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(['menu_id' => $menuId]);

        return $statement->fetchAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findAllergensByMenuId(int $menuId): array
    {
        $sql = <<<SQL
            SELECT DISTINCT
                a.id_allergene,
                a.libelle
            FROM menu_plats mp
            INNER JOIN plat_allergenes pa ON pa.id_plat = mp.id_plat
            INNER JOIN allergenes a ON a.id_allergene = pa.id_allergene
            WHERE mp.id_menu = :menu_id
            ORDER BY a.libelle ASC
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(['menu_id' => $menuId]);

        return $statement->fetchAll();
    }

    /**
     * @param array{id_regime: int, id_theme: int, titre: string, description: string, conditions: string, nombre_personnes_minimum: int, prix_minimum: float, stock_disponible: int, actif: int} $data
     */
    public function create(array $data): int
    {
        $statement = $this->pdo()->prepare(
            'INSERT INTO menus (id_regime, id_theme, titre, description, conditions, nombre_personnes_minimum, prix_minimum, stock_disponible, actif)
             VALUES (:id_regime, :id_theme, :titre, :description, :conditions, :nombre_personnes_minimum, :prix_minimum, :stock_disponible, :actif)'
        );
        $statement->execute($data);

        return (int) $this->pdo()->lastInsertId();
    }

    /**
     * @param array{id_regime: int, id_theme: int, titre: string, description: string, conditions: string, nombre_personnes_minimum: int, prix_minimum: float, stock_disponible: int, actif: int} $data
     */
    public function updateBasic(int $menuId, array $data): bool
    {
        $statement = $this->pdo()->prepare(
            'UPDATE menus
             SET id_regime = :id_regime,
                 id_theme = :id_theme,
                 titre = :titre,
                 description = :description,
                 conditions = :conditions,
                 nombre_personnes_minimum = :nombre_personnes_minimum,
                 prix_minimum = :prix_minimum,
                 stock_disponible = :stock_disponible,
                 actif = :actif,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id_menu = :id_menu'
        );
        $statement->execute($data + ['id_menu' => $menuId]);

        return $statement->rowCount() === 1;
    }

    /**
     * @param list<int> $menuIds
     */
    public function updateActiveSelection(array $menuIds): void
    {
        $menuIds = $this->normalizeIds($menuIds);

        $this->pdo()->beginTransaction();

        try {
            $this->pdo()->exec('UPDATE menus SET actif = 0, updated_at = CURRENT_TIMESTAMP');

            if ($menuIds !== []) {
                $placeholders = implode(', ', array_fill(0, count($menuIds), '?'));
                $statement = $this->pdo()->prepare(
                    "UPDATE menus SET actif = 1, updated_at = CURRENT_TIMESTAMP WHERE id_menu IN ({$placeholders})"
                );
                $statement->execute($menuIds);
            }

            $this->pdo()->commit();
        } catch (\Throwable $exception) {
            $this->pdo()->rollBack();
            throw $exception;
        }
    }

    /**
     * @param list<int> $dishIds
     */
    public function syncDishes(int $menuId, array $dishIds): void
    {
        $dishIds = $this->normalizeIds($dishIds);

        $this->pdo()->beginTransaction();

        try {
            $delete = $this->pdo()->prepare('DELETE FROM menu_plats WHERE id_menu = :menu_id');
            $delete->execute(['menu_id' => $menuId]);

            $insert = $this->pdo()->prepare(
                'INSERT INTO menu_plats (id_menu, id_plat, position) VALUES (:menu_id, :dish_id, :position)'
            );

            foreach ($dishIds as $position => $dishId) {
                $insert->execute([
                    'menu_id' => $menuId,
                    'dish_id' => $dishId,
                    'position' => $position + 1,
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
