CREATE DATABASE IF NOT EXISTS vite_gourmand
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE vite_gourmand;

CREATE TABLE roles (
    id_role INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE utilisateurs (
    id_utilisateur INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_role INT UNSIGNED NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nom VARCHAR(80) NOT NULL,
    prenom VARCHAR(80) NOT NULL,
    telephone VARCHAR(30) NOT NULL,
    adresse_postale VARCHAR(255) NOT NULL,
    ville VARCHAR(80) NOT NULL,
    pays VARCHAR(80) NOT NULL DEFAULT 'France',
    actif BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_utilisateurs_roles
FOREIGN KEY (id_role) REFERENCES roles(id_role),
    CONSTRAINT chk_utilisateurs_email
CHECK (email LIKE '%_@_%._%')
) ENGINE=InnoDB;

CREATE TABLE regimes (
    id_regime INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE themes (
    id_theme INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE menus (
    id_menu INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_regime INT UNSIGNED NOT NULL,
    id_theme INT UNSIGNED NOT NULL,
    titre VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    conditions TEXT NOT NULL,
    nombre_personnes_minimum INT UNSIGNED NOT NULL,
    prix_minimum DECIMAL(10,2) NOT NULL,
    stock_disponible INT UNSIGNED NOT NULL DEFAULT 0,
    actif BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_menus_regimes
FOREIGN KEY (id_regime) REFERENCES regimes(id_regime),
    CONSTRAINT fk_menus_themes
FOREIGN KEY (id_theme) REFERENCES themes(id_theme),
    CONSTRAINT chk_menus_minimum
CHECK (nombre_personnes_minimum > 0),
    CONSTRAINT chk_menus_prix
CHECK (prix_minimum >= 0)
) ENGINE=InnoDB;

CREATE TABLE menu_images (
    id_image INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_menu INT UNSIGNED NOT NULL,
    url VARCHAR(255) NOT NULL,
    texte_alternatif VARCHAR(180) NOT NULL,
    position INT UNSIGNED NOT NULL DEFAULT 1,
    CONSTRAINT fk_menu_images_menus
FOREIGN KEY (id_menu) REFERENCES menus(id_menu)
ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE plats (
    id_plat INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre_plat VARCHAR(120) NOT NULL,
    type_plat VARCHAR(30) NOT NULL,
    description TEXT NULL,
    CONSTRAINT chk_plats_type
CHECK (type_plat IN ('entree', 'plat', 'dessert'))
) ENGINE=InnoDB;

CREATE TABLE menu_plats (
    id_menu INT UNSIGNED NOT NULL,
    id_plat INT UNSIGNED NOT NULL,
    position INT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (id_menu, id_plat),
    CONSTRAINT fk_menu_plats_menus
FOREIGN KEY (id_menu) REFERENCES menus(id_menu)
ON DELETE CASCADE,
    CONSTRAINT fk_menu_plats_plats
FOREIGN KEY (id_plat) REFERENCES plats(id_plat)
ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE allergenes (
    id_allergene INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE plat_allergenes (
    id_plat INT UNSIGNED NOT NULL,
    id_allergene INT UNSIGNED NOT NULL,
    PRIMARY KEY (id_plat, id_allergene),
    CONSTRAINT fk_plat_allergenes_plats
FOREIGN KEY (id_plat) REFERENCES plats(id_plat)
ON DELETE CASCADE,
    CONSTRAINT fk_plat_allergenes_allergenes
FOREIGN KEY (id_allergene) REFERENCES allergenes(id_allergene)
ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE commandes (
    id_commande INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT UNSIGNED NOT NULL,
    id_menu INT UNSIGNED NOT NULL,
    date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_prestation DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    adresse_livraison VARCHAR(255) NOT NULL,
    ville_livraison VARCHAR(80) NOT NULL,
    distance_km DECIMAL(6,2) NOT NULL DEFAULT 0,
    nombre_personnes INT UNSIGNED NOT NULL,
    prix_menu DECIMAL(10,2) NOT NULL,
    remise DECIMAL(10,2) NOT NULL DEFAULT 0,
    prix_livraison DECIMAL(10,2) NOT NULL DEFAULT 0,
    prix_total DECIMAL(10,2) NOT NULL,
    statut_actuel VARCHAR(40) NOT NULL DEFAULT 'en_attente',
    materiel_prete BOOLEAN NOT NULL DEFAULT FALSE,
    materiel_retourne BOOLEAN NOT NULL DEFAULT FALSE,
    mode_contact_modification VARCHAR(20) NULL,
    motif_annulation TEXT NULL,
    CONSTRAINT fk_commandes_utilisateurs
FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
    CONSTRAINT fk_commandes_menus
FOREIGN KEY (id_menu) REFERENCES menus(id_menu),
    CONSTRAINT chk_commandes_personnes
CHECK (nombre_personnes > 0),
    CONSTRAINT chk_commandes_montants
CHECK (prix_menu >= 0 AND remise >= 0 AND prix_livraison >= 0 AND prix_total >= 0),
    CONSTRAINT chk_commandes_distance
CHECK (distance_km >= 0),
    CONSTRAINT chk_commandes_statut
CHECK (statut_actuel IN (
    'en_attente',
    'acceptee',
    'en_preparation',
    'en_cours_de_livraison',
    'livre',
    'en_attente_retour_materiel',
    'terminee',
    'annulee'
)),
    CONSTRAINT chk_commandes_contact
CHECK (mode_contact_modification IS NULL OR mode_contact_modification IN ('gsm', 'email'))
) ENGINE=InnoDB;

CREATE TABLE commande_statuts (
    id_statut INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_commande INT UNSIGNED NOT NULL,
    id_utilisateur INT UNSIGNED NULL,
    statut VARCHAR(40) NOT NULL,
    commentaire TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_commande_statuts_commandes
FOREIGN KEY (id_commande) REFERENCES commandes(id_commande)
ON DELETE CASCADE,
    CONSTRAINT fk_commande_statuts_utilisateurs
FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
ON DELETE SET NULL,
    CONSTRAINT chk_commande_statuts_statut
CHECK (statut IN (
    'en_attente',
    'acceptee',
    'en_preparation',
    'en_cours_de_livraison',
    'livre',
    'en_attente_retour_materiel',
    'terminee',
    'annulee'
))
) ENGINE=InnoDB;

CREATE TABLE avis (
    id_avis INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT UNSIGNED NOT NULL,
    id_commande INT UNSIGNED NOT NULL UNIQUE,
    note TINYINT UNSIGNED NOT NULL,
    commentaire TEXT NOT NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'en_attente',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    moderated_at DATETIME NULL,
    moderated_by INT UNSIGNED NULL,
    CONSTRAINT fk_avis_utilisateurs
FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
    CONSTRAINT fk_avis_commandes
FOREIGN KEY (id_commande) REFERENCES commandes(id_commande),
    CONSTRAINT fk_avis_moderateurs
FOREIGN KEY (moderated_by) REFERENCES utilisateurs(id_utilisateur)
ON DELETE SET NULL,
    CONSTRAINT chk_avis_note
CHECK (note BETWEEN 1 AND 5),
    CONSTRAINT chk_avis_statut
CHECK (statut IN ('en_attente', 'valide', 'refuse'))
) ENGINE=InnoDB;

CREATE TABLE horaires (
    id_horaire INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    jour_semaine TINYINT UNSIGNED NOT NULL UNIQUE,
    ouverture_matin TIME NULL,
    fermeture_matin TIME NULL,
    ouverture_apres_midi TIME NULL,
    fermeture_apres_midi TIME NULL,
    ferme BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT chk_horaires_jour
CHECK (jour_semaine BETWEEN 1 AND 7)
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
    id_contact_message INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(160) NOT NULL,
    email VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    traite BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT chk_contact_messages_email
CHECK (email LIKE '%_@_%._%')
) ENGINE=InnoDB;

CREATE TABLE password_resets (
    id_reset INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT UNSIGNED NOT NULL,
    token_hash VARCHAR(255) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_password_resets_utilisateurs
FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
ON DELETE CASCADE
) ENGINE=InnoDB;
