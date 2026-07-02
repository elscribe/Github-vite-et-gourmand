# Organisation du depot GitHub

Ce document explique comment le depot GitHub du projet Vite & Gourmand est
organise et maintenu pour le rendu ECF.

## Role du depot

Le depot sert a centraliser :

- le README d'installation et de presentation ;
- les scripts SQL de creation et d'insertion ;
- les scripts MongoDB de creation et de seed ;
- la documentation technique utile au jury ;
- les sources draw.io et exports PNG des diagrammes Merise et UML ;
- le futur code applicatif PHP MVC.

La gestion de projet detaillee reste suivie dans Notion. Les elements techniques
necessaires a l'installation ou a la comprehension du projet sont repris dans le
depot.

## Branches

Etat actuel :

- `main` : branche principale utilisee pendant la phase de documentation.

Organisation prevue pendant le developpement :

- `main` : version stable et presentable ;
- `feature/*` : branches de travail pour les grandes fonctionnalites ;
- `develop` : branche intermediaire seulement si le volume de code le justifie.

Pour un projet ECF realise seul, l'usage de `main` reste acceptable pendant la
phase de cadrage. Les branches de fonctionnalites deviendront utiles au moment
de l'implementation PHP.

## Fichiers versionnes

Les fichiers versionnes doivent etre utiles au projet ou au jury :

- code source ;
- documentation Markdown ;
- fichiers SQL et MongoDB ;
- diagrammes sources `.drawio` ;
- exports lisibles `.png` ;
- fichiers d'exemple comme `.env.example`.

## Fichiers ignores

Les fichiers suivants ne doivent pas etre envoyes sur GitHub :

- `.env` et autres fichiers contenant des secrets ;
- `.DS_Store`, cree automatiquement par macOS ;
- fichiers temporaires ou backups comme `.bkp`, `.tmp`, `.bak` ;
- dossiers de dependances comme `vendor/` ou `node_modules/` ;
- caches, logs et sorties de build.

Ces exclusions sont gerees dans `.gitignore`.

## Regles de commit

Les messages de commit restent simples et explicites :

```text
docs: update database documentation
docs: add UML diagrams
db: add SQL seed data
feat: add authentication
fix: correct order status workflow
```

Chaque commit doit idealement correspondre a une action claire : documentation,
schema de base de donnees, diagramme, fonctionnalite ou correction.

## Controle avant rendu

Avant de transmettre le lien au jury :

- verifier que le depot est public ;
- verifier que le lien GitHub s'ouvre sans connexion ;
- verifier que le README est a jour ;
- verifier que `.env` n'est pas versionne ;
- verifier que les scripts SQL et MongoDB sont presents ;
- verifier que les fichiers parasites ne sont pas presents ;
- verifier que les diagrammes ont leurs sources `.drawio` et leurs exports PNG ;
- pousser tous les commits locaux vers `origin/main`.
