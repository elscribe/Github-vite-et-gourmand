# Documentation gestion de projet

Ce dossier regroupe les documents locaux qui completent le suivi Notion du
projet Vite & Gourmand.

La source de pilotage principale reste Notion, mais ces fichiers permettent de
garder une trace versionnee dans GitHub pour le jury.

## Documents

| Fichier | Role |
|---|---|
| `user-story-implementation-report.md` | Etat d'avancement des user stories et tests realises. |
| `audit-page-07-commande-espace-client-2026-07-22.md` | Audit page 7 de l'enonce : commande, espace client, suivi, emails et avis. |
| `audit-page-08-espace-employe-2026-07-22.md` | Audit page 8 de l'enonce : espace employe, statuts, annulations, notifications retour materiel et moderation des avis. |
| `audit-page-09-espace-admin-contact-deploiement-2026-07-22.md` | Audit page 9 de l'enonce : espace administrateur, creation employe, statistiques MongoDB, contact, deploiement et RGAA. |
| `client-connected-user-stories-validation.md` | Validation detaillee des user stories client connecte : compte, commandes, suivi, modification, annulation et avis. |
| `user-story-debug-log.md` | Journal des echecs, causes et solutions par user story. |
| `audit-context.md` | Regle permanente des audits ECF : source locale, criteres de verification et decision push/main. |
| `decision-avis-accueil-2026-07-22.md` | Decision de perimetre : les avis valides sont affiches automatiquement ; la selection editoriale manuelle est une evolution future. |
| `decision-role-employe-menus-2026-07-22.md` | Decision de perimetre : l'employe traite commandes/avis, l'administrateur garde menus/plats/horaires. |
| `../manual/final-user-story-test-matrix.md` | Matrice finale de recette : US, role, page Figma, route code, test, statut et preuve. |
| `recherche-anglophone.md` | Recherche anglophone : source OWASP, traduction et apport au controle d'acces. |
| `public-figma-inventory.md` | Correspondance entre pages publiques, Figma, code et captures. |
| `public-layout-documentation.md` | Explication du layout public, des routes, vues, assets et interactions. |
| `journal-de-bord-public-layout.md` | Entree de journal de bord pour l'integration publique et le premier commit. |
| `audit-page-05-catalogue-auth-detail-2026-07-22.md` | Audit page 5 de l'enonce : catalogue, filtres dynamiques, inscription, connexion, reset, detail menu et commande preselectionnee. |
| `audit-page-06-visiteur-commande-conditions-2026-07-22.md` | Audit page 6 de l'enonce : visiteur bloque avant commande, reprise apres authentification et conditions menu visibles. |

## Utilisation pour l'oral

Avant une demonstration, relire dans cet ordre :

```text
1. user-story-implementation-report.md
2. client-connected-user-stories-validation.md
3. public-layout-documentation.md
4. user-story-debug-log.md
5. journal-de-bord-public-layout.md
```

Cette lecture donne le fil logique : besoin utilisateur, code MVC, tests,
problemes rencontres, solutions et prochaines actions.
