# SQL legacy

Ce dossier contient l'ancien decoupage SQL :

- `create.sql`
- `seed.sql`
- `indexes.sql`
- `views.sql`

Ces fichiers sont conserves uniquement comme archive de travail. Pour l'ECF et pour initialiser la base de demonstration, utiliser les scripts situes dans le dossier parent :

```bash
mariadb -u root -p < database/sql/create_database.sql
mariadb -u root -p < database/sql/seed_database.sql
```

Ne pas melanger les scripts `legacy` avec `create_database.sql` et `seed_database.sql`.
