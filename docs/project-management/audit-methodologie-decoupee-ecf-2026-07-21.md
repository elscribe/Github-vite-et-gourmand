# Methodologie d'audit decoupee - ECF Vite & Gourmand

Date : 21 juillet 2026.

## Objectif

Auditer le projet par blocs pour ne manquer aucun critere de l'enonce ECF.

La bonne methode n'est pas de choisir entre :

- audit par page de l'enonce ;
- audit par domaine : documentation, securite, code, UX/UI, deploiement.

La methode la plus sure est hybride :

1. Extraire les exigences page par page depuis l'enonce.
2. Classer chaque exigence dans un domaine d'audit.
3. Auditer chaque domaine avec des preuves concretes.
4. Revenir a la matrice page par page pour verifier qu'aucune ligne de l'enonce
   n'a ete oubliee.

## Analyse du conseil Gemini

Le conseil propose l'ordre suivant :

1. Gestion de projet et conception.
2. Base de donnees SQL/NoSQL.
3. Code back et securite.
4. Front-end, UX/UI et RGAA.
5. Git, deploiement et livrables.

Lecture : cet ordre est globalement bon, car il commence par les fondations et
termine par la publication. Il faut toutefois l'adapter au contexte actuel du
projet Vite & Gourmand :

- les livrables publics ne doivent pas etre controles seulement a la fin, car
  GitHub public, deploiement et Notion sont deja des bloquants de rejet ;
- la base de donnees doit etre integree a l'audit code et architecture, car les
  regles metier dependent directement des schemas SQL/MongoDB ;
- la securite doit etre auditee a part, car elle est demandee dans l'enonce, la
  copie Studi et le referentiel DWWM ;
- l'audit UX/UI doit inclure Figma, exports PDF, responsive et RGAA, pas
  seulement le rendu visuel.

Ordre recommande pour notre suite :

1. Documentation et gestion de projet.
2. Code, architecture et base de donnees.
3. Securite.
4. UX/UI, Figma, responsive et RGAA.
5. Git, deploiement, publication et copie Studi.
6. Controle final page par page de l'enonce.

## Matrice page par page de l'enonce

| Page | Exigences identifiees | Domaine d'audit |
|---|---|---|
| 1 | Copie Word/Excel a rendre, nommage du fichier, contexte ECF, mise en situation professionnelle. | Documentation finale / Copie Studi |
| 2 | Competences evaluees : environnement de travail, maquettage, interfaces statiques, interfaces dynamiques, base relationnelle, acces SQL/NoSQL, composants metier serveur, documentation de deploiement. | Documentation, code, architecture, UX/UI, deploiement |
| 3 | Contexte Vite & Gourmand : Julie et Jose, entreprise bordelaise de 25 ans, menus envoyes par mail, besoin d'application web. Page d'accueil : presentation entreprise, professionnalisme, avis clients valides. | Documentation fonctionnelle, UX/UI, code front/back |
| 4 | Menu applicatif : accueil, menus, connexion, contact. Footer : horaires lundi-dimanche, mentions legales, CGV. Vue globale menus. Donnees menu : titre, galerie, description, theme, plats, minimum personnes, prix, allergenes, conditions, regime, plats reutilisables, stock. Configuration par admin/employe. | Code, architecture, BDD, UX/UI |
| 5 | Liste menus visible aux visiteurs et utilisateurs. Filtres dynamiques sans rechargement : prix max, fourchette prix, theme, regime, minimum personnes. Creation compte : nom, prenom, GSM, email, adresse, mot de passe fort, role utilisateur, mail bienvenue. Connexion email/mot de passe. Reset par mail. Detail menu complet et bouton commande. | Code, securite, UX/UI, email |
| 6 | Visiteur qui commande doit etre redirige vers connexion ou creation de compte. Conditions du menu visibles clairement. | Code, UX/UI, securite |
| 7 | Commande : informations client pre-remplies, adresse/date/heure/lieu, livraison Bordeaux/hors Bordeaux, menu preselectionne, minimum personnes, reduction 10%, detail prix, mail confirmation. Espace utilisateur : commandes detaillees, modification infos, annulation/modification avant acceptation, suivi des statuts, invitation avis apres commande terminee, note 1 a 5 + commentaire. | Code, BDD, securite, UX/UI, email |
| 8 | Espace employe : modifier/supprimer menus, plats, horaires. Modification/annulation commande seulement apres contact client par GSM/mail avec motif et mode de contact. Filtres commandes par statut/client. Statuts : accepte, en preparation, en livraison, livre, attente retour materiel, terminee. Notification retour materiel sous 10 jours ou 600 euros. Moderation avis. | Code, BDD, securite, UX/UI, email |
| 9 | Espace administrateur : creation compte employe, email notification sans mot de passe, desactivation employe, compte Jose cree hors inscription publique, impossibilite creer admin depuis app, admin herite des droits employe. Statistiques NoSQL : nombre commandes par menu, graphique, chiffre d'affaires par menu avec filtres. Contact : formulaire titre/description/email et envoi mail entreprise. Deploiement obligatoire sous peine de penalites. Application accessible RGAA. | Code, BDD SQL/NoSQL, securite, UX/UI, deploiement |
| 10 | Technologies libres sauf obligation d'une base relationnelle et non relationnelle. Stack possible : HTML/CSS/JS, PHP PDO, MySQL/MariaDB/PostgreSQL, MongoDB, plateforme de deploiement. Annexe MCD. | Documentation technique, architecture, BDD |
| 11 | Livrables : lien GitHub public, lien application deployee, lien outil gestion projet. Git : README local, branche principale, branche develop, branches feature, merge develop puis main. SQL creation + donnees. Manuel PDF avec presentation app et identifiants. Charte graphique PDF avec palette, polices, wireframes et mockups, 3 desktop et 3 mobile. | Git, deploiement, documentation, UX/UI |
| 12 | Documentation gestion projet. Documentation technique : reflexions technologiques initiales, configuration environnement, MCD ou diagramme de classe, diagramme d'utilisation, diagramme de sequence, documentation du deploiement. | Documentation, architecture, deploiement |

## Decoupage des audits a realiser

### Audit 1 - Documentation et gestion de projet

But : verifier que le dossier raconte correctement le projet et repond aux
questions de la copie Studi.

A controler :

- README principal.
- Documentation locale `docs/`.
- Notion : analyse besoin, cahier des charges, gestion projet, backlog,
  checklist, documentation technique, livrables finaux.
- Copie Studi : resume 200-250 mots, cahier des charges, choix techniques,
  environnement, securite, veille, recherche anglophone.
- Coherence des informations : noms Julie/Jose, 25 ans, Bordeaux, roles,
  statut du projet, URL finales.

Sortie attendue :

- liste des documents conformes ;
- liste des documents incomplets ;
- textes manquants a rediger ou synchroniser ;
- priorite des corrections.

### Audit 2 - Code, architecture et base de donnees

But : verifier que l'application implemente les fonctionnalites de l'enonce.

A controler :

- arborescence MVC ;
- routes ;
- controleurs ;
- modeles ;
- services ;
- vues ;
- scripts SQL creation + seed ;
- scripts MongoDB ;
- coherence UML/Merise/code/base ;
- regles metier : menus, commandes, statuts, remises, livraison, avis,
  employes, statistiques.

Sortie attendue :

- matrice fonctionnalite -> route -> controleur -> modele -> vue -> preuve ;
- bugs ou ecarts avec l'enonce ;
- reserves assumables devant le jury.

### Audit 3 - Securite

But : verifier les mecanismes reellement presents et la documentation associee.

A controler :

- authentification ;
- roles visiteur/client/employe/admin ;
- impossibilite de creer un administrateur depuis l'application ;
- mot de passe fort ;
- hash des mots de passe ;
- reset password ;
- CSRF ;
- echappement XSS ;
- requetes preparees PDO ;
- `.env` non versionne ;
- messages d'erreur non sensibles ;
- documentation securite et veille.

Sortie attendue :

- tableau mesure securite -> emplacement code -> preuve ;
- risques residuels ;
- phrase explicable dans la copie Studi.

### Audit 4 - UX/UI, Figma, responsive et RGAA

But : verifier les livrables visuels et l'experience utilisateur.

A controler :

- Figma : pages, charte, wireframes, mockups, prototype, design system ;
- exports PDF : charte, 6 wireframes, 6 mockups ;
- coherence Figma/code ;
- pages publiques et espaces prives ;
- filtres dynamiques sans rechargement ;
- conditions de menu visibles ;
- responsive mobile/desktop ;
- bases RGAA : contrastes, alt, labels, clavier, structure HTML.

Sortie attendue :

- conformite des livrables UX/UI ;
- ecarts visuels ou ergonomiques ;
- reserves RGAA ;
- captures finales a conserver.

### Audit 5 - Git, deploiement et livrables finaux

But : verifier que le projet est vraiment rendable au jury.

A controler :

- depot GitHub public ;
- branche `main` a jour ;
- branche `develop` coherente ;
- branches feature et historique defendable ;
- README installe et explique le local ;
- application deployee et fonctionnelle ;
- URL renseignee partout ;
- Notion partage et accessible ;
- Figma accessible ;
- manuel PDF final ;
- copie Studi completee avec liens et identifiants admin.

Sortie attendue :

- checklist finale de rendu ;
- blocants restants ;
- ordre exact d'execution avant depot.

## Regle de controle finale

Quand les cinq audits sont termines, on refait une derniere passe page par page
de l'enonce :

```text
Page de l'enonce -> Exigence -> Preuve locale/GitHub/Notion/Figma/deploiement -> OK ou correction
```

Cette derniere passe sert a verifier que le decoupage par domaine n'a pas laisse
tomber une exigence discrete, comme :

- le mail de bienvenue ;
- le mail de confirmation commande ;
- le mail de retour materiel ;
- la regle des 600 euros ;
- la visibilite explicite des conditions de menu ;
- le role administrateur cree hors inscription publique ;
- les donnees NoSQL pour statistiques ;
- le lien public GitHub ;
- le manuel PDF avec identifiants.
