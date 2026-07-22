# PROJECT STATE

## 1. Project Summary
- Project name: Vite & Gourmand.
- Purpose: application web ECF Studi pour un traiteur familial bordelais, avec consultation des menus, commandes client, suivi interne, moderation des avis, administration et statistiques.
- Current completion percentage: non chiffre officiellement dans le depot. Etat factuel: MVP applicatif principal implemente localement; livrables finaux ECF encore incomplets.
- Current sprint: branche active `feature/back-office`; phase de finalisation back-office et preparation des livrables ECF.
- Current priorities: stabiliser et pousser la version finale, rendre GitHub public, deployer l'application, finaliser manuel utilisateur, documentation securite/deploiement, Notion et copie Studi.

---

## 2. Functional Scope

### Visitor
- Implemented: accueil public, presentation de l'entreprise, avis valides sur l'accueil, catalogue menus, filtres publics, detail menu avec images/plats/allergenes, contact, pages legales, inscription, connexion, mot de passe oublie/reinitialisation.
- In Progress: recette responsive/accessibilite finale et alignement des preuves Figma/Notion avec le code.
- Planned: paiement en ligne, geolocalisation automatique, gestion editoriale complete du contenu public.

### Customer
- Implemented: compte client, modification profil, creation de commande, calcul serveur prix/remise/livraison, confirmation email en mode log, liste des commandes, detail commande, historique de statuts, modification/annulation tant que la commande est `en_attente`, depot d'avis apres commande `terminee`.
- In Progress: manuel utilisateur final avec captures et preuves de recette.
- Planned: paiement reel, notifications email SMTP de production, SMS.

### Employee
- Implemented: tableau de bord employe, liste et filtres commandes, modification commande apres contact client, changement de statut, annulation avec mode de contact et motif, moderation des avis, notifications email journalisees selon certains statuts.
- In Progress: validation finale des parcours et preuves de recette.
- Planned: espace livreur dedie, audit trail complet des actions internes.

### Administrator
- Implemented: tableau de bord admin, acces aux fonctions commande/avis employe, statistiques par menu/periode, gestion employes, activation/desactivation employes, gestion horaires, gestion menus, selection des menus publics, gestion plats, allergenes, composition menu/plats.
- In Progress: finalisation livrables ECF, documentation et publication.
- Planned: statistiques avancees, gestion avancee des images depuis l'administration.

---

## 3. Architecture
- Folder structure: `app/` contient le code prive MVC; `config/` la configuration; `database/sql/` et `database/mongodb/` les scripts de donnees; `docs/` la documentation; `public/` le point d'entree web et les assets; `storage/` les logs; `tests/` les futurs tests automatises; `scripts/` les outils de generation documentaire.
- MVC architecture: PHP natif 8.3, sans framework applicatif; autoload Composer PSR-4 `App\\` vers `app/`; vues PHP rendues par `BaseController`.
- Routing: `public/index.php` charge l'environnement, la session, le routeur et `config/routes.php`; le routeur supporte GET/POST, parametres `{id}` et middlewares par route.
- Controllers: `HomeController`, `MenuController`, `ContactController`, `AuthController`, `AccountController`, `OrderController`, `ReviewController`, `AdminController`, `PlaceholderController`, `ErrorController`.
- Models: `HomeModel`, `MenuModel`, `OrderModel`, `ReviewModel`, `UserModel`, `PasswordResetModel`, `StatisticsModel`, `ScheduleModel`, `ContactModel`, `DishModel`.
- Views: vues publiques, auth, compte, commandes, avis, espace employe, admin, erreurs, layout principal et partial overlay filtres.
- Services: `MenuPresentation` pour la presentation menu; `MailService` pour notifications email en mode log local.
- Middlewares: `AuthMiddleware`, `RoleMiddleware`, `CsrfMiddleware`; roles normalises en `utilisateur`, `employe`, `administrateur`.
- Configuration: `.env.example`, `config/app.php`, `config/database.php`, `config/session.php`, `config/routes.php`; SQL via PDO; MongoDB lu via `mongosh` dans `StatisticsModel`.
- Important architectural decisions: MVC simple PHP au lieu de Symfony/Laravel; PDO uniquement pour SQL; Bootstrap 5 via CDN avec CSS/JS maison; SQL source de verite metier; MongoDB reserve aux agregats statistiques; emails locaux journalises dans `storage/logs/mail.log`.

---

## 4. Database

### SQL
- Tables: `roles`, `utilisateurs`, `regimes`, `themes`, `menus`, `menu_images`, `plats`, `menu_plats`, `allergenes`, `plat_allergenes`, `commandes`, `commande_statuts`, `avis`, `horaires`, `contact_messages`, `password_resets`.
- Relations: utilisateurs relies aux roles; commandes reliees aux utilisateurs et menus; menus relies aux regimes/themes/images/plats; plats relies aux allergenes; commandes reliees a l'historique de statuts et a un avis unique; avis moderes par un utilisateur; password resets relies aux utilisateurs.
- Constraints: cles primaires, cles etrangeres, index, unicites (`email`, libelles, avis par commande, token reset), checks sur types de plats, statuts, notes, prix, distances, jours et modes de contact.
- Triggers: aucun trigger SQL detecte dans `database/sql/create_database.sql`.
- Seed status: `database/sql/seed_database.sql` present avec roles, utilisateurs de demonstration, menus, plats, allergenes, commandes, statuts, avis, horaires, messages contact et resets.

### MongoDB
- Collections: `menu_statistics`, `monthly_statistics`, `menu_monthly_statistics`, `dashboard_statistics`.
- Purpose: fournir les agregats du dashboard administrateur: commandes par menu, chiffre d'affaires, panier moyen, notes, evolution mensuelle et filtres par menu/periode.
- Synchronization with SQL: SQL reste la source de verite. Les collections MongoDB sont recalculables depuis SQL via scripts; le code lit MongoDB avec `mongosh` et utilise un secours SQL si MongoDB est indisponible. Aucune synchronisation automatique applicative n'a ete observee hors scripts.

---

## 5. UX/UI
- Wireframes completed: exports PDF locaux presents pour 6 wireframes: espace public mobile/bureau, mon espace gourmand mobile/bureau, espace employe mobile, espace administrateur bureau.
- Mockups completed: exports PDF locaux presents pour 6 maquettes: espace public mobile/bureau, mon espace gourmand mobile/bureau, tableau de bord employe mobile, tableau de bord admin bureau.
- Design System: Figma documente dans les audits; charte graphique PDF presente; audit du 21/07/2026 indique 8 pages Figma, 40 variables locales et 51 composants/component sets. `docs/design.md` est plus ancien et signale des informations obsoletes.
- Typography: `Inter` pour le texte courant; `Playfair Display` pour les titres; fallbacks Arial/Helvetica et Georgia/Times.
- Colors: primaire `#722f37`, primaire sombre `#4a1c23`, accent `#d8a84e`, fond `#f9f9f9`, surface chaude `#fffdf8`, surface `#ffffff`, texte `#2b1a14`, muted `#6b7280`, bordure `#e5e1d8`.
- Components: header desktop/mobile, footer, boutons, overlay filtres, cartes menus, formulaires, tableaux back-office, badges/statuts, cartes statistiques.
- Remaining work: audit responsive/accessibilite final, nettoyage/alignement Figma, manuel utilisateur avec captures, reconciliation des docs UX obsoletes.

---

## 6. Git
- Current branch: `feature/back-office`.
- Latest milestones: `feat(admin): finalize back office management`; consolidation des parcours employe/admin; navigation interne; redirection staff apres login; restauration espace admin/employe; emails transactionnels; MVP user stories.
- Recent commits: `811f9b3 feat(admin): finalize back office management`; `77cba77 feat(backoffice): consolider les parcours employe et admin`; `14b33d7 feat(back-office): add internal shell navigation`; `88f84c4 feat(back-office): redirect staff after login`; `c12efeb feat(back-office): recover admin and employee workspace`.
- Pending documentation before commit: fichiers modifies `docs/manual/README.md`, `docs/project-management/README.md`; nouveaux fichiers `docs/manual/final-user-story-test-matrix.md`, `docs/project-management/audit-livrables-ecf-2026-07-21.md`, et ce fichier `docs/PROJECT_STATE.md` apres regeneration.
- Remote state: `main` local est en avance sur `origin/main`; `develop` local est en avance sur `origin/develop`; `feature/back-office` n'affiche pas de branche upstream dans `git branch -vv`.

---

## 7. Development Progress

### Frontend
- Completed %: non chiffre officiellement.
- Remaining work: QA responsive/accessibilite, captures finales, alignement avec Figma et documentation utilisateur.

### Backend
- Completed %: non chiffre officiellement; MVP metier principal implemente.
- Remaining work: durcir cas limites, ajouter tests automatises, finaliser integration SMTP/production.

### Database
- Completed %: non chiffre officiellement; scripts SQL et MongoDB presents.
- Remaining work: verifier schema final avant rendu, documenter synchronisation MongoDB et environnement final.

### Authentication
- Completed %: non chiffre officiellement; inscription, connexion, deconnexion, sessions, reset password et roles sont implementes.
- Remaining work: verifier parcours email en production et finaliser documentation securite.

### Security
- Completed %: non chiffre officiellement; CSRF, roles, auth, hash mots de passe, tokens hashes et PDO prepares sont en place.
- Remaining work: audit securite final, configuration production (`APP_DEBUG=false`, `SESSION_SECURE=true`), documentation OWASP/ANSSI/CNIL.

### Deployment
- Completed %: non chiffre officiellement; documentation de deploiement encore placeholder.
- Remaining work: choisir hebergement, deployer, configurer variables, migrer/seed, tester URL publique.

### Documentation
- Completed %: non chiffre officiellement; nombreuses docs techniques/projet existent.
- Remaining work: manuel utilisateur PDF, securite, deploiement, recherche anglophone, Notion et copie Studi finale.

### Testing
- Completed %: non chiffre officiellement; recette manuelle documentee.
- Remaining work: installer un framework de tests, automatiser auth, droits, commandes, prix, statuts et stats.

---

## 8. Current Sprint
- Travail actuel: finalisation back-office sur `feature/back-office`, avec gestion admin menus/plats/employes/horaires/statistiques et consolidation des livrables.
- Objectif immediate: transformer la version locale en livrable ECF demonstrable, documente, pousse, public et deploye.

---

## 9. Next Tasks
1. Committer les modifications documentaires et `docs/PROJECT_STATE.md`.
2. Pousser `feature/back-office`, fusionner proprement vers `develop` puis `main` selon le workflow retenu.
3. Rendre le depot GitHub public et tester le lien sans session.
4. Choisir et executer le deploiement, puis renseigner l'URL publique.
5. Finaliser manuel utilisateur avec parcours, captures et identifiants de demonstration.
6. Completer documentation securite, deploiement et recherche anglophone.
7. Mettre a jour Notion et la copie Studi avec liens, identifiants et preuves.
8. Executer recette finale multi-roles et conserver les captures.
9. Ajouter des tests automatises prioritaires si le delai le permet.

---

## 10. Decisions Log

Decision 001
Utiliser PHP MVC natif au lieu de Symfony/Laravel.

Decision 002
Utiliser l'autoload Composer PSR-4 avec le namespace `App\\`.

Decision 003
Utiliser PDO uniquement pour l'acces SQL.

Decision 004
Utiliser MariaDB/MySQL comme source de verite metier.

Decision 005
Utiliser MongoDB uniquement pour les agregats statistiques administrateur.

Decision 006
Utiliser Bootstrap 5 avec CSS personnalise et JavaScript vanilla.

Decision 007
Utiliser des middlewares serveur pour l'authentification, les roles et le CSRF.

Decision 008
Hasher les mots de passe avec les fonctions PHP natives et les tokens de reset en SHA-256.

Decision 009
Journaliser les emails en local tant que la configuration SMTP de production n'existe pas.

Decision 010
Proteger les routes client, employe et administrateur avec des middlewares de role dedies.

Decision 011
Conserver l'historique des statuts dans `commande_statuts` au lieu d'ecraser seulement l'etat courant.

Decision 012
Garder les agregats MongoDB recalculables et non sources de verite.

---

## 11. Known Issues
- Current bugs: aucun bug runtime courant consigne dans les derniers fichiers inspectes.
- Technical debt: pas de framework de tests automatises; documentation de deploiement placeholder; documentation securite placeholder; `docs/design.md` obsolete par rapport a l'audit Figma plus recent; service de synchronisation MongoDB automatique non observe dans le code.
- Missing features: deploiement production, publication GitHub publique, manuel PDF final, vrai SMTP, tests automatises, pages Notion finales completes.
- Potential risks: etat local non pousse sur remote, jury pouvant voir un `origin/main` obsolète, ecart de configuration MongoDB/SQL si `.env` differe, docs manuelles pouvant contredire le code si elles ne sont pas reconciliees.

---

## 12. Oral Defense Notes
- The project deliberately uses native PHP MVC to show understanding of routing, controllers, models, views, sessions and security without hiding logic behind a framework.
- SQL is the source of truth because orders, users, menus and reviews need strong relational integrity and constraints.
- MongoDB is justified for read-oriented dashboard aggregates and ECF NoSQL requirement; aggregates are recalculable from SQL.
- PDO prepared statements, CSRF middleware, role middleware, password hashing and hashed reset tokens are the main security arguments.
- The employee workflow enforces business rules: status history, contact mode and reason for order changes/cancellations.
- Customer reviews are tied to completed orders and moderated before public display.
- Email is implemented through a service and logged locally so the workflow is demonstrable without exposing SMTP credentials.
- Back-office admin extends employee capabilities and adds employees, menus, dishes, horaires and statistics.
- Remaining delivery risk is mostly packaging/publication/documentation, not core local application behavior.

---

## 13. AI Context
- Current objectives: finish ECF readiness by stabilizing local code, publishing Git state, deploying, completing final docs and preserving proof of manual tests.
- Constraints: do not invent completion percentages, deployment URLs, public visibility, Notion content or test results. Verify current Git/code before updating this file.
- Coding conventions: strict types in PHP files, namespace `App`, final classes common, constructor-less models extending `BaseModel`, prepared PDO queries, arrays documented with PHPDoc, ASCII text used in repository files.
- Naming conventions: French database/table/field names; English/PHP class names; routes in French; roles normalized in session as `utilisateur`, `employe`, `administrateur`.
- Architecture philosophy: small native MVC, explicit routes, thin controllers where possible, models for data access, services for reusable presentation/email logic, SQL integrity first, NoSQL only for derived statistics.
- Important business rules: visitors can browse but must authenticate to order; public signup always creates a customer; customers can edit/cancel only pending orders; employees must record contact mode and reason for order changes/cancellations; reviews require completed orders and moderation; admins create/deactivate employees and manage catalog data.
- Things that must never be modified without explicit request: do not reset or discard user changes; do not commit secrets; do not remove SQL/MongoDB scripts; do not weaken auth/role/CSRF protections; do not make MongoDB the business source of truth; do not overwrite Figma/Notion facts with guesses.
