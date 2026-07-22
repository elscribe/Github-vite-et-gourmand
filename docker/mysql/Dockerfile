FROM mysql:8.4

COPY database/sql/create_database.sql /docker-entrypoint-initdb.d/01-create_database.sql
COPY database/sql/seed_database.sql /docker-entrypoint-initdb.d/02-seed_database.sql
