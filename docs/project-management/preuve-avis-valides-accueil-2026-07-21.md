# Preuve - avis clients valides sur la page d'accueil

Date de controle : 21 juillet 2026
Perimetre : demande UX/UI page 3 de l'enonce, "afficher les avis clients qui sont valides" sur la page d'accueil.

## Constat initial

La page d'accueil affichait bien trois cartes d'avis avec un badge `Avis valide`, mais ces avis etaient declares en dur dans `app/Views/home/index.php`. Le projet possedait deja le workflow de moderation et la methode `ReviewModel::findValidated()`, mais l'accueil ne les utilisait pas.

Risque pour le jury : impossible de prouver que les avis visibles etaient de vrais avis clients moderes.

## Correctif applique

L'accueil consomme maintenant les avis valides depuis la base de donnees :

```text
HomeController -> ReviewModel::findValidated(3) -> avis.statut = 'valide' -> home/index.php
```

Fichiers modifies :

- `app/Controllers/HomeController.php` : chargement de `ReviewModel` et passage de `validatedReviews` a la vue.
- `app/Models/ReviewModel.php` : ajout de `commandes.date_prestation AS event_date` pour afficher le mois de la prestation.
- `app/Views/home/index.php` : suppression des avis statiques et rendu des avis valides provenant du modele.
- `database/sql/seed_database.sql` : reprise des trois avis decoratifs comme donnees de demonstration reelles.

## Donnees de demonstration ajoutees au seed

Les avis historiques de l'accueil ont ete conserves, mais ils sont maintenant relies a une chaine fonctionnelle complete :

```text
client -> commande terminee -> avis -> statut valide -> moderation employe/admin -> publication accueil
```

Donnees ajoutees sur une base fraiche :

| Client | Commande | Menu | Statut commande | Avis | Statut avis |
| --- | --- | --- | --- | --- | --- |
| Sophie Renaud | 21 | Menu Noel Tradition | `terminee` | 11 | `valide` |
| Marc Lemoine | 22 | Menu Cocktail Bordelais | `terminee` | 12 | `valide` |
| Helene Bernard | 23 | Menu Paques en Famille | `terminee` | 13 | `valide` |

Les dates `created_at` des avis sont les plus recentes du seed, afin que `ReviewModel::findValidated(3)` affiche ces trois avis sur une installation neuve.

## Tests realises

### 1. Validation PHP complete

Commande :

```bash
composer check
```

Resultat : OK. Le lint PHP complet passe sur `app/`, `config/` et `public/`.

### 2. Test modele sur base fraiche issue du seed

Controle effectue :

- creation d'une base temporaire `vite_gourmand_audit_205214` ;
- import de `database/sql/create_database.sql` ;
- import de `database/sql/seed_database.sql` ;
- appel direct a `ReviewModel::findValidated(3)` ;
- suppression de la base temporaire apres controle.

Resultat obtenu :

```text
validated_reviews=3
Sophie Renaud | Menu Noel Tradition | note=5 | statut=valide
Marc Lemoine | Menu Cocktail Bordelais | note=5 | statut=valide
Helene Bernard | Menu Paques en Famille | note=4 | statut=valide
```

Conclusion : sur une installation neuve, les trois avis de l'accueil viennent bien de la base et sont tous au statut `valide`.

### 3. Test HTTP sur base fraiche issue du seed

Controle effectue :

- demarrage local de l'application sur `http://127.0.0.1:8027` ;
- connexion a une base temporaire `vite_gourmand_audit_http_205230` ;
- requete HTTP `GET /` ;
- verification HTML des cartes avis.

Resultat obtenu :

```text
status 200
title Accueil - Vite & Gourmand
review_cards 3
verified_badges 3
Sophie R. present
Marc L. present
Helene B. present
```

Controle negatif :

```text
avis en_attente absent
avis refuse absent
```

Conclusion : la page d'accueil publie uniquement des avis valides.

### 4. Test HTTP sur la base locale actuelle

Controle effectue :

- demarrage local de l'application sur `http://127.0.0.1:8028` ;
- requete HTTP `GET /` ;
- verification HTML des cartes avis.

Resultat obtenu :

```text
status 200
title Accueil - Vite & Gourmand
review_cards 3
verified_badges 3
empty_review_message False
contains_en_attente False
contains_refuse False
```

Controle modele sur la base locale actuelle :

```text
main_db_validated_reviews=3
Claire Martin | Menu Paques en Famille | 5
Claire Martin | Menu Paques en Famille | 5
Maxime Girard | Menu Vege-Gourmand | 4
```

Conclusion : la base locale contient deja des avis valides de recette plus recents que ceux du seed. L'accueil affiche donc ces avis locaux, ce qui confirme que l'affichage est dynamique et non plus decoratif.

## Explication courte pour le jury

Avant correction, les avis visibles sur l'accueil etaient decoratifs. Maintenant, la page d'accueil appelle `ReviewModel::findValidated(3)`, qui filtre les avis avec `avis.statut = 'valide'`. Les avis visibles sont lies a un client, une commande terminee et un menu. Un avis en attente ou refuse n'est pas publie sur l'accueil.

La preuve a ete faite deux fois : sur une base fraiche issue du seed, puis sur la base locale actuelle. Dans les deux cas, la page retourne 200, affiche trois cartes avec trois badges `Avis valide`, et ne publie aucun avis non valide.
