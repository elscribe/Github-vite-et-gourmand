# Vite & Gourmand

Projet ECF Studi - Developpeur Web et Web Mobile.

Vite & Gourmand est une application web prevue pour une entreprise familiale de
traiteur situee a Bordeaux. L'objectif est de remplacer un fonctionnement base
sur l'envoi manuel de menus par mail par une plateforme permettant de consulter
les menus, passer commande, suivre les commandes et piloter l'activite depuis un
espace interne.

## Etat du projet

Etat au 2026-07-02 :

- documentation fonctionnelle et technique en cours de structuration dans Notion ;
- backlog, user stories, priorisation MVP et gestion agile documentes ;
- modelisation Merise disponible : MCD, MLD et MPD ;
- diagrammes UML principaux disponibles en draw.io et PNG ;
- scripts SQL de creation et d'insertion disponibles et audites ;
- scripts MongoDB de creation et de seed disponibles et audites ;
- depot nettoye des fichiers locaux inutiles comme `.DS_Store` ;
- application PHP MVC non encore implementee dans le depot ;
- maquettes UX/UI, charte graphique, deploiement et manuel utilisateur encore a produire.

Ce depot contient donc principalement la documentation technique, la modelisation
et les scripts de base de donnees. Le code applicatif PHP sera ajoute ensuite.

## Depot et rendu

- Depot GitHub vise : <https://github.com/elscribe/Github-vite-et-gourmand>
- Branche principale : `main`
- Dernier commit local de nettoyage : `9fd282c docs: polish github repository`
- Statut actuel : le depot local est pret, mais le dernier commit doit encore
  etre pousse vers GitHub depuis un terminal authentifie.
- Point de vigilance rendu : verifier que le depot est public et accessible sans
  connexion avant de transmettre le lien au jury.

Controles locaux effectues le 2026-07-02 :

- liens Markdown internes verifies ;
- fichier `.env` ignore par Git ;
- fichiers parasites `.DS_Store` et `.bkp` nettoyes ;
- `.gitignore` mis a jour pour ignorer les backups `.bkp` ;
- documentation d'organisation du depot ajoutee dans `docs/repository.md`.

## Contexte ECF

Le sujet demande une application web ou web mobile securisee, responsive,
documentee, deployee et appuyee par :

- une base de donnees relationnelle ;
- une base de donnees non relationnelle ;
- un depot GitHub public pour le rendu ;
- une documentation de gestion de projet ;
- des maquettes desktop et mobile ;
- une charte graphique ;
- un manuel utilisateur ;
- une documentation de deploiement.

## Fonctionnalites attendues

Les fonctionnalites ci-dessous sont documentees dans l'analyse, le backlog, les
diagrammes UML et la modelisation. Elles ne sont pas encore implementees dans le
code applicatif.

### Visiteur

- [ ] Consulter la page d'accueil.
- [ ] Consulter la presentation de l'entreprise.
- [ ] Consulter les avis clients valides.
- [ ] Consulter la liste des menus.
- [ ] Filtrer les menus sans rechargement complet de la page.
- [ ] Consulter le detail d'un menu.
- [ ] Acceder a la page de contact.
- [ ] Envoyer un message de contact.
- [ ] Creer un compte utilisateur.

### Utilisateur connecte

- [ ] Se connecter avec un email et un mot de passe.
- [ ] Reinitialiser son mot de passe.
- [ ] Commander un menu.
- [ ] Voir le prix detaille avant validation.
- [ ] Recevoir une confirmation de commande.
- [ ] Consulter ses commandes.
- [ ] Modifier ses informations personnelles.
- [ ] Modifier ou annuler une commande tant qu'elle n'est pas acceptee.
- [ ] Suivre les etats d'une commande acceptee.
- [ ] Laisser un avis apres une commande terminee.

### Employe

- [ ] Gerer les menus.
- [ ] Gerer les plats.
- [ ] Gerer les horaires.
- [ ] Filtrer les commandes par statut ou par client.
- [ ] Mettre a jour le statut d'une commande.
- [ ] Annuler ou modifier une commande apres contact client.
- [ ] Renseigner le motif d'annulation et le mode de contact.
- [ ] Valider ou refuser les avis clients.

### Administrateur

- [ ] Acceder aux fonctionnalites employe.
- [ ] Creer un compte employe.
- [ ] Desactiver un compte employe.
- [ ] Visualiser le nombre de commandes par menu.
- [ ] Comparer les menus avec un graphique.
- [ ] Consulter le chiffre d'affaires par menu.
- [ ] Filtrer les statistiques par menu et par periode.

## Stack technique cible

### Frontend

- HTML5.
- CSS3.
- Bootstrap 5.
- JavaScript vanilla.

### Backend

- PHP 8.
- PDO pour l'acces a la base relationnelle.
- Architecture MVC simple.
- Services pour la logique metier reutilisable.
- Middlewares pour l'authentification, les roles et les protections de routes.

### Bases de donnees

- MariaDB pour les donnees metier relationnelles.
- MongoDB pour les statistiques agregees du tableau de bord administrateur.

La base SQL reste la source de verite. MongoDB contient uniquement des agregats
de lecture recalculables a partir des commandes SQL.

Les scripts SQL restent compatibles avec MySQL 8 pour faciliter les tests en
environnement local ou de demonstration.

### Deploiement cible

- Fly.io est l'hebergeur pressenti pour la mise en ligne de l'application PHP.
- La procedure de deploiement sera documentee lorsque le code applicatif sera
  implemente.

## Architecture cible

L'application est prevue en PHP natif avec une architecture MVC simple :

```text
public/
app/
├── Controllers/
├── Models/
├── Views/
├── Core/
├── Services/
└── Middlewares/
config/
database/
docs/
```

Responsabilites principales :

- `public/` : point d'entree HTTP et assets publics.
- `app/Controllers/` : reception des requetes et coordination des actions.
- `app/Models/` : acces aux donnees SQL / NoSQL.
- `app/Views/` : rendu HTML.
- `app/Core/` : routeur, base controller, connexion et outils communs.
- `app/Services/` : logique metier reutilisable.
- `app/Middlewares/` : session, authentification, roles, CSRF.
- `config/` : configuration applicative.
- `database/` : scripts SQL, MongoDB et regles metier.
- `docs/` : documentation technique, Merise, UML et exports.

Le depot ne contient pas encore le code applicatif PHP final.

## Installation locale

### Prerequis

- Git.
- PHP 8.
- MariaDB.
- MongoDB.
- `mongosh`.
- Navigateur web moderne.

Composer et npm ne sont pas encore requis, car aucun `composer.json` ni
`package.json` n'est present dans le depot.

### Recuperer le projet

```bash
git clone https://github.com/elscribe/Github-vite-et-gourmand.git
cd Github-vite-et-gourmand
```

### Configuration

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

### Initialiser la base SQL

Les scripts SQL sont prevus pour une base de demonstration ECF.

Attention : `database/sql/create_database.sql` commence par
`DROP DATABASE IF EXISTS vite_gourmand`.

```bash
mariadb -u root -p < database/sql/create_database.sql
mariadb -u root -p < database/sql/seed_database.sql
```

Equivalent possible avec MySQL 8 pour verifier la compatibilite :

```bash
mysql -u root -p < database/sql/create_database.sql
mysql -u root -p < database/sql/seed_database.sql
```

### Initialiser MongoDB

```bash
mongosh database/mongodb/create_collections.js
mongosh database/mongodb/seed_mongodb.js
```

Les collections statistiques creees sont :

- `menu_statistics`
- `monthly_statistics`
- `dashboard_statistics`

### Lancer l'application

L'application PHP n'est pas encore implementee. Lorsque le point d'entree
`public/index.php` sera ajoute, le lancement local pourra se faire par exemple
avec :

```bash
php -S localhost:8000 -t public
```

## Configuration disponible

Le fichier `.env.example` documente les variables attendues :

| Variable | Description |
| --- | --- |
| `APP_NAME` | Nom de l'application. |
| `APP_ENV` | Environnement d'execution, par exemple `local` ou `production`. |
| `APP_DEBUG` | Activation du debug en local. |
| `APP_URL` | URL locale ou publique de l'application. |
| `APP_KEY` | Cle applicative a remplacer en local. |
| `PASSWORD_MIN_LENGTH` | Longueur minimale du mot de passe. |
| `SESSION_NAME` | Nom de la session PHP. |
| `SESSION_LIFETIME_MINUTES` | Duree de vie prevue de la session. |
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
| `STORE_CITY` | Ville de reference pour la livraison. |
| `DELIVERY_BASE_FEE` | Frais fixes hors Bordeaux. |
| `DELIVERY_PRICE_PER_KM` | Prix par kilometre hors Bordeaux. |
| `DELIVERY_DISTANCE_PROVIDER` | Methode prevue pour la distance. |

## Base de donnees

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

Le seed SQL contient notamment :

- 3 roles ;
- 13 utilisateurs ;
- 6 menus ;
- 24 plats ;
- 14 allergenes standards ;
- 20 commandes ;
- un historique de statut pour les commandes ;
- des avis, horaires, messages de contact et resets de mot de passe.

L'audit des scripts indique une compatibilite MariaDB 11 / MySQL 8 et une
execution conforme pour un environnement local ou de demonstration.

## Documentation technique

### Documentation base de donnees

- [Documentation base de donnees](docs/database/README.md)
- [Regles metier et cardinalites](database/business-rules.md)
- [Dictionnaire de donnees](docs/database/data-dictionary.md)
- [Justification SQL, MongoDB, Merise et UML](docs/database/database-choices.md)
- [Rapport d'audit de coherence](docs/database/audit-report.md)
- [Audit des scripts SQL et MongoDB](docs/database/scripts-audit.md)
- [Documentation Notion importable](docs/notion/database-documentation.md)

### Livrables Merise

- [MCD draw.io](docs/database/MCD.drawio) / [MCD PNG](docs/database/MCD.png)
- [MLD draw.io](docs/database/MLD.drawio) / [MLD PNG](docs/database/MLD.png)
- [MPD draw.io](docs/database/MPD.drawio) / [MPD PNG](docs/database/MPD.png)

### Livrables UML

- [Cas d'utilisation](docs/uml/use-case-diagram.drawio) / [PNG](docs/uml/use-case-diagram.png)
- [Diagramme de classes](docs/uml/class-diagram.drawio) / [PNG](docs/uml/class-diagram.png)
- [Sequence authentification](docs/uml/sequence-authentication.drawio) / [PNG](docs/uml/sequence-authentication.png)
- [Sequence consultation et commande](docs/uml/sequence-consultation-commande.drawio) / [PNG](docs/uml/sequence-consultation-commande.png)
- [Sequence gestion commande employe](docs/uml/sequence-gestion-commande-employe.drawio) / [PNG](docs/uml/sequence-gestion-commande-employe.png)
- [Sequence gestion avis](docs/uml/sequence-gestion-avis.drawio) / [PNG](docs/uml/sequence-gestion-avis.png)
- [Sequence dashboard administrateur MongoDB](docs/uml/sequence-dashboard-admin-mongodb.drawio) / [PNG](docs/uml/sequence-dashboard-admin-mongodb.png)

### Scripts

- [Creation SQL complete](database/sql/create_database.sql)
- [Donnees SQL de demonstration](database/sql/seed_database.sql)
- [Collections MongoDB](database/mongodb/collections.md)
- [Documents d'exemple MongoDB](database/mongodb/sample-data.json)
- [Creation collections MongoDB](database/mongodb/create_collections.js)
- [Seed MongoDB](database/mongodb/seed_mongodb.js)
- [Generation documentation BDD](scripts/generate_database_docs.py)
- [Organisation du depot GitHub](docs/repository.md)

## Securite prevue

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

## Qualite et tests

Aucun test automatise n'est encore present.

Tests a prevoir :

- tests fonctionnels des parcours visiteur, client, employe et administrateur ;
- tests des calculs de prix, reduction et frais de livraison ;
- tests des droits par role ;
- tests des statuts de commande ;
- tests des formulaires et messages d'erreur ;
- tests d'accessibilite : navigation clavier, contrastes, labels, textes alternatifs ;
- verification du dashboard administrateur alimente par MongoDB.

## Roadmap

### Termine

- [x] Analyse du besoin.
- [x] Cahier des charges.
- [x] Identification des acteurs et roles.
- [x] Backlog MVP et user stories.
- [x] Methode de gestion hybride : predictive puis agile.
- [x] Planning agile simplifie.
- [x] Choix de stack cible.
- [x] Architecture MVC cible documentee.
- [x] MCD, MLD et MPD documentes.
- [x] Diagrammes UML principaux.
- [x] Scripts SQL de creation et d'insertion.
- [x] Scripts MongoDB de creation et d'insertion.
- [x] Audit de coherence Merise / SQL / MongoDB / UML.
- [x] Fichier `.env.example`.

### En cours

- [ ] Implementation de l'application PHP MVC.
- [ ] Maquettes desktop et mobile.
- [ ] Charte graphique.
- [ ] Documentation securite.
- [ ] Documentation de deploiement.
- [ ] Manuel utilisateur.
- [ ] Recherche anglophone.
- [ ] Livrables finaux.

### Ameliorations futures

- [ ] Ajouter des tests automatises.
- [ ] Ajouter des captures d'ecran de l'application.
- [ ] Ajouter une strategie RGPD detaillee.
- [ ] Ajouter une documentation de maintenance.

## Decisions techniques

| Decision | Raison | Limite |
| --- | --- | --- |
| PHP 8 avec PDO | Stack simple, lisible et adaptee a une application web serveur. | Moins de structure prete a l'emploi qu'un framework complet. |
| Architecture MVC simple | Separation claire entre affichage, logique metier et acces aux donnees. | Demande de definir soi-meme certaines briques techniques. |
| HTML5, CSS3, Bootstrap 5 et JavaScript vanilla | Interface responsive sans framework front-end lourd. | Moins d'outillage que React, Vue ou Angular. |
| MariaDB | Donnees fortement relationnelles : utilisateurs, menus, commandes, roles. | Les scripts sont conserves compatibles MySQL 8 pour faciliter les tests. |
| MongoDB pour les statistiques | Repond a l'obligation NoSQL et isole les agregats administrateur. | Les agregats doivent rester coherents avec la base SQL. |
| Notion pour la gestion de projet | Outil simple pour suivre backlog, risques, planning et livrables. | Les elements importants doivent etre exportables ou cites dans le rendu. |
| Fly.io pressenti pour le deploiement | Plateforme compatible avec une application PHP conteneurisee et explicable a l'oral. | La procedure definitive sera validee apres implementation. |

## Informations restant a fournir

- Verification de l'acces public au depot GitHub.
- Lien de l'application deployee.
- Identifiants administrateur de demonstration.
- Informations auteur pour le document final.
- Licence eventuelle.
- Captures d'ecran de l'application.
- Exports PDF des maquettes et de la charte graphique.
- Manuel utilisateur PDF.

## Notes pour le jury

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
