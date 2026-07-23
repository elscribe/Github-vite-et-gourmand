# Veille securite - Vite & Gourmand

Date de consolidation : 21 juillet 2026.

Cette veille complete la documentation securite. Elle montre comment des sources
professionnelles ont ete utilisees pour guider les choix du projet.

## Sujet de veille

Comment securiser une application web PHP avec comptes utilisateurs, commandes
et espaces internes par role ?

## Sources consultees

| Source | Type | Lien |
|---|---|---|
| OWASP Top 10:2021 | Reference internationale securite web | <https://owasp.org/Top10/2021/> |
| OWASP A01 Broken Access Control | Risque principal lie aux droits d'acces | <https://owasp.org/Top10/2021/A01_2021-Broken_Access_Control/> |
| ANSSI Guide d'hygiene informatique | Guide francais de bonnes pratiques | <https://messervices.cyber.gouv.fr/guides/guide-dhygiene-informatique> |
| CNIL Guide securite des donnees personnelles 2024 | Guide RGPD/securite des donnees | <https://www.cnil.fr/sites/cnil/files/2024-03/cnil_guide_securite_personnelle_2024.pdf> |
| CNIL Mots de passe | Recommandations sur l'authentification | <https://www.cnil.fr/fr/mots-de-passe-recommandations-pour-maitriser-sa-securite> |

## Elements retenus

### OWASP Top 10

OWASP presente le Top 10 comme un document de sensibilisation aux risques les
plus critiques pour les applications web. Pour Vite & Gourmand, les points les
plus pertinents sont :

- controle d'acces ;
- injection ;
- defaillances cryptographiques ;
- authentification ;
- mauvaise configuration de securite.

Application au projet :

- les routes employe/admin sont protegees par middleware ;
- les requetes SQL sont preparees ;
- les mots de passe sont hashes ;
- les secrets sont places dans `.env`.

### ANSSI

Le guide d'hygiene informatique de l'ANSSI regroupe des mesures essentielles
pour renforcer la securite d'un systeme d'information. Pour ce projet ECF, les
mesures transposees sont :

- separation des droits ;
- non-versionnement des secrets ;
- limitation des acces internes ;
- journalisation des erreurs et emails en local ;
- preparation d'une configuration production distincte.

### CNIL

La CNIL insiste sur la protection des donnees personnelles et l'authentification
des utilisateurs. Le projet contient des donnees personnelles limitees :

- nom, prenom, email, telephone ;
- adresse et ville pour la livraison ;
- commandes ;
- avis ;
- messages de contact.

Application au projet :

- collecte limitee aux besoins fonctionnels ;
- mots de passe non stockes en clair ;
- droits d'acces separes ;
- page de confidentialite presente ;
- variables sensibles non versionnees.

## Synthese pour l'oral

J'ai utilise OWASP pour identifier les risques web prioritaires, notamment le
controle d'acces et l'injection. J'ai utilise l'ANSSI pour cadrer les bonnes
pratiques generales comme la separation des droits et la gestion des secrets. La
CNIL m'a servi a relier ces choix techniques a la protection des donnees
personnelles des clients.

## Limites identifiees

| Limite | Raison | Evolution future |
|---|---|---|
| Pas de double authentification | Non imposee dans le perimetre ECF. | Ajouter MFA pour les administrateurs. |
| Pas de scan OWASP ZAP automatise | Recette manuelle privilegiee pour l'ECF. | Ajouter un scan avant mise en production. |
| SMTP reel non branche | Le MVP conserve les preuves via le mode log. | Brancher SMTP ou un service transactionnel. |
| Audit RGAA expert non realise | L'ECF documente une recette interne, pas une certification exhaustive. | Refaire clavier, contrastes, zoom/reflow et lecteur d'ecran sur l'URL deployee. |
