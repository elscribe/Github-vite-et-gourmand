# Rapport d'implementation des user stories

Source fonctionnelle : backlog Notion `Gestion de projet` et checklist `Vite & Gourmand`.
Date de mise a jour : 16 juillet 2026.

## Methode suivie

Chaque fonctionnalite suit le meme cycle :

```text
Route HTTP -> Controleur -> Modele -> Base de donnees -> Vue -> Test manuel
```

Les formulaires sensibles utilisent un token CSRF. Les espaces prives utilisent la session PHP et les middlewares `AuthMiddleware` / `RoleMiddleware`.

Le detail des erreurs rencontrees et des solutions appliquees est conserve dans
`docs/project-management/user-story-debug-log.md`.

## Synthese par user story

| US | Fonctionnalite | Etat | Fichiers principaux | Test manuel |
|---|---|---|---|---|
| US-001 | Accueil avec avis valides | Valide renforce | `HomeController`, `ReviewModel`, `home/index.php`, `seed_database.sql` | `GET /` affiche les avis issus de `ReviewModel::findValidated(3)` et seulement les statuts `valide`. |
| US-002 | Liste publique des menus | Valide | `MenuController`, `MenuModel`, `menus/index.php` | `GET /menus` sans connexion. |
| US-003 | Filtres menus dynamiques | Valide | `MenuModel`, `menus/index.php`, `app.js` | Filtrer par theme/regime/prix/personnes sans rechargement. |
| US-004 | Detail complet d'un menu | Valide | `MenuModel`, `MenuController`, `menus/show.php` | `GET /menus/1` affiche l'image principale synchronisee, la galerie des plats, allergenes, conditions, stock. |
| US-005 | Contact entreprise | Valide | `ContactController`, `ContactModel`, `contact/create.php` | Envoi formulaire puis insertion `contact_messages`. |
| US-006 | Creation de compte | Valide | `AuthController`, `UserModel`, `auth/register.php` | POST `/inscription`, role client impose. |
| US-007 | Connexion/deconnexion | Valide | `AuthController`, `Session`, `auth/login.php` | Connexion client/employe/admin, menu adapte au role. |
| US-008 | Mot de passe oublie | Valide demo | `PasswordResetModel`, vues auth reset | Token hash, expiration, reinitialisation locale. |
| US-009 | Modification profil | Valide | `AccountController`, `UserModel`, `account/show.php` | POST `/mon-compte`. |
| US-010 | Commander un menu | Valide | `OrderController`, `OrderModel`, `orders/create.php` | POST `/commandes` cree commande + historique initial. |
| US-011 | Calcul prix/remise/livraison | Valide | `OrderModel::calculateTotals`, `app.js` | Menu Cocktail Bordelais, 15 pers., Pessac 10 km = 307,90 EUR. |
| US-012 | Historique commandes client | Valide | `OrderController::index`, `orders/index.php` | `GET /commandes`. |
| US-013 | Suivi statut commande | Valide | `OrderModel::findHistory`, `orders/show.php` | Detail commande affiche timeline. |
| US-014 | Modifier/annuler avant acceptation | Valide | `OrderController::edit/update/cancel` | Autorise uniquement statut `en_attente`. |
| US-015 | Liste commandes employe | Valide | `OrderController::employeeIndex`, `OrderModel::findAll`, `employee/orders.php` | Filtre par statut/client, coordonnees client visibles. |
| US-016 | Mise a jour statut employe | Valide | `OrderModel::changeStatusByEmployee`, `MailService` | POST statut, historique ajoute, emails client sur statuts sensibles, commandes cloturees verrouillees. |
| US-017 | Annulation employe avec motif | Valide renforce | `OrderModel::cancelByEmployee`, `employee/orders.php` | Annulation impossible via select statut, mode `email/gsm` + motif obligatoires, commandes cloturees non annulables. |
| US-018 | Moderation avis | Valide | `ReviewController`, `ReviewModel`, `employee/reviews.php` | Avis client en attente puis validation employe. |
| US-019 | Gestion menus | Valide admin, decision employe documentee | `MenuModel`, `AdminController`, `admin/menus.php` | Creation/modification des champs principaux, association des plats et gestion galerie par administrateur ; `menu_images.position = 1` pilote l'image principale accueil/catalogue/detail. |
| US-020 | Gestion plats | Valide admin, decision employe documentee | `DishModel`, `AdminController`, `admin/dishes.php` | Creation/modification des plats + association des allergenes par administrateur. |
| US-021 | Gestion horaires | Valide admin, decision employe documentee | `ScheduleModel`, `AdminController`, `admin/schedules.php`, `layouts/main.php` | `GET/POST /admin/horaires`, puis affichage public dynamique dans le footer. |
| US-022 | Comptes employes | Valide | `UserModel`, `AdminController`, `MailService`, `admin/employees.php` | Creation employe + notification email sans mot de passe + activation/desactivation. |
| US-023 | Dashboard admin | Valide | `AdminController`, `StatisticsModel`, `admin/dashboard.php` | `GET /admin` avec compte admin. |
| US-024 | Comparer commandes par menu | Valide | `StatisticsModel::menuStatistics` | Graphique barres par menu. |
| US-025 | CA par menu/periode | Valide avec reserve | `admin/statistics.php`, scripts MongoDB | Filtre menu/periode OK. Actuel SQL local, MongoDB documente. |
| US-026 | Accessibilite RGAA | Partiel solide | Toutes vues formulaires | Labels, contrastes, alt images, responsive. Audit final a faire. |
| US-027 | Securite par role | Valide | Middlewares, routes | Client bloque employe/admin, admin accede employe. |

## Tests deja realises

- `composer check` : validation Composer et lint PHP complet.
- Pages publiques testees : `/`, `/menus`, `/menus/1`, `/contact`, `/connexion`, `/inscription`, `/mot-de-passe/oublie`.
- Pages client testees : `/mon-compte`, `/commandes`, `/commandes/creation`.
- Pages employe testees : `/employe`, `/employe/commandes`, `/employe/avis`.
- Pages admin testees : `/admin`, `/admin/statistiques`, `/admin/employes`, `/admin/horaires`, `/admin/menus`, `/admin/plats`.
- Associations admin testees : `/admin/menus` affiche les plats associes, gere la galerie `menu_images`, `/admin/plats` affiche les allergenes.
- Decision de perimetre : l'employe ne modifie pas menus/plats/horaires ; justification dans `docs/project-management/decision-role-employe-menus-2026-07-22.md`.
- Connexion admin : `admin.jose@vitegourmand.test` / `AdminVite2026!`.
- Connexion employe : `lucas.employee@vitegourmand.test` / `EmployeVite2026!`.
- Connexion client : `claire.martin@example.test` / `ClientVite2026!`.
- Exemple de calcul serveur : Menu Cocktail Bordelais, 15 personnes a Pessac,
  10 km = `307,90 EUR`.
- Changement statut employe : #21 de `en_attente` a `acceptee`, puis `terminee`.
- Annulation employe : le statut `annulee` est bloque dans le formulaire de statut et doit passer par le formulaire motive.
- Depot avis client sur #21, moderation en `valide`.
- Reinitialisation mot de passe par token local.
- Notifications email en mode log : inscription, reset, commande creee, invitation avis, rappel retour materiel, creation employe et contact.

## Points a expliquer au jury

- Le modele MVC separe les responsabilites : le controleur orchestre, le modele parle a la base, la vue affiche.
- Les requetes SQL sensibles sont preparees avec PDO.
- Les mots de passe sont stockes avec `password_hash`, jamais en clair.
- Les formulaires POST utilisent `_csrf_token`.
- Les droits sont verifies cote serveur par middleware, pas seulement par l'affichage du menu.
- Les statuts de commande sont historises dans `commande_statuts`.
- MongoDB est prepare par scripts et documentation pour les statistiques ; dans l'environnement PHP local actuel, l'extension MongoDB n'est pas installee, donc l'ecran admin utilise une aggregation SQL locale transparente.

## Reste conseille avant rendu final

- Relire `docs/project-management/public-layout-documentation.md` pour expliquer
  la partie layout public au jury.
- Relire `docs/project-management/journal-de-bord-public-layout.md` pour
  presenter les decisions de la session et le premier commit.
- Rejouer un test de galerie admin si la demo finale utilise un fichier local different des assets seedes.
- Faire un audit responsive visuel mobile/desktop.
- Remplacer les visuels de demonstration par les exports definitifs Figma si disponibles.
- Synchroniser Notion : marquer les US validees, partielles ou reportees.
- Relire le README avant rendu pour completer les liens publics definitifs.
