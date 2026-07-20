# Checklist de recette MVP et livrables obligatoires

Date de creation : 13 juillet 2026.

Objectif : tester uniquement ce qui est prioritaire pour le MVP et les
livrables obligatoires de l'ECF. Chaque test doit pouvoir etre explique devant
le jury avec la logique : action realisee, resultat attendu, preuve observee.

Pour le detail des user stories client connecte, voir aussi :
`docs/project-management/client-connected-user-stories-validation.md`.

## Comptes de test

| Role | Email | Mot de passe |
|---|---|---|
| Client | `claire.martin@example.test` | `ClientVite2026!` |
| Employe | `lucas.employee@vitegourmand.test` | `EmployeVite2026!` |
| Administrateur | `admin.jose@vitegourmand.test` | `AdminVite2026!` |

## A. Parcours visiteur

| Test | Action | Resultat attendu | Statut |
|---|---|---|---|
| A1 Accueil | Ouvrir `/` | La page d'accueil s'affiche avec la navigation publique. | A tester |
| A2 Avis publics | Observer les avis sur l'accueil | Seuls les avis valides sont visibles. | A tester |
| A3 Liste menus | Ouvrir `/menus` | Les menus actifs s'affichent en cartes. | A tester |
| A4 Filtres menus | Choisir un theme ou regime | La liste se filtre sans casser la page. | A tester |
| A5 Detail menu | Ouvrir un menu | Images, plats, allergenes, prix, stock et bouton commande visibles. | A tester |
| A6 Contact | Ouvrir `/contact` | Le formulaire contact s'affiche. | A tester |
| A7 Envoi contact | Envoyer un message valide | Redirection avec message de succes. | A tester |

## B. Parcours compte client

| Test | Action | Resultat attendu | Statut |
|---|---|---|---|
| B1 Inscription | Ouvrir `/inscription` et creer un compte test | Compte client cree, mot de passe hashe. | A tester |
| B2 Connexion client | Se connecter comme client | Redirection vers l'espace compte ou menu client. | Teste 15/07 |
| B3 Profil | Ouvrir `/mon-compte` puis `/mon-compte/modifier` | Les informations client sont visibles et modifiables. | Teste 15/07 |
| B4 Creation commande | Ouvrir `/commandes/creation` ou `/commandes/creation/{menuId}` | Formulaire de commande disponible. | Teste 15/07 |
| B5 Calcul prix | Saisir menu, personnes, ville, distance | Le recapitulatif de prix se met a jour, le serveur recalcule au POST. | Serveur teste 15/07, visuel a rejouer |
| B6 Validation commande | Envoyer la commande | Commande creee, statut `en_attente` et historique initial ajoute. | Teste 15/07 |
| B7 Detail commande | Ouvrir la commande | Timeline de statut visible. | Teste 15/07 |
| B8 Modification avant acceptation | Modifier une commande `en_attente` | Modification acceptee uniquement avant acceptation. | Teste 15/07 |
| B9 Annulation client | Annuler une commande `en_attente` | Statut annule et historique ajoute. | Teste 15/07 |
| B10 Avis apres commande terminee | Ouvrir `/avis` et deposer un avis eligible | Avis cree en attente de moderation. | Teste 15/07 |

## C. Parcours employe

| Test | Action | Resultat attendu | Statut |
|---|---|---|---|
| C1 Connexion employe | Se connecter comme employe | Acces a l'espace employe. | A tester |
| C2 Liste commandes | Ouvrir `/employe/commandes` | Liste des commandes visible. | A tester |
| C3 Filtre commandes | Filtrer par statut ou client | Liste reduite selon le filtre. | A tester |
| C4 Changement statut | Passer une commande a un autre statut | Statut mis a jour et historique ajoute. | A tester |
| C5 Annulation employe | Annuler avec mode contact + motif | Annulation enregistree avec justification. | A tester |
| C6 Moderation avis | Ouvrir `/employe/avis` | Avis en attente visibles. | A tester |
| C7 Valider/refuser avis | Moderer un avis | L'avis change de statut. | A tester |

## D. Parcours administrateur

| Test | Action | Resultat attendu | Statut |
|---|---|---|---|
| D1 Connexion admin | Se connecter comme administrateur | Acces au dashboard admin. | A tester |
| D2 Dashboard | Ouvrir `/admin` | Indicateurs principaux visibles. | A tester |
| D3 Statistiques | Ouvrir `/admin/statistiques` | Commandes et CA par menu visibles, filtres disponibles. | A tester |
| D4 Employes | Ouvrir `/admin/employes` | Creation et activation/desactivation employe possibles. | A tester |
| D5 Horaires | Ouvrir `/admin/horaires` | Gestion simple des horaires possible. | A tester |
| D6 Menus admin | Ouvrir `/admin/menus` | Creation/modification des champs principaux menu possible. | A tester |
| D7 Plats admin | Ouvrir `/admin/plats` | Creation/modification des plats possible. | A tester |

## E. Securite minimale

| Test | Action | Resultat attendu | Statut |
|---|---|---|---|
| E1 Client bloque admin | Connecte client, ouvrir `/admin` | Acces refuse ou redirection. | A tester |
| E2 Visiteur bloque commandes | Deconnecte, ouvrir `/commandes` | Redirection connexion. | A tester |
| E3 CSRF | Verifier les formulaires POST | Champ `_csrf_token` present. | A tester |
| E4 Mot de passe | Verifier en base | Mot de passe stocke en hash, pas en clair. | A tester |

## F. Livrables obligatoires ECF

| Livrable | Fichier ou emplacement | Statut actuel |
|---|---|---|
| Depot GitHub | README, lien GitHub a verifier avant rendu | Present, lien public a confirmer |
| Application PHP MVC | `app/`, `public/`, `config/` | Present |
| Base SQL | `database/sql/create_database.sql`, `seed_database.sql` | Present |
| Base NoSQL | `database/mongodb/` | Present et documentee |
| MCD/MLD/MPD | `docs/database/` | Present |
| UML | `docs/uml/` | Present |
| Dictionnaire de donnees | `docs/database/data-dictionary.md` | Present |
| Choix techniques SQL/NoSQL | `docs/database/database-choices.md` | Present |
| Maquettes / wireframes | `docs/deliverables/` + lien Figma README | Present, lien a verifier |
| Charte graphique | `docs/deliverables/graphic-charter/` + Figma | Present |
| Gestion projet / backlog | Notion + rapport user stories | Present, lien Notion a completer |
| Manuel / revisions | `docs/manual/` | Present |
| Deploiement | `docs/deployment/README.md` | Present, URL finale a completer |
| Securite | `docs/security/README.md` + middlewares | Present |

## Notes pendant test

```text
Test :
Observation :
Probleme :
Solution :
Statut final :
```
