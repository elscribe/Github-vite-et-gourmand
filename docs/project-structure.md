# Structure Du Projet MVC

Ce document explique le squelette cree pour le projet PHP 8.3 Vite & Gourmand.

```text
public/
    index.php              Controleur frontal appele par le navigateur.
    assets/                CSS, JavaScript et images publics.

app/
    Controllers/           Gestion des requetes dans l'architecture MVC.
    Models/                Classes d'acces aux donnees SQL.
    Views/                 Templates HTML rendus par les controleurs.
    Core/                  Classes techniques partagees.
    Services/              Services reutilisables.
    Middlewares/           Middlewares applicatifs.

config/                    Fichiers de configuration PHP.
database/sql/              Scripts et notes MySQL ou MariaDB.
database/mongodb/          Zone MongoDB preparee, non connectee au PHP.
docs/                      Documentation projet et technique.
storage/                   Logs et fichiers generes localement.
tests/                     Futurs tests automatises.
```

L'idee importante est la separation des responsabilites : `public/index.php`
demarre la requete, le `Router` choisit un controleur, le controleur coordonne
les modeles et services, puis charge une vue.

Les routes principales sont centralisees dans `config/routes.php`. Les
controleurs doivent rester minces : validation et orchestration cote
controleur, acces aux donnees cote modele, logique reutilisable cote service.
