# Livrables finaux ECF

Date de consolidation : 23 juillet 2026.

Ce document liste les livrables a fournir au jury et indique leur emplacement
dans le depot ou dans les outils externes.

## Liens a fournir dans la copie Studi

| Lien | Statut | Valeur |
|---|---|---|
| Depot GitHub public | Public, a verifier une derniere fois hors session | <https://github.com/elscribe/Github-vite-et-gourmand> |
| Outil de gestion de projet Notion | Page projet a partager au jury | <https://app.notion.com/p/3794ea958e18801aba79dc472cbe9fb7> |
| Application deployee | Deployee sur Fly.io | <https://vite-gourmand-ecf-jmf.fly.dev> |
| Figma | Present | <https://www.figma.com/design/sMkvVuvOyBkMvlTIsq2eCY/Vite---Gourmand?m=auto&t=eaqGOcxDQGMr22Ek-6> |

## Identifiants de demonstration

| Role | Email | Mot de passe |
|---|---|---|
| Client | `claire.martin@example.test` | `ClientVite2026!` |
| Employe | `lucas.employee@vitegourmand.test` | `EmployeVite2026!` |
| Administrateur | `admin.jose@vitegourmand.test` | `AdminVite2026!` |

Ces comptes ont ete utilises pendant la recette de l'application deployee. Une
derniere verification manuelle reste conseillee le jour du depot.

## Documents locaux

| Livrable | Emplacement | Statut |
|---|---|---|
| README projet | `README.md` | Synchronise avec Fly.io. |
| Documentation gestion projet | `docs/project-management/README.md` | Presente. |
| Matrice finale des tests | `docs/manual/final-user-story-test-matrix.md` | Presente. |
| Checklist recette MVP | `docs/manual/mvp-test-checklist.md` | Presente, statuts a confirmer apres recette finale. |
| Manuel utilisateur | `docs/manual/user-manual.md` | Present. |
| Manuel utilisateur PDF | `docs/manual/user-manual.pdf` | Present, a joindre au rendu. |
| Documentation securite | `docs/security/README.md` | Presente. |
| Accessibilite / RGAA | `docs/accessibility/rgaa-audit-2026-07-22.md` | Audit interne documente ; ne pas annoncer une conformite RGAA totale. |
| Veille securite | `docs/security/security-watch.md` | Presente. |
| Recherche anglophone | `docs/project-management/recherche-anglophone.md` | Presente. |
| Documentation de deploiement | `docs/deployment/README.md` | Presente avec URL Fly.io. |
| Documentation base de donnees | `docs/database/README.md` | Presente. |
| Dictionnaire de donnees | `docs/database/data-dictionary.md` | Present. |
| Choix SQL/NoSQL | `docs/database/database-choices.md` | Present. |
| UML | `docs/uml/` | Present. |
| Merise | `docs/database/MCD.*`, `MLD.*`, `MPD.*` | Present. |

## Exports Figma

Les exports PDF sont disponibles dans `Maquettes/export pdf/` :

- charte graphique complete ;
- 6 wireframes ;
- 6 maquettes haute fidelite.

## Verification finale

| Controle | Statut |
|---|---|
| GitHub public teste sans connexion. | A refaire juste avant depot. |
| URL deployee testee sans connexion. | OK au 23/07/2026, a refaire juste avant depot. |
| Notion partage au jury et teste. | A verifier apres partage public. |
| Figma accessible au jury. | A verifier hors session. |
| Compte admin teste sur URL deployee. | OK au 23/07/2026, a refaire juste avant depot. |
| README contient les liens finaux. | OK. |
| Documentation deploiement contient l'URL reelle. | OK. |
| Manuel PDF contient les informations finales. | OK apres regeneration. |
| Copie Studi completee avec liens et identifiants. | A finaliser dans le document Word de rendu. |

## Phrase pour le jury

Les livrables sont regroupes dans le depot GitHub et dans Notion. Le depot porte
le code, les scripts SQL/MongoDB, les diagrammes, les documents techniques, la
recette et le manuel utilisateur. Notion sert de suivi projet et Figma sert de
preuve UX/UI.
