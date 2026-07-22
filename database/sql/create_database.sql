DROP DATABASE IF EXISTS vite_gourmand;
CREATE DATABASE vite_gourmand
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

USE vite_gourmand;

-- -----------------------------------------------------
-- Table roles
-- -----------------------------------------------------
CREATE TABLE `roles` (
    `id_role` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `libelle` VARCHAR(80) NOT NULL,
    CONSTRAINT `pk_roles`
        PRIMARY KEY (`id_role`),
    CONSTRAINT `uq_roles_libelle`
        UNIQUE (`libelle`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table utilisateurs
-- -----------------------------------------------------
CREATE TABLE `utilisateurs` (
    `id_utilisateur` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_role` INT UNSIGNED NOT NULL,
    `email` VARCHAR(120) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `nom` VARCHAR(80) NOT NULL,
    `prenom` VARCHAR(80) NOT NULL,
    `telephone` VARCHAR(30) NOT NULL,
    `adresse_postale` VARCHAR(255) NOT NULL,
    `ville` VARCHAR(80) NOT NULL,
    `pays` VARCHAR(80) DEFAULT 'France',
    `canal_contact_prefere` VARCHAR(20) NOT NULL DEFAULT 'email',
    `actif` BOOLEAN DEFAULT TRUE,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL,
    CONSTRAINT `pk_utilisateurs`
        PRIMARY KEY (`id_utilisateur`),
    CONSTRAINT `uq_utilisateurs_email`
        UNIQUE (`email`),
    INDEX `idx_utilisateurs_id_role` (`id_role`),
    CONSTRAINT `chk_utilisateurs_canal_contact_prefere`
        CHECK (`canal_contact_prefere` IN ('email', 'telephone')),
    CONSTRAINT `fk_utilisateurs_roles`
        FOREIGN KEY (`id_role`)
        REFERENCES `roles` (`id_role`)
        ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table regimes
-- -----------------------------------------------------
CREATE TABLE `regimes` (
    `id_regime` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `libelle` VARCHAR(80) NOT NULL,
    CONSTRAINT `pk_regimes`
        PRIMARY KEY (`id_regime`),
    CONSTRAINT `uq_regimes_libelle`
        UNIQUE (`libelle`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table themes
-- -----------------------------------------------------
CREATE TABLE `themes` (
    `id_theme` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `libelle` VARCHAR(80) NOT NULL,
    CONSTRAINT `pk_themes`
        PRIMARY KEY (`id_theme`),
    CONSTRAINT `uq_themes_libelle`
        UNIQUE (`libelle`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table menus
-- -----------------------------------------------------
CREATE TABLE `menus` (
    `id_menu` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_regime` INT UNSIGNED NOT NULL,
    `id_theme` INT UNSIGNED NOT NULL,
    `titre` VARCHAR(120) NOT NULL,
    `description` TEXT NOT NULL,
    `conditions` TEXT NOT NULL,
    `nombre_personnes_minimum` INT UNSIGNED NOT NULL,
    `prix_minimum` DECIMAL(10,2) NOT NULL,
    `stock_disponible` INT UNSIGNED DEFAULT 0,
    `actif` BOOLEAN DEFAULT TRUE,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL,
    CONSTRAINT `pk_menus`
        PRIMARY KEY (`id_menu`),
    INDEX `idx_menus_id_regime` (`id_regime`),
    INDEX `idx_menus_id_theme` (`id_theme`),
    INDEX `idx_menus_catalogue_filters` (`actif`, `id_theme`, `id_regime`, `prix_minimum`),
    INDEX `idx_menus_nombre_personnes_minimum` (`nombre_personnes_minimum`),
    CONSTRAINT `fk_menus_regimes`
        FOREIGN KEY (`id_regime`)
        REFERENCES `regimes` (`id_regime`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_menus_themes`
        FOREIGN KEY (`id_theme`)
        REFERENCES `themes` (`id_theme`)
        ON DELETE RESTRICT,
    CONSTRAINT `chk_menus_nombre_personnes_minimum`
        CHECK (`nombre_personnes_minimum` > 0),
    CONSTRAINT `chk_menus_prix_minimum`
        CHECK (`prix_minimum` >= 0),
    CONSTRAINT `chk_menus_stock_disponible`
        CHECK (`stock_disponible` >= 0)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table menu_images
-- -----------------------------------------------------
CREATE TABLE `menu_images` (
    `id_image` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_menu` INT UNSIGNED NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `texte_alternatif` VARCHAR(180) NOT NULL,
    `position` INT UNSIGNED DEFAULT 1,
    CONSTRAINT `pk_menu_images`
        PRIMARY KEY (`id_image`),
    INDEX `idx_menu_images_id_menu_position` (`id_menu`, `position`),
    CONSTRAINT `fk_menu_images_menus`
        FOREIGN KEY (`id_menu`)
        REFERENCES `menus` (`id_menu`)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table plats
-- -----------------------------------------------------
CREATE TABLE `plats` (
    `id_plat` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `titre_plat` VARCHAR(120) NOT NULL,
    `type_plat` VARCHAR(30) NOT NULL,
    `description` TEXT NULL,
    CONSTRAINT `pk_plats`
        PRIMARY KEY (`id_plat`),
    CONSTRAINT `chk_plats_type_plat`
        CHECK (`type_plat` IN ('entree', 'plat', 'dessert'))
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table menu_plats
-- -----------------------------------------------------
CREATE TABLE `menu_plats` (
    `id_menu` INT UNSIGNED NOT NULL,
    `id_plat` INT UNSIGNED NOT NULL,
    `position` INT UNSIGNED DEFAULT 1,
    CONSTRAINT `pk_menu_plats`
        PRIMARY KEY (`id_menu`, `id_plat`),
    INDEX `idx_menu_plats_id_plat` (`id_plat`),
    CONSTRAINT `fk_menu_plats_menus`
        FOREIGN KEY (`id_menu`)
        REFERENCES `menus` (`id_menu`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_menu_plats_plats`
        FOREIGN KEY (`id_plat`)
        REFERENCES `plats` (`id_plat`)
        ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table allergenes
-- -----------------------------------------------------
CREATE TABLE `allergenes` (
    `id_allergene` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `libelle` VARCHAR(80) NOT NULL,
    CONSTRAINT `pk_allergenes`
        PRIMARY KEY (`id_allergene`),
    CONSTRAINT `uq_allergenes_libelle`
        UNIQUE (`libelle`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table plat_allergenes
-- -----------------------------------------------------
CREATE TABLE `plat_allergenes` (
    `id_plat` INT UNSIGNED NOT NULL,
    `id_allergene` INT UNSIGNED NOT NULL,
    CONSTRAINT `pk_plat_allergenes`
        PRIMARY KEY (`id_plat`, `id_allergene`),
    INDEX `idx_plat_allergenes_id_allergene` (`id_allergene`),
    CONSTRAINT `fk_plat_allergenes_plats`
        FOREIGN KEY (`id_plat`)
        REFERENCES `plats` (`id_plat`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_plat_allergenes_allergenes`
        FOREIGN KEY (`id_allergene`)
        REFERENCES `allergenes` (`id_allergene`)
        ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table commandes
-- -----------------------------------------------------
CREATE TABLE `commandes` (
    `id_commande` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_utilisateur` INT UNSIGNED NOT NULL,
    `id_menu` INT UNSIGNED NOT NULL,
    `date_commande` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `date_prestation` DATE NOT NULL,
    `heure_livraison` TIME NOT NULL,
    `adresse_livraison` VARCHAR(255) NOT NULL,
    `code_postal_livraison` VARCHAR(10) NOT NULL DEFAULT '33000',
    `ville_livraison` VARCHAR(80) NOT NULL,
    `distance_km` DECIMAL(6,2) DEFAULT 0,
    `commentaire_client` TEXT NULL,
    `nombre_personnes` INT UNSIGNED NOT NULL,
    `prix_menu` DECIMAL(10,2) NOT NULL,
    `remise` DECIMAL(10,2) DEFAULT 0,
    `prix_livraison` DECIMAL(10,2) DEFAULT 0,
    `prix_total` DECIMAL(10,2) NOT NULL,
    `statut_actuel` VARCHAR(40) NOT NULL DEFAULT 'en_attente',
    `materiel_prete` BOOLEAN DEFAULT FALSE,
    `materiel_retourne` BOOLEAN DEFAULT FALSE,
    `mode_contact_modification` VARCHAR(20) NULL,
    `motif_annulation` TEXT NULL,
    CONSTRAINT `pk_commandes`
        PRIMARY KEY (`id_commande`),
    INDEX `idx_commandes_id_utilisateur` (`id_utilisateur`),
    INDEX `idx_commandes_id_menu` (`id_menu`),
    INDEX `idx_commandes_statut_actuel` (`statut_actuel`),
    INDEX `idx_commandes_date_prestation` (`date_prestation`),
    CONSTRAINT `fk_commandes_utilisateurs`
        FOREIGN KEY (`id_utilisateur`)
        REFERENCES `utilisateurs` (`id_utilisateur`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_commandes_menus`
        FOREIGN KEY (`id_menu`)
        REFERENCES `menus` (`id_menu`)
        ON DELETE RESTRICT,
    CONSTRAINT `chk_commandes_distance_km`
        CHECK (`distance_km` >= 0),
    CONSTRAINT `chk_commandes_nombre_personnes`
        CHECK (`nombre_personnes` > 0),
    CONSTRAINT `chk_commandes_prix_menu`
        CHECK (`prix_menu` >= 0),
    CONSTRAINT `chk_commandes_remise`
        CHECK (`remise` >= 0),
    CONSTRAINT `chk_commandes_prix_livraison`
        CHECK (`prix_livraison` >= 0),
    CONSTRAINT `chk_commandes_prix_total`
        CHECK (`prix_total` >= 0),
    CONSTRAINT `chk_commandes_mode_contact_modification`
        CHECK (
            `mode_contact_modification` IS NULL
            OR `mode_contact_modification` IN ('gsm', 'email')
        ),
    CONSTRAINT `chk_commandes_statut_actuel`
        CHECK (
            `statut_actuel` IN (
                'en_attente',
                'acceptee',
                'en_preparation',
                'en_cours_de_livraison',
                'livre',
                'en_attente_retour_materiel',
                'terminee',
                'annulee'
            )
        )
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table commande_statuts
-- -----------------------------------------------------
CREATE TABLE `commande_statuts` (
    `id_statut` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_commande` INT UNSIGNED NOT NULL,
    `id_utilisateur` INT UNSIGNED NULL,
    `statut` VARCHAR(40) NOT NULL,
    `commentaire` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `pk_commande_statuts`
        PRIMARY KEY (`id_statut`),
    INDEX `idx_commande_statuts_id_commande_created_at` (`id_commande`, `created_at`),
    INDEX `idx_commande_statuts_id_utilisateur` (`id_utilisateur`),
    CONSTRAINT `fk_commande_statuts_commandes`
        FOREIGN KEY (`id_commande`)
        REFERENCES `commandes` (`id_commande`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_commande_statuts_utilisateurs`
        FOREIGN KEY (`id_utilisateur`)
        REFERENCES `utilisateurs` (`id_utilisateur`)
        ON DELETE SET NULL,
    CONSTRAINT `chk_commande_statuts_statut`
        CHECK (
            `statut` IN (
                'en_attente',
                'acceptee',
                'en_preparation',
                'en_cours_de_livraison',
                'livre',
                'en_attente_retour_materiel',
                'terminee',
                'annulee'
            )
        )
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table avis
-- -----------------------------------------------------
CREATE TABLE `avis` (
    `id_avis` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_utilisateur` INT UNSIGNED NOT NULL,
    `id_commande` INT UNSIGNED NOT NULL,
    `note` TINYINT UNSIGNED NOT NULL,
    `commentaire` TEXT NOT NULL,
    `statut` VARCHAR(30) NOT NULL DEFAULT 'en_attente',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `moderated_at` DATETIME NULL,
    `moderated_by` INT UNSIGNED NULL,
    CONSTRAINT `pk_avis`
        PRIMARY KEY (`id_avis`),
    CONSTRAINT `uq_avis_id_commande`
        UNIQUE (`id_commande`),
    INDEX `idx_avis_id_utilisateur` (`id_utilisateur`),
    INDEX `idx_avis_moderated_by` (`moderated_by`),
    INDEX `idx_avis_statut` (`statut`),
    CONSTRAINT `fk_avis_utilisateurs`
        FOREIGN KEY (`id_utilisateur`)
        REFERENCES `utilisateurs` (`id_utilisateur`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_avis_commandes`
        FOREIGN KEY (`id_commande`)
        REFERENCES `commandes` (`id_commande`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_avis_moderateurs`
        FOREIGN KEY (`moderated_by`)
        REFERENCES `utilisateurs` (`id_utilisateur`)
        ON DELETE SET NULL,
    CONSTRAINT `chk_avis_note`
        CHECK (`note` BETWEEN 1 AND 5),
    CONSTRAINT `chk_avis_statut`
        CHECK (`statut` IN ('en_attente', 'valide', 'refuse'))
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table horaires
-- -----------------------------------------------------
CREATE TABLE `horaires` (
    `id_horaire` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `jour_semaine` TINYINT NOT NULL,
    `ouverture_matin` TIME NULL,
    `fermeture_matin` TIME NULL,
    `ouverture_apres_midi` TIME NULL,
    `fermeture_apres_midi` TIME NULL,
    `ferme` BOOLEAN DEFAULT FALSE,
    CONSTRAINT `pk_horaires`
        PRIMARY KEY (`id_horaire`),
    CONSTRAINT `uq_horaires_jour_semaine`
        UNIQUE (`jour_semaine`),
    CONSTRAINT `chk_horaires_jour_semaine`
        CHECK (`jour_semaine` BETWEEN 1 AND 7)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table contact_messages
-- -----------------------------------------------------
CREATE TABLE `contact_messages` (
    `id_contact_message` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `titre` VARCHAR(160) NOT NULL,
    `email` VARCHAR(120) NOT NULL,
    `description` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `traite` BOOLEAN DEFAULT FALSE,
    CONSTRAINT `pk_contact_messages`
        PRIMARY KEY (`id_contact_message`),
    INDEX `idx_contact_messages_traite` (`traite`),
    CONSTRAINT `chk_contact_messages_email`
        CHECK (`email` LIKE '%@%')
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table password_resets
-- -----------------------------------------------------
CREATE TABLE `password_resets` (
    `id_reset` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_utilisateur` INT UNSIGNED NOT NULL,
    `token_hash` VARCHAR(255) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `used_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `pk_password_resets`
        PRIMARY KEY (`id_reset`),
    CONSTRAINT `uq_password_resets_token_hash`
        UNIQUE (`token_hash`),
    INDEX `idx_password_resets_id_utilisateur` (`id_utilisateur`),
    INDEX `idx_password_resets_expires_at` (`expires_at`),
    INDEX `idx_password_resets_used_at` (`used_at`),
    CONSTRAINT `fk_password_resets_utilisateurs`
        FOREIGN KEY (`id_utilisateur`)
        REFERENCES `utilisateurs` (`id_utilisateur`)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
