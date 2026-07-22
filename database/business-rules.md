# Regles metier et analyse des donnees

Ce document formalise la partie base de donnees de Vite & Gourmand a partir de l'enonce ECF et du MCD fourni en annexe. Les ajouts au MCD initial sont signales comme des extensions necessaires pour couvrir les fonctionnalites demandees explicitement dans l'enonce.

## Entites identifiees

- `roles` : Roles applicatifs : utilisateur, employe, administrateur.
- `utilisateurs` : Comptes clients, employes et administrateurs.
- `regimes` : Categories alimentaires associees aux menus.
- `themes` : Themes de menus tels que Noel, Paques ou classique.
- `menus` : Offres de menus traiteur commandables.
- `menu_images` : Galerie d'images des menus.
- `plats` : Entrees, plats et desserts reutilisables dans plusieurs menus.
- `menu_plats` : Association entre menus et plats.
- `allergenes` : Referentiel des allergenes.
- `plat_allergenes` : Association entre plats et allergenes.
- `commandes` : Commandes passees par les utilisateurs.
- `commande_statuts` : Historique date et heure des statuts de commande.
- `avis` : Avis clients moderes par les employes.
- `horaires` : Horaires affiches dans le pied de page.
- `contact_messages` : Messages envoyes depuis la page contact.
- `password_resets` : Jetons de reinitialisation de mot de passe.

## Attributs principaux

### `roles`
- `id_role` (INT) : Identifiant du role
- `libelle` (VARCHAR(80)) : utilisateur, employe ou administrateur
### `utilisateurs`
- `id_utilisateur` (INT) : Identifiant du compte
- `id_role` (INT) : Role du compte
- `email` (VARCHAR(120)) : Identifiant de connexion
- `password_hash` (VARCHAR(255)) : Mot de passe hache
- `nom` (VARCHAR(80)) : Nom
- `prenom` (VARCHAR(80)) : Prenom
- `telephone` (VARCHAR(30)) : GSM
- `adresse_postale` (VARCHAR(255)) : Adresse
- `ville` (VARCHAR(80)) : Ville
- `pays` (VARCHAR(80)) : Pays
- `canal_contact_prefere` (VARCHAR(20)) : Preference client pour le suivi (`email` ou `telephone`)
- `actif` (BOOLEAN) : Desactivation employe
- `created_at` (DATETIME) : Date de creation
- `updated_at` (DATETIME) : Derniere modification
### `regimes`
- `id_regime` (INT) : Identifiant du regime
- `libelle` (VARCHAR(80)) : Classique, vegetarien, vegan...
### `themes`
- `id_theme` (INT) : Identifiant du theme
- `libelle` (VARCHAR(80)) : Noel, Paques, classique...
### `menus`
- `id_menu` (INT) : Identifiant du menu
- `id_regime` (INT) : Regime alimentaire
- `id_theme` (INT) : Theme du menu
- `titre` (VARCHAR(120)) : Titre commercial
- `description` (TEXT) : Presentation du menu
- `conditions` (TEXT) : Conditions de commande/conservation
- `nombre_personnes_minimum` (INT) : Minimum obligatoire
- `prix_minimum` (DECIMAL(10,2)) : Prix pour le minimum
- `stock_disponible` (INT) : Commandes restantes
- `actif` (BOOLEAN) : Affichage catalogue
- `created_at` (DATETIME) : Date de creation
- `updated_at` (DATETIME) : Derniere modification
### `menu_images`
- `id_image` (INT) : Identifiant image
- `id_menu` (INT) : Menu illustre
- `url` (VARCHAR(255)) : Chemin ou URL image
- `texte_alternatif` (VARCHAR(180)) : Accessibilite
- `position` (INT) : Ordre galerie
### `plats`
- `id_plat` (INT) : Identifiant du plat
- `titre_plat` (VARCHAR(120)) : Nom du plat
- `type_plat` (VARCHAR(30)) : entree, plat, dessert
- `description` (TEXT) : Details du plat
### `menu_plats`
- `id_menu` (INT) : Menu
- `id_plat` (INT) : Plat
- `position` (INT) : Ordre dans le menu
### `allergenes`
- `id_allergene` (INT) : Identifiant allergene
- `libelle` (VARCHAR(80)) : Gluten, lait, oeufs...
### `plat_allergenes`
- `id_plat` (INT) : Plat concerne
- `id_allergene` (INT) : Allergene present
### `commandes`
- `id_commande` (INT) : Identifiant commande
- `id_utilisateur` (INT) : Client
- `id_menu` (INT) : Menu commande
- `date_commande` (DATETIME) : Date de saisie
- `date_prestation` (DATE) : Date evenement
- `heure_livraison` (TIME) : Heure souhaitee
- `adresse_livraison` (VARCHAR(255)) : Lieu prestation
- `code_postal_livraison` (VARCHAR(10)) : Code postal de livraison
- `ville_livraison` (VARCHAR(80)) : Ville livraison
- `distance_km` (DECIMAL(6,2)) : Distance hors Bordeaux utilisee pour calculer la livraison
- `commentaire_client` (TEXT) : Demande particuliere du client : allergenes, code d'acces, etage ou consignes de livraison
- `nombre_personnes` (INT) : Convives
- `prix_menu` (DECIMAL(10,2)) : Prix menu calcule
- `remise` (DECIMAL(10,2)) : Reduction appliquee
- `prix_livraison` (DECIMAL(10,2)) : Frais livraison
- `prix_total` (DECIMAL(10,2)) : Total TTC
- `statut_actuel` (VARCHAR(40)) : Etat courant
- `materiel_prete` (BOOLEAN) : Materiel a restituer
- `materiel_retourne` (BOOLEAN) : Retour materiel
- `mode_contact_modification` (VARCHAR(20)) : Appel ou email
- `motif_annulation` (TEXT) : Motif employe/client
### `commande_statuts`
- `id_statut` (INT) : Identifiant historique
- `id_commande` (INT) : Commande
- `id_utilisateur` (INT) : Auteur changement
- `statut` (VARCHAR(40)) : Statut applique
- `commentaire` (TEXT) : Precision
- `created_at` (DATETIME) : Date et heure
### `avis`
- `id_avis` (INT) : Identifiant avis
- `id_utilisateur` (INT) : Auteur
- `id_commande` (INT) : Commande terminee
- `note` (TINYINT) : Note de 1 a 5
- `commentaire` (TEXT) : Commentaire client
- `statut` (VARCHAR(30)) : en_attente, valide, refuse
- `created_at` (DATETIME) : Date avis
- `moderated_at` (DATETIME) : Date moderation
- `moderated_by` (INT) : Employe moderateur
### `horaires`
- `id_horaire` (INT) : Identifiant horaire
- `jour_semaine` (TINYINT) : 1 lundi a 7 dimanche
- `ouverture_matin` (TIME) : Ouverture matin
- `fermeture_matin` (TIME) : Fermeture matin
- `ouverture_apres_midi` (TIME) : Ouverture apres-midi
- `fermeture_apres_midi` (TIME) : Fermeture apres-midi
- `ferme` (BOOLEAN) : Jour ferme
### `contact_messages`
- `id_contact_message` (INT) : Identifiant message
- `titre` (VARCHAR(160)) : Sujet
- `email` (VARCHAR(120)) : Email visiteur
- `description` (TEXT) : Message
- `created_at` (DATETIME) : Date envoi
- `traite` (BOOLEAN) : Suivi interne
### `password_resets`
- `id_reset` (INT) : Identifiant du jeton
- `id_utilisateur` (INT) : Compte concerne
- `token_hash` (VARCHAR(255)) : Jeton hache unique
- `expires_at` (DATETIME) : Date d'expiration
- `used_at` (DATETIME) : Date d'utilisation eventuelle
- `created_at` (DATETIME) : Date de creation

## Relations et cardinalites

- `roles` (1,n) - possede - `utilisateurs` (1,1).
- `utilisateurs` (0,n) - passe - `commandes` (1,1).
- `menus` (0,n) - concerne - `commandes` (1,1).
- `regimes` (0,n) - adapte - `menus` (1,1).
- `themes` (0,n) - propose - `menus` (1,1).
- `menus` (0,n) - illustre - `menu_images` (1,1).
- `menus` (1,n) - compose - `menu_plats` (1,1).
- `plats` (0,n) - compose - `menu_plats` (1,1).
- `plats` (0,n) - contient - `plat_allergenes` (1,1).
- `allergenes` (0,n) - qualifie - `plat_allergenes` (1,1).
- `commandes` (1,n) - historise - `commande_statuts` (1,1).
- `commandes` (0,1) - donne_lieu - `avis` (1,1).
- `utilisateurs` (0,n) - publie - `avis` (1,1).
- `utilisateurs` (0,n) - modifie - `commande_statuts` (0,1).
- `utilisateurs` (0,n) - demande - `password_resets` (1,1).

## Contraintes metier

- Un visiteur peut consulter les menus sans compte, mais doit se connecter ou creer un compte avant de commander.
- A la creation publique d'un compte, le role attribue est obligatoirement `utilisateur`.
- Un administrateur ne doit pas pouvoir etre cree depuis l'application publique. Le compte de Jose est cree par le developpeur ou par une operation d'administration hors parcours public.
- Un administrateur peut creer et desactiver des comptes employes.
- Un menu doit afficher un titre, une galerie d'images, une description, un theme, un regime, une liste de plats, des conditions, un nombre minimum de personnes, un prix pour ce minimum et un stock disponible.
- Un plat peut appartenir a plusieurs menus.
- Un plat peut posseder plusieurs allergenes ; la table `plat_allergenes` conserve uniquement l'association plat/allergene.
- Le nombre de personnes commande doit etre superieur ou egal au minimum du menu.
- Une reduction de 10 % s'applique lorsque la commande contient au moins 5 personnes de plus que le minimum du menu.
- La livraison dans Bordeaux n'ajoute pas la majoration hors Bordeaux ; hors Bordeaux, l'enonce indique 5 EUR plus 0,59 EUR par kilometre parcouru. En version MVP, aucune API de geolocalisation n'est branchee : le client saisit une distance approximative depuis Bordeaux, puis l'equipe verifie l'adresse et la distance avant validation. Une evolution possible consisterait a brancher une API d'adresses/geocodage.
- Un client peut modifier ou annuler une commande tant qu'elle n'est pas acceptee. Le choix du menu n'est pas modifiable.
- Un employe ne peut modifier ou annuler une commande qu'apres contact client par appel GSM ou mail ; le mode de contact et le motif doivent etre conserves.
- Les statuts de commande doivent etre historises avec une date et une heure.
- Les avis ne sont possibles qu'apres une commande terminee et ne sont publics qu'apres validation par un employe.
- Les horaires doivent etre visibles dans le pied de page et modifiables par l'espace administrateur.
- Le role employe ne modifie pas les menus, les plats ou les horaires dans cette version : ces actions sont reservees a l'administrateur pour proteger le catalogue public, les prix et les informations publiees.
- Les statistiques administrateur doivent provenir d'une base NoSQL : nombre de commandes par menu, comparaison entre menus, chiffre d'affaires par menu et filtres temporels.

## Justification des choix

- Le MCD de l'enonce sert de socle : `utilisateur`, `role`, `commande`, `menu`, `plat`, `avis`, `regime`, `theme`, `allergene`.
- `menu_images` est ajoute car l'enonce demande une galerie d'images par menu. Ce besoin n'est pas visible dans le MCD fourni mais il est explicite dans le texte.
- `commande_statuts` est ajoute car l'enonce demande un suivi de commande listant tous les etats avec date et heure de modification.
- `horaires` est ajoute car les horaires doivent etre visibles dans le pied de page et modifiables depuis l'espace administrateur.
- `contact_messages` est ajoute car l'enonce demande un formulaire de contact avec titre, description et email.
- `password_resets` est ajoute pour couvrir le parcours de recuperation d'acces sans stocker de jeton en clair.
- `avis.id_commande` est ajoute pour garantir qu'un avis correspond bien a une commande terminee.
- `utilisateurs.actif` permet de rendre inutilisable un compte employe sans supprimer l'historique des commandes ou moderations.
- Les statistiques ne sont pas modelisees comme source de verite SQL : elles sont stockees dans MongoDB sous forme d'agregats pour respecter l'obligation NoSQL.
