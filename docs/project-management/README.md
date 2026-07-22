# Documentation gestion de projet

Ce dossier regroupe les documents locaux qui completent le suivi Notion du
projet Vite & Gourmand.

La source de pilotage principale reste Notion, mais ces fichiers permettent de
garder une trace versionnee dans GitHub pour le jury.

## Documents

| Fichier | Role |
|---|---|
| `user-story-implementation-report.md` | Etat d'avancement des user stories et tests realises. |
| `user-story-debug-log.md` | Journal des echecs, causes et solutions par user story. |
| `audit-context.md` | Regle permanente des audits ECF : source locale, criteres de verification et decision push/main. |
| `decision-avis-accueil-2026-07-22.md` | Decision de perimetre : les avis valides sont affiches automatiquement ; la selection editoriale manuelle est une evolution future. |
| `decision-role-employe-menus-2026-07-22.md` | Decision de perimetre : l'employe traite commandes/avis, l'administrateur garde menus/plats/horaires. |
| `../manual/final-user-story-test-matrix.md` | Matrice finale de recette : US, role, page Figma, route code, test, statut et preuve. |
| `recherche-anglophone.md` | Recherche anglophone : source OWASP, traduction et apport au controle d'acces. |
| `public-figma-inventory.md` | Correspondance entre pages publiques, Figma, code et captures. |
| `public-layout-documentation.md` | Explication du layout public, des routes, vues, assets et interactions. |
| `journal-de-bord-public-layout.md` | Entree de journal de bord pour l'integration publique et le premier commit. |
| `audit-page-06-visiteur-commande-conditions-2026-07-22.md` | Audit page 6 de l'enonce : visiteur bloque avant commande, reprise apres authentification et conditions menu visibles. |

## Utilisation pour l'oral

Avant une demonstration, relire dans cet ordre :

```text
1. user-story-implementation-report.md
2. ../manual/final-user-story-test-matrix.md
3. public-layout-documentation.md
4. user-story-debug-log.md
5. journal-de-bord-public-layout.md
```

Cette lecture donne le fil logique : besoin utilisateur, code MVC, tests,
problemes rencontres, solutions et prochaines actions.
