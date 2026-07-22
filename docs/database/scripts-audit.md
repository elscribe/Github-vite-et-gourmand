# Audit des scripts SQL et MongoDB

Date de controle : 2026-07-01

## Perimetre audite

- `database/sql/create_database.sql`
- `database/sql/seed_database.sql`
- `database/mongodb/create_collections.js`
- `database/mongodb/seed_mongodb.js`

## Conclusion

Statut : conforme pour une execution ECF en environnement local ou de demonstration.

Les scripts ont ete executes sur des instances temporaires MariaDB et MongoDB. Les bases temporaires ont ete supprimees apres validation. Aucun script n'a ete execute contre une base de production.

## Ordre d'execution recommande

```bash
mariadb -u root -p < database/sql/create_database.sql
mariadb -u root -p < database/sql/seed_database.sql
mongosh database/mongodb/create_collections.js
mongosh database/mongodb/seed_mongodb.js
```

Attention : `create_database.sql` commence par `DROP DATABASE IF EXISTS vite_gourmand`. `create_collections.js` supprime et recree uniquement les collections MongoDB statistiques. Ces scripts sont donc prevus pour initialiser ou reinitialiser une base de demonstration.

## Controle SQL

### Syntaxe et compatibilite

- Compatible MariaDB 11 / MySQL 8.
- Moteur `InnoDB` precise sur toutes les tables.
- Encodage `utf8mb4` et collation `utf8mb4_unicode_ci`.
- `CHECK` utilise uniquement des expressions compatibles MariaDB 11 et MySQL 8.
- `BOOLEAN` conserve comme alias SQL standard accepte par MariaDB/MySQL.

### Ordre des tables

Statut : conforme.

Les tables parentes sont creees avant les tables dependantes :

- `roles` avant `utilisateurs`.
- `regimes` et `themes` avant `menus`.
- `menus` et `plats` avant `menu_plats`.
- `plats` et `allergenes` avant `plat_allergenes`.
- `utilisateurs` et `menus` avant `commandes`.
- `commandes` avant `commande_statuts` et `avis`.
- `utilisateurs` avant `password_resets`.

### Cles, contraintes et index

Statut : conforme.

- Toutes les cles primaires sont presentes.
- Les cles primaires entieres utilisent `AUTO_INCREMENT`.
- Toutes les cles etrangeres ont un index utilisable.
- Les contraintes `UNIQUE` attendues sont presentes : emails, libelles, avis par commande, token reset, jour d'ouverture.
- Les contraintes `CHECK` protegent les prix, distances, notes, statuts, types de plats et jours de semaine.
- Aucun index exactement duplique n'a ete detecte.

Index ajoutes/corriges pendant l'audit :

- Catalogue menus : `idx_menus_catalogue_filters`, `idx_menus_nombre_personnes_minimum`.
- Images menu : `idx_menu_images_id_menu_position`.
- Commandes : `idx_commandes_statut_actuel`, `idx_commandes_date_prestation`.
- Historique statuts : `idx_commande_statuts_id_commande_created_at`.
- Avis : `idx_avis_statut`.
- Contacts : `idx_contact_messages_traite`.
- Password reset : `idx_password_resets_expires_at`, `idx_password_resets_used_at`.

### Validation du seed SQL

Execution reelle sur MariaDB temporaire : OK.

Resultats controles :

- 16 tables creees.
- 3 roles.
- 13 utilisateurs : 2 administrateurs, 3 employes, 8 clients.
- 6 menus.
- 24 plats.
- 14 allergenes standards.
- 20 commandes.
- 20 commandes avec historique de statut.
- 0 avis rattache a une commande non terminee.
- 0 incoherence entre `commandes.statut_actuel` et le dernier statut de `commande_statuts`.
- 0 index exactement duplique.

## Controle MongoDB

### Collections et validateurs

Statut : conforme.

Collections creees :

- `menu_statistics`
- `monthly_statistics`
- `menu_monthly_statistics`
- `dashboard_statistics`

Chaque collection possede un validateur `$jsonSchema`. Les champs numeriques acceptent les types BSON produits naturellement par MongoDB Playground : `int`, `long`, `double` et `decimal` selon le champ.

### Index MongoDB

Statut : conforme.

- `menu_statistics.menuId` unique.
- `monthly_statistics.month` unique.
- `menu_monthly_statistics.menuId` + `month` unique.
- Index de lecture sur `revenue`, `updatedAt`, `generatedAt` et `topMenu`.

### Validation du seed MongoDB

Execution reelle sur MongoDB temporaire : OK.

Resultats controles :

- `menu_statistics` : 6 documents.
- `monthly_statistics` : 12 documents.
- `menu_monthly_statistics` : 72 documents.
- `dashboard_statistics` : 12 documents.
- Total : 102 documents.
- Somme des commandes par menu = somme des commandes mensuelles : 191.
- Somme des revenus par menu = somme des revenus mensuels : 184981.79.
- Les snapshots `dashboard_statistics` correspondent aux valeurs mensuelles.
- `menu_monthly_statistics` sert au dashboard code pour filtrer les statistiques
  par menu et par periode via `StatisticsModel`.

## Points de vigilance pour l'ECF

- La base SQL reste la source de verite metier.
- MongoDB contient uniquement des agregats de lecture pour le tableau de bord administrateur.
- Les scripts d'initialisation sont destructifs par conception. Ils sont adaptes a une demonstration ECF, pas a une execution directe sur une base de production contenant des donnees a conserver.
- Les libelles de roles sont en anglais (`Administrator`, `Employee`, `Customer`) car c'etait demande dans le seed. Si le code applicatif teste des libelles francais, il faudra soit utiliser les IDs de roles, soit adapter les libelles.

## Decision finale

Les scripts peuvent etre executes dans l'ordre recommande pour initialiser l'environnement de demonstration du projet Vite & Gourmand.
