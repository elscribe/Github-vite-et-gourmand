# Rapport de recette finale - Vite & Gourmand

Date de recette : 21 juillet 2026.
Environnement teste : application locale sur `http://127.0.0.1:8022`.
Branche locale : `feature/back-office`.

## Verdict

La recette finale locale valide les parcours principaux du MVP : visiteur,
client, employe, administrateur, securite par role, responsive de base,
emails en mode log local, statistiques admin et preuves en base.

Le projet reste a verifier une seconde fois apres deploiement, car cette
recette porte sur l'environnement local et non sur une URL publique.

## Comptes utilises

| Role | Email |
|---|---|
| Client | `claire.martin@example.test` |
| Employe | `lucas.employee@vitegourmand.test` |
| Administrateur | `admin.jose@vitegourmand.test` |

Les mots de passe de demonstration sont ceux documentes dans le README du
projet. Ils ne sont pas recopies dans ce rapport de preuve.

## Parcours visiteur

| Test | Resultat | Preuve |
|---|---|---|
| Accueil desktop | Valide | `captures/01-visiteur-accueil-desktop.jpg` |
| Accueil mobile | Valide | `captures/02-visiteur-accueil-mobile.jpg` |
| Liste des menus | Valide | `captures/03-visiteur-menus-desktop.jpg` |
| Filtre rapide disponible | Valide | `captures/04-visiteur-menus-filtre-disponible.jpg` |
| Overlay de filtres | Valide | `captures/05-visiteur-menus-overlay-filtres.jpg` |
| Liste menus mobile | Valide | `captures/06-visiteur-menus-mobile.jpg` |
| Detail menu | Valide | `captures/07-visiteur-detail-menu-desktop.jpg` |
| Formulaire contact | Valide | `captures/08-visiteur-contact-formulaire.jpg` |
| Envoi contact | Valide | `captures/09-visiteur-contact-succes.jpg` |

Donnee creee : message contact `Test recette finale`, id `15`.

## Parcours client

| Test | Resultat | Preuve |
|---|---|---|
| Connexion client et compte | Valide | `captures/10-client-mon-compte.jpg` |
| Liste des commandes avant test | Valide | `captures/11-client-commandes-liste-avant.jpg` |
| Formulaire creation commande | Valide | `captures/12-client-creation-commande-vide.jpg` |
| Calcul prix commande | Valide | `captures/13-client-creation-commande-recap-prix.jpg` |
| Detail commande creee | Valide | `captures/14-client-commande-detail-creee.jpg` |
| Modification commande en attente | Valide | `captures/15-client-commande-modification-formulaire.jpg` |
| Detail commande modifiee | Valide | `captures/16-client-commande-modifiee-detail.jpg` |
| Liste des commandes apres test | Valide | `captures/17-client-commandes-liste-apres.jpg` |

Commande de preuve : `#42`.

Calcul initial observe :

```text
Menu Cocktail Bordelais
15 personnes
Pessac, 10 km hors Bordeaux
Menu 330,00 EUR - remise 33,00 EUR - livraison 10,90 EUR - total estime 307,90 EUR
```

Modification observee :

```text
16 personnes
8 km hors Bordeaux
Total final : 326,52 EUR
```

## Parcours employe

| Test | Resultat | Preuve |
|---|---|---|
| Dashboard employe | Valide | `captures/18-employe-dashboard.jpg` |
| Liste commandes employe | Valide | `captures/19-employe-commandes-liste.jpg` |
| Filtre par ID commande `#42` | Valide | `captures/20-employe-commandes-filtre-id-commande.jpg` |
| Panneau gerer commande | Valide | `captures/21-employe-commande-gerer-ouverte.jpg` |
| Formulaire statut acceptee | Valide | `captures/22-employe-statut-acceptee-formulaire.jpg` |
| Statut mis a jour | Valide | `captures/23-employe-statut-mis-a-jour.jpg` |
| Page moderation avis | Valide | `captures/24-employe-avis-moderation.jpg` |
| Avis approuve | Valide | `captures/25-employe-avis-approuve.jpg` |

Action employee realisee : la commande `#42` est passee de `en_attente` a
`acceptee`.

Avis de preuve : avis `#11`, statut final `valide`.

## Parcours administrateur

| Test | Resultat | Preuve |
|---|---|---|
| Dashboard administrateur | Valide | `captures/26-admin-dashboard.jpg` |
| Statistiques globales | Valide | `captures/27-admin-statistiques.jpg` |
| Statistiques filtrees par menu/periode | Valide | `captures/28-admin-statistiques-filtrees-menu.jpg` |
| Liste employes avant creation | Valide | `captures/29-admin-employes-avant.jpg` |
| Formulaire creation employe | Valide | `captures/30-admin-employe-creation-formulaire.jpg` |
| Employe cree et notification log | Valide | `captures/31-admin-employe-cree.jpg` |
| Employe desactive | Valide | `captures/32-admin-employe-desactive.jpg` |
| Horaires admin | Valide | `captures/33-admin-horaires.jpg` |
| Menus, plats et composition | Valide | `captures/34-admin-menus-plats.jpg` |
| Commande `#42` visible cote admin | Valide | `captures/35-admin-commandes-filtre-commande-42.jpg` |

Employe de preuve :

```text
recette.employe.1784594664578@example.test
id utilisateur : 20
statut final : desactive
```

## Securite

| Test | Resultat | Preuve |
|---|---|---|
| Visiteur tente `/admin` | Redirection vers `/connexion` | `captures/36-securite-visiteur-admin-redirige-connexion.jpg` |
| Client tente `/admin` | Acces refuse | `captures/37-securite-client-admin-403.jpg` |
| Employe tente `/admin/statistiques` | Acces refuse | `captures/38-securite-employe-statistiques-admin-403.jpg` |
| CSRF sur connexion | 1 token detecte | Verification technique |
| CSRF sur contact | 1 token detecte | Verification technique |
| CSRF sur creation commande | 1 token detecte | Verification technique |
| CSRF sur admin employes | 5 tokens detectes | Verification technique |
| Mot de passe admin | Hash bcrypt, longueur 60 | Verification technique |

Point UX a noter : la page `403` affiche un message tres simple `Acces
refuse.` sans layout applicatif. Ce n'est pas bloquant pour la securite, mais
peut etre presente comme une amelioration de finition.

## Responsive

| Test | Resultat | Preuve |
|---|---|---|
| Connexion mobile | Valide | `captures/39-responsive-connexion-mobile.jpg` |
| Commandes client mobile | Valide | `captures/40-responsive-client-commandes-mobile.jpg` |
| Commandes employe mobile | Valide | `captures/41-responsive-employe-commandes-mobile.jpg` |
| Statistiques admin mobile | Valide avec reserve | `captures/42-responsive-admin-statistiques-mobile.jpg` |

Reserve responsive : les tableaux admin restent denses sur mobile. La page est
consultable, mais une amelioration future pourrait transformer les lignes de
tableau en cartes mobiles.

## Verifications techniques

Resultat de controle en base et dans les services :

```json
{
  "commande_42": {
    "id_commande": 42,
    "statut_actuel": "acceptee",
    "nombre_personnes": 16,
    "prix_total": "326.52",
    "distance_km": "8.00",
    "ville_livraison": "Pessac"
  },
  "commande_42_statuts_count": 3,
  "contact_recette": {
    "id_contact_message": 15,
    "email": "recette.final@example.test",
    "titre": "Test recette finale"
  },
  "employee_recette": {
    "id_utilisateur": 20,
    "email": "recette.employe.1784594664578@example.test",
    "actif": 0
  },
  "admin_password_hash": {
    "email": "admin.jose@vitegourmand.test",
    "algoName": "bcrypt",
    "length": 60,
    "startsWithBcryptPrefix": true
  },
  "avis_modere": {
    "id_avis": 11,
    "statut": "valide",
    "commentaire": "Test fonctionnel tres clair pour la soutenance."
  },
  "statistics_source": "Agregats MongoDB lus depuis la base vite_gourmand",
  "statistics_menu_rows": 1,
  "statistics_month_rows": 12,
  "mail_log_exists": true,
  "mail_log_size": 1748
}
```

## Emails

Le mode local utilise `MAIL_MAILER=log`.

Emails observes dans `storage/logs/mail.log` pendant la recette :

- demande de contact `Test recette finale` ;
- confirmation de commande `#42` envoyee a Claire Martin ;
- notification de creation du compte employe de test.

Reserve a annoncer : en local, les emails ne sont pas envoyes par SMTP reel,
ils sont journalises pour la demonstration. Le service email est deja centralise
dans le code et peut etre branche a un SMTP de production.

## MongoDB et statistiques

Les statistiques filtrees de `/admin/statistiques?menu=6&start=2026-01-01&end=2026-12-31`
ont ete servies par :

```text
Agregats MongoDB lus depuis la base vite_gourmand
```

Reserve a annoncer : SQL reste la source de verite metier. MongoDB sert les
agregats du dashboard. Le code prevoit un secours SQL local si MongoDB n'est
pas disponible sur l'environnement de demonstration.

## Accessibilite

Controle realise pendant la recette :

- presence de labels sur les formulaires principaux ;
- navigation par roles protegee cote serveur ;
- messages de succes et d'erreur visibles ;
- captures desktop/mobile des pages principales ;
- champs CSRF presents dans les formulaires POST.

Reserve a annoncer : ce controle correspond a une verification de base, pas a
un audit RGAA complet.

## Points a refaire apres deploiement

1. Rejouer au minimum les connexions client, employe et admin sur l'URL publique.
2. Refaire une creation de commande simple sur l'URL publique.
3. Tester le lien GitHub en navigation privee lorsque le depot sera public.
4. Tester le lien Notion partage en navigation privee.
5. Completer la copie Studi avec les liens publics et les identifiants de demo.
