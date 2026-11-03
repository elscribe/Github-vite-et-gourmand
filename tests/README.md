# Tests automatisés

Le projet utilise PHPUnit 12 pour vérifier les règles métier, l'accès aux
données et les contrôles de sécurité. PHPUnit est une dépendance de
développement uniquement : l'image Docker installe avec `--no-dev`.

## Lancer les tests

```bash
composer install
composer test          # toutes les suites
composer test:unit     # tests unitaires seuls, sans base de donnees
```

## Organisation

- `Unit/` : tests sans base de donnees.
  - `OrderTotalsTest` : prix du menu, remise de 10 % a partir du minimum + 5
    personnes, livraison gratuite a Bordeaux, 5 EUR + 0,59 EUR/km ailleurs,
    et exemple documente du Cocktail Bordelais a 307,90 EUR.
  - `OrderValidationTest` : minimum de personnes, dates mal formees,
    incompletes (`2026-11-3`) ou passees.
  - `UserRoleNormalizationTest` : conversion des rôles SQL vers les rôles
    applicatifs utilisés par les middlewares.
  - `CsrfMiddlewareTest` : requêtes GET autorisées, jeton valide accepté,
    jeton absent, incorrect, partiel ou sans session refusés.
  - `MailServiceTest` : écriture d'un e-mail dans le journal ; un journal
    inaccessible renvoie `false` sans exception, pour ne pas faire échouer une
    action déjà enregistrée.
- `Integration/` : tests utilisant une base temporaire ou le serveur HTTP local.
  - `OrderStockTest` : réservation, refus à stock nul, menu inactif, minimum
    modifié, fiche administrateur périmée, annulations répétées, anciennes
    commandes, modifications client et employé (y compris sans changement ou
    après annulation) et deux réservations simultanées de la dernière place.
    Ce dernier test est un outil de contrôle : il lance deux processus PHP et
    vérifie qu'ils attendent tous deux le verrou SQL avant de conclure.
  - `OrderStockHttpTest` : menu épuisé, refus d'une commande envoyée malgré
    l'interface, création quand une place est disponible et commande conservée
    si l'e-mail de confirmation échoue, en HTTP local.
    Ses notifications sont écrites dans un journal temporaire isolé grâce à
    `MAIL_LOG_PATH`, puis supprimées avec les fichiers de session du test.
  - `OrderStatusTransitionTest` : transitions autorisees et interdites,
    `livre -> terminee` sans materiel, `livre -> en_attente_retour_materiel`
    avec materiel, double soumission.
  - `MiddlewareAccessTest` : compte desactive, role non autorise, role SQL
    normalise (`Employee` -> `employe`).
  - `ReviewEligibilityTest` : avis possible seulement sur sa commande terminee,
    une seule fois.
  - `LoginRedirectTest` : redirections de connexion selon le rôle et refus d'un
    compte désactivé, via `php -S` et le cycle CSRF réel.
- `Support/` : base temporaire et lanceur de middleware en processus séparé.

## Base de test

Les tests d'integration lisent les identifiants de `.env`, creent une base
`vg_test_<aleatoire>` a partir de `database/sql/create_database.sql` et
`seed_database.sql` (nom de base remplace, `DROP DATABASE` retire), puis la
suppriment en fin d'execution. Chaque processus ne supprime que la base qu'il
a lui-même créée. La base `vite_gourmand` n'est jamais touchee.

Si aucun serveur SQL n'est joignable, les tests d'intégration sont ignorés
(`skipped`) et les tests unitaires s'executent normalement.

## Résultat de référence

- **Environnement local (état actuel, 3 novembre 2026)** : 102 tests et
  305 assertions réussis, sans test ignoré, avec PHP 8.5.8 et MariaDB locale,
  en ordre normal puis aléatoire. `composer check` réussi.
- **Environnement Docker isolé (état actuel, 3 novembre 2026)** : 102 tests et
  305 assertions réussis, sans test ignoré, avec PHP 8.3.36 et MySQL 8.4.10,
  en ordre normal puis aléatoire ; `composer check` réussi dans le conteneur.
  Après les tests : aucun serveur `php -S` actif et aucune base `vg_test_*`.
  Le dépôt était copié dans un conteneur temporaire, MySQL n'avait aucun volume
  persistant, puis conteneurs et réseau ont été supprimés.

Cette exécution a révélé un défaut du test `LoginRedirectTest` sous Linux : le
serveur lancé via une chaîne de commande (`sh -c`) survivait à la fin du test.
Il est désormais lancé avec un tableau d'arguments, sans shell intermédiaire.

Ces résultats valident le code testé sous PHP 8.3, pas l'image de production
(construite avec `--no-dev`, donc sans PHPUnit) ni le déploiement public.

Un arrêt forcé du processus (`kill -9`) peut laisser une base temporaire.
Sa suppression doit être décidée après identification, car une base ancienne
peut encore être utilisée par une autre session de test.
