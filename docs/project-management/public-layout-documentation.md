# Documentation du layout public

Date de mise a jour : 14 juillet 2026.

Objectif : expliquer comment la partie publique de Vite & Gourmand est codee,
comment elle se rattache au backlog Notion et comment la presenter devant le
jury.

## Perimetre documente

Cette documentation couvre le socle public et visiteur :

- page d'accueil `/` ;
- liste des menus `/menus` ;
- details de menus `/menus/{id}` ;
- page contact `/contact` ;
- pages d'authentification publiques `/connexion`, `/inscription`,
  `/mot-de-passe/oublie`, `/mot-de-passe/reinitialisation` ;
- pages legales du footer `/mentions-legales`, `/cgv`, `/confidentialite` ;
- header, menu mobile, footer, bouton retour en haut et overlays publics.

## User stories rattachees

| US | Fonctionnalite | Etat actuel |
|---|---|---|
| US-001 | Consulter la page d'accueil | Codee et responsive, fidelite Figma encore a affiner par passes visuelles. |
| US-002 | Consulter tous les menus | Codee avec cartes visuelles, images, badges, prix et liens de detail. |
| US-003 | Filtrer les menus dynamiquement | Codee en JavaScript sans rechargement de page. |
| US-004 | Consulter le detail d'un menu | Codee pour 6 menus avec galerie, conditions et CTA. |
| US-005 | Contacter l'entreprise | Codee avec validation, CSRF et insertion en base. |
| US-006 | Creer un compte | Codee avec role client impose cote serveur. |
| US-007 | Se connecter | Codee avec session et redirection selon le role. |
| US-008 | Mot de passe oublie | Codee en mode local avec token hash et expiration. |
| US-026 | Accessibilite RGAA | Audit interne documente : labels, alt, captions, skip-link, focus, reduced-motion ; reserves clavier/contrastes/lecteur d'ecran sur URL deployee. |
| US-027 | Securite par role | Routes protegees par middlewares cote serveur. |

## Structure technique

Le projet suit une architecture MVC simple.

```text
Requete HTTP
    -> config/routes.php
    -> Controller
    -> Model ou Service
    -> View
    -> Layout global
    -> CSS / JavaScript / Images
```

### Routes

Les routes publiques sont declarees dans `config/routes.php`.

Elles relient une URL a une methode de controleur. Exemple :

```text
/menus -> MenuController::index
/menus/{id} -> MenuController::show
/contact -> ContactController::create
```

### Controleurs

Les controleurs preparent les donnees de la page et choisissent la vue.

| Controleur | Role |
|---|---|
| `HomeController` | Affiche la page d'accueil et pose la classe `page-home`. |
| `MenuController` | Charge les menus, themes, regimes et details de menu. |
| `ContactController` | Affiche et traite le formulaire de contact. |
| `AuthController` | Gere inscription, connexion et mot de passe oublie. |
| `PlaceholderController` | Rend les pages legales publiques. |

### Modeles et service de presentation

Les modeles parlent a la base de donnees avec PDO.

| Fichier | Role |
|---|---|
| `MenuModel` | Lit les menus actifs, themes, regimes, plats, images et allergenes. |
| `ContactModel` | Enregistre les messages envoyes depuis le formulaire public. |
| `UserModel` | Cree les clients, verifie les identifiants et gere les roles. |
| `PasswordResetModel` | Genere et verifie les tokens de reinitialisation. |
| `MenuPresentation` | Ajoute les contenus Figma propres aux cartes et pages detail. |

`MenuPresentation` est important a expliquer : il evite de mettre toute la
presentation Figma directement dans SQL. La base garde les menus, le service
ajoute les visuels, libelles marketing et sections de presentation.

### Vues

Les vues contiennent le HTML propre a chaque ecran.

| Vue | Usage |
|---|---|
| `home/index.php` | Accueil public Figma : hero, histoire, engagements, menus, avis. |
| `menus/index.php` | Liste des menus, filtres rapides et overlay des filtres. |
| `menus/show.php` | Detail d'un menu avec galerie et conditions. |
| `contact/create.php` | Formulaire contact et informations de contact. |
| `auth/*.php` | Connexion, inscription et mot de passe oublie. |
| `placeholder/show.php` | Pages legales publiques harmonisees. |

## Layout commun

Le layout global est `app/Views/layouts/main.php`.

Il contient :

- les polices Google Fonts et Bootstrap ;
- le header public ;
- le menu mobile public ;
- l'affichage des messages flash ;
- le `<main>` qui recoit la vue ;
- le bouton `Retour en haut` ;
- le footer commun ;
- le chargement de `public/assets/js/app.js`.

### Header

Le header utilise deux logos :

- `logo-primary.png` pour desktop ;
- `logo-mobile-vg.png` pour mobile.

Les liens actifs sont calcules dans le layout avec le chemin courant :

```text
/ -> Accueil actif
/menus... -> Nos Menus actif
/contact -> Contact actif
/connexion, /inscription, reset -> Mon espace actif
```

Les pages `page-home` et `page-menus` ont un header transparent au chargement.
Le JavaScript ajoute `header-scrolled` apres le scroll pour afficher le fond.

### Menu mobile

Le menu mobile est dans le layout pour etre disponible sur toutes les pages.
Il s'ouvre avec le bouton burger et se ferme avec :

- le bouton fermer ;
- la touche Echap ;
- un clic sur un lien.

Le body recoit `mobile-menu-open` pour bloquer le scroll de la page pendant que
l'overlay est ouvert.

### Footer

Le footer est un composant partage. Une correction du footer s'applique donc a
toutes les pages publiques et client.

Il contient :

- logo compact ;
- adresse ;
- horaires ;
- contact ;
- liens informations : mentions legales, CGV, confidentialite.

### Retour en haut

Le bouton `Retour en haut` pointe vers l'ancre `#top` posee sur le body.
Il apparait au scroll via la classe `show-back-to-top` ajoutee par JavaScript.

## JavaScript public

Le fichier `public/assets/js/app.js` gere les interactions legeres.

| Comportement | Pourquoi |
|---|---|
| Header transparent puis fonce au scroll | Reprendre l'effet Figma et garder la lisibilite. |
| Bouton retour en haut | Faciliter la navigation mobile et longue page. |
| Menu mobile | Rendre la partie publique navigable sur telephone. |
| Filtres rapides | Mettre a jour les menus sans rechargement. |
| Overlay `Tous les filtres` | Preparer le composant Figma avance. |
| Selecteur visuel de role login | Suivre Figma sans laisser l'utilisateur choisir son role serveur. |

## CSS public

Le fichier `public/assets/css/style.css` centralise le design.

Les classes principales a connaitre :

- `page-home`, `page-menus`, `page-menu-detail` : variantes par page ;
- `site-header`, `site-nav`, `site-nav-links` : navigation ;
- `mobile-public-menu` : overlay mobile ;
- `home-*` : sections de l'accueil ;
- `menu-*` : liste et details des menus ;
- `auth-*`, `contact-*`, `legal-*` : pages publiques secondaires ;
- `site-footer` : footer global.

## Tests realises

Tests techniques :

```text
composer check
node --check public/assets/js/app.js
```

Tests fonctionnels :

- ouverture de `/`, `/menus`, `/menus/1` a `/menus/6` ;
- ouverture de `/contact`, `/connexion`, `/inscription` ;
- ouverture des pages legales du footer ;
- formulaire contact avec CSRF et insertion en base ;
- filtres menus sans rechargement ;
- routes publiques en `200 OK`.

Tests responsive :

- scan mobile en 390px sur les pages publiques ;
- verification absence d'erreur JavaScript ;
- verification absence de debordement horizontal bloquant ;
- captures locales comparees aux references Figma quand disponibles.

## Decisions importantes

| Decision | Explication pour le jury |
|---|---|
| Garder le texte important en HTML | Meilleure accessibilite, meilleur responsive, texte modifiable. |
| Utiliser `MenuPresentation` | Separarer donnees SQL et presentation Figma. |
| Footer dans le layout | Un composant commun evite les duplications. |
| Header transparent par classes body | Le comportement depend de la page et du scroll. |
| Filtres rapides en JavaScript | Respect de l'enonce : resultats mis a jour sans rechargement. |
| Overlay filtres separee | Permettra d'integrer la future frame Figma sans casser les filtres actuels. |

## Points encore a finir

- refaire une passe visuelle stricte avec Figma sur accueil mobile ;
- ajuster les espacements fins des pages menus et details ;
- finaliser l'overlay `Tous les filtres` quand la frame Figma sera stabilisee ;
- lancer un audit accessibilite plus formel ;
- deployer puis retester les routes sur l'URL publique.
