FROM mongo:7

COPY database/mongodb/create_collections.js /docker-entrypoint-initdb.d/01-create_collections.js
COPY database/mongodb/seed_mongodb.js /docker-entrypoint-initdb.d/02-seed_mongodb.js
