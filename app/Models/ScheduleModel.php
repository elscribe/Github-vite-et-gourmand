<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

/**
 * Acces aux horaires affiches par l'application.
 */
final class ScheduleModel extends BaseModel
{
    /**
     * @return list<array<string, mixed>>
     */
    public function findAll(): array
    {
        $statement = $this->pdo()->query('SELECT * FROM horaires ORDER BY jour_semaine ASC');

        return $statement->fetchAll();
    }

    /**
     * @param list<array{jour_semaine: int, ouverture_matin: string|null, fermeture_matin: string|null, ouverture_apres_midi: string|null, fermeture_apres_midi: string|null, ferme: int}> $rows
     */
    public function updateAll(array $rows): void
    {
        $statement = $this->pdo()->prepare(
            'UPDATE horaires
             SET ouverture_matin = :ouverture_matin,
                 fermeture_matin = :fermeture_matin,
                 ouverture_apres_midi = :ouverture_apres_midi,
                 fermeture_apres_midi = :fermeture_apres_midi,
                 ferme = :ferme
             WHERE jour_semaine = :jour_semaine'
        );

        foreach ($rows as $row) {
            $statement->execute($row);
        }
    }
}
