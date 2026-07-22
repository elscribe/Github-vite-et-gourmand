# Audit diagrammes Merise/UML vs MCD enonce page 10

Date : 22 juillet 2026.

## Question traitee

Faut-il garder les diagrammes actuels du projet, ou revenir vers le MCD affiche
dans l'enonce page 10 ?

## Verdict court

Les diagrammes du projet sont meilleurs que le MCD affiche dans l'enonce page
10, a condition de les presenter comme un modele enrichi et justifie par les
besoins applicatifs.

Le MCD de l'enonce est un socle pedagogique. Il pose les entites centrales :
utilisateur, role, commande, avis, menu, plat, allergene, regime, theme et
horaire. Le projet conserve ce socle, puis ajoute les elements necessaires pour
repondre aux pages fonctionnelles de l'enonce.

## Pourquoi le modele projet est meilleur

Le modele local ajoute des elements que le MCD de l'enonce ne detaille pas assez
pour une application complete :

- `menu_images` pour la galerie d'images demandee pour chaque menu ;
- `commande_statuts` pour historiser tous les changements de statut avec date
  et heure ;
- `contact_messages` pour persister les demandes envoyees depuis la page contact ;
- `password_resets` pour gerer la reinitialisation de mot de passe sans stocker
  de jeton en clair ;
- `avis.id_commande` pour garantir qu'un avis correspond a une commande terminee ;
- `avis.moderated_by` et `moderated_at` pour tracer la moderation ;
- `utilisateurs.actif` pour desactiver un employe sans supprimer son historique ;
- un document MongoDB statistique pour repondre a l'obligation NoSQL.

Le modele est donc plus proche de l'application demandee que le MCD de reference.

## Comparaison avec le MCD de l'enonce

| Element | MCD enonce page 10 | Modele projet | Verdict |
|---|---|---|---|
| Utilisateur / role | Present | Present avec email unique, hash mot de passe, actif, ville/pays. | Mieux. |
| Menu | Present | Present avec theme, regime, conditions, minimum, prix minimum, stock, actif. | Mieux. |
| Prix menu | Plutot `prix_par_personne` dans le visuel | `prix_minimum`, puis prix par personne calcule par `prix_minimum / nombre_personnes_minimum`. | Defendable, car le texte demande le prix pour le minimum. |
| Galerie menu | Absente du MCD enonce | `menu_images`. | Mieux, besoin explicite. |
| Plat / allergenes | Present | N-n materialise par `plat_allergenes`. | Mieux. |
| Menu / plats | Present | N-n materialise par `menu_plats` avec position. | Mieux. |
| Commande | Present | Present avec prix menu, remise, livraison, total, distance, materiel, motif/contact. | Mieux. |
| Historique des statuts | Non detaille | `commande_statuts`. | Mieux, besoin explicite. |
| Avis | Present | Lie a commande, auteur, statut, moderation. | Mieux. |
| Horaires | Present mais isole | Present comme table autonome. | Correct. |
| Contact | Absent du MCD enonce | `contact_messages`. | Mieux, besoin explicite. |
| NoSQL | Mentionne dans la stack, pas dans le MCD relationnel | Les 4 collections MongoDB statistiques sont representees et les scripts sont disponibles. | Mieux. |

## Analyse par diagramme

### MCD

Statut : bon.

Le MCD local garde toutes les entites du MCD fourni et ajoute les entites utiles
aux parcours reels. Les cardinalites principales sont coherentes :

- un role possede plusieurs utilisateurs ;
- un utilisateur passe plusieurs commandes ;
- une commande concerne un menu ;
- une commande possede un historique de statuts ;
- une commande peut donner lieu a un avis ;
- un menu est compose de plusieurs plats via une association ;
- un plat peut contenir plusieurs allergenes via une association.

Reserve de forme : le MCD est un peu technique pour un pur MCD Merise, car il
affiche des identifiants et des champs techniques comme `password_hash` ou
`created_at`. Ce n'est pas bloquant, car le MCD de l'enonce melange deja
conceptuel et attributs techniques.

### MLD

Statut : tres bon.

Le MLD transforme correctement les associations n-n en tables d'association :

- `menu_plats` ;
- `plat_allergenes`.

Il ajoute les cles etrangeres, l'unicite de l'avis par commande et les relations
utilisateur/role/commande de maniere lisible. C'est plus exploitable que le MCD
de l'enonce pour prouver le passage vers SQL.

### MPD

Statut : tres bon.

Le MPD precise les types SQL, les cles primaires, les cles etrangeres, les index
et les contraintes `CHECK`. C'est un vrai plus pour le jury, car le sujet exige
des fichiers SQL de creation et d'integration de donnees.

Le statut initial `en_attente` est un ajout technique coherent : il permet au
client de modifier ou annuler une commande avant acceptation employe.

### Diagramme de cas d'utilisation

Statut : bon, corrige.

Le diagramme couvre bien les acteurs visiteur, utilisateur, employe et
administrateur. Il montre aussi les droits importants : commande reservee aux
comptes connectes, avis apres commande terminee, moderation et statistiques.

Correction appliquee : la gestion menus/plats/horaires est maintenant placee
dans l'espace administrateur, comme dans les routes codees. L'espace employe
reste centre sur les commandes et la moderation des avis.

### Diagramme de classes

Statut : bon.

Il reprend les entites principales du MCD/MLD/MPD et montre les objets metier
utiles : `Commande`, `Avis`, `Menu`, `MenuPlat`, `PlatAllergene`,
`PasswordReset`, `ContactMessage`, `StatistiqueMenu`.

Correction appliquee : la partie MongoDB mentionne maintenant les collections
`menu_statistics`, `monthly_statistics`, `menu_monthly_statistics` et
`dashboard_statistics`, ainsi que la lecture par `StatisticsModel` via
`mongosh`.

### Diagrammes de sequence

Statut : globalement bon.

Les sequences authentification, consultation/commande, gestion commande employe
et gestion avis expliquent bien pourquoi le MCD a ete enrichi :

- `password_resets` est justifie par la reinitialisation ;
- `commande_statuts` est justifie par le suivi de commande ;
- `avis` lie a une commande est justifie par le depot apres commande terminee ;
- `mode_contact_modification` et `motif_annulation` sont justifies par la
  modification/annulation employe apres contact client.

Correction appliquee : la sequence dashboard MongoDB ne parle plus d'un
`AggregationService` non code. Elle montre maintenant le flux reel :
`AdminController::statistics` appelle `StatisticsModel`, qui lit MongoDB avec
`mongosh`, puis bascule sur SQL si MongoDB est indisponible.

## Points d'oral a garder

1. Le choix `prix_minimum` est maintenant documente dans les diagrammes et les
   choix BDD. Il reste simplement a savoir le formuler a l'oral.
   Formulation recommandee :

   ```text
   Le MCD de l'enonce parle de prix par personne, mais le texte fonctionnel
   demande le prix pour le nombre minimum de personnes. J'ai donc stocke
   `prix_minimum`, puis je calcule le prix par personne a la commande avec
   `prix_minimum / nombre_personnes_minimum`.
   ```

## Decision recommandee

Ne pas revenir au MCD de l'enonce.

Garder les diagrammes du projet, car ils sont plus complets, plus proches du
code et plus defendables pour l'ECF. Les incoherences UML/MongoDB identifiees
pendant l'audit ont ete corrigees pour refleter le site code.
