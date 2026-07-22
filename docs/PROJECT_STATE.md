# Etat du projet

Date de mise a jour : 22 juillet 2026.
Branche d'integration : `develop`.

## Synthese

Vite & Gourmand est une application web PHP MVC pour un traiteur familial
bordelais. Le MVP local couvre les parcours visiteur, client, employe et
administrateur, avec une base SQL comme source de verite metier et MongoDB pour
les agregats statistiques du tableau de bord.

Le projet est en phase de finalisation avant fusion vers `main` :

- code applicatif principal integre sur `develop` ;
- audits par pages de l'enonce documentes dans `docs/project-management/` ;
- livrables techniques consolides dans `docs/` ;
- preparation Docker/Fly.io disponible ;
- deploiement public non encore effectue ;
- URL finale, depot public et copie Studi a completer apres tests.

## Perimetre fonctionnel

### Visiteur

- Accueil public avec presentation de l'entreprise.
- Avis clients valides affiches dynamiquement depuis la base.
- Catalogue menus public avec filtres rapides et overlay de filtres.
- Detail menu avec image principale, galerie, plats, allergenes, conditions et
  bouton de commande.
- Formulaire contact avec sauvegarde en base.
- Inscription, connexion, mot de passe oublie et pages legales.

### Client

- Espace client avec informations personnelles.
- Modification du profil.
- Creation de commande avec calcul prix/remise/livraison cote serveur.
- Liste des commandes et detail avec timeline de statuts.
- Modification ou annulation uniquement tant que la commande est `en_attente`.
- Depot d'avis apres commande `terminee`, puis moderation avant publication.

### Employe

- Tableau de bord employe.
- Liste des commandes avec filtres.
- Changement de statut avec historique.
- Annulation avec mode de contact et motif.
- Moderation des avis clients.
- Notifications email journalisees en mode local.

### Administrateur

- Dashboard administrateur.
- Statistiques par menu et periode, avec lecture MongoDB via `mongosh` et
  secours SQL local.
- Gestion des employes avec activation/desactivation.
- Gestion des horaires.
- Gestion des menus, images, plats, allergenes et associations menu/plats.
- Acces aux fonctionnalites employe.

## Architecture

- PHP 8.3 natif, architecture MVC simple.
- Autoload Composer PSR-4 avec namespace `App`.
- Routeur maison dans `App\Core\Router`.
- Routes centralisees dans `config/routes.php`.
- Vues PHP dans `app/Views`.
- Modeles PDO dans `app/Models`.
- Services metier dans `app/Services`.
- Middlewares serveur pour authentification, roles et CSRF.
- Assets publics dans `public/`.
- Logs et emails locaux dans `storage/logs/`.

## Base de donnees

### SQL

Tables principales :

- `roles`, `utilisateurs`, `password_resets` ;
- `menus`, `menu_images`, `plats`, `menu_plats`, `allergenes`,
  `plat_allergenes`, `regimes`, `themes` ;
- `commandes`, `commande_statuts` ;
- `avis`, `horaires`, `contact_messages`.

Regles importantes :

- SQL reste la source de verite metier.
- Les mots de passe sont hashes.
- Les commandes conservent un historique de statuts.
- Les avis sont lies a une commande terminee et moderes avant affichage public.
- Les menus/plats/horaires sont reserves a l'administrateur dans cette version.

### MongoDB

Collections :

- `menu_statistics` ;
- `monthly_statistics` ;
- `menu_monthly_statistics` ;
- `dashboard_statistics`.

MongoDB stocke des agregats de lecture pour le dashboard administrateur. Les
donnees restent recalculables depuis SQL.

## Documentation et livrables

Documents principaux :

- `README.md` : presentation generale, installation, stack, liens de rendu.
- `docs/manual/` : manuel utilisateur, checklist MVP et matrice finale.
- `docs/project-management/` : audits par exigences, decisions et preuves.
- `docs/database/` : Merise, SQL/NoSQL, diagrammes et audits.
- `docs/uml/` : use cases, classes et sequences.
- `docs/security/` : securite, veille et bases accessibilite.
- `docs/deployment/` : procedure Fly.io via Docker.
- `docs/deliverables/` : wireframes, mockups, charte et livrables finaux.

## Deploiement

Etat actuel :

- Dockerfile de production present.
- Configuration Apache du conteneur dans `docker/apache-vhost.conf`.
- Exemple Fly.io present dans `fly.toml.example`.
- Variables a renseigner via `.env` ou secrets Fly.io.
- URL publique non encore renseignee.

Avant rendu final :

1. Tester `develop` localement.
2. Fusionner `develop` vers `main` uniquement apres validation.
3. Pousser `main`.
4. Rendre le depot GitHub public.
5. Deployer sur Fly.io.
6. Renseigner l'URL publique dans README, Notion et copie Studi.
7. Rejouer la recette sur l'URL publique.

## Tests et controles

Controles disponibles :

- `composer check` pour Composer et lint PHP.
- `node --check public/assets/js/app.js` pour la syntaxe JavaScript.
- Recette manuelle par role dans `docs/manual/mvp-test-checklist.md`.
- Matrice finale dans `docs/manual/final-user-story-test-matrix.md`.
- Audits locaux avec captures dans `docs/project-management/`.

Points restants :

- Test final complet sur `develop`.
- Test final sur l'URL deployee.
- Audit RGAA complet si une declaration d'accessibilite forte est ajoutee.
- Verification des liens publics GitHub, Figma, Notion et application.

## Decisions defendables a l'oral

- PHP MVC natif pour montrer le fonctionnement routeur, controleurs, modeles,
  vues, sessions et securite sans framework applicatif.
- PDO et requetes preparees pour l'acces SQL.
- SQL comme source de verite metier.
- MongoDB limite aux agregats statistiques NoSQL.
- Emails journalises localement tant que SMTP production n'est pas configure.
- Middleware serveur pour les droits, pas seulement masquage des liens.
- Historique des statuts dans `commande_statuts`.
- Avis publics uniquement apres commande terminee et moderation.

## Risques restants

- Deploiement public non encore realise.
- Depot GitHub encore a rendre public avant transmission.
- Tests automatises applicatifs limites.
- Configuration SMTP production non branchee.
- URL finale et identifiants de demo a verifier une derniere fois avant rendu.
