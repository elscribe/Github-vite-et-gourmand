# UX/UI et charte graphique

Derniere consolidation documentaire : 22 juillet 2026.

## Acces Figma

- Lien global : <https://www.figma.com/design/sMkvVuvOyBkMvlTIsq2eCY/Vite---Gourmand?m=auto&t=eaqGOcxDQGMr22Ek-6>
- Fichier Figma : `Vite---Gourmand`
- Cle Figma : `sMkvVuvOyBkMvlTIsq2eCY`

Le lien Figma doit etre teste avant rendu depuis un navigateur non connecte.

## Etat Figma

Inventaire controle le 21 juillet 2026 :

| Element | Etat |
|---|---|
| Pages Figma | 8 pages principales. |
| Styles texte | 15 styles locaux. |
| Variables | 40 variables locales. |
| Collections de variables | 3 collections. |
| Composants / component sets | 51 composants ou ensembles de composants. |
| Exports PDF locaux | 13 PDF : charte + 6 wireframes + 6 maquettes. |

Pages principales :

- `00 - Design System`
- `01 - Charte graphique`
- `02 - Wireframes`
- `03 - Maquettes test`
- `03 - Maquettes 2`
- `04 - Prototype`
- `05 - Exports PDF`
- `00 bis - Design System MVP ECF - Proposition`

## Wireframes basse fidelite

Les exports locaux couvrent les 6 wireframes attendus :

| Ecran | Format |
|---|---|
| Espace publique | Desktop |
| Espace publique | Mobile |
| Mon espace gourmand | Desktop |
| Mon espace gourmand | Mobile |
| Espace employe | Mobile |
| Espace administrateur | Desktop |

Reserve a connaitre : au moment du controle, certains wireframes publics sont
plus faciles a retrouver dans les exports PDF locaux que comme frames directs
dans la page Figma `02 - Wireframes`. Les PDF locaux restent la preuve de rendu.

## Maquettes haute fidelite

Les exports locaux couvrent les 6 maquettes finales attendues :

| Ecran | Format |
|---|---|
| Espace publique | Desktop |
| Espace publique | Mobile |
| Mon espace gourmand | Desktop |
| Mon espace gourmand | Mobile |
| Tableau de bord employe | Mobile |
| Tableau de bord administrateur | Desktop |

Correction appliquee le 22 juillet 2026 : la section Figma
`03 - Maquettes 2 > Desktop - Administration` etait vide/incomplete. Les frames
admin desktop existantes ont ete recopiees depuis `03 - Maquettes test >
Section 1` vers cette section :

- `Admin - Dashboard - Desktop` ;
- `Admin - Dishes - Desktop` ;
- `Admin - Employees - Desktop` ;
- `Admin - Employee Create - Desktop` ;
- `Admin - Employee Edit - Desktop` ;
- `Admin - Menus - Desktop` ;
- `Admin - Menus Recettes Plats - Desktop` ;
- `Admin - Horaires - Desktop` ;
- `Admin - Statistics - Desktop` ;
- `Admin - Statistics Filters - Desktop`.

Reserve restante : recontroler la correspondance exacte de la frame
`Admin - Menus Recettes Plats - Desktop` avec le dernier code de la page admin
`/admin/menus`, puis reexporter le PDF final si cette page est presentee au jury.

Les maquettes couvrent les roles principaux :

- visiteur public ;
- client connecte ;
- employe ;
- administrateur.

## Design system

Le fichier Figma contient maintenant un socle de design system :

- variables de couleurs ;
- styles typographiques ;
- composants de navigation ;
- boutons ;
- cartes ;
- badges ;
- elements de formulaire ;
- tableaux et surfaces back-office ;
- composants utiles aux dashboards.

Ce design system sert de reference visuelle pour garder une coherence entre les
pages publiques, l'espace client, l'espace employe et l'administration.

## Charte graphique

La charte graphique presente :

- logos desktop, mobile et compact ;
- palette de couleurs ;
- typographies ;
- iconographie ;
- boutons et CTA ;
- visuels de reference ;
- exemples d'application sur les maquettes.

Export local :

```text
Maquettes/export pdf/Charte graphique complete - Vite & Gourmand - ECF.pdf
```

## Correspondance Figma / code

Le document `docs/project-management/public-figma-inventory.md` relie les pages
publiques aux captures Figma et aux vues PHP.

La matrice finale de recette se trouve dans :

```text
docs/manual/final-user-story-test-matrix.md
```

Elle relie chaque user story a :

- une page Figma attendue ;
- une route ou page code ;
- un test de recette ;
- un statut ;
- une preuve a conserver.

## Points de coherence a verifier avant rendu

| Sujet | Controle |
|---|---|
| Identite narrative | Garder les memes noms dans Figma, Notion, SQL et code. |
| Anciennete entreprise | Garder une formulation unique : entreprise familiale de Bordeaux, environ 25 ans d'activite si conserve. |
| Statuts commande | Harmoniser Figma, SQL et interface : `en_attente`, `acceptee`, `en_preparation`, `en_livraison`, `livree`, `en_attente_retour_materiel`, `terminee`, `annulee`. |
| Responsive | Verifier les pages principales mobile et desktop. |
| Accessibilite | Verifier contrastes, labels, alt images et navigation clavier de base. |
| Source Figma admin desktop | Section `03 - Maquettes 2 > Desktop - Administration` completee le 22 juillet 2026 ; recontroler l'ecran Menus/Plats avec le dernier code avant export final. |

## Phrase pour le jury

La conception UX/UI a ete realisee dans Figma avec wireframes, maquettes,
charte graphique et design system. Les exports PDF prouvent les ecrans demandes,
et la matrice de recette relie ces ecrans aux user stories et aux routes codees.
