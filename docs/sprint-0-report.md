# Rapport Sprint 0

Date de verification : 2026-07-17

## Perimetre

Ce rapport documente la preparation du depot avant poursuite du developpement.
Il ne remplace pas la documentation fonctionnelle, les maquettes, le manuel
utilisateur final ni la documentation de deploiement.

## Repository Status

- Branche auditee : `feature/email-notifications`.
- Depot Git detecte dans le dossier `Github/`.
- Structure MVC presente : `app/Controllers`, `app/Models`, `app/Views`,
  `app/Core`, `app/Services`, `app/Middlewares`.
- Dossiers projet presents : `config`, `database`, `docs`, `public`, `tests`,
  `scripts`, `storage`.
- Autoload PSR-4 configure : namespace `App\\` vers `app/`.
- Composer valide avec `composer validate --strict`.
- Autoload optimise genere sans erreur PSR-4.
- Syntaxe PHP valide via `composer check`.

## Problemes Corriges

- Documentation README alignee avec l'etat reel de la branche : routes et
  parcours MVC principaux deja presents, pas uniquement des placeholders.
- Documentation de structure projet mise a jour pour refleter les modeles,
  services et middlewares existants.
- Documentation depot mise a jour pour parler du code applicatif present.
- Placeholders de deploiement, manuel, securite et tests clarifies sans
  inventer de contenu final.
- Rappel de configuration production ajoute dans `.env.example`.
- Rapport Sprint 0 ajoute dans `docs/`.

## Etat Des Livrables

- Documentation technique : presente dans `README.md`, `docs/project-structure.md`,
  `docs/repository.md`, `docs/database/` et `docs/security/`.
- Documentation de gestion projet : presente dans `docs/project-management/`.
- SQL : present dans `database/sql/`.
- MongoDB : present dans `database/mongodb/`.
- Wireframes : emplacement present dans `docs/deliverables/wireframes/` et
  lien Figma reference dans le README.
- Mockups : emplacement present dans `docs/deliverables/mockups/` et lien Figma
  reference dans le README.
- Charte graphique : emplacement present dans
  `docs/deliverables/graphic-charter/` et lien Figma reference dans le README.
- Documentation de deploiement : placeholder present dans `docs/deployment/`.
- Manuel utilisateur : placeholder present dans `docs/manual/`.

## Points Restants

- Finaliser les contenus reels de deploiement et de manuel utilisateur apres
  validation de la cible d'hebergement et des parcours.
- Ajouter une suite de tests automatisee quand les parcours MVP seront
  stabilises.
- Verifier l'acces public aux liens GitHub, Figma, Notion et application avant
  rendu.
- Synchroniser les branches locales avec le remote selon la strategie Git
  retenue par le projet.

## Priorites Sprint 1

1. Stabiliser les parcours deja presents avec tests manuels reproductibles.
2. Ajouter les tests critiques : authentification, roles, commandes, prix,
   statuts, CSRF.
3. Completer la documentation de deploiement avec la plateforme retenue.
4. Completer le manuel utilisateur avec captures ou etapes verifiees.
5. Faire une revue securite finale avant presentation.

## Readiness

Le depot est pret pour poursuivre le developpement : l'architecture MVC, la
configuration, les dossiers attendus, l'autoload Composer, les routes, les pages
d'erreur et les helpers de base sont en place. Les livrables finaux manquants
sont clairement isoles comme placeholders a completer avec du contenu reel.
