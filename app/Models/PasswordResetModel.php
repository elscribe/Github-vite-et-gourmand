<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;
use App\Core\Security;

/**
 * Gestion des jetons de reinitialisation de mot de passe.
 */
final class PasswordResetModel extends BaseModel
{
    public function createForUser(int $userId): string
    {
        $token = Security::randomToken(32);
        $hash = Security::hashToken($token);

        $statement = $this->pdo()->prepare(
            'INSERT INTO password_resets (id_utilisateur, token_hash, expires_at) VALUES (:user_id, :token_hash, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 HOUR))'
        );
        $statement->execute([
            'user_id' => $userId,
            'token_hash' => $hash,
        ]);

        return $token;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findValidByToken(string $token): ?array
    {
        $statement = $this->pdo()->prepare(
            'SELECT * FROM password_resets WHERE token_hash = :token_hash AND used_at IS NULL AND expires_at > CURRENT_TIMESTAMP LIMIT 1'
        );
        $statement->execute(['token_hash' => Security::hashToken($token)]);

        $reset = $statement->fetch();

        return $reset === false ? null : $reset;
    }

    public function markUsed(int $resetId): bool
    {
        $statement = $this->pdo()->prepare(
            'UPDATE password_resets SET used_at = CURRENT_TIMESTAMP WHERE id_reset = :reset_id'
        );

        return $statement->execute(['reset_id' => $resetId]);
    }
}
