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
| A1 Accueil | Ouvrir `/` | La page d'accueil s'affiche avec la navigation publique. | Valide local |
| A2 Avis publics | Observer les avis sur l'accueil | Seuls les avis valides sont visibles. | Valide local |
| A3 Liste menus | Ouvrir `/menus` | Les menus actifs s'affichent en cartes. | Valide local |
| A4 Filtres menus | Choisir un theme ou regime | La liste se filtre sans casser la page. | Valide local |
| A5 Detail menu | Ouvrir un menu | Images, plats, allergenes, prix, stock et bouton commande visibles. | Valide local |
| A6 Contact | Ouvrir `/contact` | Le formulaire contact s'affiche. | Valide local |
| A7 Envoi contact | Envoyer un message valide | Redirection avec message de succes. | Valide local |

## B. Parcours compte client

| Test | Action | Resultat attendu | Statut |
|---|---|---|---|
| B1 Inscription | Ouvrir `/inscription` et creer un compte test | Compte client cree, mot de passe hashe. | Valide local |
| B2 Connexion client | Se connecter comme client | Redirection vers l'espace compte ou menu client. | Valide local |
| B3 Profil | Ouvrir `/mon-compte` puis `/mon-compte/modifier` | Les informations client sont visibles et modifiables. | Valide local |
| B4 Creation commande | Ouvrir `/commandes/creation` ou `/commandes/creation/{menuId}` | Formulaire de commande disponible. | Valide local |
| B5 Calcul prix | Saisir menu, personnes, ville, distance | Le recapitulatif de prix se met a jour, le serveur recalcule au POST. | Valide local |
| B6 Validation commande | Envoyer la commande | Commande creee, statut `en_attente` et historique initial ajoute. | Valide local |
| B7 Detail commande | Ouvrir la commande | Timeline de statut visible. | Valide local |
| B8 Modification avant acceptation | Modifier une commande `en_attente` | Modification acceptee uniquement avant acceptation. | Valide local |
| B9 Annulation client | Annuler une commande `en_attente` | Statut annule et historique ajoute. | Valide local |
| B10 Avis apres commande terminee | Ouvrir `/avis` et deposer un avis eligible | Avis cree en attente de moderation. | Valide local |

## C. Parcours employe

| Test | Action | Resultat attendu | Statut |
|---|---|---|---|
| C1 Connexion employe | Se connecter comme employe | Acces a l'espace employe. | Teste 22/07 audit employe |
| C2 Liste commandes | Ouvrir `/employe/commandes` | Liste des commandes visible. | Teste 22/07 audit employe |
| C3 Filtre commandes | Filtrer par statut ou client | Liste reduite selon le filtre. | Teste 22/07 audit employe |
| C4 Changement statut | Passer une commande a un autre statut | Statut mis a jour et historique ajoute. | Teste 22/07 audit employe |
| C5 Notification retour materiel | Passer une commande en `en_attente_retour_materiel` | Email client avec 10 jours ouvres, 600 EUR et lien CGV. | Teste 22/07 audit employe |
| C6 Annulation employe | Annuler avec mode contact + motif | Annulation enregistree avec justification. | Teste 22/07 audit employe |
| C7 Moderation avis | Ouvrir `/employe/avis` | Avis en attente visibles. | Teste 22/07 audit employe |
| C8 Valider/refuser avis | Moderer un avis | L'avis change de statut et un avis valide peut apparaitre sur l'accueil. | Teste 22/07 audit employe |

## D. Parcours administrateur

| Test | Action | Resultat attendu | Statut |
|---|---|---|---|
| D1 Connexion admin | Se connecter comme administrateur | Acces au dashboard admin. | Valide local |
| D2 Dashboard | Ouvrir `/admin` | Indicateurs principaux visibles. | Valide local |
| D3 Statistiques | Ouvrir `/admin/statistiques` | Commandes et CA par menu visibles, filtres disponibles. | Valide local |
| D4 Employes | Ouvrir `/admin/employes` | Creation et activation/desactivation employe possibles. | Valide local |
| D5 Horaires | Ouvrir `/admin/horaires` | Gestion simple des horaires possible. | Valide local |
| D6 Menus admin | Ouvrir `/admin/menus` | Creation/modification des champs principaux menu possible. | Valide local |
| D7 Plats admin | Ouvrir `/admin/plats` | Creation/modification des plats possible. | Valide local |

## E. Securite minimale

| Test | Action | Resultat attendu | Statut |
|---|---|---|---|
| E1 Client bloque admin | Connecte client, ouvrir `/admin` | Acces refuse ou redirection. | Valide local |
| E1b Employe bloque catalogue admin | Connecte employe, ouvrir `/admin/menus`, `/admin/plats`, `/admin/horaires` | Acces refuse car catalogue et horaires reserves admin. | Teste 22/07 audit employe |
| E2 Visiteur bloque commandes | Deconnecte, ouvrir `/commandes` | Redirection connexion. | Valide local |
| E3 CSRF | Verifier les formulaires POST | Champ `_csrf_token` present. | Valide local |
| E4 Mot de passe | Verifier en base | Mot de passe stocke en hash, pas en clair. | Valide local |

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
| Gestion projet / backlog | Notion + rapport user stories + matrice finale | Present, lien Notion a completer |
| Manuel / revisions | `docs/manual/` | Present, PDF a regenerer si captures finales |
| Deploiement | `docs/deployment/README.md` | Procedure presente, URL finale a completer |
| Securite | `docs/security/README.md` + `docs/security/security-watch.md` + middlewares | Present |

## Notes pendant test

```text
Test :
Observation :
Probleme :
Solution :
Statut final :
```
