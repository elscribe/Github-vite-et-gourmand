# Matrice finale de recette des user stories

Date de consolidation : 21 juillet 2026.

Objectif : prouver que chaque user story du projet Vite & Gourmand est reliee
a une page Figma, a une route ou page code, a un test fonctionnel et a une
preuve observable. Ce document complete la checklist MVP et le rapport
d'implementation des user stories.

## Methode de recette

Chaque user story est controlee avec la logique suivante :

```text
User story -> Page Figma -> Route code -> Action de test -> Resultat observe -> Preuve -> Statut final
```

Les statuts utilises sont :

| Statut | Signification |
|---|---|
| Valide | La fonctionnalite est implementee et testee manuellement avec un resultat conforme. |
| Valide demo | La logique metier est implementee et testable en environnement local, avec une limite liee a l'environnement de demonstration. |
| Valide avec reserve | La fonctionnalite principale est conforme, mais une reserve technique doit etre expliquee au jury. |
| Partiel solide | Les bases sont presentes, mais une passe finale reste necessaire avant le rendu. |
| Reporte | Fonctionnalite identifiee mais volontairement placee hors perimetre MVP/ECF. |

## Ce que doit contenir la colonne preuve

La colonne preuve doit permettre au jury de comprendre rapidement comment la
fonctionnalite a ete verifiee. Elle peut contenir une ou plusieurs preuves selon
la user story.

| Code preuve | A mettre dans la colonne preuve | Exemple concret |
|---|---|---|
| P-FIGMA | Page, frame ou capture Figma correspondant a l'ecran teste. | Frame `Accueil Desktop / Mobile`, export PDF, capture Figma. |
| P-ROUTE | Route ou URL locale/deployee testee. | `GET /menus`, `POST /commandes`, `/admin/statistiques`. |
| P-COMPTE | Compte utilise pour tester le role. | Client `claire.martin@example.test`, employe `lucas.employee@vitegourmand.test`, admin `admin.jose@vitegourmand.test`. |
| P-ACTION | Action manuelle effectuee pendant le test. | Filtrer par regime, creer une commande, modifier un statut. |
| P-RESULTAT | Resultat observe a l'ecran. | Message de succes, redirection correcte, timeline visible. |
| P-DONNEE | Donnee creee ou modifiee pendant le test. | ID commande, avis en attente, statut `acceptee`, employe desactive. |
| P-DB | Verification dans la base ou via le modele. | Ligne dans `commandes`, `commande_statuts`, `contact_messages`, hash du mot de passe. |
| P-MAIL | Trace email ou log d'envoi en mode demo/local. | Log reset password, commande creee, invitation avis, creation employe. |
| P-SECURITE | Preuve de controle serveur ou acces refuse. | Redirection connexion, acces refuse, token CSRF, middleware role. |
| P-RESPONSIVE | Capture ou verification desktop/mobile. | Page lisible en mobile et desktop, menu burger, formulaire non casse. |
| P-RESERVE | Explication courte d'une limite connue. | MongoDB prepare par scripts, fallback SQL local faute d'extension PHP MongoDB. |

Format recommande pour une preuve finale :

```text
Date - Compte/role - Route testee - Action - Resultat observe - Capture ou donnee de preuve - Reserve eventuelle
```

Exemple :

```text
21/07/2026 - Client Claire - /commandes/creation - creation commande menu Cocktail Bordelais 15 pers. Pessac 10 km - total 307,90 EUR et commande visible dans /commandes - preuve : capture recap + ID commande + ligne commande_statuts.
```

## Matrice finale

| US | Role | Groupe de pages | Page Figma attendue | Route / page code | Test de recette | Statut final | Preuve a conserver |
|---|---|---|---|---|---|---|---|
| US-001 | Visiteur | Socle public | Accueil desktop/mobile | `GET /` | Ouvrir l'accueil, verifier presentation entreprise, CTA, avis valides et responsive. | Valide | P-FIGMA, P-ROUTE, P-ACTION, P-RESULTAT, P-RESPONSIVE. Capture accueil desktop/mobile + note "avis valides uniquement". |
| US-002 | Visiteur | Menus publics | Liste menus desktop/mobile | `GET /menus` | Ouvrir la liste des menus sans connexion et verifier les cartes menus. | Valide | P-FIGMA, P-ROUTE, P-RESULTAT, P-RESPONSIVE. Capture liste menus avec titres, prix, minimum personnes et bouton detail. |
| US-003 | Visiteur | Menus publics | Liste menus + overlay/filtres | `GET /menus` + JS | Filtrer par theme, regime, prix et nombre de personnes sans rechargement complet. | Valide | P-FIGMA, P-ROUTE, P-ACTION, P-RESULTAT, P-RESPONSIVE. Capture avant/apres filtre + filtres actifs visibles. |
| US-004 | Visiteur | Detail menu | Detail menu desktop/mobile | `GET /menus/{id}` | Ouvrir un menu et verifier images, plats, allergenes, conditions, stock et CTA commande. | Valide | P-FIGMA, P-ROUTE, P-RESULTAT, P-RESPONSIVE. Capture detail menu + galerie + composition + bouton commander. |
| US-005 | Visiteur | Contact | Contact desktop/mobile | `GET /contact`, `POST /contact` | Envoyer un message valide depuis le formulaire contact. | Valide | P-FIGMA, P-ROUTE, P-ACTION, P-RESULTAT, P-DB, P-SECURITE. Capture formulaire + message succes + ligne `contact_messages` + CSRF present. |
| US-006 | Visiteur | Authentification | Inscription desktop/mobile | `GET /inscription`, `POST /inscription` | Creer un compte client et verifier que le role client est impose cote serveur. | Valide | P-FIGMA, P-ROUTE, P-ACTION, P-RESULTAT, P-DB, P-SECURITE. Capture inscription + utilisateur cree + role client + mot de passe hashe. |
| US-007 | Tous roles connectes | Authentification | Connexion desktop/mobile | `GET /connexion`, `POST /connexion`, deconnexion | Se connecter avec client, employe et admin ; verifier menu et redirection selon role. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-SECURITE. Captures des trois connexions et menus par role. |
| US-008 | Utilisateur connecte | Authentification | Mot de passe oublie / reset | `/mot-de-passe/oublie`, `/mot-de-passe/reinitialisation` | Generer un lien de reinitialisation local, utiliser un token valide et verifier expiration/hash. | Valide demo | P-FIGMA, P-ROUTE, P-ACTION, P-RESULTAT, P-DB, P-MAIL, P-RESERVE. Capture demande reset + token local/log + explication email reel branche en production. |
| US-009 | Client | Espace compte | Mon compte client | `GET /mon-compte`, `POST /mon-compte` | Modifier les informations du compte connecte et verifier que seul l'utilisateur courant est modifie. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DB, P-SECURITE. Capture profil avant/apres + utilisateur de session. |
| US-010 | Client | Commande | Creation commande | `GET /commandes/creation`, `POST /commandes` | Creer une commande depuis un menu avec les informations client. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DONNEE, P-DB. Capture recap + ID commande + historique initial. |
| US-011 | Client | Commande | Recapitulatif prix commande | `POST /commandes`, calcul serveur + JS | Verifier minimum de personnes, frais livraison et remise ; comparer affichage JS et calcul serveur. | Valide | P-FIGMA, P-ROUTE, P-ACTION, P-RESULTAT, P-DONNEE. Exemple conserve : Cocktail Bordelais, 15 personnes, Pessac 10 km = 307,90 EUR. |
| US-012 | Client | Commandes client | Mes commandes | `GET /commandes` | Afficher uniquement les commandes du client connecte. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-RESULTAT, P-DB, P-SECURITE. Capture historique client + filtre par `id_user`. |
| US-013 | Client | Commandes client | Detail commande / suivi | `GET /commandes/{id}` | Ouvrir une commande et verifier la timeline des statuts. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-RESULTAT, P-DONNEE, P-DB. Capture timeline + lignes `commande_statuts`. |
| US-014 | Client | Commandes client | Modification / annulation client | `/commandes/{id}/modifier`, `/commandes/{id}/annuler` | Modifier ou annuler une commande uniquement si elle est `en_attente`. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DONNEE, P-SECURITE. Capture bouton disponible en attente + blocage apres acceptation. |
| US-015 | Employe | Espace employe | Liste commandes employe | `GET /employe/commandes` | Afficher les commandes et filtrer par statut ou client. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT. Capture liste commandes + filtres statut/client. |
| US-016 | Employe | Espace employe | Mise a jour statut | `POST /employe/commandes/{id}/statut` | Changer le statut d'une commande et verifier l'historique. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DONNEE, P-DB, P-MAIL. Capture changement statut + historique + log email si statut sensible. |
| US-017 | Employe | Espace employe | Annulation motivee | `POST /employe/commandes/{id}/annuler` | Annuler avec mode de contact et motif obligatoires ; verifier que `annulee` ne passe pas par le select statut. | Valide renforce | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DONNEE, P-DB, P-SECURITE. Capture formulaire motif + mode contact + rejet annulation generique. |
| US-018 | Employe | Moderation | Avis employe | `GET /employe/avis`, `POST /employe/avis/{id}` | Moderer un avis client en attente : validation ou refus. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DONNEE, P-DB. Capture avis en attente puis statut modifie. |
| US-019 | Admin | Administration menus | Gestion menus | `GET/POST /admin/menus` | Creer ou modifier un menu et associer des plats. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DONNEE, P-DB. Capture formulaire menu + associations `menu_plats`. |
| US-020 | Admin | Administration plats | Gestion plats | `GET/POST /admin/plats` | Creer ou modifier un plat, type et allergenes associes. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DONNEE, P-DB. Capture formulaire plat + associations allergenes. |
| US-021 | Admin | Administration horaires | Gestion horaires | `GET/POST /admin/horaires` | Modifier les horaires et verifier leur disponibilite dans l'interface publique. | Valide simple | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DONNEE. Capture admin horaires + footer public mis a jour. |
| US-022 | Admin | Administration employes | Gestion employes | `GET/POST /admin/employes` | Creer un compte employe, puis activer/desactiver le compte. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DONNEE, P-DB, P-MAIL, P-SECURITE. Capture creation employe + statut actif/inactif + log notification sans mot de passe. |
| US-023 | Admin | Dashboard | Dashboard admin | `GET /admin` | Ouvrir le dashboard admin et verifier les indicateurs principaux. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-RESULTAT. Capture dashboard avec indicateurs lisibles. |
| US-024 | Admin | Statistiques | Graphique commandes par menu | `GET /admin/statistiques` | Comparer le nombre de commandes par menu via graphique. | Valide | P-FIGMA, P-ROUTE, P-COMPTE, P-RESULTAT, P-DONNEE. Capture graphique + valeurs par menu. |
| US-025 | Admin | Statistiques | CA par menu / periode | `GET /admin/statistiques` | Filtrer par menu et periode, verifier le chiffre d'affaires par menu. | Valide avec reserve | P-FIGMA, P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-DONNEE, P-RESERVE. Capture filtres + resultat ; reserve : fallback SQL local, MongoDB documente par scripts. |
| US-026 | Tous | Transversal accessibilite | Toutes pages principales | Toutes vues principales | Verifier labels, contrastes, textes alternatifs, responsive et navigation clavier de base. | Partiel solide | P-FIGMA, P-ROUTE, P-RESULTAT, P-RESPONSIVE, P-RESERVE. Captures mobile/desktop + checklist RGAA de base ; audit final complet a realiser avant rendu. |
| US-027 | Tous roles prives | Transversal securite | Acces refuses / navigation par role | Routes privees client, employe, admin | Tenter les acces avec mauvais role et verifier blocage serveur. | Valide | P-ROUTE, P-COMPTE, P-ACTION, P-RESULTAT, P-SECURITE. Capture client bloque sur admin, visiteur redirige connexion, employe bloque statistiques admin si applicable. |

## Parcours de recette a executer avant rendu

### Parcours visiteur

1. Ouvrir `/`.
2. Verifier avis publics valides.
3. Ouvrir `/menus`.
4. Tester les filtres rapides et l'overlay de filtres.
5. Ouvrir un detail menu.
6. Ouvrir `/contact` et envoyer un message.
7. Verifier pages legales : `/mentions-legales`, `/cgv`, `/confidentialite`.

### Parcours client

1. Creer un compte depuis `/inscription`.
2. Se connecter depuis `/connexion`.
3. Modifier le profil depuis `/mon-compte`.
4. Creer une commande depuis `/commandes/creation`.
5. Verifier calcul prix/remise/livraison.
6. Consulter `/commandes`.
7. Ouvrir le detail d'une commande et verifier la timeline.
8. Modifier puis annuler une commande encore `en_attente`.
9. Deposer un avis apres une commande `terminee`.

### Parcours employe

1. Se connecter avec le compte employe.
2. Ouvrir `/employe/commandes`.
3. Filtrer par statut et par client.
4. Changer un statut de commande.
5. Annuler une commande avec mode de contact et motif.
6. Ouvrir `/employe/avis`.
7. Valider ou refuser un avis.

### Parcours administrateur

1. Se connecter avec le compte admin.
2. Ouvrir `/admin`.
3. Ouvrir `/admin/statistiques` et tester les filtres.
4. Ouvrir `/admin/employes` et tester creation/desactivation.
5. Ouvrir `/admin/horaires` et modifier un horaire.
6. Ouvrir `/admin/menus` et modifier un menu.
7. Ouvrir `/admin/plats` et modifier un plat.

### Parcours securite et transversal

1. Deconnecte, tenter `/commandes`, `/employe`, `/admin`.
2. Connecte client, tenter `/employe` et `/admin`.
3. Connecte employe, tenter les pages strictement admin.
4. Verifier que les formulaires POST contiennent un token CSRF.
5. Verifier que les erreurs restent lisibles et non techniques.
6. Verifier le rendu desktop et mobile des pages principales.

## Reservations a assumer devant le jury

| Sujet | Reserve | Argument jury |
|---|---|---|
| US-008 Mot de passe oublie | En local, l'envoi email reel est simule par log/lien de demonstration. | La logique securisee existe : token hash, expiration et reinitialisation. Le SMTP reel se branche au deploiement. |
| US-025 MongoDB statistiques | L'environnement PHP local utilise une aggregation SQL de secours si l'extension MongoDB n'est pas installee. | MongoDB est prepare et documente par scripts ; le dashboard reste testable localement. |
| US-026 Accessibilite | Les bases RGAA sont appliquees, mais un audit complet reste a finaliser. | Labels, contrastes, textes alternatifs et responsive sont en place ; l'audit final est identifie dans le plan de recette. |

## Fonctionnalites hors perimetre ou futures

Ces fonctionnalites ne bloquent pas la recette ECF si elles sont documentees
comme evolutions futures :

| Fonctionnalite | Raison du report | Preuve attendue dans la documentation |
|---|---|---|
| Paiement en ligne reel | Non indispensable au parcours ECF, depend d'un prestataire externe. | Mention "hors MVP / evolution future" dans la documentation de gestion de projet. |
| Envoi SMS | Besoin d'une API externe et de donnees telephone fiables. | Justification de report et remplacement par email/log demo. |
| Serveur mail professionnel de production | Depend de l'hebergeur et de la configuration DNS/SMTP. | Documentation du mode local et plan de branchement production. |
| Geolocalisation automatique | API cartographique externe non necessaire pour demontrer la regle de livraison. | Justification : distance saisie/testable en MVP. |
| Statistiques avancees | Non demandees pour valider le tableau de bord principal. | Mention comme amelioration apres ECF. |
| Gestion editoriale complete du contenu public | Trop large pour le perimetre ECF. | Mention comme evolution CMS/back-office futur. |
| Gestion avancee des images depuis l'admin | Les visuels peuvent etre geres par assets/projet pour la demo. | Mention comme amelioration admin future. |
| Historique complet des actions internes | Utile mais non indispensable au MVP. | Mention comme audit trail futur. |
| Espace livreur dedie | Role non demande explicitement dans le perimetre principal. | Mention comme extension de l'espace employe. |

## Sources documentaires

- `docs/project-management/user-story-implementation-report.md`
- `docs/manual/mvp-test-checklist.md`
- `docs/project-management/user-story-debug-log.md`
- `docs/project-management/public-figma-inventory.md`
- Notion : page `Gestion de projet`, section user stories et matrice de tracabilite.
