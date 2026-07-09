# Dossier Config

Le dossier `config/` regroupe les fichiers de configuration PHP : application,
base de donnees, session et routes.

Separer la configuration des controleurs rend l'application plus facile a lire,
a tester et a expliquer pendant la soutenance. Les valeurs sensibles doivent
venir du fichier `.env` local, jamais du depot Git.
