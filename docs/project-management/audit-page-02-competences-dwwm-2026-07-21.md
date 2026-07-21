# Audit page 2 - Competences DWWM demontrees par le projet

Date : 21 juillet 2026.

## Source controlee

Page 2 de l'enonce ECF :

- developper la partie front-end d'une application web ou web mobile securisee ;
- installer et configurer son environnement de travail ;
- maquetter des interfaces utilisateur web ou web mobile ;
- realiser des interfaces utilisateur statiques ;
- developper la partie dynamique des interfaces ;
- developper la partie back-end d'une application web ou web mobile securisee ;
- mettre en place une base de donnees relationnelle ;
- developper des composants d'acces aux donnees SQL et NoSQL ;
- developper des composants metier cote serveur ;
- documenter le deploiement d'une application dynamique.

## Verdict

Le projet demontre bien la majorite des competences de la page 2.

Etat synthetique :

- 7 competences sont demontrees localement.
- 1 competence reste partielle au niveau rendu jury : la documentation du
  deploiement existe, mais le deploiement reel, l'URL publique et la recette
  production ne sont pas encore faits.

Conclusion : les competences techniques sont largement visibles dans le projet
local. Le risque de page 2 n'est pas le manque de code, mais le manque de preuve
finale partageable pour le deploiement et la publication.

## Tableau de controle

| Competence page 2 | Statut | Preuves dans le projet | Reserve |
|---|---|---|---|
| Installer et configurer son environnement de travail | Demontree localement | `README.md` decrit les prerequis, l'installation, `.env`, Composer, SQL, MongoDB. `composer.json` declare PHP, PDO, autoload PSR-4 et scripts `serve`, `check`. `.env.example` documente app, session, SQL, MongoDB, email et livraison. | Les liens publics et la branche `main` finale restent a stabiliser avant rendu. |
| Maquetter des interfaces utilisateur web ou web mobile | Demontree | Figma contient pages design system, charte, wireframes, maquettes, prototype. Les exports PDF locaux contiennent 1 charte, 6 wireframes et 6 mockups. | Verifier le partage Figma en navigation privee. |
| Realiser des interfaces utilisateur statiques web ou web mobile | Demontree | Vues PHP dans `app/Views/`, layout global, pages publiques, auth, compte, commandes, employe, admin. CSS dans `public/assets/css/style.css`, assets images dans `public/images/`. | Une passe UX/RGAA dediee reste utile pour la finition. |
| Developper la partie dynamique des interfaces utilisateur | Demontree | `public/assets/js/app.js` gere menu mobile, notifications, filtres menus sans rechargement, overlay de filtres, lightbox images, calcul dynamique du prix commande, recherche inline admin. | Le filtrage est dynamique cote DOM, pas une API AJAX ; c'est coherent avec l'exigence "sans rechargement". |
| Mettre en place une base de donnees relationnelle | Demontree | `database/sql/create_database.sql`, `seed_database.sql`, MCD/MLD/MPD, dictionnaire de donnees, contraintes, cles et index. | Verifier que la version finale est bien presente sur `main`. |
| Developper des composants d'acces aux donnees SQL et NoSQL | Demontree avec reserve | SQL : modeles `UserModel`, `MenuModel`, `OrderModel`, `ReviewModel`, `ContactModel`, `DishModel`, `ScheduleModel`. NoSQL : `StatisticsModel` interroge MongoDB via `mongosh`, scripts `database/mongodb/create_collections.js` et `seed_mongodb.js`. | Le code prevoit un secours SQL si MongoDB est indisponible. En production, il faudra configurer MongoDB ou expliquer clairement la reserve. |
| Developper des composants metier cote serveur | Demontree | Controleurs `AuthController`, `MenuController`, `OrderController`, `ReviewController`, `AdminController`, `ContactController`, services mail/presentation, routes client/employe/admin, regles commande/statuts/avis/employes/statistiques. | L'audit code detaille devra verifier chaque regle metier de l'enonce. |
| Documenter le deploiement d'une application dynamique | Partielle | `docs/deployment/README.md` decrit prerequis, variables, web root `public/`, SQL, MongoDB, permissions et tests post-deploiement. | L'URL publique, l'hebergeur retenu, le commit deploye et la recette production sont encore vides. Competence pas encore pleinement prouvable devant le jury. |

## Preuves techniques executees pendant le controle

- `composer check` : OK.
- 70 fichiers PHP controles sans erreur de syntaxe.
- `node --check public/assets/js/app.js` : OK.
- 13 exports PDF UX/UI detectes dans `../Maquettes/export pdf/`.

## Lecture competence par competence

### Front-end securise

La competence front-end est visible dans :

- les vues HTML/PHP ;
- les assets CSS et images ;
- le JavaScript d'interaction ;
- les formulaires avec labels et tokens CSRF ;
- l'echappement des sorties dynamiques avec `htmlspecialchars` ou helper
  d'echappement.

La preuve est suffisante pour dire que la partie front-end existe et n'est pas
simplement une maquette.

### Back-end securise

La competence back-end est visible dans :

- l'architecture MVC ;
- les routes ;
- les middlewares d'authentification, de role et CSRF ;
- les modeles PDO ;
- les services ;
- la separation client, employe et administrateur ;
- les mots de passe hashes.

La preuve est suffisante pour dire que le back-end est developpe. La securite
doit toutefois faire l'objet d'un audit dedie, car l'enonce et la copie Studi la
demandent explicitement.

### SQL et NoSQL

Le SQL est solide : scripts, schema, seed, documentation et modeles PDO.

Le NoSQL est present et relie au dashboard administrateur via MongoDB. Le choix
technique est particulier : le projet utilise `mongosh` depuis PHP plutot que
l'extension PHP MongoDB. C'est acceptable pour une demo ECF si c'est explique,
mais il faut eviter de laisser croire que l'extension PHP MongoDB est requise ou
deja installee partout.

### Deploiement

La documentation existe, mais elle est encore pre-deploiement. Pour transformer
cette competence en preuve finale, il faut :

1. choisir l'hebergeur ;
2. deployer ;
3. renseigner l'URL ;
4. documenter le commit deploye ;
5. tester les parcours principaux sur l'URL publique ;
6. mettre a jour README, Notion, manuel et copie Studi.

## Decision d'audit page 2

Reponse a la question : toutes les competences sont-elles demontrees ?

Non, pas encore a 100 % pour le rendu.

Reponse nuancee :

- oui pour les competences de conception, front-end, dynamique, back-end,
  base relationnelle, acces SQL/NoSQL et composants metier ;
- partiel pour le deploiement, car la documentation existe mais la preuve
  finale de mise en ligne manque encore.
