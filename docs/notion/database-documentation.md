# Documentation Notion - Modelisation et bases de donnees

Cette page peut etre collee dans Notion pour presenter la partie modelisation du projet Vite & Gourmand.

## Objectif

Documenter la transformation de l'enonce ECF en modele de donnees, scripts SQL et collections MongoDB.

## Livrables disponibles

- `database/business-rules.md` : regles metier, entites, cardinalites et justifications.
- `docs/database/MCD.drawio` et `docs/database/MCD.png` : modele conceptuel de donnees.
- `docs/database/MLD.drawio` et `docs/database/MLD.png` : modele logique de donnees.
- `docs/database/MPD.drawio` et `docs/database/MPD.png` : modele physique de donnees.
- `docs/database/data-dictionary.md` : dictionnaire de donnees.
- `database/sql/create_database.sql` : creation complete de la base relationnelle.
- `database/sql/seed_database.sql` : donnees de demonstration completes.
- `database/sql/legacy/` : ancien decoupage SQL conserve uniquement comme archive.
- `database/mongodb/collections.md` : collections MongoDB.
- `database/mongodb/sample-data.json` : exemples de documents statistiques.
- `docs/database/database-choices.md` : justification orale.
- `docs/database/audit-report.md` : controle final de coherence.

## Decision principale

SQL est la source de verite pour les donnees metier. MongoDB est reserve aux statistiques du tableau de bord administrateur parce que l'enonce impose une base non relationnelle pour ces donnees.

## Points a presenter au jury

- Le MCD de l'enonce est conserve comme base, avec suppression de l'ancienne entite de qualification des allergenes devenue non necessaire au modele final.
- Les ajouts sont justifies par le texte de l'enonce et les parcours applicatifs : galerie d'images, horaires, contact, reinitialisation de mot de passe, historique des statuts et avis rattache a une commande.
- Les statistiques MongoDB sont des agregats recalculables depuis SQL.
- Le modele evite de supprimer les donnees historiques : on desactive un employe au lieu de le supprimer.
