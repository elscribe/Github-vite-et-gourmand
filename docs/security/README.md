# Documentation securite et veille

Date de consolidation : 21 juillet 2026.

Ce document presente les mesures de securite mises en place dans l'application
Vite & Gourmand et la veille securite retenue pour justifier les choix devant
le jury.

## Objectif

L'application manipule des comptes clients, des commandes, des informations de
contact, des avis et des espaces internes employe/administrateur. Les mesures
de securite visent donc a :

- proteger les espaces prives ;
- limiter les acces selon les roles ;
- eviter les injections SQL ;
- proteger les formulaires sensibles ;
- ne jamais stocker de mots de passe en clair ;
- limiter l'exposition d'informations techniques en production ;
- documenter les limites restantes du MVP.

## Synthese des mesures appliquees

| Risque | Mesure appliquee | Preuve code / documentation |
|---|---|---|
| Acces non autorise | Middleware d'authentification sur les routes privees. | `AuthMiddleware`, routes `/commandes`, `/employe`, `/admin`. |
| Mauvais role | Middleware de role distinct pour employe et administrateur. | `RoleMiddleware`, groupes `$employeeAccess` et `$adminAccess`. |
| CSRF sur les formulaires POST | Token `_csrf_token` genere et verifie cote serveur. | `Security::csrfToken`, `CsrfMiddleware`, vues de formulaires. |
| Injection SQL | Requetes PDO preparees dans les modeles. | `prepare()` dans `UserModel`, `OrderModel`, `MenuModel`, etc. |
| Mot de passe en clair | Hash avec `password_hash`, verification avec `password_verify`. | `UserModel`, `AuthController`. |
| Session faible | Regeneration d'ID de session apres connexion. | `Session::regenerate`. |
| XSS | Echappement des sorties dynamiques. | `Security::escape`, `htmlspecialchars`. |
| Erreurs techniques exposees | Variables `.env` pour separer local/production. | `APP_DEBUG`, `APP_DISPLAY_ERRORS`, `APP_LOG_ERRORS`. |
| Secrets versionnes | `.env` exclu, `.env.example` documente les variables. | `.gitignore`, `.env.example`. |
| Email reel indisponible localement | Mode mail `log` en developpement, SMTP prevu en production. | `MailService`, `storage/logs/mail.log`, `MAIL_*`. |

## Controle des acces par role

Les routes publiques restent accessibles sans compte :

- `/`
- `/menus`
- `/menus/{id}`
- `/contact`
- `/connexion`
- `/inscription`
- `/mot-de-passe/oublie`
- `/mentions-legales`
- `/cgv`
- `/confidentialite`

Les routes client necessitent une session :

- `/mon-compte`
- `/commandes`
- `/commandes/creation`
- `/commandes/{id}`
- `/avis/creation/{orderId}`

Les routes employe necessitent le role `employe` ou `administrateur` :

- `/employe`
- `/employe/commandes`
- `/employe/avis`

Les routes administrateur necessitent le role `administrateur` :

- `/admin`
- `/admin/statistiques`
- `/admin/employes`
- `/admin/horaires`
- `/admin/menus`
- `/admin/plats`

Point important pour l'oral : les liens caches dans l'interface ne suffisent pas.
Le controle est effectue cote serveur par middleware. Un utilisateur qui tape
directement une URL interdite est bloque.

## Authentification et mots de passe

Les mots de passe ne sont jamais stockes en clair. A la creation ou a la
reinitialisation d'un compte, le mot de passe est transforme avec
`password_hash`. A la connexion, il est controle avec `password_verify`.

Regles documentees dans `.env.example` :

- `PASSWORD_MIN_LENGTH=10`
- `SESSION_HTTP_ONLY=true`
- `SESSION_SAME_SITE=Lax`
- `SESSION_SECURE=false` en local, a passer a `true` en production HTTPS.

En production, les valeurs attendues sont :

```text
APP_ENV=production
APP_DEBUG=false
APP_DISPLAY_ERRORS=false
SESSION_SECURE=true
APP_KEY=<valeur secrete unique>
```

## CSRF

Les formulaires sensibles en `POST` contiennent un champ cache `_csrf_token`.
Le middleware `CsrfMiddleware` compare le token envoye avec celui stocke en
session. En cas d'erreur, la requete est refusee.

Formulaires proteges :

- connexion ;
- inscription ;
- mot de passe oublie ;
- reinitialisation du mot de passe ;
- creation/modification/annulation de commande ;
- modification du profil ;
- moderation des avis ;
- gestion employe/admin ;
- contact.

## Donnees et base SQL

Les modeles utilisent PDO avec requetes preparees. Les parametres utilisateur ne
sont pas concatennes directement dans les requetes SQL.

Exemples de donnees protegees :

- compte utilisateur ;
- commande ;
- historique de statut ;
- avis ;
- message de contact ;
- token de reinitialisation.

## Email et limites du mode local

Le projet implemente les notifications email via un `MailService` centralise.
En local, `MAIL_MAILER=log` permet de verifier les messages sans serveur SMTP.

Emails concernes :

- bienvenue apres inscription ;
- lien de reinitialisation du mot de passe ;
- confirmation de commande ;
- invitation a deposer un avis ;
- rappel de retour de materiel ;
- creation de compte employe ;
- demande de contact.

Reserve a expliquer : l'envoi SMTP reel depend de l'hebergeur ou du prestataire
mail choisi au deploiement. La logique applicative est testable en local via les
logs.

## Accessibilite et securite des formulaires

Les formulaires principaux utilisent :

- des labels ;
- des messages d'erreur lisibles ;
- des champs requis lorsque necessaire ;
- une validation serveur ;
- une aide visuelle pour l'utilisateur.

Une recette accessibilite RGAA interne est documentee dans
`docs/accessibility/rgaa-audit-2026-07-22.md`. Elle confirme les bases sur un
echantillon representatif : langue, titres, labels, textes alternatifs,
captions de tableaux, focus, lien d'evitement et reduction de mouvement.

Reserve restante : la passe finale sur l'URL deployee doit encore verifier
clavier, contrastes, zoom/reflow et lecteur d'ecran.

## Veille securite

### Sources retenues

| Source | Interet pour le projet | Lien |
|---|---|---|
| OWASP Top 10 | Identifier les principaux risques web : controle d'acces, injection, erreurs de configuration, authentification. | <https://owasp.org/Top10/2021/> |
| OWASP Broken Access Control | Justifier la protection serveur par role. | <https://owasp.org/Top10/2021/A01_2021-Broken_Access_Control/> |
| ANSSI - Guide d'hygiene informatique | Rappeler les bonnes pratiques generales : comptes, mises a jour, sauvegardes, separation des droits. | <https://messervices.cyber.gouv.fr/guides/guide-dhygiene-informatique> |
| CNIL - Guide securite des donnees personnelles 2024 | Relier securite applicative et protection des donnees personnelles. | <https://www.cnil.fr/sites/cnil/files/2024-03/cnil_guide_securite_personnelle_2024.pdf> |
| CNIL - Mots de passe | Justifier une politique minimale de mot de passe et l'absence de stockage en clair. | <https://www.cnil.fr/fr/mots-de-passe-recommandations-pour-maitriser-sa-securite> |

### Apport concret au projet

| Sujet de veille | Decision appliquee |
|---|---|
| OWASP - controle d'acces | Les droits sont verifies par middleware cote serveur, pas seulement par le menu. |
| OWASP - injection | Les requetes utilisent PDO prepare. |
| OWASP - identification/authentification | Les mots de passe sont hashes et les sessions sont regenerees. |
| ANSSI - hygiene informatique | Les secrets sont separes dans `.env`, non versionnes. |
| CNIL - donnees personnelles | Les donnees collectees restent limitees au besoin : compte, contact, commande, livraison. |
| CNIL - mots de passe | Mot de passe minimal de 10 caracteres et stockage sous forme de hash. |

## Tests de securite a conserver comme preuves

| Test | Resultat attendu | Preuve a garder |
|---|---|---|
| Visiteur ouvre `/commandes` | Redirection vers `/connexion`. | Capture ou note de test. |
| Client ouvre `/admin` | Acces refuse ou 403. | Capture client bloque. |
| Client ouvre `/employe` | Acces refuse ou 403. | Capture client bloque. |
| Employe ouvre une route admin stricte | Acces refuse. | Capture employe bloque. |
| Formulaire POST sans token CSRF | Requete refusee. | Note technique ou capture erreur. |
| Creation compte | Mot de passe en base sous forme de hash. | Capture/commande SQL sans afficher le hash complet. |
| Connexion | Session creee et menu adapte au role. | Capture par role. |
| Mot de passe oublie | Token hash + expiration. | Log ou note de test. |

## Limites et ameliorations futures

| Limite | Justification | Evolution possible |
|---|---|---|
| Pas de MFA | Non demande dans le MVP ECF. | Ajouter une double authentification admin. |
| Email SMTP non finalise localement | Depend du deploiement. | Brancher SMTP ou service transactionnel. |
| Pas de tests automatises de securite | Perimetre ECF centre sur recette manuelle. | Ajouter tests integration et scans OWASP ZAP. |
| Audit RGAA expert non realise | Une recette interne est documentee, mais pas un audit exhaustif des 106 criteres. | Refaire clavier, contrastes, zoom/reflow et lecteur d'ecran sur l'URL deployee. |

## Phrase de presentation jury

La securite est appliquee cote serveur : les routes privees sont protegees par
authentification et par role, les formulaires POST utilisent un token CSRF, les
mots de passe sont hashes, les requetes SQL sont preparees avec PDO et les
secrets restent dans un fichier `.env` non versionne.
