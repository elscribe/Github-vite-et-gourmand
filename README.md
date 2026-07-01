# Vite & Gourmand

## Missing Information

Les informations suivantes n'ont pas ete trouvees ou restent a confirmer :

- code applicatif PHP ;
- fichier `composer.json` ;
- fichier `package.json` ;
- chargement applicatif des variables d'environnement a implementer ;
- choix definitif entre MySQL et MariaDB ;
- strategie d'envoi des emails ;
- methode de calcul de distance hors Bordeaux ;
- hebergeur final ;
- configuration de deploiement ;
- tests automatises ;
- outils de linting ou formatting ;
- maquettes finales ;
- charte graphique ;
- manuel utilisateur ;
- identifiants de demonstration ;
- informations auteur ;
- licence.

## Description

Vite & Gourmand est un projet d'application web pour une entreprise familiale de traiteur situee a Bordeaux.

L'application doit permettre a des visiteurs de consulter les menus, a des clients connectes de passer commande et de suivre leurs commandes, et a l'equipe interne de gerer les menus, les commandes, les avis et les statistiques.

Le probleme principal traite par le projet est la digitalisation d'un fonctionnement actuellement base sur l'envoi de menus par mail aux clients habituels. L'objectif est d'ameliorer la visibilite de l'entreprise, de rendre l'offre plus accessible et de centraliser le parcours de commande.

## Project Context

Ce projet est realise dans le cadre d'un ECF Studi "Developpeur Web et Web Mobile".

Le sujet demande une application web ou web mobile securisee, responsive, documentee, deployee et appuyee par une base de donnees relationnelle ainsi qu'une base non relationnelle.

Objectifs principaux :

- presenter l'entreprise Vite & Gourmand ;
- afficher les menus et leurs details ;
- permettre une recherche dynamique avec filtres ;
- permettre la creation de compte et la connexion ;
- permettre la commande d'un menu ;
- permettre le suivi des commandes ;
- fournir un espace employe pour la gestion operationnelle ;
- fournir un espace administrateur avec statistiques ;
- produire les livrables attendus pour l'ECF : documentation, modelisation, README, fichiers SQL, maquettes, manuel utilisateur et deploiement.

Contraintes identifiees :

- depot GitHub public attendu ;
- application deployee attendue ;
- accessibilite RGAA attendue ;
- securite applicative attendue ;
- base SQL obligatoire ;
- base NoSQL obligatoire ;
- fichiers SQL de creation et d'insertion attendus.

## Features

Etat actuel : les fonctionnalites ci-dessous sont documentees dans l'analyse et le backlog. La structure applicative existe dans le depot, mais l'implementation n'est pas encore presente dans les fichiers inspectes.

### Visitor

- [ ] Consulter la page d'accueil.
- [ ] Consulter la presentation de l'entreprise.
- [ ] Consulter les avis clients valides.
- [ ] Consulter la liste des menus.
- [ ] Filtrer les menus sans rechargement complet de la page.
- [ ] Consulter le detail d'un menu.
- [ ] Acceder a la page de contact.
- [ ] Envoyer un message de contact.
- [ ] Creer un compte utilisateur.

### Authenticated User

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

### Administrator

- [ ] Acceder aux fonctionnalites employe.
- [ ] Creer un compte employe.
- [ ] Desactiver un compte employe.
- [ ] Visualiser le nombre de commandes par menu.
- [ ] Comparer les menus avec un graphique.
- [ ] Consulter le chiffre d'affaires par menu.
- [ ] Filtrer les statistiques par menu et par periode.

### Employee

- [ ] Gerer les menus.
- [ ] Gerer les plats.
- [ ] Gerer les horaires.
- [ ] Filtrer les commandes par statut ou par client.
- [ ] Mettre a jour le statut d'une commande.
- [ ] Annuler ou modifier une commande apres contact client.
- [ ] Renseigner le motif d'annulation et le mode de contact.
- [ ] Valider ou refuser les avis clients.

## Tech Stack

Les technologies ci-dessous sont confirmees dans les documents d'analyse. Elles representent la stack cible du projet, pas encore une implementation verifiee par des fichiers de configuration applicatifs.

### Frontend

- HTML5.
- CSS3.
- Bootstrap 5.
- JavaScript vanilla.

### Backend

- PHP 8.
- PDO pour l'acces a la base relationnelle.
- Architecture MVC simple.
- Organisation cible : controllers, models, views, core, services et middlewares.

### Database

- Base relationnelle cible : MySQL ou MariaDB, choix final encore a confirmer.
- Base non relationnelle cible : MongoDB.
- Usage SQL prevu : utilisateurs, roles, menus, plats, commandes, avis, regimes, themes et allergenes.
- Usage MongoDB prevu : statistiques agregees pour l'espace administrateur.

### Architecture

- Architecture MVC simple.
- Separation prevue entre le point d'entree public, les controleurs, les modeles, les vues, les services metier, les middlewares et la configuration.
- Les documents actuels de modelisation relient les entites metier au futur code PHP.

### Deployment

- Deploiement attendu par le sujet.
- Hebergeur final non confirme.
- Pistes documentees : Render, Railway, Fly.io ou autre hebergeur compatible avec PHP, SQL et MongoDB.
- Aucune configuration de deploiement n'a ete identifiee dans les fichiers inspectes.

### Tools

- GitHub pour le depot public.
- Notion comme outil de gestion de projet.
- Mermaid pour les diagrammes UML dans la documentation.
- Bootstrap 5 prevu pour l'interface responsive.
- Aucun outil de test, linter ou formatter n'a encore ete identifie dans le code.

## Architecture

L'architecture applicative cible est une architecture MVC simple en PHP natif. Elle doit permettre de separer les responsabilites :

- les vues affichent les pages HTML ;
- les controleurs recoivent les actions utilisateur et coordonnent les traitements ;
- les modeles gerent l'acces aux donnees ;
- les services contiennent la logique metier reutilisable ;
- les middlewares protegent les routes sensibles ;
- les bases SQL et NoSQL separent les donnees metier et les statistiques.

Arborescence actuelle inspectee :

```text
VIte-et-Gourmand/
├── app/
│   ├── Controllers/
│   ├── Core/
│   ├── Models/
│   └── Views/
├── config/
├── database/
├── docs/
├── public/
├── .env.example
└── README.md
```

Le depot contient la structure applicative cible, mais les dossiers applicatifs sont encore vides au moment de la generation de ce README.

Architecture cible documentee :

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

## Installation

L'installation locale ne peut pas encore etre verifiee, car aucun fichier applicatif comme `composer.json`, `package.json`, script SQL ou point d'entree PHP n'a ete trouve. Les fichiers d'environnement d'exemple existent et documentent les variables attendues.

Commandes a prevoir lorsque l'application sera implementee :

```bash
git clone <repository-url>
cd <project-folder>
```

Si le projet reste en PHP natif sans dependances Composer :

```bash
php -S localhost:8000 -t public
```

Si Composer est ajoute plus tard :

```bash
composer install
php -S localhost:8000 -t public
```

Si des dependances front-end sont ajoutees plus tard :

```bash
npm install
npm run dev
```

Prerequis prevus :

- PHP 8 ;
- serveur SQL MySQL ou MariaDB ;
- MongoDB ;
- navigateur web moderne ;
- Git.

## Configuration

Les fichiers d'environnement suivants sont disponibles :

- `.env` : fichier local ignore par Git ;
- `.env.example` : modele principal versionnable.

Les variables ci-dessous sont documentees pour la future implementation et devront etre chargees par le code applicatif.

| Variable | Required | Description |
| --- | --- | --- |
| `APP_ENV` | Yes | Environnement d'execution, par exemple `local` ou `production`. |
| `APP_URL` | Yes | URL locale ou publique de l'application. |
| `DB_HOST` | Yes | Hote de la base SQL. |
| `DB_PORT` | Yes | Port de la base SQL. |
| `DB_NAME` | Yes | Nom de la base SQL. |
| `DB_USER` | Yes | Utilisateur SQL. |
| `DB_PASSWORD` | Yes | Mot de passe SQL. |
| `NOSQL_HOST` | Yes | Hote MongoDB local ou distant. |
| `NOSQL_PORT` | Yes | Port MongoDB. |
| `NOSQL_DATABASE` | Yes | Base MongoDB utilisee pour les statistiques. |
| `MAIL_HOST` | To confirm | Serveur utilise pour l'envoi des emails. |
| `MAIL_PORT` | To confirm | Port du serveur mail. |
| `MAIL_USER` | To confirm | Identifiant du service mail. |
| `MAIL_PASSWORD` | To confirm | Mot de passe ou token du service mail. |

Secrets et mots de passe ne doivent jamais etre versionnes.

## Usage

Usage attendu apres implementation :

1. Demarrer l'application en local.
2. Importer le schema SQL et les donnees de demonstration.
3. Configurer la connexion SQL et MongoDB.
4. Ouvrir l'application dans un navigateur.
5. Tester les parcours principaux : consultation des menus, filtres, creation de compte, connexion, commande, suivi de commande, gestion employe et statistiques administrateur.

Parcours de demonstration recommandes pour l'ECF :

- visiteur : consulter et filtrer les menus ;
- utilisateur : creer un compte, commander un menu, suivre une commande ;
- employe : mettre a jour une commande et moderer un avis ;
- administrateur : creer ou desactiver un employe et consulter les statistiques.

Des captures d'ecran ou GIFs seront utiles lorsque l'interface sera implementee.

## Database Design

La modelisation part du MCD fourni dans l'enonce ECF. Les ajouts au schema initial sont documentes lorsqu'ils couvrent une exigence explicite de l'enonce : galerie d'images, horaires, contact, reinitialisation de mot de passe, historique des statuts et statistiques NoSQL.

Point d'entree de la documentation :

- [Documentation base de donnees](docs/database/README.md)
- [Regles metier et cardinalites](database/business-rules.md)
- [Dictionnaire de donnees](docs/database/data-dictionary.md)
- [Justification SQL, MongoDB, Merise et UML](docs/database/database-choices.md)
- [Rapport d'audit de coherence](docs/database/audit-report.md)

Livrables Merise :

- [MCD draw.io](docs/database/MCD.drawio) / [MCD PNG](docs/database/MCD.png)
- [MLD draw.io](docs/database/MLD.drawio) / [MLD PNG](docs/database/MLD.png)
- [MPD draw.io](docs/database/MPD.drawio) / [MPD PNG](docs/database/MPD.png)

Livrables UML :

- [Cas d'utilisation](docs/uml/use-case-diagram.drawio) / [PNG](docs/uml/use-case-diagram.png)
- [Diagramme de classes](docs/uml/class-diagram.drawio) / [PNG](docs/uml/class-diagram.png)
- [Sequence authentification](docs/uml/sequence-authentication.drawio) / [PNG](docs/uml/sequence-authentication.png)
- [Sequence consultation et commande](docs/uml/sequence-consultation-commande.drawio) / [PNG](docs/uml/sequence-consultation-commande.png)
- [Sequence gestion commande employe](docs/uml/sequence-gestion-commande-employe.drawio) / [PNG](docs/uml/sequence-gestion-commande-employe.png)
- [Sequence gestion avis](docs/uml/sequence-gestion-avis.drawio) / [PNG](docs/uml/sequence-gestion-avis.png)
- [Sequence dashboard administrateur MongoDB](docs/uml/sequence-dashboard-admin-mongodb.drawio) / [PNG](docs/uml/sequence-dashboard-admin-mongodb.png)

Base relationnelle :

- [Creation SQL](database/sql/create.sql)
- [Donnees de demonstration](database/sql/seed.sql)
- [Index SQL](database/sql/indexes.sql)
- [Vues SQL](database/sql/views.sql)

Base NoSQL :

- [Collections MongoDB](database/mongodb/collections.md)
- [Documents d'exemple MongoDB](database/mongodb/sample-data.json)

Documentation Notion :

- [Page Notion importable](docs/notion/database-documentation.md)

## Security

Mecanismes de securite prevus dans la documentation :

- mots de passe haches ;
- validation des donnees cote serveur ;
- validation des formulaires cote client ;
- requetes preparees avec PDO ;
- protection contre les injections SQL ;
- protection contre les failles XSS ;
- gestion des roles et autorisations ;
- protection des routes sensibles ;
- reinitialisation de mot de passe securisee ;
- messages d'erreur prudents ;
- prise en compte des donnees personnelles selon le RGPD.

Mecanismes non encore verifies dans le code :

- authentification ;
- autorisation par role ;
- protection CSRF ;
- politique de session ;
- sanitisation effective des sorties ;
- journalisation des erreurs ;
- chiffrement ou stockage securise des secrets.

## Quality Assurance

Aucun test automatise n'a ete identifie dans le dossier inspecte.

Strategie qualite recommandee :

- ajouter des tests fonctionnels sur les parcours principaux ;
- ajouter des tests unitaires sur les calculs de prix, remises et frais de livraison ;
- tester les regles de commande selon les statuts ;
- tester les autorisations par role ;
- verifier l'accessibilite des pages principales ;
- documenter les commandes de test dans ce README lorsque l'application sera implementee.

Commandes a completer apres mise en place des outils :

```bash
# Exemple si PHPUnit est ajoute
vendor/bin/phpunit
```

```bash
# Exemple si un linter front-end est ajoute
npm run lint
```

## Roadmap

### Completed

- [x] Analyse du besoin.
- [x] Cahier des charges.
- [x] Identification des roles utilisateurs.
- [x] Backlog MVP.
- [x] Choix de stack cible.
- [x] Architecture MVC cible documentee.
- [x] MCD, MLD et MPD documentes.
- [x] Diagrammes UML principaux.
- [x] Agent README renomme en `agents/readme-agent.md`.
- [x] Fichiers d'environnement simples pour l'ECF : `.env` et `.env.example`.
- [x] Scripts SQL de creation, insertion, vues et index.
- [x] Strategie MongoDB pour les statistiques administrateur.
- [x] Historique des statuts de commande documente.
- [x] Galerie d'images de menus integree au modele.

### In Progress

- [ ] Finaliser le choix entre MySQL et MariaDB.
- [ ] Realiser les maquettes desktop et mobile.
- [ ] Produire la charte graphique.
- [ ] Implementer l'application PHP MVC.
- [ ] Documenter la securite et le deploiement.

### Future Improvements

- [ ] Ajouter des tests automatises.
- [ ] Ajouter une documentation de deploiement detaillee.
- [ ] Ajouter des captures d'ecran de l'application.
- [ ] Ajouter un manuel utilisateur PDF.
- [ ] Ajouter une strategie RGPD plus detaillee.

## Documentation

- [Contexte du projet](../Analyse/analysis/project-context.md)
- [Cahier des charges](../Analyse/analysis/cahier-des-charges.md)
- [Besoins fonctionnels et non fonctionnels](../Analyse/analysis/requirements.md)
- [Roles utilisateurs](../Analyse/analysis/user-roles.md)
- [Choix techniques](../Analyse/analysis/technical-choices.md)
- [Decision de stack](../Analyse/analysis/stack-decision.md)
- [Questions a clarifier](../Analyse/analysis/questions-to-clarify.md)
- [Backlog](../Gestion/backlog.md)
- [Checklist livrables ECF](../Gestion/checklist-livrables.md)
- [Journal de bord](../Gestion/journal-de-bord.md)
- [Programme 15 jours](../Gestion/programme-15-jours.md)
- [Informations de rendu](../Oral/informations-rendu.md)
- [Contexte maître pour agents et développeurs](../AGENT.md)
- [Documentation officielle base de donnees](../docs/database.md)

## Author

Author information not provided.

## License

No license has been specified for this project.

## Skills Demonstrated

Le projet demontre deja plusieurs competences attendues dans un contexte ECF :

- analyse d'un besoin client ;
- transformation d'un enonce en cahier des charges ;
- identification des roles et droits ;
- redaction de user stories ;
- priorisation MVP ;
- choix d'une stack technique defendable ;
- modelisation relationnelle ;
- preparation d'une architecture MVC ;
- production de diagrammes UML ;
- prise en compte de la securite, du RGPD, de l'accessibilite et du deploiement.

Les competences d'implementation devront etre completees lorsque le code applicatif sera developpe.

## Technical Decisions

| Decision | Reason | Trade-off |
| --- | --- | --- |
| PHP 8 avec PDO | Stack simple, adaptee a une application web serveur et defendable pour un niveau junior. | Moins de structure prete a l'emploi qu'un framework complet. |
| Architecture MVC simple | Separation claire entre affichage, logique metier et acces aux donnees. | Demande de definir soi-meme certaines briques techniques. |
| HTML5, CSS3, Bootstrap 5 et JavaScript vanilla | Permet de construire une interface responsive sans framework front-end lourd. | Moins d'outillage que React, Vue ou Angular. |
| MySQL ou MariaDB | Adapted aux donnees fortement relationnelles : utilisateurs, menus, commandes, roles. | Choix final encore a effectuer. |
| MongoDB pour les statistiques | Repond a l'obligation NoSQL et isole les agregats administrateur. | Il faut maintenir la coherence entre SQL et les statistiques agregees. |
| Notion pour la gestion de projet | Outil simple pour suivre les livrables, decisions et priorites. | Les donnees de suivi doivent etre exportees ou referencees clairement pour le rendu. |

## Challenges Encountered

- Transformer un enonce complet en perimetre MVP clair.
- Distinguer les fonctionnalites indispensables des ameliorations.
- Justifier l'usage d'une base SQL et d'une base NoSQL sans complexifier inutilement le projet.
- Preparer une architecture MVC simple mais suffisamment claire pour etre maintenable.
- Anticiper la securite, l'accessibilite et le RGPD avant l'implementation.

## Possible Improvements

- [ ] Implementer le code applicatif dans le dossier projet.
- [x] Completer le fichier `.env.example`.
- [ ] Ajouter les scripts SQL.
- [ ] Ajouter les collections MongoDB documentees.
- [ ] Ajouter des tests automatises.
- [ ] Ajouter une documentation de deploiement.
- [ ] Ajouter des captures d'ecran.
- [ ] Finaliser les maquettes et la charte graphique.
- [ ] Renforcer la validation et la gestion d'erreurs.
- [ ] Documenter precisement les choix RGPD.

## Jury Notes

Ce que le projet prouve actuellement :

- la comprehension du besoin client ;
- la capacite a cadrer un projet web complet ;
- la capacite a produire une modelisation de donnees ;
- la capacite a justifier une stack technique ;
- la preparation des livrables attendus pour un ECF.

Choix a expliquer a l'oral :

- pourquoi une stack PHP native MVC plutot qu'un framework complet ;
- pourquoi SQL pour les donnees metier et MongoDB pour les statistiques ;
- comment les roles structurent les acces ;
- comment les commandes changent d'etat ;
- comment les regles de prix, reduction et livraison seront controlees ;
- comment la securite sera appliquee dans le code.

Fichiers ou workflows a montrer :

- cahier des charges ;
- backlog ;
- modelisation MCD, MLD, MPD ;
- diagrammes UML ;
- futur parcours de commande ;
- futur back-office employe et administrateur ;
- future procedure d'installation locale.

Limites connues a presenter honnetement :

- l'application n'est pas encore implementee dans les fichiers inspectes ;
- le choix SQL final est encore ouvert ;
- le deploiement n'est pas encore documente ;
- les tests automatises ne sont pas encore en place ;
- les maquettes et documents PDF restent a produire.

## Self-Review

| Criterion | Score | Notes |
| --- | ---: | --- |
| Completeness | 8/10 | Les sections demandees sont presentes, mais plusieurs elements dependent encore de l'implementation. |
| Clarity | 9/10 | Le README distingue clairement l'etat documente, l'etat prevu et les informations manquantes. |
| Reproducibility | 6/10 | Les variables d'environnement sont documentees, mais l'installation reste incomplete tant que le code et les scripts SQL ne sont pas finalises. |
| Professionalism | 9/10 | Le ton et la structure sont adaptes a GitHub et a un jury ECF. |
| Jury readiness | 8/10 | Le document aide a defendre le projet, mais devra etre mis a jour apres implementation et deploiement. |

Gaps restants : implementation applicative, scripts SQL, configuration locale, tests, deploiement, captures d'ecran, manuel utilisateur, charte graphique et informations auteur.
