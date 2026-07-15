# Fiches de revision et exercices

Voir aussi : `docs/project-management/user-story-debug-log.md` pour reviser
les erreurs rencontrees et les solutions appliquees.
Voir aussi : `docs/manual/mvp-test-checklist.md` pour derouler la recette MVP
et verifier les livrables obligatoires.

## Fiche 1 - Lire une fonctionnalite MVC

Question a te poser :

```text
Quelle route appelle quel controleur ?
Quel modele recupere ou modifie les donnees ?
Quelle vue affiche le resultat ?
Quel test prouve que ca fonctionne ?
```

Exemple menus :

- Route : `GET /menus`
- Controleur : `MenuController::index`
- Modele : `MenuModel::findActiveMenus`
- Vue : `app/Views/menus/index.php`
- Test : ouvrir `/menus`, puis filtrer par theme.

Exercice : refais le meme schema pour `/contact`, `/connexion`, `/commandes/creation/5`, `/employe/commandes`.

## Fiche 2 - Commandes terminal utiles

```bash
git status
composer check
mysql -uroot -proot vite_gourmand -e "SELECT * FROM menus;"
mysql -uroot -proot vite_gourmand -e "SELECT id_commande, statut_actuel FROM commandes ORDER BY id_commande DESC LIMIT 5;"
```

A savoir expliquer :

- `git status` montre les fichiers modifies ou nouveaux.
- `composer check` verifie la configuration et la syntaxe PHP.
- `mysql ... -e` lance une requete SQL sans entrer dans le shell MySQL.

Exercice : ecris une requete qui affiche les avis en attente.

## Fiche 3 - Securite

Elements presents dans le code :

- Hash mot de passe : `password_hash` et `password_verify`.
- Sessions : `Session::login`, `Session::logout`, `Session::role`.
- CSRF : champ cache `_csrf_token` + `CsrfMiddleware`.
- Roles : `RoleMiddleware(['employe', 'administrateur'])`.
- SQL : requetes preparees PDO.

Exercice : explique pourquoi cacher un bouton admin dans la vue ne suffit pas. Reponse attendue : il faut aussi proteger la route cote serveur.

## Fiche 4 - Calcul d'une commande

Regles :

- Prix par personne = `prix_minimum / nombre_personnes_minimum`.
- Prix menu = prix par personne x nombre de personnes.
- Remise = 10 % si la commande contient au moins 5 personnes de plus que le minimum.
- Livraison = 0 EUR a Bordeaux, sinon `5 + 0,59 x distance_km`.

Exemple :

```text
Menu Cocktail Bordelais : 220 EUR pour 10 personnes
Commande : 15 personnes a Pessac, 10 km
Prix menu : 22 x 15 = 330 EUR
Remise : 33 EUR
Livraison : 5 + 0,59 x 10 = 10,90 EUR
Total : 330 - 33 + 10,90 = 307,90 EUR
```

Exercice : calcule le total du Menu Végé-Gourmand pour 11 personnes a Talence, 5 km.

## Fiche 5 - Parcours de demo jury

1. Ouvrir `/menus`.
2. Filtrer par theme ou regime.
3. Ouvrir `/menus/1` et montrer images, plats, allergenes.
4. Se connecter avec `claire.martin@example.test` / `ClientVite2026!`.
5. Commander un menu.
6. Aller dans `/commandes` puis ouvrir le detail.
7. Se connecter employe avec `lucas.employee@vitegourmand.test` / `EmployeVite2026!`.
8. Aller dans `/employe/commandes`, filtrer, changer un statut.
9. Se connecter admin avec `admin.jose@vitegourmand.test` / `AdminVite2026!`.
10. Aller dans `/admin` puis `/admin/statistiques`.
11. Aller dans `/admin/menus`, `/admin/plats`, `/admin/horaires`, `/admin/employes`.

## Fiche 6 - Exercices de code progressifs

Exercice 1 : ajouter une colonne visible

- Objectif : afficher le telephone client dans la liste employe.
- Etapes : modifier la requete `OrderModel::findAll`, passer la donnee a la vue, afficher dans `employee/orders.php`, lancer `composer check`.

Exercice 2 : ajouter un filtre

- Objectif : filtrer les commandes par ville.
- Etapes : ajouter `city` dans `OrderController::employeeIndex`, ajouter une condition SQL dans `OrderModel::findAll`, ajouter un input dans la vue.

Exercice 3 : modifier une page admin existante

- Objectif : ajouter une colonne "pause" dans `/admin/horaires`.
- Etapes : modifier la vue, adapter le modele si une nouvelle colonne SQL existe, tester avec un compte admin.

Exercice 4 : securiser une route

- Objectif : verifier que `/admin/horaires` est interdit aux clients.
- Etapes : lire `$adminAccess` dans `config/routes.php`, tester avec compte client puis admin.

## Fiche 7 - Recoder une feature par famille

Page publique en lecture, comme `/menus` :

```text
Route GET -> Controleur index/show -> Modele SELECT -> Vue -> Test navigateur
```

Formulaire public, comme `/contact` ou `/inscription` :

```text
Route GET formulaire -> Route POST -> Validation -> Modele INSERT/UPDATE -> Redirection -> Test base
```

Espace client, comme `/commandes` :

```text
Route protegee -> Session utilisateur -> Modele filtre par id_user -> Vue client -> Test avec compte client
```

Action employe, comme changer un statut :

```text
Route protegee employe -> Controleur verifie donnees -> Modele UPDATE + historique -> Redirection -> Test timeline
```

Ecran admin simple, comme `/admin/menus` :

```text
Route protegee admin -> Liste existante -> Formulaire creation/modification -> Modele prepare SQL -> Test page + base
```

Exercice : choisis une user story et classe-la dans une de ces familles avant de coder.

## Fiche 8 - Phrases courtes pour l'oral

- "J'ai choisi une architecture MVC pour separer affichage, logique de parcours et acces aux donnees."
- "La base SQL reste la source de verite pour les commandes, car on a besoin de relations et de contraintes."
- "Les statistiques admin sont prevues en MongoDB sous forme d'agregats, car elles servent surtout a la lecture et au dashboard."
- "Les changements de statut sont historises pour garder une trace de qui a fait quoi et quand."
- "Les acces prives sont controles par middleware cote serveur."
- "Les formulaires POST sont proteges par token CSRF."

## Fiche 9 - User stories client connecte

Document de reference :
`docs/project-management/client-connected-user-stories-validation.md`.

Schema commun :

```text
Route protegee -> AuthMiddleware -> Session::userId()
-> Controleur client -> Modele filtre par utilisateur
-> Vue client -> Test navigateur + verification SQL si besoin
```

US a savoir expliquer :

- US-009 : `/mon-compte` affiche l'espace client, `/mon-compte/modifier` modifie le profil.
- US-010 : `/commandes/creation/{menuId}` prepare une commande depuis un menu.
- US-011 : `OrderModel::calculateTotals` recalcule le prix cote serveur.
- US-012 : `OrderModel::findForUser` affiche uniquement les commandes du client connecte.
- US-013 : `OrderModel::findHistory` alimente la timeline de suivi.
- US-014 : modification et annulation client seulement si `statut_actuel = en_attente`.
- Avis client : `/avis` affiche le formulaire seulement apres une commande `terminee` sans avis.

Exercice oral :

```text
Explique le parcours "je commande un menu" en 60 secondes :
1. route
2. controleur
3. modele
4. regle metier
5. test qui prouve que ca marche
```

Phrases utiles :

- "L'id client ne vient jamais d'un champ cache : il vient de la session."
- "La vue peut masquer un bouton, mais le modele reverifie toujours la regle."
- "Le JavaScript aide a afficher l'estimation ; le serveur reste la source de verite."
- "Un avis est possible seulement apres une commande terminee et reste en attente de moderation."
