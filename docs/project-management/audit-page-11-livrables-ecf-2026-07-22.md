# Audit page 11 - Objectif et livrables ECF

Date : 22 juillet 2026.

Source controlee : page 11 de l'enonce ECF, rendue depuis
`Enonce/Enonce.pdf`.

## Verdict court

La page 11 est **partiellement conforme**.

Le projet possede deja la plupart des livrables de fond : README, scripts SQL,
scripts MongoDB, manuel utilisateur PDF, documentation de deploiement, Notion,
Figma, charte graphique et exports de maquettes.

En revanche, le dossier n'est **pas encore pret pour le depot final Studi**,
car les livrables publics et la chaine Git finale ne sont pas stabilises :

- le depot GitHub public n'est pas verifie comme accessible au jury ;
- `main` et `origin/main` ne contiennent pas encore la version locale auditee ;
- l'application n'est pas encore deployee, par choix actuel ;
- le lien Notion jury n'est pas encore renseigne/teste ;
- la charte graphique PDF et les exports Figma sont hors depot Git ;
- la page source Figma `03 - Maquettes 2 > Desktop - Administration` a ete
  completee le 22 juillet 2026, mais l'ecran Menus/Plats doit etre recontrole
  avec le dernier code avant export final ;
- le manuel PDF contient encore l'URL deployee a renseigner.

## Exigences page 11

| Exigence | Statut | Preuve | Reserve |
|---|---:|---|---|
| Lien GitHub public contenant le code | Bloquant | README pointe vers `https://github.com/elscribe/Github-vite-et-gourmand`. Le connecteur GitHub retourne `404` sur ce depot. | Rendre le depot public et tester sans connexion. |
| Lien de l'application deployee | Bloquant assume | README et docs de deploiement indiquent que l'URL est a completer. | Deploiement Fly.io via Docker a faire plus tard, puis recette production. |
| Lien vers logiciel de gestion de projet | Bloquant | Notion contient bien `ECF - Vite & Gourmand`, `Gestion de projet`, `Livrables`, `Informations de rendu`. | Il manque le lien partage jury teste sans connexion. |
| README avec demarche locale | Conforme avec reserve finale | `README.md` contient installation, `.env`, SQL, MongoDB, comptes demo et liens. | Remplacer les mentions "a completer" apres deploiement et partage Notion. |
| Branche principale | Present mais pas final | Branches locales `main` et `develop` presentes. | `main` n'a pas encore recu `develop`; `origin/main` est tres en retard. |
| Branche de developpement | Present | Branche locale `develop`, branche distante `origin/develop`. | `develop` local a 11 commits non pousses. |
| Branches feature issues de develop | Present | Plusieurs branches `feature/*`, `fix/*`, `docs/*` existent. | Certaines branches distantes sont anciennes et plusieurs travaux sont encore sur branches docs/fix. |
| Merge feature -> develop -> main | Non final | `develop` a 15 commits que `main` n'a pas. Le travail courant est 23 commits devant `origin/main`. | Faire le merge final seulement apres recette et nettoyage. |
| Fichier SQL de creation | Conforme | `database/sql/create_database.sql`, 426 lignes, contient `DROP DATABASE`, `CREATE DATABASE` et 16 `CREATE TABLE`. | Rien de bloquant. |
| Fichier SQL d'integration de donnees | Conforme | `database/sql/seed_database.sql`, 437 lignes, contient les `INSERT INTO` pour roles, utilisateurs, menus, commandes, avis, horaires, etc. | Rien de bloquant. |
| Manuel utilisateur PDF | Conforme avec reserve | `docs/manual/user-manual.pdf`, 6 pages, contient les comptes client/employe/admin. | L'URL deployee reste "A renseigner apres deploiement". |
| Charte graphique PDF | Present hors depot | `Maquettes/export pdf/Charte graphique complete - Vite & Gourmand - ECF.pdf`, 1 page longue, 19 Mo. | A copier/versionner dans le depot ou joindre explicitement au rendu. |
| Palette et police dans la charte | Conforme | La charte affiche palette, couleurs metier, `Playfair Display` et `Inter`. | Rien de bloquant sur le contenu. |
| Exports wireframes et mockups : 3 desktop + 3 mobile | Present hors depot avec reserve de recontrole Figma | 6 wireframes PDF et 6 mockups PDF trouves dans `Maquettes/export pdf/`. La section `03 - Maquettes 2 > Desktop - Administration` a ete completee le 22 juillet 2026. | Les exports ne sont pas dans le depot Git et l'ecran admin Menus/Plats doit etre recompare au dernier code avant export final. |

## Controle Git

Etat observe :

- branche courante : `docs/finalisation-dossier-jury` ;
- modifications locales non committees issues de l'audit precedent ;
- `develop` contient 15 commits absents de `main` ;
- `main` contient 2 commits absents de `origin/main` ;
- `develop` local contient 11 commits absents de `origin/develop` ;
- la branche courante contient 23 commits absents de `origin/main`.

Interpretation :

Le workflow Git existe, mais il n'est pas dans son etat final ECF. Pour le
rendu, il faut que le jury voie une branche principale publique contenant la
meme version que l'application deployee.

## Controle PDF UX/UI

Exports locaux trouves dans :

`/Users/jordanmf/Documents/Programation/studi/Projets/ECF/Vite&Gourmand/Maquettes/export pdf`

Contenu :

- 1 charte graphique PDF ;
- 3 wireframes desktop/mobile publics ou internes ;
- 3 wireframes complementaires pour atteindre 6 wireframes ;
- 3 mockups desktop : espace public, espace gourmand, dashboard admin ;
- 3 mockups mobile : espace public, espace gourmand, dashboard employe.

La preuve UX/UI existe donc, mais elle n'est pas encore integree au depot Git.

Correction Figma ajoutee apres recontrole : l'export PDF admin desktop etait
bien present, mais la section source `03 - Maquettes 2 > Desktop -
Administration` etait vide/incomplete. Elle contient maintenant 10 frames admin
desktop recopiees depuis `03 - Maquettes test > Section 1`. Le statut reste
"conforme avec reserve" tant que l'ecran admin Menus/Plats n'a pas ete compare
au dernier code et que les exports PDF finaux ne sont pas regroupes dans le
rendu.

## Controle Notion

Pages retrouvees :

- `ECF - Vite & Gourmand` ;
- `Gestion de projet` ;
- `Livrables` ;
- `Informations de rendu` ;
- `UX/UI`.

Notion est bien utilise comme outil de gestion projet. La page
`Informations de rendu` indique encore que le lien GitHub public, le lien
application deployee et le lien Notion partage jury restent a completer.

## Controle technique rapide

Commande de verification lancee :

```text
composer check
```

Resultat : OK. Le `composer.json` est valide et les fichiers PHP controles ne
presentent pas d'erreur de syntaxe.

Controle secrets :

- `.env` existe localement mais est ignore par `.gitignore` ;
- `.env.example` est versionne ;
- les `.DS_Store` sont ignores par `.gitignore`.

## Corrections recommandees dans l'ordre

1. Terminer les corrections documentaires en cours et stabiliser le worktree.
2. Recontroler dans Figma l'ecran admin Menus/Plats avec le dernier code de
   `/admin/menus`, puis reexporter les PDF UX/UI finaux si necessaire.
3. Copier/versionner les PDF UX/UI obligatoires dans un dossier clair, par
   exemple `docs/design/exports/`.
4. Mettre a jour `docs/deliverables/final-deliverables.md` avec les vrais liens
   et les vrais statuts.
5. Committer la version stable.
6. Fusionner les branches utiles vers `develop`, tester, puis fusionner
   `develop` vers `main`.
7. Pousser `main` et `develop` sur GitHub.
8. Rendre le depot GitHub public et tester l'URL sans connexion.
9. Partager Notion au jury et tester le lien sans connexion.
10. Deployer l'application sur Fly.io via Docker.
11. Initialiser SQL et MongoDB sur l'environnement de deploiement.
12. Tester les comptes demo sur l'URL deployee.
13. Mettre a jour README, manuel PDF, documentation de deploiement, Notion et
    copie Studi avec les liens definitifs.

## Decision

Ne pas deposer encore la page 11 telle quelle.

Le contenu existe, mais les livrables publics ne sont pas encore fermes. La
priorite n'est pas de refaire le fond du projet ; elle est de rendre les preuves
accessibles au jury : source Figma admin desktop propre et recontrolee, GitHub
public a jour, Notion partage, application Fly.io, PDF UX/UI dans le rendu, et
URLs finales partout.
