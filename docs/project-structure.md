# Structure Du Projet MVC

Ce document explique le squelette cree pour le projet PHP 8.3 Vite & Gourmand.

```text
public/
    index.php              Controleur frontal appele par le navigateur.
    assets/                CSS, JavaScript et images publics.

app/
    Controllers/           Gestion des requetes dans l'architecture MVC.
    Models/                Futures classes d'acces aux donnees.
    Views/                 Templates HTML rendus par les controleurs.
    Core/                  Classes techniques partagees.
    Services/              Futurs services metier reutilisables.
    Middlewares/           Futurs middlewares applicatifs.

config/                    Fichiers de configuration PHP.
database/sql/              Scripts et notes MySQL ou MariaDB.
database/mongodb/          Zone MongoDB preparee, non connectee au PHP.
docs/                      Documentation projet et technique.
storage/                   Logs et fichiers generes localement.
tests/                     Futurs tests automatises.
```

L'idee importante est la separation des responsabilites : `public/index.php`
demarre la requete, le `Router` choisit un controleur, le controleur charge une
vue, puis les modeles et services seront ajoutes plus tard uniquement lorsque les
fonctionnalites metier seront developpees.

Sprint 0 reserve aussi les routes des futures sections dans `config/routes.php`.
Les placeholders retournent `501 Not Implemented` tant que les fonctionnalites
ne sont pas codees.
