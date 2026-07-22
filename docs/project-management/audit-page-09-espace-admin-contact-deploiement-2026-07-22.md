# Audit page 9 - Espace administrateur, contact, deploiement

Date de l'audit : 22 juillet 2026.

Source enonce : `docs/project-management/audit-page-09-assets/source-enonce-page-09.txt`
et capture `docs/project-management/audit-page-09-assets/source-enonce-page-09-09.png`.

Verdict global : **conforme cote application locale apres corrections**, avec
une reserve transversale bloquante avant rendu final : deploiement public
effectif. L'accessibilite possede maintenant une trace d'audit interne, mais
la passe finale clavier/contrastes/lecteur d'ecran reste a refaire sur l'URL
deployee.

## Corrections appliquees pendant l'audit

| Fichier | Correction | Pourquoi |
|---|---|---|
| `app/Controllers/AdminController.php` | Creation employe avec mot de passe fourni par l'administrateur, validation serveur et notification email sans mot de passe. | L'enonce demande email + mot de passe, mais interdit d'envoyer le mot de passe dans l'email. |
| `app/Views/admin/employees.php` | Ajout des champs `password` et `password_confirmation`; mention que le mot de passe n'est pas transmis par email. | Rendre l'action claire pour un administrateur et eviter un flux implicite par reset. |
| `app/Models/UserModel.php` | Parametre renomme en `$password` pour la creation employe. | Clarifier le code apres abandon du mot de passe temporaire genere. |
| `app/Views/admin/statistics.php` | Ajout du graphique "Commandes par menu" et affichage de la source des statistiques. | L'enonce demande une comparaison graphique du nombre de commandes et une preuve NoSQL. |

## Controle des exigences

| Exigence page 9 | Etat | Preuves et tests |
|---|---|---|
| L'administrateur se connecte et accede a son espace. | Conforme | Routes admin protegees par `RoleMiddleware(['administrateur'])` dans `config/routes.php`. Test HTTP : `GET /connexion=200`, login Jose `=200`, `GET /admin=200`. |
| L'administrateur cree un compte employe avec email et mot de passe. | Conforme apres correction | Formulaire `admin/employees.php` avec email, mot de passe et confirmation. Controleur `AdminController::storeEmployee` valide email, mot de passe fort et confirmation. Test : utilisateur temporaire cree en base avec `role=Employee`, `actif=1`, `password_ok=true`. |
| L'employe recoit un email de notification sans mot de passe. | Conforme | `AdminController::storeEmployee` envoie une notification via `MailService` et indique de contacter l'administrateur. Test mail log : email employe trouve 1 fois, mot de passe teste trouve 0 fois. |
| Rendre inutilisable un compte employe. | Conforme | Route `POST /admin/employes/{id}/activation`, vue de desactivation/reactivation et `UserModel::setEmployeeActive`. Test : employe temporaire passe a `actif=0`. |
| Compte Jose cree. | Conforme | Seed SQL : `admin.jose@vitegourmand.test`, role `Administrator`, actif. Test login admin utilise ce compte avec succes. |
| Impossible de creer un administrateur depuis l'application. | Conforme | `AuthController::storeRegister` ne lit aucun champ role et appelle `UserModel::createCustomer`. Test d'injection POST public avec `role=Administrator` et `id_role=1` : compte cree avec `role=Customer`, `id_role=3`. |
| L'administrateur peut faire ce qu'un employe peut faire. | Conforme | Routes admin reutilisent les controleurs employe pour commandes et avis : `/admin/commandes`, `/admin/avis`. Test HTTP : `/employe=200` avec admin, `/admin/commandes=200`, `/admin/avis=200`. |
| Visualiser le nombre de commandes par menu et comparer via graphique. | Conforme apres correction | Ajout du bloc "Commandes par menu" dans `app/Views/admin/statistics.php`. Test HTTP filtre : `/admin/statistiques?menu=6&start=2026-01-01&end=2026-12-31=200`, marqueur "Commandes par menu" trouve. |
| Donnees statistiques issues d'une base non relationnelle. | Conforme localement | `StatisticsModel` execute `mongosh` sur `menu_monthly_statistics`, puis affiche `Agregats MongoDB lus depuis la base vite_gourmand`. Test local : `mongosh vite_gourmand` retourne 72 documents dans `menu_monthly_statistics`, page statistiques affiche la source MongoDB. |
| Chiffre d'affaires par menu avec filtres menu et duree. | Conforme | Filtres `menu`, `start`, `end` dans `admin/statistics.php`; aggregation Mongo filtre `menuId` et `month`. Test HTTP filtre `menu=6`, dates 2026 : `200`. |
| Contact accessible depuis le menu applicatif. | Conforme | Navigation publique contient le lien `/contact` dans `app/Views/layouts/main.php`; route `GET /contact`. Test HTTP : `/contact=200`. |
| Formulaire contact avec titre, description et email. | Conforme | Vue `contact/create.php` contient `name="titre"`, `name="email"`, `name="description"` avec labels. Test HTML : 3 marqueurs requis trouves. |
| Demande de contact envoyee par email a l'entreprise. | Conforme | `ContactController::store` persiste la demande puis envoie a `MAIL_CONTACT_TO` ou adresse par defaut. Test : POST contact `=200`, ligne `contact_messages` creee, mail log contient le sujet une fois. |
| Deploiement de l'application en ligne et fonctionnelle. | Reserve bloquante hors code | `docs/deployment/README.md` existe, mais l'URL publique reste "A renseigner apres deploiement". Il faudra deployer, renseigner le README/Notion/copie Studi et tester l'URL finale. |
| Accessibilite conforme RGAA. | Valide avec reserve | Audit interne documente dans `docs/accessibility/rgaa-audit-2026-07-22.md` : 16 pages locales testees, `lang`, titres, H1, labels, alt, captions, skip-link, focus et reduced-motion OK. | Ne pas annoncer une conformite totale ; refaire clavier, contrastes, zoom/reflow et lecteur d'ecran sur l'URL deployee. |

## Tests executes

Serveur local utilise :

```bash
APP_URL=http://127.0.0.1:8001 MAIL_MAILER=log php -S 127.0.0.1:8001 -t public
```

Resultats HTTP et base :

```text
login_get=200
login_post_followed=200
admin=200
admin_can_open_employee_dashboard=200
admin_orders=200
admin_reviews=200
stats_filtered=200
employee_page=200
employee_create_followed=200
employee_db={"id":24,"role":"Employee","actif":1,"password_ok":true}
employee_toggle_followed=200
employee_active_after_toggle=0
contact_get=200
contact_post_followed=200
contact_db={"id_contact_message":17,"titre":"AUDIT_PAGE9_RERUN_CONTACT_1784689366","email":"contact-page9-rerun-1784689366@example.test","traite":0}
mail_employee_mentions=1
mail_contact_mentions=1
mail_password_mentions=0
stats_mongodb_source_mentions=1
stats_orders_graph_mentions=1
contact_required_fields_markers=3
cleanup={"deleted_users":1,"deleted_contacts":1}
```

Test creation admin impossible :

```text
register_get=200
register_post_followed=200
register_role_after_injected_admin={"id":25,"role":"Customer","id_role":3}
cleanup={"deleted_users":1}
```

Controle MongoDB :

```text
which mongosh=/opt/homebrew/bin/mongosh
db.menu_monthly_statistics.countDocuments()=72
```

Controle qualite :

```text
composer check=OK
git diff --check=OK
```

## Conclusion page 9

La partie admin, statistiques NoSQL, contact et accessibilite de base est
maintenant defendable devant le jury sur l'application locale. Pour dire que la
page 9 est entierement OK, il reste surtout le deploiement public et une passe
finale accessibilite sur l'URL livree.
