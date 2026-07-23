# Documentation de deploiement Fly.io via Docker

Date de preparation : 21 juillet 2026.
Date de mise a jour finale : 23 juillet 2026.

Cette documentation decrit la procedure de deploiement appliquee pour rendre
l'application Vite & Gourmand accessible au jury. La cible retenue est Fly.io
via Docker.

URL publique :

```text
https://vite-gourmand-ecf-jmf.fly.dev
```

## Etat actuel

| Element | Etat |
|---|---|
| Application locale | Fonctionnelle en environnement PHP local. |
| Cible de deploiement | Fly.io via Docker. |
| Preparation Docker | `Dockerfile`, `.dockerignore`, `fly.toml`, `fly.mysql.toml` et `fly.mongo.toml` disponibles. |
| Depot GitHub | Public : <https://github.com/elscribe/Github-vite-et-gourmand>. |
| Branche a deployer | `main`, apres fusion de la version stable. |
| URL publique | <https://vite-gourmand-ecf-jmf.fly.dev>. |
| Base SQL | Service Fly.io `vite-gourmand-ecf-jmf-mysql`, initialise avec les scripts du depot. |
| Base MongoDB | Service Fly.io `vite-gourmand-ecf-jmf-mongo`, initialise avec les scripts du depot. |
| Emails | Mode `log` en local, SMTP a configurer en production si disponible. |

## Pre-requis serveur

L'application sera executee dans un conteneur Docker publie sur Fly.io. Les
services SQL et MongoDB devront etre accessibles depuis ce conteneur via les
variables d'environnement.

La cible de production doit fournir :

- Fly.io et `flyctl` ;
- Docker pour construire l'image ;
- PHP 8.3 dans le conteneur ;
- `mongosh` dans le conteneur pour lire les agregats MongoDB ;
- MariaDB ou MySQL 8 accessible depuis Fly.io ;
- MongoDB ou service MongoDB accessible depuis Fly.io ;
- variables d'environnement ;
- HTTPS ;
- possibilite d'ecrire dans `storage/logs/`.

## Preparation Docker et Fly.io

Les fichiers de preparation sont presents dans le depot :

- `Dockerfile` : construit l'image PHP 8.3 avec Apache, installe `pdo_mysql`
  et `mongosh`, installe Composer en mode production et sert `public/` sur le
  port 80 ;
- `.dockerignore` : exclut les secrets, dependances locales, logs et fichiers
  temporaires de l'image ;
- `fly.toml` : configuration Fly.io de l'application PHP ;
- `fly.mysql.toml` : configuration Fly.io du service MySQL ;
- `fly.mongo.toml` : configuration Fly.io du service MongoDB ;
- `fly.toml.example` : modele de reference.

Configuration appliquee :

1. Application Fly.io : `vite-gourmand-ecf-jmf`.
2. URL publique : `https://vite-gourmand-ecf-jmf.fly.dev`.
3. Region : `cdg`.
4. SQL : `vite-gourmand-ecf-jmf-mysql.internal`.
5. MongoDB : `vite-gourmand-ecf-jmf-mongo.internal`.
6. Secrets Fly.io deployes pour les valeurs sensibles, notamment `APP_KEY` et
   `DB_PASSWORD`.

## Version a deployer

Avant chaque deploiement :

1. Verifier que le code local est stable.
2. Lancer les checks :

```bash
composer check
```

3. Committer les changements.
4. Fusionner la version stable vers `main`.
5. Pousser `main` sur GitHub.
6. Verifier que le depot public contient la meme version stable.
7. Deployer avec Fly.io.
8. Tester le lien GitHub sans session connectee.

Le jury doit voir la meme version sur GitHub et sur l'application deployee.

## Variables d'environnement

Creer un fichier `.env` de production a partir de `.env.example`.

Valeurs a adapter :

```text
APP_NAME=ViteEtGourmand
APP_ENV=production
APP_DEBUG=false
APP_DISPLAY_ERRORS=false
APP_LOG_ERRORS=true
APP_URL=https://vite-gourmand-ecf-jmf.fly.dev
APP_KEY=<cle secrete unique>

SESSION_SECURE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=Lax

DB_CONNECTION=mysql
DB_HOST=vite-gourmand-ecf-jmf-mysql.internal
DB_PORT=3306
DB_NAME=vite_gourmand
DB_USER=vite_gourmand
DB_PASSWORD=<secret Fly.io>

NOSQL_CONNECTION=mongodb
NOSQL_HOST=vite-gourmand-ecf-jmf-mongo.internal
NOSQL_PORT=27017
NOSQL_DATABASE=vite_gourmand

MAIL_MAILER=log
MAIL_HOST=<hote SMTP si utilise>
MAIL_PORT=<port SMTP si utilise>
MAIL_USERNAME=<identifiant SMTP si utilise>
MAIL_PASSWORD=<mot de passe SMTP si utilise>
MAIL_FROM_ADDRESS=no-reply@vitegourmand.test
MAIL_FROM_NAME="Vite et Gourmand"
MAIL_CONTACT_TO=contact@vitegourmand.test
```

Important : le fichier `.env` ne doit jamais etre commite.

## Installation du code

Le deploiement Fly.io construit l'image depuis le depot et le `Dockerfile`.
Pour une installation manuelle equivalente :

```bash
git clone https://github.com/elscribe/Github-vite-et-gourmand.git
cd Github-vite-et-gourmand
git checkout main
composer install --no-dev --optimize-autoloader
cp .env.example .env
```

En production Fly.io, les valeurs sensibles sont gerees par les secrets Fly.io
plutot que par un fichier `.env` versionne.

## Configuration du serveur web

Le web root doit pointer vers :

```text
public/
```

Le fichier d'entree est :

```text
public/index.php
```

Les autres dossiers (`app/`, `config/`, `database/`, `storage/`) ne doivent pas
etre exposes directement comme racine web.

## Initialisation SQL

Attention : les scripts de creation recreent la base de demonstration.

```bash
mysql -u <user> -p < database/sql/create_database.sql
mysql -u <user> -p < database/sql/seed_database.sql
```

Equivalent MariaDB :

```bash
mariadb -u <user> -p < database/sql/create_database.sql
mariadb -u <user> -p < database/sql/seed_database.sql
```

## Initialisation MongoDB

MongoDB sert les statistiques administrateur.

```bash
mongosh database/mongodb/create_collections.js
mongosh database/mongodb/seed_mongodb.js
```

Collections attendues :

- `menu_statistics`
- `monthly_statistics`
- `menu_monthly_statistics`
- `dashboard_statistics`

Le conteneur Docker installe `mongosh` pour permettre au dashboard
administrateur de lire les agregats MongoDB. Si MongoDB ou `mongosh` sont
indisponibles, le code prevoit un secours SQL local pour garder le dashboard
lisible, mais le rendu final doit conserver MongoDB configure et seedable.

## Permissions

Le dossier suivant doit etre accessible en ecriture par l'application :

```text
storage/logs/
```

Il sert aux logs applicatifs et aux emails en mode `log`.

## Tests post-deploiement

Apres publication de l'URL publique, tester :

| Parcours | Route | Resultat attendu |
|---|---|---|
| Accueil public | `/` | Page visible sans connexion. |
| Menus | `/menus` | Liste des menus active. |
| Detail menu | `/menus/1` | Detail menu, plats, allergenes, CTA commande. |
| Contact | `/contact` | Formulaire disponible et message de succes apres envoi. |
| Inscription | `/inscription` | Creation compte client possible. |
| Connexion client | `/connexion` | Acces `/mon-compte` et `/commandes`. |
| Connexion employe | `/connexion` | Acces `/employe`. |
| Connexion admin | `/connexion` | Acces `/admin` et `/admin/statistiques`. |
| Securite role | `/admin` avec client | Acces refuse. |
| Deconnexion | `/deconnexion` | Session fermee. |

Comptes de demonstration :

| Role | Email | Mot de passe |
|---|---|---|
| Client | `claire.martin@example.test` | `ClientVite2026!` |
| Employe | `lucas.employee@vitegourmand.test` | `EmployeVite2026!` |
| Administrateur | `admin.jose@vitegourmand.test` | `AdminVite2026!` |

## Checklist finale de deploiement

| Verification | Statut |
|---|---|
| Branche `main` a jour avec la version stable. | A verifier apres fusion finale. |
| Depot GitHub public. | Public au 23/07/2026. |
| Dockerfile de production present. | Pret. |
| Configuration Fly.io presente. | `fly.toml`, `fly.mysql.toml`, `fly.mongo.toml`. |
| `fly.toml` cree avec le vrai nom d'application Fly.io. | Pret. |
| URL publique renseignee dans README. | Pret. |
| URL publique renseignee dans Notion. | A verifier apres mise a jour Notion. |
| URL publique testee sans session connectee. | OK au 23/07/2026. |
| Base SQL initialisee. | OK sur le service Fly.io MySQL. |
| MongoDB initialise et seedable. | OK sur le service Fly.io MongoDB. |
| Variables de production renseignees. | Variables et secrets Fly.io en place. |
| Identifiants demo testes en production. | OK au 23/07/2026. |
| Copie Studi completee avec GitHub, Notion, URL et admin. | A verifier avant depot. |

## Informations finales

```text
Hebergeur retenu : Fly.io
Mode de deploiement : Docker
URL publique : https://vite-gourmand-ecf-jmf.fly.dev
Date de verification : 23 juillet 2026
Branche de reference : main
Base SQL : vite-gourmand-ecf-jmf-mysql
Base MongoDB : vite-gourmand-ecf-jmf-mongo
Mode email : log
Compte admin teste : admin.jose@vitegourmand.test
Resultat recette production : parcours publics, client, employe, admin et statistiques MongoDB OK
```

## Phrase pour le jury

L'application est deployee sur Fly.io via Docker depuis la branche stable
`main`. Le conteneur lance PHP 8.3 avec Apache sur le dossier `public/`, les
secrets sont stockes dans les variables d'environnement Fly.io, la base SQL est
initialisee par scripts, MongoDB fournit les statistiques et les parcours
public, client, employe et administrateur ont ete verifies sur l'URL publique.
