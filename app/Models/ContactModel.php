<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

/**
 * Acces aux messages envoyes depuis la page contact.
 */
final class ContactModel extends BaseModel
{
    public function createMessage(string $title, string $email, string $description): void
    {
        $sql = <<<SQL
            INSERT INTO contact_messages (titre, email, description)
            VALUES (:titre, :email, :description)
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute([
            'titre' => $title,
            'email' => $email,
            'description' => $description,
        ]);
    }
}
