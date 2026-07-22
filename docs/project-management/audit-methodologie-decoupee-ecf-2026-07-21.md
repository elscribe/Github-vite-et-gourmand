# Methodologie d'audit decoupee - ECF Vite & Gourmand

Date : 21 juillet 2026.

## Objectif

Auditer le projet par pages de l'enonce, dans un ordre logique applicatif, tout
en verifiant a chaque etape les impacts documentation, code, architecture,
securite, UX/UI, base de donnees, Git et deploiement.

La bonne methode n'est donc pas seulement :

- un audit par page de l'enonce ;
- ou un audit par domaine separe.

La methode la plus sure est hybride :

1. Lire la page de l'enonce.
2. Extraire toutes les exigences de cette page.
3. Identifier les domaines touches par ces exigences.
4. Verifier les preuves dans le projet local, GitHub, Figma, Notion et la
   documentation.
5. Corriger ce qui bloque avant de passer aux pages suivantes, quand cela evite
   de revenir sur une decision deja auditee.

## Analyse de l'ordre d'audit propose

Le conseil propose l'ordre suivant :

1. Gestion de projet et conception.
2. Base de donnees SQL/NoSQL.
3. Code back et securite.
4. Front-end, UX/UI et RGAA.
5. Git, deploiement et livrables.

Lecture : cet ordre est globalement bon pour un audit par domaines. Pour notre
usage, il faut toutefois l'adapter, car l'objectif est de suivre l'enonce page
par page sans rater les dependances applicatives.

Points a garder :

- commencer par les fondations ;
- verifier SQL/NoSQL avant les statistiques ;
- isoler la securite ;
- finir par Git, deploiement et livrables finaux.

Points a modifier :

- les livrables publics ne doivent pas etre controles seulement a la fin, car
  GitHub public, deploiement et Notion sont des criteres bloquants ;
- la base de donnees doit etre reliee au code et aux regles metier ;
- la securite doit etre controlee au moment ou les roles et formulaires sont
  audites ;
- l'UX/UI doit etre comparee a la fois a Figma, aux exports PDF et au rendu
  reel.

## Methode retenue

Nous auditons page par page dans l'ordre logique suivant :

1. Page 2 - Competences DWWM demontrees par le projet.
2. Page 3 - Contexte, objectifs, page d'accueil, avis valides.
3. Page 4 - Navigation, footer, horaires, mentions legales, catalogue menus,
   donnees menus, configuration admin/employe.
4. Page 5 - Filtres dynamiques, creation de compte, connexion, reset password,
   detail menu et bouton commande.
5. Page 6 - Redirection visiteur avant commande, visibilite des conditions menu.
6. Page 7 - Commande, calcul prix/livraison/remise, espace client, suivi, avis.
7. Page 8 - Espace employe, statuts, modification/annulation motivee, moderation
   avis, notification retour materiel.
8. Page 9 - Espace administrateur, comptes employes, statistiques NoSQL,
   contact, deploiement obligatoire, RGAA.
9. Page 10 - Choix techniques, SQL/NoSQL, stack, justification.
10. Page 11 - Livrables GitHub, application deployee, Notion, README, Gitflow,
    SQL, manuel PDF, charte et maquettes.
11. Page 12 - Documentation gestion projet, documentation technique, MCD ou
    classes, use cases, sequences, deploiement.
12. Page 1 et copie Studi - Informations administratives et document final.

Cette sequence permet de verifier d'abord ce que le projet doit demontrer, puis
le parcours applicatif, puis les livrables finaux.

## Matrice page par page

| Page | Exigences principales | Domaines touches |
|---|---|---|
| 1 | Copie Word/Excel a rendre, nommage du fichier, contexte ECF. | Copie Studi, livrables finaux |
| 2 | Competences front-end, back-end, BDD relationnelle, acces SQL/NoSQL, composants metier, documentation de deploiement. | Code, architecture, BDD, UX/UI, deploiement |
| 3 | Contexte Vite & Gourmand, Julie/Jose, Bordeaux, 25 ans, besoin d'application web, accueil, presentation, professionnalisme, avis valides. | Documentation, UX/UI, code |
| 4 | Menu applicatif, footer, horaires, mentions legales, CGV, vue globale menus, donnees menu, configuration admin/employe. | Code, BDD, UX/UI, securite |
| 5 | Filtres menus dynamiques, creation de compte, mot de passe fort, role utilisateur, mail bienvenue, connexion, reset password, detail menu, bouton commande. | Code, securite, UX/UI, email |
| 6 | Visiteur redirige vers connexion/creation de compte avant commande, conditions de menu tres visibles. | Code, UX/UI, securite |
| 7 | Commande, pre-remplissage, livraison Bordeaux/hors Bordeaux, minimum personnes, reduction 10 %, detail prix, mail confirmation, espace utilisateur, modification/annulation avant acceptation, suivi, avis apres commande terminee. | Code, BDD, UX/UI, securite, email |
| 8 | Espace employe, modification/suppression menus/plats/horaires, modification/annulation commande apres contact, filtres commandes, statuts, retour materiel, moderation avis. | Code, BDD, securite, UX/UI, email |
| 9 | Admin, creation/desactivation employe, Jose, impossibilite creation admin publique, droits employe pour admin, statistiques NoSQL, contact, deploiement, RGAA. | Code, BDD SQL/NoSQL, securite, UX/UI, deploiement |
| 10 | Technologies libres sauf SQL + NoSQL obligatoires, stack technique possible, MCD. | Architecture, documentation technique, BDD |
| 11 | GitHub public, application deployee, outil gestion projet, README local, Gitflow, SQL creation/donnees, manuel PDF, charte PDF, wireframes/mockups 3 desktop + 3 mobile. | Git, deploiement, documentation, UX/UI |
| 12 | Documentation gestion projet, documentation technique, choix technologiques, environnement, MCD/classes, cas d'utilisation, sequences, deploiement. | Documentation, architecture, deploiement |

## Structure d'un audit de page

Chaque audit de page doit produire :

- la source exacte de la page auditee ;
- la liste des exigences ;
- le statut global : OK, partiel, bloquant ;
- les preuves trouvees ;
- les ecarts ;
- les corrections recommandees ;
- les impacts sur les pages suivantes.

Format conseille :

```text
Exigence -> preuve locale/GitHub/Notion/Figma/deploiement -> statut -> action
```

## Points de vigilance a ne pas oublier

- Le deploiement public est bloquant.
- Le depot GitHub doit etre public.
- La branche `main` doit contenir la version finale.
- Notion doit etre partage et coherent avec l'etat reel.
- Figma doit etre accessible au jury.
- La copie Studi demande les liens et identifiants administrateur.
- Les emails peuvent etre en mode log local, mais cela doit etre explique.
- MongoDB est obligatoire pour demontrer le NoSQL, meme si un secours SQL existe.
- L'audit RGAA complet n'est pas encore prouve, seules les bases sont presentes.

## Etat des audits deja lances

- Page 2 : audit cree dans `docs/project-management/audit-page-02-competences-dwwm-2026-07-21.md`.
- Page 4 : audit cree dans `docs/project-management/audit-page-04-menu-footer-catalogue-2026-07-21.md`.

## Regle de cloture

Quand toutes les pages sont auditees, refaire une passe finale :

```text
Page de l'enonce -> exigence -> preuve -> statut final -> lien vers correction
```

Cette passe finale doit permettre de remplir la copie Studi avec confiance et
de savoir exactement quelles reserves expliquer au jury.
