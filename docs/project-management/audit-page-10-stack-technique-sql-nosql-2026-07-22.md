# Audit page 10 - Stack technique, SQL et NoSQL

Date : 22 juillet 2026.

## Source controlee

Page 10 de l'enonce ECF :

```text
Aucune technologie n'est obligatoire pour cet ECF, a l'exception de
l'utilisation d'une base de donnees relationnelle et non relationnelle.

Exemple de stack possible :
- Front : HTML 5, CSS (Bootstrap), JS
- Back-end : PHP avec utilisation de PDO
- Base de donnees relationnelle : MySQL, MariaDB ou PostgreSQL
- Base de donnees NoSQL : MongoDB
- Deploiement : fly.io, Heroku, Azure, Vercel

Annexe 1 : schemas de base de donnees relationnelle (MCD)
```

## Verdict

Page 10 : conforme.

Le projet demontre bien une stack technique coherente avec l'enonce :

- front HTML/CSS/Bootstrap/JavaScript ;
- back PHP MVC natif ;
- acces SQL avec PDO ;
- base relationnelle MariaDB/MySQL ;
- base NoSQL MongoDB pour les statistiques administrateur ;
- MCD/MLD/MPD presents ;
- documentation de justification SQL/NoSQL presente.

Les reserves documentaires identifiees pendant l'audit ont ete corrigees :

1. le deploiement cible est maintenant indique comme Fly.io via Docker ;
2. la documentation MongoDB ne contient plus l'ancienne formulation obsolete ;
3. le role de MongoDB est clarifie : agregats statistiques administrateur,
   avec SQL comme source de verite metier et secours local.

Precision importante : le deploiement public n'est pas effectue a ce stade. La
page 10 est validee sur le choix de stack et la preparation technique, pas sur
la publication finale de l'application.

## Grille d'audit

| Exigence page 10 | Etat | Preuves observees | Reserve / correction |
|---|---|---|---|
| Technologie libre | OK | Le projet choisit une stack simple, documentee et justifiee. | Aucune obligation de framework ; PHP MVC natif est defendable. |
| Front HTML/CSS/Bootstrap/JS | OK | `app/Views/`, `public/assets/css/style.css`, `public/assets/js/app.js`, layout avec Bootstrap 5. | A auditer plus finement dans les pages UX/RGAA. |
| Back-end PHP + PDO | OK | `composer.json` exige PHP `^8.3`, `ext-pdo`, `ext-pdo_mysql`; autoload PSR-4 `App\\`; `config/database.php` configure PDO. | OK. |
| Base relationnelle | OK | `database/sql/create_database.sql` contient 16 tables ; `seed_database.sql` contient les donnees de demo ; MCD/MLD/MPD disponibles dans `docs/database/`. | Verifier que tout est bien pousse sur la branche finale. |
| Base non relationnelle | OK | `database/mongodb/create_collections.js` cree 4 collections ; `seed_mongodb.js` alimente les donnees ; `StatisticsModel` lit MongoDB via `mongosh`. | SQL reste le secours local si MongoDB ou `mongosh` sont indisponibles. |
| MongoDB pour les statistiques | OK | `menu_statistics`, `monthly_statistics`, `menu_monthly_statistics`, `dashboard_statistics`; `/admin/statistiques` affiche la source des statistiques. | Le jury doit voir que le NoSQL n'est pas seulement un dossier annexe. |
| MCD relationnel | OK | `docs/database/MCD.drawio`, `MCD.png`, `MLD.*`, `MPD.*`, documentation database. | OK. |
| Plateforme de deploiement identifiee | OK pour page 10 | README indique Fly.io via Docker ; `Dockerfile`, `.dockerignore` et `fly.toml.example` preparent la cible. | URL publique et commit deploye a renseigner apres deploiement effectif. |

## Preuves code et documentation

### Composer et PHP

`composer.json` prouve :

- PHP `^8.3` ;
- extensions `pdo` et `pdo_mysql` ;
- autoload PSR-4 ;
- scripts `serve`, `validate:composer`, `lint`, `check`.

Controle execute pendant l'audit :

- `composer check` : OK ;
- 70 fichiers PHP controles sans erreur de syntaxe.

### Configuration SQL

`config/database.php` configure :

- driver MySQL ;
- hote/port/base/utilisateur/mot de passe via variables d'environnement ;
- charset `utf8mb4` ;
- erreurs PDO en exceptions ;
- fetch associatif ;
- emulate prepares desactive.

Lecture : c'est une configuration propre pour un projet ECF PHP/PDO.

### Base relationnelle

Le script SQL contient 16 tables :

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

Le schema contient des cles etrangeres, index, contraintes d'unicite et
contraintes `CHECK`. C'est coherent avec l'obligation de base relationnelle.

### Base NoSQL

Le script MongoDB cree 4 collections :

- `menu_statistics`
- `monthly_statistics`
- `menu_monthly_statistics`
- `dashboard_statistics`

Ces collections correspondent aux statistiques administrateur demandees dans
l'enonce : nombre de commandes par menu, chiffre d'affaires, comparaison entre
menus et filtres par periode.

`StatisticsModel` lit d'abord MongoDB avec `mongosh`. Si MongoDB est
indisponible, le modele bascule sur un secours SQL local pour que le dashboard
reste lisible.

Lecture d'audit : c'est defendable, mais il faut le presenter comme une
strategie de demonstration robuste, pas comme un remplacement de MongoDB.

## Corrections documentaires appliquees

### 1. `docs/project-structure.md`

La phrase obsolete de `docs/project-structure.md` a ete remplacee par :

```text
database/mongodb/ Scripts et donnees MongoDB pour les agregats statistiques lus par le dashboard.
```

### 2. README / deploiement

La ligne de stack deploiement du README a ete remplacee par :

```text
Deploiement prevu | Fly.io via Docker, non deploye a ce stade
```

Pour la livraison finale, il faudra renseigner l'URL publique apres le
deploiement effectif.

### 3. Formulation MongoDB

La documentation clarifie maintenant :

```text
MongoDB stocke les agregats statistiques du dashboard administrateur. SQL reste
la source de verite metier. En local, un secours SQL garde le dashboard lisible
si MongoDB ou mongosh ne sont pas disponibles.
```

## Reponse a la question page 10

Est-ce que la page 10 est couverte ?

Oui.

Le projet respecte l'obligation principale de la page 10 : une base
relationnelle et une base non relationnelle sont presentes, documentees et
reliees au besoin metier.

La cible de deploiement est maintenant fixee : Fly.io via Docker. Le
deploiement public n'est pas encore lance volontairement ; l'URL publique et le
commit deploye seront a renseigner apres publication.

## Decision pour la suite

Page 10 peut etre consideree comme validee :

- stack cible claire ;
- SQL et MongoDB presents ;
- MongoDB relie au dashboard administrateur ;
- preparation Docker/Fly.io disponible ;
- garder une phrase claire pour l'oral sur SQL source de verite / MongoDB
  agregats statistiques.
