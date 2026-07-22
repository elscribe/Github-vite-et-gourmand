# Audit page 5 - Catalogue, filtres, compte, connexion et detail menu

Date de l'audit : 2026-07-22.
Source de verite : depot local, car toutes les modifications ne sont pas encore
poussees.
Application testee : `http://127.0.0.1:8000`.
Verdict global : **conforme apres corrections mineures realisees pendant
l'audit**.

## Source enonce

Extraction locale : `docs/project-management/audit-page-05-assets/source-enonce-page-05.txt`.

La page 5 demande :

- une vue globale des menus accessible aux visiteurs et aux utilisateurs
  authentifies ;
- pour chaque menu : titre, description, minimum de personnes, prix et bouton de
  detail ;
- des filtres dynamiques sans rechargement : prix maximum, fourchette de prix,
  theme, regime, nombre minimum de personnes ;
- une creation de compte avec nom, prenom, GSM, email, adresse postale, mot de
  passe fort, role utilisateur et email de bienvenue ;
- une connexion par email et mot de passe ;
- une reinitialisation par lien email ;
- une page detail menu affichant toutes les informations du menu ;
- un bouton commande qui envoie l'utilisateur authentifie vers une commande avec
  le menu preselectionne.

## Captures acceptees

| Capture | Preuve |
|---|---|
| `audit-page-05-assets/01-catalogue-menus-initial.png` | Catalogue public avec 6 menus, titre, description, minimum, prix et bouton detail. |
| `audit-page-05-assets/02-catalogue-filtre-budget.png` | Filtre rapide "Moins de 150 EUR" applique sans changement d'URL. |
| `audit-page-05-assets/03-catalogue-overlay-filtres.png` | Overlay de filtres : budget, prix maximum, theme, regime, personnes, disponibilite, allergenes. |
| `audit-page-05-assets/04-catalogue-filtre-avance.png` | Filtre avance prix maximum + regime vegetarien, resultat reduit au menu attendu. |
| `audit-page-05-assets/05-detail-menu-complet.png` | Detail menu avec galerie, faits, allergenes, conditions, composition et CTA commande. |
| `audit-page-05-assets/06-inscription.png` | Formulaire inscription avec champs obligatoires. |
| `audit-page-05-assets/07-connexion.png` | Formulaire connexion avec liens inscription et mot de passe oublie. |
| `audit-page-05-assets/08-mot-de-passe-oublie.png` | Formulaire reset par email. |
| `audit-page-05-assets/09-commande-menu-preselectionne.png` | Commande accessible apres connexion avec menu Cocktail Bordelais preselectionne. |

## Resultats par exigence

| Exigence page 5 | Statut | Verification |
|---|---|---|
| Vue globale accessible sans compte | Conforme | `GET /menus` retourne 200 et affiche 6 cartes. |
| Vue globale accessible avec compte | Conforme | Les routes publiques restent accessibles apres connexion client. |
| Titre, description, minimum, prix, bouton detail | Conforme | Capture `01-catalogue-menus-initial.png`; HTML des cartes avec `data-menu-card`. |
| Filtre prix maximum | Conforme | Overlay avec champ "Prix maximum"; test prix max 150 applique sans rechargement. |
| Filtre fourchette de prix | Conforme | Boutons budget : moins de 100, 100-150, 150-200, plus de 200 EUR. |
| Filtre theme | Conforme | Boutons theme dans l'overlay, alimentes par les themes du catalogue. |
| Filtre regime | Conforme | Test regime vegetarien + prix max 150 : seul "Menu Vege-Gourmand" reste visible. |
| Filtre nombre minimum de personnes | Conforme | Boutons 2 personnes, 4 a 6, 8 a 10, plus de 10 personnes. |
| Mise a jour dynamique sans rechargement | Conforme | URL reste `/menus`; le nombre de cartes visibles change cote JS. |
| Inscription : nom, prenom, GSM, email, adresse | Conforme | Vue `auth/register.php`; test POST avec compte jetable. |
| Mot de passe fort 10 caracteres + complexite | Conforme apres correction UX | `Validator::validatePassword()` applique 10 caracteres, majuscule, minuscule, chiffre, special ; placeholders corriges de 8 a 10 caracteres. |
| Role utilisateur a la creation | Conforme | Test DB : compte cree avec role SQL `Customer`, normalise en session `utilisateur`. |
| Email de bienvenue | Conforme demo | `MailService` trace "Bienvenue chez Vite & Gourmand" dans `storage/logs/mail.log`. |
| Connexion email + mot de passe | Conforme | Test login client demo : `POST /connexion` retourne 302 vers `/mon-compte`. |
| Mot de passe oublie | Conforme demo | Reset cree un token hash expire 1h et logge un lien ; test reset retourne 302 vers `/connexion`, token marque utilise. |
| Detail menu complet | Conforme apres correction | Ajout d'une section Allergènes traduite et affichage de la condition issue de la table `menus`. |
| Bouton commande avec menu preselectionne | Conforme | Anonyme : redirection `/connexion`; connecte : `/commandes/creation/6` en 200 avec option `value="6"` selectionnee. |

## Tests executes

```text
GET /menus                              200, 6 cartes menu
GET /menus/6                            200
GET /commandes/creation/6 anonyme        redirect final /connexion
JS filtre rapide budget                 URL inchangee, 2 menus visibles
JS filtre avance max 150 + vegetarien    URL inchangee, 1 menu visible
POST /inscription mot de passe faible    200 avec erreur de complexite
POST /inscription compte jetable         302 /mon-compte
DB utilisateur cree                      role Customer
Mail bienvenue                           trace dans storage/logs/mail.log
POST /mot-de-passe/oublie                200, lien reset logge
POST /mot-de-passe/reinitialisation      302 /connexion, token utilise
POST /connexion client demo              302 /mon-compte
GET /commandes/creation/6 connecte       200, menu 6 preselectionne
composer check                           OK
git diff --check                         OK
```

Le compte jetable cree pour la recette a ete supprime en fin de test.

## Corrections realisees pendant l'audit

- `app/Views/menus/show.php` : ajout d'une section claire "Allergenes" sur la
  page detail, avec traduction francaise via `MenuPresentation::allergenLabel`.
- `app/Views/menus/show.php` : affichage de la condition issue de la table
  `menus.conditions` dans le bloc "Conditions importantes".
- `app/Views/auth/register.php` et `app/Views/auth/reset-password.php` :
  placeholders et texte d'aide alignes sur la regle de 10 caracteres.
- `public/assets/css/style.css` : style du texte d'aide formulaire et de la
  section allergenes.

## Points d'attention non bloquants

- Le formulaire de commande preselectionne bien le menu, mais ne remplit pas
  automatiquement le nombre minimum de personnes. La validation serveur bloque
  les valeurs inferieures au minimum. A traiter plutot avec l'audit page 7,
  dedie au parcours commande.
- Les emails sont testes en mode local `log`, ce qui est documente. Un SMTP reel
  sera a brancher en production.
- La capture du navigateur peut afficher de l'autofill sur la page connexion ;
  les assertions finales de connexion reposent donc sur la recette HTTP isolee.

## Decision push / main

| Cible | Decision | Justification |
|---|---:|---|
| Push branche de travail | Possible | Corrections ciblees, tests OK, captures et rapport ajoutes. |
| Merge `develop` | Possible apres revue | La page 5 est conforme et les corrections reduisent le risque jury. |
| Merge `main` | Possible avec le lot audite | Pas de reserve bloquante identifiee sur la page 5. |
