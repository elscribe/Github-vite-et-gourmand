# Rapport d'implementation des user stories

Source fonctionnelle : backlog Notion `Gestion de projet` et checklist `Vite & Gourmand`.
Date de mise a jour : 15 juillet 2026.

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
| US-001 | Accueil avec avis valides | Valide | `HomeController`, `ReviewModel`, `home/index.php` | `GET /` affiche seulement les avis `valide`. |
| US-002 | Liste publique des menus | Valide | `MenuController`, `MenuModel`, `menus/index.php` | `GET /menus` sans connexion. |
| US-003 | Filtres menus dynamiques | Valide | `MenuModel`, `menus/index.php`, `app.js` | Filtrer par theme/regime/prix/personnes sans rechargement. |
| US-004 | Detail complet d'un menu | Valide | `MenuModel`, `MenuController`, `menus/show.php` | `GET /menus/1` affiche images, plats, allergenes, conditions, stock. |
| US-005 | Contact entreprise | Valide | `ContactController`, `ContactModel`, `contact/create.php` | Envoi formulaire puis insertion `contact_messages`. |
| US-006 | Creation de compte | Valide | `AuthController`, `UserModel`, `auth/register.php` | POST `/inscription`, role client impose. |
| US-007 | Connexion/deconnexion | Valide | `AuthController`, `Session`, `auth/login.php` | Connexion client/employe/admin, menu adapte au role. |
| US-008 | Mot de passe oublie | Valide demo | `PasswordResetModel`, vues auth reset | Token hash, expiration, reinitialisation locale. |
| US-009 | Mon compte + modification profil | Valide | `AccountController`, `UserModel`, `account/show.php`, `account/edit.php` | `GET /mon-compte`, puis `GET/POST /mon-compte/modifier`. |
| US-010 | Commander un menu | Valide | `OrderController`, `OrderModel`, `orders/create.php` | POST `/commandes` cree commande + historique initial. |
| US-011 | Calcul prix/remise/livraison | Valide | `OrderModel::calculateTotals`, `app.js` | Menu Cocktail Bordelais, 15 pers., Pessac 10 km = 307,90 EUR. |
| US-012 | Historique commandes client | Valide | `OrderController::index`, `orders/index.php` | `GET /commandes`. |
| US-013 | Suivi statut commande | Valide | `OrderModel::findHistory`, `orders/show.php` | Detail commande affiche timeline. |
| US-014 | Modifier/annuler avant acceptation | Valide | `OrderController::edit/update/cancel` | Autorise uniquement statut `en_attente`. |
| Avis client | Avis apres commande terminee | Valide | `ReviewController`, `ReviewModel`, `reviews/index.php`, `reviews/_form.php` | `GET /avis`, depot en statut `en_attente`. |
| US-015 | Liste commandes employe | Valide | `OrderController::employeeIndex`, `employee/orders.php` | Filtre par statut ou client. |
| US-016 | Mise a jour statut employe | Valide | `OrderModel::changeStatusByEmployee` | POST statut, historique ajoute. |
| US-017 | Annulation employe avec motif | Valide | `OrderModel::cancelByEmployee` | Mode `email/gsm` + motif obligatoires. |
| US-018 | Moderation avis | Valide | `ReviewController`, `ReviewModel`, `employee/reviews.php` | Avis client en attente puis validation employe. |
| US-019 | Gestion menus | Valide simple | `MenuModel`, `AdminController`, `admin/menus.php` | `GET/POST /admin/menus`, creation et modification des champs principaux. |
| US-020 | Gestion plats | Valide simple | `DishModel`, `AdminController`, `admin/dishes.php` | `GET/POST /admin/plats`, creation et modification des plats. |
| US-021 | Gestion horaires | Valide simple | `ScheduleModel`, `AdminController`, `admin/schedules.php` | `GET/POST /admin/horaires`. |
| US-022 | Comptes employes | Valide simple | `UserModel`, `AdminController`, `admin/employees.php` | Creation employe + activation/desactivation, sans creation admin. |
| US-023 | Dashboard admin | Valide | `AdminController`, `StatisticsModel`, `admin/dashboard.php` | `GET /admin` avec compte admin. |
| US-024 | Comparer commandes par menu | Valide | `StatisticsModel::menuStatistics` | Graphique barres par menu. |
| US-025 | CA par menu/periode | Valide avec reserve | `admin/statistics.php`, scripts MongoDB | Filtre menu/periode OK. Actuel SQL local, MongoDB documente. |
| US-026 | Accessibilite RGAA | Partiel solide | Toutes vues formulaires | Labels, contrastes, alt images, responsive. Audit final a faire. |
| US-027 | Securite par role | Valide | Middlewares, routes | Client bloque employe/admin, admin accede employe. |

## Tests deja realises

- `composer check` : validation Composer et lint PHP complet.
- Pages publiques testees : `/`, `/menus`, `/menus/1`, `/contact`, `/connexion`, `/inscription`, `/mot-de-passe/oublie`.
- Pages client testees : `/mon-compte`, `/mon-compte/modifier`, `/commandes`, `/commandes/creation`, `/commandes/{id}`, `/avis`.
- Pages employe testees : `/employe`, `/employe/commandes`, `/employe/avis`.
- Pages admin testees : `/admin`, `/admin/statistiques`, `/admin/employes`, `/admin/horaires`, `/admin/menus`, `/admin/plats`.
- Connexion admin : `admin.jose@vitegourmand.test` / `AdminVite2026!`.
- Connexion employe : `lucas.employee@vitegourmand.test` / `EmployeVite2026!`.
- Connexion client : `claire.martin@example.test` / `ClientVite2026!`.
- Exemple de calcul serveur : Menu Cocktail Bordelais, 15 personnes a Pessac,
  10 km = `307,90 EUR`.
- Changement statut employe : #21 de `en_attente` a `acceptee`, puis `terminee`.
- Depot avis client sur commande terminee, creation en `en_attente`, puis moderation possible en espace employe.
- Reinitialisation mot de passe par token local.

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
- Decider si les associations admin avancees images/plats/allergenes sont obligatoires pour la demo finale.
- Faire un audit responsive visuel mobile/desktop.
- Remplacer les visuels de demonstration par les exports definitifs Figma si disponibles.
- Synchroniser Notion : marquer les US validees, partielles ou reportees.
- Relire le README avant rendu pour completer les liens publics definitifs.
