# Documentation de deploiement

Date de preparation : 21 juillet 2026.

Cette documentation decrit la procedure de deploiement a appliquer pour rendre
l'application Vite & Gourmand accessible au jury. Elle devra etre completee avec
l'URL publique exacte apres le deploiement effectif.

## Etat actuel

| Element | Etat |
|---|---|
| Application locale | Fonctionnelle en environnement PHP local. |
| Depot GitHub | A rendre public avant rendu. |
| Branche a deployer | `main`, apres fusion de la version stable. |
| URL publique | A renseigner apres deploiement. |
| Base SQL | Scripts disponibles dans `database/sql/`. |
| Base MongoDB | Scripts disponibles dans `database/mongodb/`. |
| Emails | Mode `log` en local, SMTP a configurer en production si disponible. |

## Pre-requis serveur

L'hebergeur retenu doit fournir :

- PHP 8.3 ou compatible ;
- acces a un dossier public configure sur `public/` ;
- Composer ;
- MariaDB ou MySQL 8 ;
- MongoDB ou service MongoDB accessible ;
- variables d'environnement ;
- HTTPS ;
- possibilite d'ecrire dans `storage/logs/`.

## Version a deployer

Avant de deployer :

1. Verifier que le code local est stable.
2. Lancer les checks :

```bash
composer check
```

3. Committer les changements.
4. Fusionner la version stable vers `main`.
5. Pousser `main` sur GitHub.
6. Rendre le depot public.
7. Tester le lien GitHub sans session connectee.

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
APP_URL=<URL publique>
APP_KEY=<cle secrete unique>

SESSION_SECURE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=Lax

DB_CONNECTION=mysql
DB_HOST=<hote SQL>
DB_PORT=3306
DB_NAME=vite_gourmand
DB_USER=<utilisateur SQL>
DB_PASSWORD=<mot de passe SQL>

NOSQL_CONNECTION=mongodb
NOSQL_HOST=<hote MongoDB>
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

Sur l'environnement de production ou de preproduction :

```bash
git clone https://github.com/elscribe/Github-vite-et-gourmand.git
cd Github-vite-et-gourmand
git checkout main
composer install --no-dev --optimize-autoloader
cp .env.example .env
```

Ensuite, renseigner les variables reelles dans `.env`.

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

Reserve : si MongoDB n'est pas disponible dans l'environnement PHP, le code
prevoit un secours SQL local pour garder le dashboard lisible. Pour le rendu,
MongoDB doit rester documente et seedable.

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
| Branche `main` a jour avec la version stable. | A faire avant deploiement. |
| Depot GitHub public. | A faire avant rendu. |
| URL publique renseignee dans README. | A faire apres deploiement. |
| URL publique renseignee dans Notion. | A faire apres deploiement. |
| URL publique testee sans session connectee. | A faire apres deploiement. |
| Base SQL initialisee. | A faire sur l'hebergeur. |
| MongoDB initialise ou reserve documentee. | A faire sur l'hebergeur. |
| Variables `.env` production renseignees. | A faire sur l'hebergeur. |
| Identifiants demo testes en production. | A faire apres deploiement. |
| Copie Studi completee avec GitHub, Notion, URL et admin. | A faire en dernier. |

## Informations finales a renseigner

```text
Hebergeur retenu :
URL publique :
Date de deploiement :
Branche / commit deploye :
Base SQL :
Base MongoDB :
Mode email :
Compte admin teste :
Resultat recette production :
```

## Phrase pour le jury

L'application est deployee depuis la branche stable `main`. Le web root pointe
vers `public/`, les secrets sont stockes dans les variables d'environnement, la
base SQL est initialisee par scripts, MongoDB fournit les statistiques et les
parcours public, client, employe et administrateur sont testes apres
deploiement.
