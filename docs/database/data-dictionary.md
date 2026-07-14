# Dictionnaire de donnees

## `roles`

Roles applicatifs : utilisateur, employe, administrateur.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_role` | `INT` | PK | Identifiant du role |
| `libelle` | `VARCHAR(80)` | UNIQUE NOT NULL | utilisateur, employe ou administrateur |

## `utilisateurs`

Comptes clients, employes et administrateurs.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_utilisateur` | `INT` | PK | Identifiant du compte |
| `id_role` | `INT` | FK NOT NULL | Role du compte |
| `email` | `VARCHAR(120)` | UNIQUE NOT NULL | Identifiant de connexion |
| `password_hash` | `VARCHAR(255)` | NOT NULL | Mot de passe hache |
| `nom` | `VARCHAR(80)` | NOT NULL | Nom |
| `prenom` | `VARCHAR(80)` | NOT NULL | Prenom |
| `telephone` | `VARCHAR(30)` | NOT NULL | GSM |
| `adresse_postale` | `VARCHAR(255)` | NOT NULL | Adresse |
| `ville` | `VARCHAR(80)` | NOT NULL | Ville |
| `pays` | `VARCHAR(80)` | NOT NULL | Pays |
| `actif` | `BOOLEAN` | NOT NULL | Desactivation employe |
| `created_at` | `DATETIME` | NOT NULL | Date de creation |
| `updated_at` | `DATETIME` | NULL | Derniere modification |

## `regimes`

Categories alimentaires associees aux menus.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_regime` | `INT` | PK | Identifiant du regime |
| `libelle` | `VARCHAR(80)` | UNIQUE NOT NULL | Classique, Végétarien, Vegan, Sans gluten... |

## `themes`

Themes commerciaux associes aux menus publics.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_theme` | `INT` | PK | Identifiant du theme |
| `libelle` | `VARCHAR(80)` | UNIQUE NOT NULL | Noël / fêtes, Saint-Valentin, Terre & Mer, Pâques / famille... |

## `menus`

Offres de menus traiteur commandables.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_menu` | `INT` | PK | Identifiant du menu |
| `id_regime` | `INT` | FK NOT NULL | Regime alimentaire |
| `id_theme` | `INT` | FK NOT NULL | Theme du menu |
| `titre` | `VARCHAR(120)` | NOT NULL | Titre commercial |
| `description` | `TEXT` | NOT NULL | Presentation du menu |
| `conditions` | `TEXT` | NOT NULL | Conditions de commande/conservation |
| `nombre_personnes_minimum` | `INT` | NOT NULL | Minimum obligatoire |
| `prix_minimum` | `DECIMAL(10,2)` | NOT NULL | Prix pour le minimum |
| `stock_disponible` | `INT` | NOT NULL | Commandes restantes |
| `actif` | `BOOLEAN` | NOT NULL | Affichage catalogue |
| `created_at` | `DATETIME` | NOT NULL | Date de creation |
| `updated_at` | `DATETIME` | NULL | Derniere modification |

Jeu de demonstration public aligne avec Figma : `Menu Noël Tradition`,
`Menu Cocktail Bordelais`, `Menu Végé-Gourmand`, `Menu Terre & Mer`,
`Menu Saint-Valentin` et `Menu Pâques en Famille`.

## `menu_images`

Galerie d'images des menus.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_image` | `INT` | PK | Identifiant image |
| `id_menu` | `INT` | FK NOT NULL | Menu illustre |
| `url` | `VARCHAR(255)` | NOT NULL | Chemin ou URL image |
| `texte_alternatif` | `VARCHAR(180)` | NOT NULL | Accessibilite |
| `position` | `INT` | NOT NULL | Ordre galerie |

## `plats`

Entrees, plats et desserts reutilisables dans plusieurs menus.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_plat` | `INT` | PK | Identifiant du plat |
| `titre_plat` | `VARCHAR(120)` | NOT NULL | Nom du plat |
| `type_plat` | `VARCHAR(30)` | NOT NULL | entree, plat, dessert |
| `description` | `TEXT` | NULL | Details du plat |

## `menu_plats`

Association entre menus et plats.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_menu` | `INT` | PK FK | Menu |
| `id_plat` | `INT` | PK FK | Plat |
| `position` | `INT` | NOT NULL | Ordre dans le menu |

## `allergenes`

Referentiel des allergenes.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_allergene` | `INT` | PK | Identifiant allergene |
| `libelle` | `VARCHAR(80)` | UNIQUE NOT NULL | Gluten, lait, oeufs... |

## `plat_allergenes`

Association entre plats et allergenes.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_plat` | `INT` | PK FK | Plat concerne |
| `id_allergene` | `INT` | PK FK | Allergene present |

## `commandes`

Commandes passees par les utilisateurs.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_commande` | `INT` | PK | Identifiant commande |
| `id_utilisateur` | `INT` | FK NOT NULL | Client |
| `id_menu` | `INT` | FK NOT NULL | Menu commande |
| `date_commande` | `DATETIME` | NOT NULL | Date de saisie |
| `date_prestation` | `DATE` | NOT NULL | Date evenement |
| `heure_livraison` | `TIME` | NOT NULL | Heure souhaitee |
| `adresse_livraison` | `VARCHAR(255)` | NOT NULL | Lieu prestation |
| `ville_livraison` | `VARCHAR(80)` | NOT NULL | Ville livraison |
| `distance_km` | `DECIMAL(6,2)` | NOT NULL | Distance hors Bordeaux utilisee pour calculer la livraison |
| `nombre_personnes` | `INT` | NOT NULL | Convives |
| `prix_menu` | `DECIMAL(10,2)` | NOT NULL | Prix menu calcule |
| `remise` | `DECIMAL(10,2)` | NOT NULL | Reduction appliquee |
| `prix_livraison` | `DECIMAL(10,2)` | NOT NULL | Frais livraison |
| `prix_total` | `DECIMAL(10,2)` | NOT NULL | Total TTC |
| `statut_actuel` | `VARCHAR(40)` | NOT NULL | Etat courant |
| `materiel_prete` | `BOOLEAN` | NOT NULL | Materiel a restituer |
| `materiel_retourne` | `BOOLEAN` | NOT NULL | Retour materiel |
| `mode_contact_modification` | `VARCHAR(20)` | NULL | Appel ou email |
| `motif_annulation` | `TEXT` | NULL | Motif employe/client |

## `commande_statuts`

Historique date et heure des statuts de commande.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_statut` | `INT` | PK | Identifiant historique |
| `id_commande` | `INT` | FK NOT NULL | Commande |
| `id_utilisateur` | `INT` | FK NULL | Auteur changement |
| `statut` | `VARCHAR(40)` | NOT NULL | Statut applique |
| `commentaire` | `TEXT` | NULL | Precision |
| `created_at` | `DATETIME` | NOT NULL | Date et heure |

## `avis`

Avis clients moderes par les employes.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_avis` | `INT` | PK | Identifiant avis |
| `id_utilisateur` | `INT` | FK NOT NULL | Auteur |
| `id_commande` | `INT` | FK NOT NULL | Commande terminee |
| `note` | `TINYINT` | NOT NULL | Note de 1 a 5 |
| `commentaire` | `TEXT` | NOT NULL | Commentaire client |
| `statut` | `VARCHAR(30)` | NOT NULL | en_attente, valide, refuse |
| `created_at` | `DATETIME` | NOT NULL | Date avis |
| `moderated_at` | `DATETIME` | NULL | Date moderation |
| `moderated_by` | `INT` | FK NULL | Employe moderateur |

## `horaires`

Horaires affiches dans le pied de page.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_horaire` | `INT` | PK | Identifiant horaire |
| `jour_semaine` | `TINYINT` | UNIQUE NOT NULL | 1 lundi a 7 dimanche |
| `ouverture_matin` | `TIME` | NULL | Ouverture matin |
| `fermeture_matin` | `TIME` | NULL | Fermeture matin |
| `ouverture_apres_midi` | `TIME` | NULL | Ouverture apres-midi |
| `fermeture_apres_midi` | `TIME` | NULL | Fermeture apres-midi |
| `ferme` | `BOOLEAN` | NOT NULL | Jour ferme |

## `contact_messages`

Messages envoyes depuis la page contact.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_contact_message` | `INT` | PK | Identifiant message |
| `titre` | `VARCHAR(160)` | NOT NULL | Sujet |
| `email` | `VARCHAR(120)` | NOT NULL | Email visiteur |
| `description` | `TEXT` | NOT NULL | Message |
| `created_at` | `DATETIME` | NOT NULL | Date envoi |
| `traite` | `BOOLEAN` | NOT NULL | Suivi interne |

## `password_resets`

Jetons de reinitialisation de mot de passe.

| Colonne | Type | Contraintes | Description |
| --- | --- | --- | --- |
| `id_reset` | `INT` | PK | Identifiant du jeton |
| `id_utilisateur` | `INT` | FK NOT NULL | Compte concerne |
| `token_hash` | `VARCHAR(255)` | UNIQUE NOT NULL | Jeton hache |
| `expires_at` | `DATETIME` | NOT NULL | Date d'expiration |
| `used_at` | `DATETIME` | NULL | Date d'utilisation eventuelle |
| `created_at` | `DATETIME` | NOT NULL | Date de creation |
