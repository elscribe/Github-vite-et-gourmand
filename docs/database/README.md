# Documentation base de donnees

Cette section regroupe les livrables de modelisation et de base de donnees du projet ECF Vite & Gourmand.

## Analyse metier

- [Regles metier et cardinalites](../../database/business-rules.md)
- [Dictionnaire de donnees](data-dictionary.md)
- [Choix techniques base de donnees](database-choices.md)
- [Rapport d'audit](audit-report.md)
- [Audit des scripts SQL et MongoDB](scripts-audit.md)

## Merise

- [MCD draw.io](MCD.drawio)
- [MCD PNG](MCD.png)
- [MLD draw.io](MLD.drawio)
- [MLD PNG](MLD.png)
- [MPD draw.io](MPD.drawio)
- [MPD PNG](MPD.png)

## SQL

- [Creation SQL](../../database/sql/create.sql)
- [Creation SQL complete](../../database/sql/create_database.sql)
- [Donnees de demonstration](../../database/sql/seed.sql)
- [Donnees de demonstration completes](../../database/sql/seed_database.sql)
- [Index](../../database/sql/indexes.sql)
- [Vues](../../database/sql/views.sql)

## MongoDB

- [Collections MongoDB](../../database/mongodb/collections.md)
- [Exemples de documents](../../database/mongodb/sample-data.json)
- [Creation collections MongoDB](../../database/mongodb/create_collections.js)
- [Seed MongoDB](../../database/mongodb/seed_mongodb.js)

## UML

- [Cas d'utilisation](../uml/use-case-diagram.drawio)
- [Diagramme de classes](../uml/class-diagram.drawio)
- [Sequence authentification](../uml/sequence-authentication.drawio)
- [Sequence consultation et commande](../uml/sequence-consultation-commande.drawio)
- [Sequence gestion commande employe](../uml/sequence-gestion-commande-employe.drawio)
- [Sequence gestion avis](../uml/sequence-gestion-avis.drawio)
- [Sequence dashboard administrateur MongoDB](../uml/sequence-dashboard-admin-mongodb.drawio)

## Documentation Notion

- [Page Notion importable](../notion/database-documentation.md)

## Notes de maintenance

Les fichiers `.drawio` sont les sources modifiables. Les fichiers `.png` sont des exports pour lecture rapide dans GitHub ou dans un dossier de rendu.

Le script [generate_database_docs.py](../../scripts/generate_database_docs.py) permet de regenerer les livrables a partir d'un modele centralise. Apres modification manuelle d'un diagramme dans diagrams.net, il faut reexporter le PNG correspondant.
