# Audit page 8 - Espace employe

Date : 22 juillet 2026.

Source auditee : page 8 de l'enonce, rendue dans
`docs/project-management/audit-page-08-assets/source-enonce-page-08-08.png`
et retranscrite dans
`docs/project-management/audit-page-08-assets/source-enonce-page-08.txt`.

Objectif : verifier que l'espace employe couvre les demandes obligatoires de
la page 8 avant push sur `main`.

## Synthese

Verdict : conforme apres corrections, avec un choix de perimetre a expliquer.

L'espace employe couvre les commandes, les filtres, les statuts, les
annulations justifiees, les notifications client et la moderation des avis.
La publication des avis sur l'accueil est maintenant reliee aux avis `valide`
en base de donnees.

Point a assumer devant le jury : l'enonce indique que l'employe peut modifier
ou supprimer menus, plats et horaires. Dans le projet, ces actions sont
volontairement reservees a l'administrateur, car elles modifient le catalogue
public, les prix, la composition des menus et les horaires affiches aux
clients. Ce choix est documente dans le `README.md` et
`database/business-rules.md`.

## Exigences et etat

| Exigence page 8 | Verification | Statut |
|---|---|---|
| L'employe accede a son espace apres connexion | Test HTTP : connexion `lucas.employee@vitegourmand.test`, `GET /employe` retourne `200`. | Conforme |
| L'employe peut modifier/supprimer menus, plats, horaires | Code : routes `/admin/menus`, `/admin/plats`, `/admin/horaires` reservees au role `administrateur`. Tests HTTP employe : `403` sur les trois routes. | Ecart volontaire documente |
| L'employe ne peut annuler qu'apres contact GSM ou email | Vue `employee/orders.php` : champs `mode_contact_modification` et `motif_annulation`. Mode limite a `email` ou `gsm`. | Conforme |
| Le motif et le mode de contact sont conserves | Test SQL commande temporaire #48 : statut `annulee`, mode `email`, motif `AUDIT_PAGE8 client contacte par email`. | Conforme |
| Filtrer les commandes par statut | Test HTTP `GET /employe/commandes?status=en_attente` retourne `200`. | Conforme |
| Filtrer les commandes par client | Bug detecte puis corrige dans `OrderModel::findAll()` : placeholders PDO separes pour email, nom et prenom. Test `customer=Claire` retourne `200` et affiche Claire Martin. | Conforme apres correction |
| Mettre a jour les statuts commandes | Test commande temporaire #47 : `acceptee`, `en_preparation`, `en_cours_de_livraison`, `livre`, `en_attente_retour_materiel`, `terminee`. Historique SQL : 6 transitions ajoutees. | Conforme |
| Envoyer un email au statut retour materiel | Correction verifiee : email journalise avec sujet retour materiel, 10 jours ouvres, 600 EUR et lien `/cgv`. | Conforme apres correction |
| Envoyer un email de demande d'avis a la commande terminee | Test mail log : `SUBJECT: Votre avis nous interesse`. | Conforme |
| Valider ou refuser les avis | Test avis temporaire #17 : moderation par employe, statut `valide`, `moderated_by = 3`, `moderated_at` renseigne. | Conforme |
| Afficher uniquement les avis valides sur l'accueil | Correction : `HomeController` transmet `ReviewModel::findValidated(3)` a la vue. Test HTTP : avis valide temporaire retrouve sur `/`, puis retest final avec avis valides du seed. Les avis decoratifs ont ete retires de la source. | Conforme apres correction |

## Corrections realisees

- `app/Models/OrderModel.php` : correction du filtre client avec trois
  placeholders PDO distincts (`customer_email`, `customer_nom`,
  `customer_prenom`) pour rester compatible avec les requetes preparees
  strictes.
- `app/Controllers/OrderController.php` : verification de la notification
  retour materiel sur le statut `en_attente_retour_materiel`, avec email
  mentionnant 10 jours ouvres, 600 EUR et CGV.
- `app/Controllers/HomeController.php` : chargement des avis valides depuis
  `ReviewModel`.
- `app/Views/home/index.php` : remplacement des avis decoratifs par des avis
  issus de la base, avec etat vide si aucun avis n'est valide.
- `README.md` et `database/business-rules.md` : documentation du choix de
  perimetre admin/employe.
- `docs/manual/mvp-test-checklist.md` : mise a jour des tests page 8.

## Tests executes

```text
composer check
php -l app/Models/OrderModel.php
php -l app/Controllers/HomeController.php
php -l app/Views/home/index.php
git diff --check
```

Tests HTTP/SQL manuels automatises avec `curl` et donnees temporaires :

```text
PASS connexion employe redirige: 302
PASS GET /employe: 200
PASS GET /employe/commandes: 200
PASS GET /employe/avis: 200
PASS employe bloque /admin/menus: 403
PASS employe bloque /admin/plats: 403
PASS employe bloque /admin/horaires: 403
PASS filtre statut HTTP: 200
PASS filtre client HTTP: 200
PASS POST statut acceptee: 302
PASS POST statut en_preparation: 302
PASS POST statut en_cours_de_livraison: 302
PASS POST statut livre: 302
PASS POST statut en_attente_retour_materiel: 302
PASS POST statut terminee: 302
PASS POST annulation employe: 302
PASS POST moderation avis valide: 302
PASS GET accueil apres moderation: 200
PASS SQL statut final: terminee
PASS SQL historique statuts: 6 transitions
PASS SQL annulation: annulee / email / motif conserve
PASS SQL moderation: valide par l'employe #3
PASS email retour materiel : sujet, 10 jours ouvres, 600 EUR, lien CGV
PASS email avis : sujet present
PASS retest final #50/#51 : avis valide visible accueil et emails conformes
PASS retest final accueil : avis valides du seed visibles, aucun ancien avis decoratif dans la source
```

Donnees temporaires utilisees puis supprimees :

```text
commandes #47, #48, #49
avis #17
retest final : commandes #50, #51 et avis #18
verification finale : commandes temporaires restantes = 0
```

## Risques residuels

- Le choix de reserver menus, plats et horaires a l'administrateur est plus
  securisant, mais il doit etre explique clairement car il differe du texte
  strict de la page 8.
- Le SMTP reel n'est pas necessaire pour prouver le parcours local si Mailpit
  ou `MAIL_MAILER=log` est presente pendant la demonstration. En production,
  un SMTP reel reste a configurer.
