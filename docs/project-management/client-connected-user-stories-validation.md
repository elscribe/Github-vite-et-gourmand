# Validation des user stories - client connecte

Date de validation documentaire : 15 juillet 2026.

Objectif : regrouper les user stories du client connecte dans un document
facile a relire avant l'oral. Pour chaque fonctionnalite, la logique a retenir
est la meme :

```text
Route protegee -> session utilisateur -> controleur -> modele -> base SQL -> vue -> test navigateur/base
```

## Compte de demonstration

| Role | Email | Mot de passe |
|---|---|---|
| Client | `claire.martin@example.test` | `ClientVite2026!` |

## Synthese des user stories

| US | Parcours utilisateur | Routes principales | Code principal | Statut documentaire |
|---|---|---|---|---|
| US-009 | Consulter mon espace et modifier mes informations | `GET /mon-compte`, `GET /mon-compte/modifier`, `POST /mon-compte/modifier` | `AccountController`, `UserModel`, `account/show.php`, `account/edit.php` | Valide |
| US-010 | Commander un menu | `GET /commandes/creation`, `GET /commandes/creation/{menuId}`, `POST /commandes` | `OrderController::create/store`, `OrderModel::create`, `orders/create.php` | Valide |
| US-011 | Calculer prix, remise et livraison | `POST /commandes`, apercu JS dans la page | `OrderModel::calculateTotals`, `public/assets/js/app.js` | Valide |
| US-012 | Voir mes commandes | `GET /commandes`, bloc historique dans `/mon-compte` | `OrderController::index`, `OrderModel::findForUser`, `orders/index.php` | Valide |
| US-013 | Suivre le statut d'une commande | `GET /commandes/{id}` | `OrderController::show`, `OrderModel::findHistory`, `orders/show.php` | Valide |
| US-014 | Modifier ou annuler avant acceptation | `GET/POST /commandes/{id}/modifier`, `POST /commandes/{id}/annuler` | `OrderController::edit/update/cancel`, `OrderModel::updatePendingForUser/cancelPendingForUser` | Valide |
| Avis client | Laisser un avis apres une commande terminee | `GET /avis`, `GET /avis/creation/{orderId}`, `POST /avis` | `ReviewController`, `ReviewModel`, `reviews/index.php`, `reviews/_form.php` | Valide |

## US-009 - Mon compte et modification profil

But utilisateur : le client retrouve ses informations, ses commandes recentes
et peut modifier ses coordonnees depuis une page separee.

Chemin MVC :

- Route : `GET /mon-compte` et `GET /mon-compte/modifier`.
- Middleware : `AuthMiddleware` impose une session active.
- Controleur : `AccountController::show`, `edit`, `update`.
- Modele : `UserModel::findById` et `UserModel::updateProfile`.
- Vue : `app/Views/account/show.php` et `app/Views/account/edit.php`.

Regles importantes :

- L'id utilisateur vient de `Session::userId()`, jamais d'un champ modifiable.
- Le formulaire POST est protege par `CsrfMiddleware`.
- Les champs obligatoires sont valides cote serveur.
- Le canal de contact prefere est limite a `email` ou `telephone`.

Test de validation :

```text
1. Se connecter avec le compte client.
2. Ouvrir /mon-compte.
3. Cliquer sur Modifier mes informations.
4. Modifier un champ, enregistrer.
5. Verifier le message de succes et le retour des donnees mises a jour.
```

Phrase jury :

> "Un client ne peut modifier que son propre profil, car le controleur prend
> l'identifiant depuis la session et non depuis l'URL ou le formulaire."

## US-010 - Commander un menu

But utilisateur : le client choisit un menu, renseigne son evenement et soumet
une demande de commande a l'equipe.

Chemin MVC :

- Route GET : `/commandes/creation` ou `/commandes/creation/{menuId}`.
- Route POST : `/commandes`.
- Controleur : `OrderController::create` affiche le formulaire, `store`
  valide et cree la commande.
- Modele : `OrderModel::findMenuForOrder`, `OrderModel::create`.
- Vue : `app/Views/orders/create.php`.

Regles importantes :

- Le menu doit exister et etre actif.
- Le nombre de personnes doit respecter le minimum du menu.
- L'adresse est separee en adresse, code postal et ville.
- Le commentaire client est optionnel et limite a 1000 caracteres.
- La commande est creee avec le statut initial `en_attente`.
- Une ligne d'historique est ajoutee dans `commande_statuts`.

Test de validation :

```text
1. Ouvrir /menus puis choisir un menu.
2. Cliquer sur Commander.
3. Completer date, heure souhaitee, convives, adresse et commentaire.
4. Valider la commande.
5. Verifier la redirection vers /commandes/{id}.
```

Verification SQL possible :

```bash
mysql -uroot -proot vite_gourmand -e "SELECT id_commande, id_utilisateur, statut_actuel, prix_total FROM commandes ORDER BY id_commande DESC LIMIT 5;"
mysql -uroot -proot vite_gourmand -e "SELECT id_commande, statut, commentaire FROM commande_statuts ORDER BY id_statut DESC LIMIT 5;"
```

Phrase jury :

> "A la creation, la commande et son premier statut sont enregistres ensemble
> pour que le suivi soit disponible des le depart."

## US-011 - Calcul prix, remise et livraison

But utilisateur : comprendre le prix estime avant de soumettre la demande.

Source de verite :

- Le JavaScript met a jour le recapitulatif pour aider le client.
- Le vrai calcul est refait cote serveur dans `OrderModel::calculateTotals`.

Regles de calcul :

```text
Prix par personne = prix_minimum / nombre_personnes_minimum
Prix menu = prix par personne x nombre de personnes
Remise = 10 % si la commande contient au moins 5 personnes de plus que le minimum
Livraison = 0 EUR a Bordeaux
Livraison hors Bordeaux = 5 + 0,59 x distance_km
Total = prix menu - remise + livraison
```

Point important :

- Il n'y a pas encore de geolocalisation automatique.
- Si la ville est Bordeaux, la distance est forcee a `0`.
- Hors Bordeaux, le client indique une distance approximative, puis l'equipe
  peut verifier avant acceptation.

Phrase jury :

> "Le navigateur affiche une estimation, mais le serveur recalcule le prix final
> pour eviter toute manipulation cote client."

## US-012 - Mes commandes

But utilisateur : retrouver l'historique de ses commandes.

Chemin MVC :

- Route : `GET /commandes`.
- Controleur : `OrderController::index`.
- Modele : `OrderModel::findForUser`.
- Vue : `app/Views/orders/index.php`.

Regle de securite :

- La requete filtre avec `WHERE c.id_utilisateur = :user_id`.
- L'id utilisateur vient de la session.

Test de validation :

```text
1. Connecte en client, ouvrir /commandes.
2. Verifier que les commandes du client sont visibles.
3. Ouvrir une commande avec le bouton Detail.
4. Tester /commandes deconnecte : redirection vers /connexion.
```

Phrase jury :

> "La liste des commandes est filtree par l'id du client connecte ; un client ne
> peut pas voir les commandes d'un autre utilisateur."

## US-013 - Suivi de commande

But utilisateur : voir l'avancement de sa commande.

Chemin MVC :

- Route : `GET /commandes/{id}`.
- Controleur : `OrderController::show`.
- Modele : `OrderModel::findOneForUser` et `OrderModel::findHistory`.
- Vue : `app/Views/orders/show.php`.

Regles importantes :

- La commande doit appartenir au client connecte.
- La timeline affiche les etapes en fonction de `commande_statuts`.
- Les statuts machine sont traduits par `OrderModel::statusLabels`.

Statuts principaux :

```text
en_attente -> acceptee -> en_preparation -> en_cours_de_livraison -> livre -> terminee
annulee peut interrompre le parcours.
```

Phrase jury :

> "Je stocke le statut courant dans la commande pour l'affichage rapide, et
> l'historique dans `commande_statuts` pour garder la tracabilite."

## US-014 - Modifier ou annuler avant acceptation

But utilisateur : corriger ou annuler une demande tant qu'elle n'est pas encore
traitee par l'equipe.

Chemin MVC :

- Modifier : `GET /commandes/{id}/modifier`, puis `POST /commandes/{id}/modifier`.
- Annuler : `POST /commandes/{id}/annuler`.
- Controleur : `OrderController::edit`, `update`, `cancel`.
- Modele : `OrderModel::updatePendingForUser`, `cancelPendingForUser`.

Regle metier centrale :

```text
Modification et annulation client autorisees uniquement si statut_actuel = en_attente.
```

Pourquoi deux verifications ?

- La vue peut masquer les boutons quand ce n'est plus autorise.
- Le modele reverifie quand meme la condition SQL `AND statut_actuel = 'en_attente'`.

Test de validation :

```text
1. Creer une commande client.
2. Tant qu'elle est en_attente, modifier la date ou l'adresse.
3. Annuler une commande en_attente.
4. Passer la commande a acceptee avec un compte employe.
5. Verifier que modifier/annuler n'est plus possible cote client.
```

Phrase jury :

> "La regle n'est pas seulement visuelle : meme si quelqu'un appelle la route
> POST directement, le modele bloque si le statut n'est plus `en_attente`."

## Avis client apres commande terminee

But utilisateur : laisser un avis seulement apres une prestation terminee.

Chemin MVC :

- Page avis : `GET /avis`.
- Formulaire direct : `GET /avis/creation/{orderId}`.
- Envoi : `POST /avis`.
- Controleur : `ReviewController::index`, `create`, `store`.
- Modele : `ReviewModel::findReviewableOrder`, `findForUser`, `create`.
- Vues : `reviews/index.php`, `reviews/create.php`, `reviews/_form.php`.

Regles importantes :

- La commande doit appartenir au client connecte.
- La commande doit avoir le statut `terminee`.
- Une commande deja notee ne peut pas recevoir un deuxieme avis.
- La note doit etre entre 1 et 5.
- Le commentaire est obligatoire.
- L'avis est cree en statut `en_attente`.
- La publication publique depend ensuite de la moderation employe.

Test de validation :

```text
1. Ouvrir /avis avec un compte client.
2. Si une commande terminee sans avis existe, le formulaire apparait.
3. Envoyer une note et un commentaire.
4. Verifier que l'avis apparait en attente.
5. Avec un compte employe, moderer l'avis dans /employe/avis.
```

Verification SQL possible :

```bash
mysql -uroot -proot vite_gourmand -e "SELECT id_avis, id_commande, note, statut FROM avis ORDER BY id_avis DESC LIMIT 5;"
```

Phrase jury :

> "Un avis client n'est jamais publie automatiquement : il est cree en attente,
> puis un employe le valide ou le refuse."

## Checklist rapide de recette client connecte

| Test | URL | Resultat attendu |
|---|---|---|
| Acces non connecte bloque | `/mon-compte` ou `/commandes` | Redirection vers `/connexion` |
| Dashboard client | `/mon-compte` | Commande en cours, historique, informations et avis visibles |
| Modification profil | `/mon-compte/modifier` | Mise a jour + message de succes |
| Creation commande | `/commandes/creation/6` | Formulaire + recapitulatif dynamique |
| Detail commande | `/commandes/{id}` | Timeline + recapitulatif + actions conditionnelles |
| Annulation avant acceptation | POST `/commandes/{id}/annuler` | Statut `annulee` si commande `en_attente` |
| Blocage apres acceptation | Modifier/annuler commande `acceptee` | Action refusee |
| Avis client | `/avis` | Formulaire seulement si commande `terminee` sans avis |

## Verification rejouee le 15 juillet 2026

Compte utilise : `claire.martin@example.test`.

| Test rejoue | Resultat observe | Statut |
|---|---|---|
| `composer check` | Lint PHP complet OK | OK |
| Visiteur non connecte sur `/commandes` | Redirection `302` vers `/connexion` | OK |
| Visiteur non connecte sur `/mon-compte` | Redirection `302` vers `/connexion` | OK |
| Connexion client | Redirection `302` vers `/mon-compte` | OK |
| `/mon-compte` | `200` | OK |
| `/mon-compte/modifier` | `200` | OK |
| `/commandes` | `200` | OK |
| `/commandes/creation` | `200` | OK |
| `/commandes/creation/1` | `200` | OK |
| `/commandes/9` | `200`, commande du client connecte | OK |
| `/commandes/9/modifier` | `200`, commande encore `en_attente` | OK |
| `/commandes/21` | `200`, commande du client connecte | OK |
| `/commandes/20` | `404`, commande appartenant a un autre client | OK |
| Client connecte sur `/admin` | `403` | OK |
| Client connecte sur `/employe` | `403` | OK |
| POST `/mon-compte/modifier` sans CSRF | `403` | OK |
| POST profil invalide avec CSRF | `200`, erreurs de validation affichees | OK |
| POST commande invalide avec CSRF | `200`, erreurs de validation affichees | OK |
| Creation reelle d'une commande temporaire | Redirection `302` vers `/commandes/{id}`, statut initial `en_attente` | OK |
| Modification reelle d'une commande temporaire `en_attente` | Redirection `302`, historique `Commande modifiee par le client` | OK |
| Annulation reelle d'une commande temporaire `en_attente` | Redirection `302`, statut `annulee`, historique d'annulation ajoute | OK |
| `/avis` | `200` | OK |
| `/avis/creation/21` | `404`, commande deja notee | OK |
| Depot d'avis sur une commande temporaire `terminee` sans avis | Redirection `302` vers `/avis`, avis cree en statut `en_attente` | OK |
| `/commandes/21/modifier` | Redirection `302` vers `/commandes/21` | OK apres correction |

Les commandes et avis temporaires crees pour cette verification ont ete
supprimes apres controle SQL.

## Exercices pour t'entrainer

1. Refaire le schema MVC de `/mon-compte/modifier`.
2. Expliquer pourquoi `Session::userId()` est plus sur qu'un champ `id_user`
   dans le formulaire.
3. Recalculer a la main un total de commande avec remise et livraison.
4. Trouver dans `OrderModel` la condition qui bloque la modification apres acceptation.
5. Creer une commande test, puis verifier en SQL son statut et son prix.
6. Expliquer pourquoi un avis reste `en_attente` avant d'apparaitre sur l'accueil.

## Echecs et solutions a retenir

| Probleme rencontre | Solution retenue | Lecon pour l'oral |
|---|---|---|
| `/mon-compte` melangeait consultation et edition | Creation de `/mon-compte/modifier` | Separer lecture et modification rend le parcours plus clair |
| Risque de modifier le mauvais compte | Id utilisateur pris depuis la session | La securite depend du serveur, pas du formulaire |
| Champ distance peu intuitif | Masquage pour Bordeaux, aide hors Bordeaux | Le MVP assume une estimation, l'equipe valide ensuite |
| Adresse trop vague | Separation adresse, code postal, ville | Meilleure UX et donnees plus propres |
| Pas de champ demande particuliere | Ajout de `commentaire_client` | Le client peut signaler allergenes, acces ou consignes |
| Recapitulatif trop pauvre | Fiche recap dynamique avec menu, prix, remise, livraison | Le client comprend ce qu'il valide |
| Lien `Mes avis` seulement en ancre | Creation de la vraie page `/avis` | Une navigation doit mener a une experience complete |
| Tester un avis etait difficile sans commande eligible | Creation d'une donnee QA temporaire puis nettoyage | Les regles metier se testent avec des donnees controlees |
| L'URL `/commandes/{id}/modifier` affichait encore le formulaire pour une commande deja traitee | Ajout d'un blocage dans `OrderController::edit` avec redirection vers le detail | La regle metier doit proteger aussi l'acces direct a l'URL, pas seulement le bouton visible |
| Premier script de test local interrompu | Le nom de variable `path` perturbait les commandes du terminal zsh | Eviter les noms reserves ou speciaux dans les scripts de verification |

## Decision finale de validation

Les user stories client connecte sont documentees et coherentes avec le code
actuel. La validation finale avant rendu consiste surtout a refaire une passe
visuelle dans le navigateur et a garder une capture ou une note d'observation
pour chaque parcours critique.
