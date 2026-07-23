# Vite & Gourmand

Projet ECF Studi - Développeur Web et Web Mobile.

Vite & Gourmand est une application web prévue pour une entreprise familiale de
traiteur située à Bordeaux. Le projet vise à remplacer un fonctionnement basé
sur l'envoi manuel des menus par mail par une plateforme permettant de consulter
les menus, passer commande, suivre les commandes et piloter l'activité depuis un
espace interne.

## État Actuel

Ce dépôt contient la version finale ECF du projet :

- analyse fonctionnelle et gestion de projet centralisées dans Notion ;
- modélisation Merise disponible : MCD, MLD et MPD ;
- diagrammes UML disponibles en sources draw.io et exports PNG ;
- scripts SQL de création et d'insertion disponibles ;
- collections MongoDB créées et alimentées pour les statistiques administrateur ;
- documentation technique de base de données disponible ;
- application PHP 8.3 MVC préparée avec Composer et autoload PSR-4 ;
- infrastructure technique disponible : routeur, pages 404/500,
  session, chargement `.env`, gestion d'erreurs, helpers HTTP/sécurité et base
  model ;
- parcours MVC principaux implémentés : menus, contact, authentification,
  commandes, suivi client, espace employé, avis et statistiques admin ;
- branches Git `main`, `develop` et `feature/*` utilisées pour le développement ;
- lien Figma global synchronisé pour les wireframes, maquettes, composants,
  charte graphique et exports ;
- fichier `.env.example` disponible pour documenter la configuration.

Le code métier PHP couvre maintenant le MVP principal : consultation des menus,
filtres, détail menu, contact, inscription, connexion, mot de passe oublié,
commande, suivi, traitement employé, modération des avis et dashboard admin. Le
fichier Figma actuel contient les wireframes basse fidélité, les maquettes haute
qualité, les composants de base, la charte graphique et une page d'exports PDF.

## Liens De Rendu

- Dépôt GitHub : <https://github.com/elscribe/Github-vite-et-gourmand>
- Maquettes et charte graphique Figma : <https://www.figma.com/design/sMkvVuvOyBkMvlTIsq2eCY/Vite---Gourmand?m=auto&t=eaqGOcxDQGMr22Ek-6>
- Outil de gestion de projet : <https://app.notion.com/p/3794ea958e18801aba79dc472cbe9fb7>
- Application déployée : <https://vite-gourmand-ecf-jmf.fly.dev>

Avant le dépôt Studi, les liens GitHub, Figma, Notion et application déployée
doivent être testés depuis une fenêtre privée ou un navigateur non connecté.

## Comptes De Démonstration

| Rôle | Email | Mot de passe |
| --- | --- | --- |
| Client | `claire.martin@example.test` | `ClientVite2026!` |
| Employé | `lucas.employee@vitegourmand.test` | `EmployeVite2026!` |
| Administrateur | `admin.jose@vitegourmand.test` | `AdminVite2026!` |

Ces mots de passe sont uniquement des identifiants de démonstration. En base,
ils sont stockés sous forme de hash bcrypt.

## Contexte ECF

Le sujet demande une application web ou web mobile sécurisée, responsive,
documentée, déployée et appuyée par :

- une base de données relationnelle ;
- une base de données non relationnelle ;
- un dépôt GitHub public ;
- une documentation de gestion de projet ;
- des maquettes desktop et mobile ;
- une charte graphique ;
- un manuel utilisateur ;
- une documentation de déploiement.

## Fonctionnalités Attendues

### Visiteur

- Consulter la page d'accueil.
- Consulter la présentation de l'entreprise.
- Consulter les avis clients validés.
- Consulter la liste des menus.
- Filtrer les menus sans rechargement complet de la page.
- Consulter le détail d'un menu.
- Accéder à la page de contact.
- Envoyer un message de contact.
- Créer un compte utilisateur.

### Utilisateur Connecté

- Se connecter avec un email et un mot de passe.
- Réinitialiser son mot de passe.
- Commander un menu.
- Voir le prix détaillé avant validation.
- Recevoir une confirmation de commande.
- Consulter ses commandes.
- Modifier ses informations personnelles.
- Modifier ou annuler une commande tant qu'elle n'est pas acceptée.
- Suivre les états d'une commande acceptée.
- Laisser un avis après une commande terminée.

### Employé

- Filtrer les commandes par statut ou par client.
- Mettre à jour le statut d'une commande.
- Annuler ou modifier une commande après contact client.
- Renseigner le motif d'annulation et le mode de contact.
- Valider ou refuser les avis clients.

### Administrateur

- Accéder aux fonctionnalités employé.
- Gérer les menus et leur composition en plats.
- Gérer les plats et leurs allergènes.
- Gérer les horaires.
- Créer un compte employé.
- Désactiver un compte employé.
- Visualiser le nombre de commandes par menu.
- Comparer les menus avec un graphique.
- Consulter le chiffre d'affaires par menu.
- Filtrer les statistiques par menu et par période.

### Choix De Périmètre Des Rôles

L'énoncé place la gestion des menus, des plats et des horaires dans l'espace
employé. Dans cette implémentation, ces actions sont volontairement réservées
au rôle `administrateur`, car elles modifient directement le catalogue public,
les prix, la composition des menus et les horaires affichés aux clients.
L'espace `employé` reste centré sur le traitement opérationnel : commandes,
annulations justifiées, changements de statuts et modération des avis.

## Stack Technique Cible

| Couche | Choix |
| --- | --- |
| Frontend | HTML5, CSS3, Bootstrap 5, JavaScript vanilla |
| Backend | PHP 8.3, PDO |
| Architecture | MVC simple |
| Autoload | Composer, PSR-4 |
| Base relationnelle | MariaDB, compatible MySQL 8 pour les tests |
| Base non relationnelle | MongoDB pour les agrégats statistiques du dashboard administrateur |
| Gestion projet | Notion |
| Dépôt | GitHub |
| Déploiement | Fly.io via Docker : <https://vite-gourmand-ecf-jmf.fly.dev> |

La base SQL reste la source de vérité pour les données métier. MongoDB sert les
agrégats statistiques utilisés par le tableau de bord administrateur. Le code
lit ces agrégats avec `mongosh` quand MongoDB est disponible et conserve un
secours SQL local si MongoDB ou `mongosh` ne sont pas disponibles.

## Organisation Du Dépôt

Arborescence cible du dépôt :

```text
vite-gourmand/
├── app/
│   ├── Controllers/
│   ├── Core/
│   ├── Middlewares/
│   ├── Models/
│   ├── Services/
│   └── Views/
├── config/
├── database/
│   ├── mongodb/
│   ├── sql/
│   └── business-rules.md
├── docs/
│   ├── database/
│   ├── uml/
│   └── repository.md
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── images/
│   │   └── js/
│   └── index.php
├── scripts/
├── storage/
├── tests/
├── docker/
├── Dockerfile
├── fly.toml.example
├── .env.example
├── .dockerignore
├── .gitignore
├── composer.json
└── README.md
```

### Rôle Des Dossiers

- `app/` : code PHP privé organisé en MVC.
- `app/Controllers/` : contrôleurs qui reçoivent les actions utilisateur.
- `app/Models/` : futures classes d'accès aux données SQL.
- `app/Views/` : vues HTML affichées à l'utilisateur.
- `app/Core/` : routeur, classe de base, connexion et outils communs.
- `app/Middlewares/` : futurs middlewares de sécurité et contrôle d'accès.
- `app/Services/` : futurs services métier réutilisables.
- `config/` : configuration applicative, session, routes et base de données.
- `database/sql/` : scripts SQL de création et de données de démonstration.
- `database/mongodb/` : collections MongoDB alimentées pour les statistiques admin.
- `docs/database/` : documentation Merise, dictionnaire, choix et audits.
- `docs/uml/` : diagrammes UML en draw.io et PNG.
- `public/` : point d'entrée web et assets publics.
- `scripts/` : outils de génération ou maintenance de documentation.
- `storage/` : logs et fichiers générés localement, non publics.
- `tests/` : futurs tests automatisés.
- `docker/` : configuration Apache du conteneur de déploiement.
- `Dockerfile` : image PHP 8.3 Apache pour le déploiement Fly.io.
- `fly.toml` / `fly.toml.example` : configuration Fly.io et modèle de référence.

Chaque dossier important contient un fichier `README.md` pour expliquer son rôle
à un développeur junior ou au jury.

## Installation Locale

### Prérequis

- Git.
- PHP 8.3.
- Composer.
- MariaDB ou MySQL 8.
- MongoDB et `mongosh`, pour les statistiques administrateur.
- Docker, pour tester ou reproduire le déploiement Fly.io.
- Navigateur web moderne.

Npm n'est pas requis pour ce squelette : Bootstrap 5 est chargé par CDN et le
CSS propre au projet est écrit dans `public/assets/css/style.css`.

### Récupérer Le Projet

```bash
git clone https://github.com/elscribe/Github-vite-et-gourmand.git
cd Github-vite-et-gourmand
composer install
```

### Configurer L'Environnement

Créer un fichier `.env` local à partir du modèle :

```bash
cp .env.example .env
```

Adapter ensuite les valeurs selon l'environnement local :

- `APP_URL`
- `APP_KEY`
- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`
- `NOSQL_HOST`
- `NOSQL_PORT`
- `NOSQL_DATABASE`
- variables mail si un service SMTP est utilisé.

Les secrets et mots de passe ne doivent jamais être versionnés.

### Initialiser La Base SQL

Attention : `database/sql/create_database.sql` supprime et recrée la base
`vite_gourmand`.

```bash
mariadb -u root -p < database/sql/create_database.sql
mariadb -u root -p < database/sql/seed_database.sql
```

Équivalent possible avec MySQL 8 :

```bash
mysql -u root -p < database/sql/create_database.sql
mysql -u root -p < database/sql/seed_database.sql
```

### Initialiser MongoDB

MongoDB sert les agrégats statistiques du tableau de bord administrateur. Si
MongoDB n'est pas disponible en local, l'application conserve un secours SQL
pour ne pas bloquer la navigation.

```bash
mongosh database/mongodb/create_collections.js
mongosh database/mongodb/seed_mongodb.js
```

Collections statistiques prévues :

- `menu_statistics`
- `monthly_statistics`
- `menu_monthly_statistics`
- `dashboard_statistics`

### Lancer L'Application

Le squelette MVC peut être lancé en local avec le serveur PHP intégré :

```bash
composer serve
```

Commande équivalente sans script Composer :

```bash
php -S localhost:8000 -t public
```

### Vérifier Le Socle Technique

```bash
composer check
```

Cette commande valide `composer.json` puis lance un contrôle de syntaxe PHP sur
`app/`, `config/` et `public/`.

## Guide Développeur

Le dépôt contient le socle MVC attendu pour développer sans framework :
autoload PSR-4, contrôleur frontal, routeur, middlewares, configuration
centralisée, helpers HTTP/sécurité, connexion PDO et dossiers de documentation.
La branche courante contient aussi des parcours MVP déjà avancés ; ils doivent
être conservés et stabilisés progressivement sans mélanger logique métier et
infrastructure.

Sections principales déclarées dans [config/routes.php](config/routes.php) :

- `GET /menus`
- `GET /menus/{id}`
- `GET /connexion`
- `GET /inscription`
- `GET /commandes`
- `GET /mon-compte`
- `GET /employe`
- `GET /admin`
- `GET /contact`

Les erreurs 404 sont gérées par le routeur via `ErrorController::notFound`.
Les erreurs 500 sont gérées par `App\Core\ErrorHandler` et rendues par
`ErrorController::serverError`.

Règles de développement :

- garder `public/` comme seul web root ;
- ajouter les futures routes dans `config/routes.php` ;
- garder les contrôleurs minces ;
- placer les requêtes SQL dans les modèles ;
- placer la logique réutilisable dans `app/Services/` ;
- utiliser `App\Core\Security::escape()` ou `htmlspecialchars()` pour les sorties ;
- protéger les futurs formulaires POST avec le token CSRF préparé ;
- ne jamais versionner `.env`.

## Configuration

Le fichier [.env.example](.env.example) documente les variables attendues :

| Variable | Description |
| --- | --- |
| `APP_NAME` | Nom de l'application. |
| `APP_ENV` | Environnement d'exécution. |
| `APP_DEBUG` | Activation du debug en local. |
| `APP_DISPLAY_ERRORS` | Affichage des erreurs en développement. |
| `APP_LOG_ERRORS` | Journalisation des erreurs PHP. |
| `APP_LOG_PATH` | Chemin du fichier de logs applicatif. |
| `APP_TIMEZONE` | Fuseau horaire applicatif. |
| `APP_URL` | URL locale ou publique de l'application. |
| `APP_KEY` | Clé applicative à remplacer en local. |
| `PASSWORD_MIN_LENGTH` | Longueur minimale du mot de passe. |
| `SESSION_NAME` | Nom de la session PHP. |
| `SESSION_LIFETIME_MINUTES` | Durée de vie prévue de la session. |
| `SESSION_SECURE` | Cookie de session limité au HTTPS en production. |
| `SESSION_HTTP_ONLY` | Cookie de session inaccessible en JavaScript. |
| `SESSION_SAME_SITE` | Politique SameSite du cookie de session. |
| `DB_CONNECTION` | Type de connexion SQL. |
| `DB_HOST` | Hôte SQL. |
| `DB_PORT` | Port SQL. |
| `DB_NAME` | Nom de la base SQL. |
| `DB_USER` | Utilisateur SQL. |
| `DB_PASSWORD` | Mot de passe SQL. |
| `NOSQL_CONNECTION` | Type de connexion NoSQL. |
| `NOSQL_HOST` | Hôte MongoDB. |
| `NOSQL_PORT` | Port MongoDB. |
| `NOSQL_DATABASE` | Base MongoDB des statistiques. |
| `MAIL_MAILER` | Stratégie d'envoi des emails : `log` (écrit dans `storage/logs/mail.log`, utilisé en production/Fly.io) ou `smtp` (envoi réel via un serveur SMTP, testé en local avec le mode sandbox de Mailtrap). |
| `MAIL_HOST` | Hôte SMTP si `MAIL_MAILER=smtp`. |
| `MAIL_PORT` | Port SMTP si `MAIL_MAILER=smtp`. |
| `MAIL_USERNAME` | Identifiant SMTP si `MAIL_MAILER=smtp`. |
| `MAIL_PASSWORD` | Mot de passe SMTP si `MAIL_MAILER=smtp`. |
| `MAIL_FROM_ADDRESS` | Adresse d'expédition. |
| `MAIL_FROM_NAME` | Nom d'expédition. |
| `MAIL_CONTACT_TO` | Adresse recevant les demandes du formulaire contact. |
| `STORE_CITY` | Ville de référence pour la livraison. |
| `DELIVERY_BASE_FEE` | Frais fixes hors Bordeaux. |
| `DELIVERY_PRICE_PER_KM` | Prix par kilomètre hors Bordeaux. |
| `DELIVERY_DISTANCE_PROVIDER` | Méthode prévue pour la distance. |

## Base De Données

La base SQL cible s'appelle `vite_gourmand`. Le script de création contient 16
tables :

- `roles`
- `utilisateurs`
- `regimes`
- `themes`
- `menus`
- `menu_images`
- `plats`
- `menu_plats`
- `allergenes`
- `plat_allergenes`
- `commandes`
- `commande_statuts`
- `avis`
- `horaires`
- `contact_messages`
- `password_resets`

Le seed SQL contient des données de démonstration pour tester les rôles, menus,
plats, commandes, historiques, avis, horaires, messages de contact et resets de
mot de passe.

## Documentation Disponible

### Base De Données

- [Documentation base de données](docs/database/README.md)
- [Règles métier et cardinalités](database/business-rules.md)
- [Dictionnaire de données](docs/database/data-dictionary.md)
- [Choix techniques base de données](docs/database/database-choices.md)
- [Rapport d'audit de cohérence](docs/database/audit-report.md)
- [Audit des scripts SQL et MongoDB](docs/database/scripts-audit.md)

### Merise

- [MCD draw.io](docs/database/MCD.drawio) / [MCD PNG](docs/database/MCD.png)
- [MLD draw.io](docs/database/MLD.drawio) / [MLD PNG](docs/database/MLD.png)
- [MPD draw.io](docs/database/MPD.drawio) / [MPD PNG](docs/database/MPD.png)

### UML

- [Cas d'utilisation](docs/uml/use-case-diagram.drawio) / [PNG](docs/uml/use-case-diagram.png)
- [Diagramme de classes](docs/uml/class-diagram.drawio) / [PNG](docs/uml/class-diagram.png)
- [Séquence authentification](docs/uml/sequence-authentication.drawio) / [PNG](docs/uml/sequence-authentication.png)
- [Séquence consultation et commande](docs/uml/sequence-consultation-commande.drawio) / [PNG](docs/uml/sequence-consultation-commande.png)
- [Séquence gestion commande employé](docs/uml/sequence-gestion-commande-employe.drawio) / [PNG](docs/uml/sequence-gestion-commande-employe.png)
- [Séquence gestion avis](docs/uml/sequence-gestion-avis.drawio) / [PNG](docs/uml/sequence-gestion-avis.png)
- [Séquence dashboard administrateur MongoDB](docs/uml/sequence-dashboard-admin-mongodb.drawio) / [PNG](docs/uml/sequence-dashboard-admin-mongodb.png)

### UX/UI

- [Suivi UX/UI et charte graphique](docs/design.md)
- [Figma global Vite & Gourmand](https://www.figma.com/design/sMkvVuvOyBkMvlTIsq2eCY/Vite---Gourmand?m=auto&t=eaqGOcxDQGMr22Ek-6)

### Scripts

- [Création SQL complète](database/sql/create_database.sql)
- [Données SQL de démonstration](database/sql/seed_database.sql)
- [Collections MongoDB](database/mongodb/collections.md)
- [Documents d'exemple MongoDB](database/mongodb/sample-data.json)
- [Création collections MongoDB](database/mongodb/create_collections.js)
- [Seed MongoDB](database/mongodb/seed_mongodb.js)
- [Génération documentation BDD](scripts/generate_database_docs.py)
- [Organisation du dépôt GitHub](docs/repository.md)

### Documentation De Recette Et Rendu

- [Gestion de projet](docs/project-management/README.md)
- [Documentation de déploiement](docs/deployment/README.md)
- [Manuel utilisateur](docs/manual/README.md)
- [Documentation sécurité](docs/security/README.md)
- [Accessibilité : bases et réserve RGAA](docs/security/README.md#accessibilite-et-securite-des-formulaires)
- [Veille sécurité](docs/security/security-watch.md)
- [Recherche anglophone](docs/project-management/recherche-anglophone.md)
- [Matrice finale de recette](docs/manual/final-user-story-test-matrix.md)
- [Livrables finaux](docs/deliverables/final-deliverables.md)
- [Wireframes](docs/deliverables/wireframes/README.md)
- [Mockups](docs/deliverables/mockups/README.md)
- [Charte graphique](docs/deliverables/graphic-charter/README.md)

## Sécurité Mise En Place

Les mécanismes suivants sont implémentés ou documentés :

- mots de passe hachés avec `password_hash` ;
- vérification des mots de passe avec `password_verify` ;
- requêtes préparées PDO ;
- validation serveur des formulaires ;
- validation client pour l'ergonomie ;
- protection des routes selon les rôles avec middlewares ;
- gestion des sessions ;
- protection CSRF sur les actions sensibles ;
- échappement des données affichées pour limiter le risque XSS ;
- messages d'erreur non techniques ;
- fichier `.env` non versionné ;
- collecte limitée des données personnelles utiles au parcours de commande.

La documentation détaillée se trouve dans
[docs/security/README.md](docs/security/README.md).

## Qualité Et Tests

Les contrôles disponibles sont :

- `composer check` pour valider Composer et la syntaxe PHP ;
- recette manuelle par rôle dans [docs/manual/mvp-test-checklist.md](docs/manual/mvp-test-checklist.md) ;
- matrice finale US / Figma / route / test / preuve dans [docs/manual/final-user-story-test-matrix.md](docs/manual/final-user-story-test-matrix.md) ;
- rapport d'implémentation des user stories dans [docs/project-management/user-story-implementation-report.md](docs/project-management/user-story-implementation-report.md) ;
- journal des problèmes et solutions dans [docs/project-management/user-story-debug-log.md](docs/project-management/user-story-debug-log.md).

Parcours couverts localement :

- parcours visiteur, client, employé et administrateur ;
- calcul du prix, de la réduction et des frais de livraison ;
- droits d'accès par rôle ;
- transitions de statuts de commande ;
- formulaires et messages d'erreur ;
- accessibilité : navigation clavier, contrastes, labels, textes alternatifs ;
- audit accessibilité RGAA interne sur un échantillon représentatif ;
- dashboard administrateur alimenté par MongoDB.

Les tests automatisés applicatifs restent une amélioration future. La priorité
ECF est la recette manuelle documentée et reproductible.

## Roadmap

### Terminé

- [x] Analyse du besoin dans Notion.
- [x] Cahier des charges dans Notion.
- [x] Backlog, user stories et priorisation MVP dans Notion.
- [x] Méthode de gestion hybride documentée dans Notion.
- [x] Architecture MVC cible documentée.
- [x] MCD, MLD et MPD.
- [x] Diagrammes UML principaux.
- [x] Scripts SQL de création et d'insertion.
- [x] Scripts MongoDB de création et d'insertion.
- [x] Audit de cohérence Merise / SQL / MongoDB / UML.
- [x] Lien Figma global synchronisé.
- [x] Fichier `.env.example`.

### Finalisé Pour Le Rendu

- [x] Export final des maquettes desktop/mobile et de la charte graphique.
- [x] Implémentation du MVP PHP MVC local.
- [x] Documentation sécurité.
- [x] Préparation Docker/Fly.io.
- [x] Déploiement public Fly.io.
- [x] Audit accessibilité RGAA interne.
- [ ] Audit RGAA complet final.
- [x] Recherche anglophone.
- [x] Matrice finale de recette.
- [x] Manuel utilisateur Markdown.
- [x] Documentation de déploiement avec URL réelle.
- [x] Manuel utilisateur PDF.
- [x] Livrables finaux locaux.
- [x] Tests principaux sur URL déployée.

### Améliorations Futures

- [ ] Ajouter des tests automatisés.
- [ ] Ajouter des captures d'écran de l'application.
- [ ] Ajouter une stratégie RGPD détaillée.
- [ ] Ajouter une documentation de maintenance.

## Suivi Git

Le README ne liste pas les derniers commits, car cette information devient vite
obsolète. L'historique complet est consultable directement dans GitHub via
l'onglet des commits.

Règles retenues :

- `main` : branche stable et présentable pour le rendu ;
- `develop` : branche d'intégration du développement ;
- `feature/*` : branches de travail par grande fonctionnalité ;
- commits courts et explicites ;
- `.env` et fichiers contenant des secrets exclus du dépôt ;
- fichiers temporaires, caches et parasites exclus via `.gitignore`.

Branches initialisées pour la suite du projet :

- `feature/setup-mvc` ;
- `feature/authentication` ;
- `feature/menu-catalog` ;
- `feature/order-workflow` ;
- `feature/back-office` ;
- `feature/admin-dashboard` ;
- `feature/final-documentation`.

Workflow prévu : les fonctionnalités partent de `develop`, sont fusionnées dans
`develop` après vérification, puis `develop` est fusionnée dans `main` quand une
version stable est prête pour présentation.

## Vérification Finale Avant Dépôt Studi

- Vérifier l'accès public au dépôt GitHub.
- Vérifier l'accès public au fichier Figma.
- Vérifier le lien Notion partagé au jury.
- Vérifier l'application déployée : <https://vite-gourmand-ecf-jmf.fly.dev>.
- Vérifier les identifiants administrateur de démonstration.
- Vérifier les informations candidat dans la copie finale Studi.
- Joindre les exports PDF des maquettes desktop/mobile et la charte graphique.
- Joindre le manuel utilisateur PDF.

## Notes Pour Le Jury

Le dépôt montre déjà :

- la compréhension du besoin client ;
- la transformation de l'énoncé en parcours fonctionnels ;
- la priorisation MVP ;
- la modélisation relationnelle ;
- la justification SQL / MongoDB ;
- la préparation d'une architecture MVC ;
- la production de diagrammes UML ;
- la prise en compte des enjeux de sécurité, accessibilité et déploiement.

Points à expliquer à l'oral :

- pourquoi une stack PHP native MVC plutôt qu'un framework complet ;
- pourquoi SQL reste la source de vérité métier ;
- pourquoi MongoDB est limité aux statistiques administrateur ;
- comment les rôles structurent les accès ;
- comment les commandes changent d'état ;
- comment les règles de prix, réduction et livraison seront contrôlées ;
- comment la sécurité est appliquée dans le code ;
- comment le déploiement Fly.io relie l'application PHP, MySQL et MongoDB.
