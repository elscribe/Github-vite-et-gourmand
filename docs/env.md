# Fichiers d'environnement

Ce projet utilise des variables d'environnement pour separer la configuration locale du code source.

## Fichiers utilises

- `.env` : fichier local avec les vraies valeurs de developpement. Il est ignore par Git.
- `.env.example` : modele versionne sur GitHub pour documenter les variables necessaires.

Cette structure est volontairement simple pour l'ECF.

## Versioning

Le fichier `.env` ne doit jamais etre pousse sur GitHub, car il peut contenir des mots de passe, cles API ou acces base de donnees.

Le fichier `.env.example` est pousse sur GitHub, car il sert uniquement de modele et ne contient pas de vrais secrets.

Regle utilisee dans `.gitignore` :

```gitignore
.env
.env.*
!.env.example
```

## Mise en place locale

Copier le modele :

```bash
cp .env.example .env
```

Puis modifier les valeurs locales :

- `APP_KEY`
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`
- `NOSQL_DATABASE`
- variables email si un vrai SMTP est utilise.

## Production

En production, il est preferable d'injecter les variables directement via l'hebergeur ou l'outil CI/CD, par exemple Render, Railway, Fly.io ou GitHub Actions.

Si un fichier `.env` physique est obligatoire sur un serveur, son acces doit etre limite.

Example:

```bash
sudo chown root:www-data .env
sudo chmod 640 .env
```

Selon l'hebergement, des permissions plus strictes peuvent etre utilisees :

```bash
sudo chmod 440 .env
```

## Phrase pour le jury

J'utilise un fichier `.env` local pour externaliser les informations sensibles et dependantes de l'environnement, comme les acces aux bases de donnees. Ce fichier est ignore par Git. Un fichier `.env.example` est versionne pour documenter les variables necessaires au demarrage du projet sans exposer de secrets.
