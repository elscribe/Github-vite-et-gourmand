# Vite & Gourmand

Projet ECF Studi - Developpeur Web et Web Mobile.

Vite & Gourmand est une application web prevue pour une entreprise familiale de
traiteur situee a Bordeaux. Le projet vise a remplacer un fonctionnement base
sur l'envoi manuel des menus par mail par une plateforme permettant de consulter
les menus, passer commande, suivre les commandes et piloter l'activite depuis un
espace interne.

## Etat Actuel

Ce depot contient actuellement la phase de cadrage technique du projet :

- analyse fonctionnelle et gestion de projet centralisees dans Notion ;
- modelisation Merise disponible : MCD, MLD et MPD ;
- diagrammes UML disponibles en sources draw.io et exports PNG ;
- scripts SQL de creation et d'insertion disponibles ;
- collections MongoDB preparees pour les statistiques administrateur ;
- documentation technique de base de donnees disponible ;
- squelette PHP 8.3 MVC prepare avec Composer et autoload PSR-4 ;
- infrastructure Sprint 0 disponible : routes de reservation, pages 404/500,
  session, chargement `.env`, gestion d'erreurs, helpers HTTP/securite et base
  model ;
- parcours MVC principaux implementes : menus, contact, authentification,
  commandes, suivi client, espace employe, avis et statistiques admin ;
- branches Git `develop` et `feature/*` initialisees pour le developpement ;
- lien Figma global synchronise pour les wireframes, maquettes, composants,
  charte graphique et exports ;
- fichier `.env.example` disponible pour documenter la configuration.

Le code metier PHP couvre maintenant le MVP principal : consultation des menus,
filtres, detail menu, contact, inscription, connexion, mot de passe oublie,
commande, suivi, traitement employe, moderation des avis et dashboard admin. Le
fichier Figma actuel contient les wireframes basse fidelite, les maquettes haute
qualite, les composants de base, la charte graphique et une page d'exports PDF.

## Liens De Rendu

- Depot GitHub : <https://github.com/elscribe/Github-vite-et-gourmand>
- Maquettes et charte graphique Figma : <https://www.figma.com/design/sMkvVuvOyBkMvlTIsq2eCY/Vite---Gourmand?m=auto&t=eaqGOcxDQGMr22Ek-6>
- Outil de gestion de projet : Notion, lien partage a completer avant rendu
- Application deployee : a completer apres deploiement

Avant le rendu final, les liens GitHub, Figma, Notion et application deployee
devront etre testes depuis une fenetre privee ou un navigateur non connecte.
Le depot GitHub peut rester prive pendant le developpement, mais il devra etre
rendu public et reverifie avant transmission au jury.

## Comptes De Demonstration

| Role | Email | Mot de passe |
| --- | --- | --- |
| Client | `claire.martin@example.test` | `ClientVite2026!` |
| Employe | `lucas.employee@vitegourmand.test` | `EmployeVite2026!` |
| Administrateur | `admin.jose@vitegourmand.test` | `AdminVite2026!` |

Ces mots de passe sont uniquement des identifiants de demonstration. En base,
ils sont stockes sous forme de hash bcrypt.

## Contexte ECF

Le sujet demande une application web ou web mobile securisee, responsive,
documentee, deployee et appuyee par :

- une base de donnees relationnelle ;
- une base de donnees non relationnelle ;
- un depot GitHub public ;
- une documentation de gestion de projet ;
- des maquettes desktop et mobile ;
- une charte graphique ;
- un manuel utilisateur ;
- une documentation de deploiement.

## Fonctionnalites Attendues

### Visiteur

- Consulter la page d'accueil.
- Consulter la presentation de l'entreprise.
- Consulter les avis clients valides.
- Consulter la liste des menus.
- Filtrer les menus sans rechargement complet de la page.
- Consulter le detail d'un menu.
- Acceder a la page de contact.
- Envoyer un message de contact.
- Creer un compte utilisateur.

### Utilisateur Connecte

- Se connecter avec un email et un mot de passe.
- Reinitialiser son mot de passe.
- Commander un menu.
- Voir le prix detaille avant validation.
- Recevoir une confirmation de commande.
- Consulter ses commandes.
- Modifier ses informations personnelles.
- Modifier ou annuler une commande tant qu'elle n'est pas acceptee.
- Suivre les etats d'une commande acceptee.
- Laisser un avis apres une commande terminee.

### Employe

- Filtrer les commandes par statut ou par client.
- Mettre a jour le statut d'une commande.
- Annuler ou modifier une commande apres contact client.
- Renseigner le motif d'annulation et le mode de contact.
- Valider ou refuser les avis clients.

### Administrateur

- Acceder aux fonctionnalites employe.
- Gerer les menus et leur composition en plats.
- Gerer les plats et leurs allergenes.
- Gerer les horaires.
- Creer un compte employe.
- Desactiver un compte employe.
- Visualiser le nombre de commandes par menu.
- Comparer les menus avec un graphique.
- Consulter le chiffre d'affaires par menu.
- Filtrer les statistiques par menu et par periode.

## Stack Technique Cible

| Couche | Choix |
| --- | --- |
| Frontend | HTML5, CSS3, Bootstrap 5, JavaScript vanilla |
| Backend | PHP 8.3, PDO |
| Architecture | MVC simple |
| Autoload | Composer, PSR-4 |
| Base relationnelle | MariaDB, compatible MySQL 8 pour les tests |
| Base non relationnelle | MongoDB prepare pour les agregats statistiques |
| Gestion projet | Notion |
| Depot | GitHub |
| Deploiement pressenti | Fly.io, a confirmer apres implementation |

La base SQL reste la source de verite pour les donnees metier. MongoDB sert les
agregats statistiques utilises par le tableau de bord administrateur. Le code
lit ces agregats avec `mongosh` quand MongoDB est disponible et conserve un
secours SQL local pour afficher le dashboard en environnement incomplet.

## Organisation Du Depot

Arborescence cible du depot :

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
├── .env.example
├── .gitignore
├── composer.json
└── README.md
```

### Role Des Dossiers

- `app/` : code PHP prive organise en MVC.
- `app/Controllers/` : controleurs qui recoivent les actions utilisateur.
- `app/Models/` : futures classes d'acces aux donnees SQL.
- `app/Views/` : vues HTML affichees a l'utilisateur.
- `app/Core/` : routeur, classe de base, connexion et outils communs.
- `app/Middlewares/` : futurs middlewares de securite et controle d'acces.
- `app/Services/` : futurs services metier reutilisables.
- `config/` : configuration applicative, session, routes et base de donnees.
- `database/sql/` : scripts SQL de creation et de donnees de demonstration.
- `database/mongodb/` : collections MongoDB alimentees pour les statistiques admin.
- `docs/database/` : documentation Merise, dictionnaire, choix et audits.
- `docs/uml/` : diagrammes UML en draw.io et PNG.
- `public/` : point d'entree web et assets publics.
- `scripts/` : outils de generation ou maintenance de documentation.
- `storage/` : logs et fichiers generes localement, non publics.
- `tests/` : futurs tests automatises.

Chaque dossier important contient un fichier `README.md` pour expliquer son role
a un developpeur junior ou au jury.

## Installation Locale

### Prerequis

- Git.
- PHP 8.3.
- Composer.
- MariaDB ou MySQL 8.
- MongoDB et `mongosh`, pour les statistiques administrateur.
- Navigateur web moderne.

Npm n'est pas requis pour ce squelette : Bootstrap 5 est charge par CDN et le
CSS propre au projet est ecrit dans `public/assets/css/style.css`.

### Recuperer Le Projet

```bash
git clone https://github.com/elscribe/Github-vite-et-gourmand.git
cd Github-vite-et-gourmand
composer install
```

### Configurer L'Environnement

Creer un fichier `.env` local a partir du modele :

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
- variables mail si un service SMTP est utilise.

Les secrets et mots de passe ne doivent jamais etre versionnes.

### Initialiser La Base SQL

Attention : `database/sql/create_database.sql` supprime et recree la base
`vite_gourmand`.

```bash
mariadb -u root -p < database/sql/create_database.sql
mariadb -u root -p < database/sql/seed_database.sql
```

Equivalent possible avec MySQL 8 :

```bash
mysql -u root -p < database/sql/create_database.sql
mysql -u root -p < database/sql/seed_database.sql
```

### Initialiser MongoDB

MongoDB sert les agregats statistiques du tableau de bord administrateur. Si
MongoDB n'est pas disponible en local, l'application conserve un secours SQL
pour ne pas bloquer la navigation.

```bash
mongosh database/mongodb/create_collections.js
mongosh database/mongodb/seed_mongodb.js
```

Collections statistiques prevues :

- `menu_statistics`
- `monthly_statistics`
- `menu_monthly_statistics`
- `dashboard_statistics`

### Lancer L'Application

Le squelette MVC peut etre lance en local avec le serveur PHP integre :

```bash
composer serve
```

Commande equivalente sans script Composer :

```bash
php -S localhost:8000 -t public
```

### Verifier Le Socle Technique

```bash
composer check
```

Cette commande valide `composer.json` puis lance un controle de syntaxe PHP sur
`app/`, `config/` et `public/`.

## Guide Developpeur

Le depot contient le socle MVC attendu pour developper sans framework :
autoload PSR-4, controleur frontal, routeur, middlewares, configuration
centralisee, helpers HTTP/securite, connexion PDO et dossiers de documentation.
La branche courante contient aussi des parcours MVP deja avances ; ils doivent
etre conserves et stabilises progressivement sans melanger logique metier et
infrastructure.

Sections principales declarees dans [config/routes.php](config/routes.php) :

- `GET /menus`
- `GET /menus/{id}`
- `GET /connexion`
- `GET /inscription`
- `GET /commandes`
- `GET /mon-compte`
- `GET /employe`
- `GET /admin`
- `GET /contact`

Les erreurs 404 sont gerees par le routeur via `ErrorController::notFound`.
Les erreurs 500 sont gerees par `App\Core\ErrorHandler` et rendues par
`ErrorController::serverError`.

Regles de developpement :

- garder `public/` comme seul web root ;
- ajouter les futures routes dans `config/routes.php` ;
- garder les controleurs minces ;
- placer les requetes SQL dans les modeles ;
- placer la logique reutilisable dans `app/Services/` ;
- utiliser `App\Core\Security::escape()` ou `htmlspecialchars()` pour les sorties ;
- proteger les futurs formulaires POST avec le token CSRF prepare ;
- ne jamais versionner `.env`.

## Configuration

Le fichier [.env.example](.env.example) documente les variables attendues :

| Variable | Description |
| --- | --- |
| `APP_NAME` | Nom de l'application. |
| `APP_ENV` | Environnement d'execution. |
| `APP_DEBUG` | Activation du debug en local. |
| `APP_DISPLAY_ERRORS` | Affichage des erreurs en developpement. |
| `APP_LOG_ERRORS` | Journalisation des erreurs PHP. |
| `APP_LOG_PATH` | Chemin du fichier de logs applicatif. |
| `APP_TIMEZONE` | Fuseau horaire applicatif. |
| `APP_URL` | URL locale ou publique de l'application. |
| `APP_KEY` | Cle applicative a remplacer en local. |
| `PASSWORD_MIN_LENGTH` | Longueur minimale du mot de passe. |
| `SESSION_NAME` | Nom de la session PHP. |
| `SESSION_LIFETIME_MINUTES` | Duree de vie prevue de la session. |
| `SESSION_SECURE` | Cookie de session limite au HTTPS en production. |
| `SESSION_HTTP_ONLY` | Cookie de session inaccessible en JavaScript. |
| `SESSION_SAME_SITE` | Politique SameSite du cookie de session. |
| `DB_CONNECTION` | Type de connexion SQL. |
| `DB_HOST` | Hote SQL. |
| `DB_PORT` | Port SQL. |
| `DB_NAME` | Nom de la base SQL. |
| `DB_USER` | Utilisateur SQL. |
| `DB_PASSWORD` | Mot de passe SQL. |
| `NOSQL_CONNECTION` | Type de connexion NoSQL. |
| `NOSQL_HOST` | Hote MongoDB. |
| `NOSQL_PORT` | Port MongoDB. |
| `NOSQL_DATABASE` | Base MongoDB des statistiques. |
| `MAIL_MAILER` | Strategie d'envoi des emails. |
| `MAIL_HOST` | Hote SMTP si necessaire. |
| `MAIL_PORT` | Port SMTP si necessaire. |
| `MAIL_USERNAME` | Identifiant mail si necessaire. |
| `MAIL_PASSWORD` | Mot de passe mail si necessaire. |
| `MAIL_FROM_ADDRESS` | Adresse d'expedition. |
| `MAIL_FROM_NAME` | Nom d'expedition. |
| `MAIL_CONTACT_TO` | Adresse recevant les demandes du formulaire contact. |
| `STORE_CITY` | Ville de reference pour la livraison. |
| `DELIVERY_BASE_FEE` | Frais fixes hors Bordeaux. |
| `DELIVERY_PRICE_PER_KM` | Prix par kilometre hors Bordeaux. |
| `DELIVERY_DISTANCE_PROVIDER` | Methode prevue pour la distance. |

## Base De Donnees

La base SQL cible s'appelle `vite_gourmand`. Le script de creation contient 16
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

Le seed SQL contient des donnees de demonstration pour tester les roles, menus,
plats, commandes, historiques, avis, horaires, messages de contact et resets de
mot de passe.

## Documentation Disponible

### Base De Donnees

- [Documentation base de donnees](docs/database/README.md)
- [Regles metier et cardinalites](database/business-rules.md)
- [Dictionnaire de donnees](docs/database/data-dictionary.md)
- [Choix techniques base de donnees](docs/database/database-choices.md)
- [Rapport d'audit de coherence](docs/database/audit-report.md)
- [Audit des scripts SQL et MongoDB](docs/database/scripts-audit.md)

### Merise

- [MCD draw.io](docs/database/MCD.drawio) / [MCD PNG](docs/database/MCD.png)
- [MLD draw.io](docs/database/MLD.drawio) / [MLD PNG](docs/database/MLD.png)
- [MPD draw.io](docs/database/MPD.drawio) / [MPD PNG](docs/database/MPD.png)

### UML

- [Cas d'utilisation](docs/uml/use-case-diagram.drawio) / [PNG](docs/uml/use-case-diagram.png)
- [Diagramme de classes](docs/uml/class-diagram.drawio) / [PNG](docs/uml/class-diagram.png)
- [Sequence authentification](docs/uml/sequence-authentication.drawio) / [PNG](docs/uml/sequence-authentication.png)
- [Sequence consultation et commande](docs/uml/sequence-consultation-commande.drawio) / [PNG](docs/uml/sequence-consultation-commande.png)
- [Sequence gestion commande employe](docs/uml/sequence-gestion-commande-employe.drawio) / [PNG](docs/uml/sequence-gestion-commande-employe.png)
- [Sequence gestion avis](docs/uml/sequence-gestion-avis.drawio) / [PNG](docs/uml/sequence-gestion-avis.png)
- [Sequence dashboard administrateur MongoDB](docs/uml/sequence-dashboard-admin-mongodb.drawio) / [PNG](docs/uml/sequence-dashboard-admin-mongodb.png)

### UX/UI

- [Suivi UX/UI et charte graphique](docs/design.md)
- [Figma global Vite & Gourmand](https://www.figma.com/design/sMkvVuvOyBkMvlTIsq2eCY/Vite---Gourmand?m=auto&t=eaqGOcxDQGMr22Ek-6)

### Scripts

- [Creation SQL complete](database/sql/create_database.sql)
- [Donnees SQL de demonstration](database/sql/seed_database.sql)
- [Collections MongoDB](database/mongodb/collections.md)
- [Documents d'exemple MongoDB](database/mongodb/sample-data.json)
- [Creation collections MongoDB](database/mongodb/create_collections.js)
- [Seed MongoDB](database/mongodb/seed_mongodb.js)
- [Generation documentation BDD](scripts/generate_database_docs.py)
- [Organisation du depot GitHub](docs/repository.md)

### Livrables A Completer

- [Gestion de projet](docs/project-management/README.md)
- [Documentation de deploiement](docs/deployment/README.md)
- [Manuel utilisateur](docs/manual/README.md)
- [Documentation securite](docs/security/README.md)
- [Wireframes](docs/deliverables/wireframes/README.md)
- [Mockups](docs/deliverables/mockups/README.md)
- [Charte graphique](docs/deliverables/graphic-charter/README.md)

## Securite Prevue

Les mecanismes suivants sont prevus dans la conception :

- mots de passe haches avec `password_hash` ;
- verification des mots de passe avec `password_verify` ;
- requetes preparees PDO ;
- validation serveur des formulaires ;
- validation client pour l'ergonomie ;
- protection des routes selon les roles ;
- gestion des sessions ;
- protection CSRF sur les actions sensibles ;
- echappement des donnees affichees pour limiter le risque XSS ;
- messages d'erreur non techniques ;
- fichier `.env` non versionne ;
- collecte limitee des donnees personnelles utiles au parcours de commande.

Ces points devront etre verifies dans le code applicatif lorsqu'il sera
implemente.

## Qualite Et Tests

Aucun test automatise n'est encore present.

Tests a prevoir :

- parcours visiteur, client, employe et administrateur ;
- calcul du prix, de la reduction et des frais de livraison ;
- droits d'acces par role ;
- transitions de statuts de commande ;
- formulaires et messages d'erreur ;
- accessibilite : navigation clavier, contrastes, labels, textes alternatifs ;
- dashboard administrateur alimente par MongoDB.

## Roadmap

### Termine

- [x] Analyse du besoin dans Notion.
- [x] Cahier des charges dans Notion.
- [x] Backlog, user stories et priorisation MVP dans Notion.
- [x] Methode de gestion hybride documentee dans Notion.
- [x] Architecture MVC cible documentee.
- [x] MCD, MLD et MPD.
- [x] Diagrammes UML principaux.
- [x] Scripts SQL de creation et d'insertion.
- [x] Scripts MongoDB de creation et d'insertion.
- [x] Audit de coherence Merise / SQL / MongoDB / UML.
- [x] Lien Figma global synchronise.
- [x] Fichier `.env.example`.

### En Cours

- [ ] Nettoyage et harmonisation finale des maquettes Figma.
- [ ] Export final des maquettes desktop/mobile et de la charte graphique.
- [ ] Implementation de l'application PHP MVC.
- [ ] Documentation securite.
- [ ] Documentation de deploiement.
- [ ] Manuel utilisateur.
- [ ] Recherche anglophone.
- [ ] Livrables finaux.

### Ameliorations Futures

- [ ] Ajouter des tests automatises.
- [ ] Ajouter des captures d'ecran de l'application.
- [ ] Ajouter une strategie RGPD detaillee.
- [ ] Ajouter une documentation de maintenance.

## Suivi Git

Le README ne liste pas les derniers commits, car cette information devient vite
obsolete. L'historique complet est consultable directement dans GitHub via
l'onglet des commits.

Regles retenues :

- `main` : branche stable et presentable pour le rendu ;
- `develop` : branche d'integration du developpement ;
- `feature/*` : branches de travail par grande fonctionnalite ;
- commits courts et explicites ;
- `.env` et fichiers contenant des secrets exclus du depot ;
- fichiers temporaires, caches et parasites exclus via `.gitignore`.

Branches initialisees pour la suite du projet :

- `feature/setup-mvc` ;
- `feature/authentication` ;
- `feature/menu-catalog` ;
- `feature/order-workflow` ;
- `feature/back-office` ;
- `feature/admin-dashboard` ;
- `feature/final-documentation`.

Workflow prevu : les fonctionnalites partent de `develop`, sont fusionnees dans
`develop` apres verification, puis `develop` est fusionnee dans `main` quand une
version stable est prete pour presentation.

## Points A Completer Avant Rendu

- Verification de l'acces public au depot GitHub.
- Verification de l'acces public au fichier Figma.
- Lien de l'application deployee.
- Identifiants administrateur de demonstration.
- Informations candidat dans la copie finale Studi.
- Exports PDF des maquettes desktop et mobile.
- Charte graphique PDF.
- Manuel utilisateur PDF.
- Documentation de deploiement.
- Recherche anglophone.
- Veille securite.

## Notes Pour Le Jury

Le depot montre deja :

- la comprehension du besoin client ;
- la transformation de l'enonce en parcours fonctionnels ;
- la priorisation MVP ;
- la modelisation relationnelle ;
- la justification SQL / MongoDB ;
- la preparation d'une architecture MVC ;
- la production de diagrammes UML ;
- l'anticipation des enjeux de securite, accessibilite et deploiement.

Points a expliquer a l'oral :

- pourquoi une stack PHP native MVC plutot qu'un framework complet ;
- pourquoi SQL reste la source de verite metier ;
- pourquoi MongoDB est limite aux statistiques administrateur ;
- comment les roles structurent les acces ;
- comment les commandes changent d'etat ;
- comment les regles de prix, reduction et livraison seront controlees ;
- comment la securite sera appliquee dans le code.
