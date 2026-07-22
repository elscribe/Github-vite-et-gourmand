# Decision de perimetre - role employe et gestion de l'offre

Date : 2026-07-22
Statut : decision projet documentee

## Decision

Dans l'application Vite & Gourmand, le role `employe` ne gere pas les menus, les
plats, les allergenes, les horaires ni la selection des menus publics.

Ces actions restent reservees au role `administrateur`. Le role `employe` couvre
les operations quotidiennes : consultation et traitement des commandes,
changement de statut, annulation motivee apres contact client, et moderation des
avis.

## Justification

La gestion des menus, des plats et des horaires modifie directement l'offre
commerciale visible par les clients : prix, stock, minimum de personnes,
composition, allergenes, conditions de vente et disponibilite publique. Une
erreur sur ces donnees peut avoir un impact client immediat.

Le projet applique donc une separation des responsabilites :

- l'employe gere l'activite operationnelle deja validee par l'entreprise ;
- l'administrateur pilote les donnees commerciales et structurelles ;
- l'administrateur conserve aussi l'acces aux fonctions employe via les routes
  admin de commandes et d'avis.

## Trace dans le code

- Routes employe autorisees : `/employe`, `/employe/commandes`, `/employe/avis`.
- Routes admin reservees : `/admin/menus`, `/admin/plats`, `/admin/horaires`,
  `/admin/employes`, `/admin/statistiques`.
- Middleware : `RoleMiddleware(['administrateur'])` protege les routes de
  gestion de l'offre ; `RoleMiddleware(['employe', 'administrateur'])` protege
  les fonctions operationnelles partagees.

## Impact sur l'enonce

L'enonce et le backlog Notion mentionnent une gestion des menus, plats et
horaires depuis les espaces Administrateur et Employe. Cette implementation est
donc un choix de securite et d'organisation a presenter explicitement au jury.

Si une conformite litterale est exigee, il faudra ajouter des routes staff
partagees pour menus, plats et horaires. Si la separation des responsabilites
est acceptee, la documentation ci-dessus justifie pourquoi l'absence de ces
ecrans dans l'espace employe est volontaire.

## Argumentaire pour l'oral

Position a presenter : l'application est volontairement plus restrictive que la
lecture litterale de l'enonce. L'employe peut traiter les commandes et moderer
les avis, car ce sont des actions operationnelles quotidiennes. La modification
des menus, des plats, des allergenes, des prix, des stocks et des horaires
impacte directement l'offre commerciale ; elle est donc reservee a
l'administrateur dans la version livree.

Si le jury demande une ouverture au role employe, l'evolution est limitee :
il ne s'agit pas de reconstruire le module. Le module `/admin/menus` existe
deja et peut etre expose via une route partagee, par exemple `/employe/menus`
ou `/staff/menus`, protegee par le meme middleware de role que les espaces
operationnels partages. C'est le meme principe que pour les commandes et les
avis, ou l'administrateur peut superviser les actions accessibles a l'employe.

Phrase courte pour l'oral :

> J'ai choisi de reserver la gestion de l'offre commerciale a
> l'administrateur pour limiter les erreurs sur les prix, stocks, allergenes et
> horaires. Techniquement, le module existe deja ; si l'entreprise souhaite le
> deleguer aux employes, il suffit d'exposer la meme page via une route employe
> ou staff avec le middleware de role adapte.
