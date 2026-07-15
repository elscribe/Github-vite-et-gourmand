<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

/**
 * Acces aux comptes utilisateurs, employes et administrateurs.
 */
final class UserModel extends BaseModel
{
    /**
     * @return array<string, mixed>|null
     */
    public function findByEmail(string $email): ?array
    {
        $sql = <<<SQL
            SELECT
                u.id_utilisateur,
                u.id_role,
                u.email,
                u.password_hash,
                u.nom,
                u.prenom,
                u.telephone,
                u.adresse_postale,
                u.ville,
                u.pays,
                u.canal_contact_prefere,
                u.actif,
                r.libelle AS role
            FROM utilisateurs u
            INNER JOIN roles r ON r.id_role = u.id_role
            WHERE u.email = :email
            LIMIT 1
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(['email' => $email]);

        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $userId): ?array
    {
        $sql = <<<SQL
            SELECT
                u.id_utilisateur,
                u.id_role,
                u.email,
                u.nom,
                u.prenom,
                u.telephone,
                u.adresse_postale,
                u.ville,
                u.pays,
                u.canal_contact_prefere,
                u.actif,
                r.libelle AS role
            FROM utilisateurs u
            INNER JOIN roles r ON r.id_role = u.id_role
            WHERE u.id_utilisateur = :user_id
            LIMIT 1
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(['user_id' => $userId]);

        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    /**
     * @param array{email: string, password: string, nom: string, prenom: string, telephone: string, adresse_postale: string, ville: string, pays: string} $data
     */
    public function createCustomer(array $data): int
    {
        $roleId = $this->customerRoleId();

        $sql = <<<SQL
            INSERT INTO utilisateurs (
                id_role,
                email,
                password_hash,
                nom,
                prenom,
                telephone,
                adresse_postale,
                ville,
                pays
            ) VALUES (
                :id_role,
                :email,
                :password_hash,
                :nom,
                :prenom,
                :telephone,
                :adresse_postale,
                :ville,
                :pays
            )
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute([
            'id_role' => $roleId,
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'],
            'adresse_postale' => $data['adresse_postale'],
            'ville' => $data['ville'],
            'pays' => $data['pays'],
        ]);

        return (int) $this->pdo()->lastInsertId();
    }

    /**
     * @param array{nom: string, prenom: string, telephone: string, adresse_postale: string, ville: string, pays: string, canal_contact_prefere: string} $data
     */
    public function updateProfile(int $userId, array $data): bool
    {
        $sql = <<<SQL
            UPDATE utilisateurs
            SET
                nom = :nom,
                prenom = :prenom,
                telephone = :telephone,
                adresse_postale = :adresse_postale,
                ville = :ville,
                pays = :pays,
                canal_contact_prefere = :canal_contact_prefere,
                updated_at = CURRENT_TIMESTAMP
            WHERE id_utilisateur = :user_id
        SQL;

        $statement = $this->pdo()->prepare($sql);

        return $statement->execute([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'],
            'adresse_postale' => $data['adresse_postale'],
            'ville' => $data['ville'],
            'pays' => $data['pays'],
            'canal_contact_prefere' => $data['canal_contact_prefere'],
            'user_id' => $userId,
        ]);
    }

    public function updatePassword(int $userId, string $password): bool
    {
        $statement = $this->pdo()->prepare(
            'UPDATE utilisateurs SET password_hash = :password_hash, updated_at = CURRENT_TIMESTAMP WHERE id_utilisateur = :user_id'
        );

        return $statement->execute([
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'user_id' => $userId,
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findEmployees(): array
    {
        $sql = <<<SQL
            SELECT
                u.id_utilisateur,
                u.email,
                u.nom,
                u.prenom,
                u.telephone,
                u.ville,
                u.actif,
                r.libelle AS role
            FROM utilisateurs u
            INNER JOIN roles r ON r.id_role = u.id_role
            WHERE r.libelle = 'Employee'
            ORDER BY u.actif DESC, u.nom ASC, u.prenom ASC
        SQL;

        $statement = $this->pdo()->query($sql);

        return $statement->fetchAll();
    }

    /**
     * @param array{email: string, nom: string, prenom: string, telephone: string, adresse_postale: string, ville: string, pays: string} $data
     */
    public function createEmployee(array $data, string $temporaryPassword): int
    {
        $roleId = $this->employeeRoleId();

        $statement = $this->pdo()->prepare(
            'INSERT INTO utilisateurs (id_role, email, password_hash, nom, prenom, telephone, adresse_postale, ville, pays)
             VALUES (:id_role, :email, :password_hash, :nom, :prenom, :telephone, :adresse_postale, :ville, :pays)'
        );
        $statement->execute([
            'id_role' => $roleId,
            'email' => $data['email'],
            'password_hash' => password_hash($temporaryPassword, PASSWORD_DEFAULT),
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'],
            'adresse_postale' => $data['adresse_postale'],
            'ville' => $data['ville'],
            'pays' => $data['pays'],
        ]);

        return (int) $this->pdo()->lastInsertId();
    }

    public function setEmployeeActive(int $userId, bool $active): bool
    {
        $statement = $this->pdo()->prepare(
            "UPDATE utilisateurs
             SET actif = :active, updated_at = CURRENT_TIMESTAMP
             WHERE id_utilisateur = :user_id
               AND id_role = (SELECT id_role FROM roles WHERE libelle = 'Employee' LIMIT 1)"
        );
        $statement->execute([
            'active' => $active ? 1 : 0,
            'user_id' => $userId,
        ]);

        return $statement->rowCount() === 1;
    }

    public function normalizeRole(string $role): string
    {
        return match (strtolower($role)) {
            'administrator', 'administrateur' => 'administrateur',
            'employee', 'employe', 'employé' => 'employe',
            default => 'utilisateur',
        };
    }

    private function customerRoleId(): int
    {
        $statement = $this->pdo()->query(
            "SELECT id_role FROM roles WHERE libelle IN ('Customer', 'utilisateur') ORDER BY id_role DESC LIMIT 1"
        );

        $roleId = $statement->fetchColumn();

        return $roleId === false ? 3 : (int) $roleId;
    }

    private function employeeRoleId(): int
    {
        $statement = $this->pdo()->query(
            "SELECT id_role FROM roles WHERE libelle = 'Employee' LIMIT 1"
        );

        $roleId = $statement->fetchColumn();

        return $roleId === false ? 2 : (int) $roleId;
    }
}
