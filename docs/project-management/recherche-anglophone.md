# Recherche anglophone

Date de consolidation : 21 juillet 2026.

## Situation de travail

Pendant le developpement de Vite & Gourmand, il fallait securiser les espaces
prives : client, employe et administrateur. Le risque principal etait qu'un
utilisateur connecte puisse acceder a une route qui ne correspond pas a son
role, par exemple un client qui tape directement `/admin` dans la barre
d'adresse.

La recherche anglophone a donc porte sur le controle d'acces dans les
applications web.

## Source retenue

Source : OWASP Top 10:2021 - A01 Broken Access Control
Lien : <https://owasp.org/Top10/2021/A01_2021-Broken_Access_Control/>

OWASP est une reference internationale utilisee pour identifier les principaux
risques de securite web.

## Extrait anglais court

> "Broken access control" is the top category in the OWASP Top 10:2021.

## Traduction francaise

Le controle d'acces defectueux est la categorie la plus critique du classement
OWASP Top 10:2021.

## Apport au projet

Cette recherche a confirme que le controle des droits ne devait pas etre limite
a l'affichage de la navigation. Cacher un lien dans le menu ne suffit pas :
l'utilisateur peut toujours saisir une URL directement.

Decision appliquee dans Vite & Gourmand :

- ajout d'un `AuthMiddleware` pour verifier qu'un utilisateur est connecte ;
- ajout d'un `RoleMiddleware` pour verifier le role attendu ;
- separation des routes client, employe et administrateur ;
- acces employe autorise aux administrateurs car l'administrateur doit pouvoir
  superviser les actions employe ;
- routes admin strictement reservees au role `administrateur`.

## Exemple concret

| Test | Resultat attendu |
|---|---|
| Visiteur non connecte ouvre `/commandes` | Redirection vers `/connexion`. |
| Client connecte ouvre `/admin` | Acces refuse. |
| Client connecte ouvre `/employe` | Acces refuse. |
| Employe connecte ouvre une route admin stricte | Acces refuse. |
| Administrateur ouvre `/admin` | Acces autorise. |

## Conclusion

La recherche anglophone a permis de transformer une bonne pratique generale en
regle concrete de developpement : chaque route sensible est protegee cote
serveur, et pas seulement dans l'interface utilisateur. Cette decision limite le
risque d'elevation de privileges et renforce la separation des roles attendue
par le cahier des charges.
