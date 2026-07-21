# Manuel utilisateur - Vite & Gourmand

Date de consolidation : 21 juillet 2026.

Ce manuel presente les parcours principaux de l'application Vite & Gourmand. Il
sert de support pour la demonstration devant le jury et pour tester les roles :
visiteur, client, employe et administrateur.

## Acces a l'application

| Element | Valeur |
|---|---|
| URL locale | `http://localhost:8000` apres `composer serve` |
| URL deployee | A renseigner apres deploiement |
| Depot GitHub | <https://github.com/elscribe/Github-vite-et-gourmand> |
| Figma | <https://www.figma.com/design/sMkvVuvOyBkMvlTIsq2eCY/Vite---Gourmand?m=auto&t=eaqGOcxDQGMr22Ek-6> |

## Comptes de demonstration

| Role | Email | Mot de passe | Usage |
|---|---|---|---|
| Client | `claire.martin@example.test` | `ClientVite2026!` | Commander, suivre, modifier profil, deposer avis. |
| Employe | `lucas.employee@vitegourmand.test` | `EmployeVite2026!` | Traiter les commandes et moderer les avis. |
| Administrateur | `admin.jose@vitegourmand.test` | `AdminVite2026!` | Dashboard, statistiques, menus, plats, horaires, employes. |

Ces mots de passe sont des identifiants de demonstration. Ils ne doivent pas
etre reutilises pour un compte personnel.

## Parcours visiteur public

### 1. Consulter l'accueil

Route : `/`

Actions :

1. Ouvrir la page d'accueil.
2. Lire la presentation de Vite & Gourmand.
3. Verifier les avis clients affiches.
4. Utiliser les boutons d'appel a l'action vers les menus ou l'inscription.

Resultat attendu : la page presente l'entreprise, les engagements, des menus et
uniquement les avis valides.

### 2. Consulter les menus

Route : `/menus`

Actions :

1. Ouvrir la liste des menus.
2. Comparer les cartes menus.
3. Verifier le titre, le prix, la description et le nombre minimum de personnes.
4. Cliquer sur le bouton de detail.

Resultat attendu : les menus actifs sont visibles sans connexion.

### 3. Filtrer les menus

Route : `/menus`

Actions :

1. Choisir un theme.
2. Choisir un regime alimentaire.
3. Modifier le prix ou le nombre de personnes.
4. Reinitialiser les filtres.

Resultat attendu : la liste se met a jour dynamiquement sans rechargement
complet de page.

### 4. Consulter le detail d'un menu

Route : `/menus/{id}`

Actions :

1. Ouvrir un menu.
2. Verifier les images.
3. Lire la composition.
4. Controler les allergenes, conditions, prix et stock.
5. Cliquer sur le bouton de commande.

Resultat attendu : le visiteur comprend le contenu du menu avant de commander.

### 5. Contacter l'entreprise

Route : `/contact`

Actions :

1. Remplir le formulaire.
2. Envoyer le message.

Resultat attendu : le message est enregistre et une notification est tracee en
mode log ou envoyee selon la configuration mail.

## Parcours client

### 1. Creer un compte

Route : `/inscription`

Actions :

1. Ouvrir le formulaire d'inscription.
2. Renseigner nom, prenom, email, telephone et mot de passe.
3. Valider.

Resultat attendu : le compte est cree avec le role client. Le mot de passe est
stocke sous forme de hash.

### 2. Se connecter

Route : `/connexion`

Actions :

1. Renseigner l'email client.
2. Renseigner le mot de passe.
3. Valider.

Resultat attendu : le client accede a son espace et voit une navigation adaptee.

### 3. Modifier son profil

Route : `/mon-compte`

Actions :

1. Ouvrir l'espace compte.
2. Modifier une information personnelle.
3. Enregistrer.

Resultat attendu : seules les informations du client connecte sont modifiees.

### 4. Commander un menu

Routes : `/commandes/creation` ou `/commandes/creation/{menuId}`

Actions :

1. Choisir un menu.
2. Saisir le nombre de personnes.
3. Renseigner la ville et la distance si necessaire.
4. Verifier le recapitulatif.
5. Valider la commande.

Resultat attendu : la commande est creee, le prix est calcule cote serveur et un
premier statut `en_attente` est ajoute.

Exemple teste : Menu Cocktail Bordelais, 15 personnes, Pessac, 10 km =
`307,90 EUR`.

### 5. Suivre ses commandes

Routes : `/commandes`, `/commandes/{id}`

Actions :

1. Ouvrir la liste des commandes.
2. Ouvrir le detail d'une commande.
3. Lire la timeline des statuts.

Resultat attendu : le client ne voit que ses commandes.

### 6. Modifier ou annuler une commande

Routes : `/commandes/{id}/modifier`, `/commandes/{id}/annuler`

Actions :

1. Ouvrir une commande encore `en_attente`.
2. Modifier ou annuler.
3. Tenter la meme action apres acceptation.

Resultat attendu : la modification ou annulation est autorisee uniquement avant
l'acceptation par l'entreprise.

### 7. Deposer un avis

Route : `/avis/creation/{orderId}`

Actions :

1. Ouvrir une commande terminee.
2. Rediger un avis.
3. Envoyer.

Resultat attendu : l'avis est cree avec le statut `en_attente` et devra etre
modere par un employe.

## Parcours employe

### 1. Acceder a l'espace employe

Route : `/employe`

Actions :

1. Se connecter avec le compte employe.
2. Ouvrir le tableau employe.

Resultat attendu : l'employe accede aux commandes et aux avis, sans acces aux
statistiques admin strictes.

### 2. Gerer les commandes

Route : `/employe/commandes`

Actions :

1. Filtrer les commandes par statut.
2. Filtrer par client.
3. Modifier le statut d'une commande.
4. Verifier l'historique.

Resultat attendu : le statut est mis a jour et une trace est ajoutee dans
`commande_statuts`.

### 3. Annuler une commande avec motif

Route : `/employe/commandes/{id}/annuler`

Actions :

1. Choisir un mode de contact.
2. Renseigner un motif.
3. Valider l'annulation.

Resultat attendu : l'annulation ne peut pas passer par le select de statut
generique. Le motif et le mode de contact sont obligatoires.

### 4. Moderer les avis

Route : `/employe/avis`

Actions :

1. Ouvrir les avis en attente.
2. Valider ou refuser un avis.

Resultat attendu : seuls les avis valides sont visibles publiquement.

## Parcours administrateur

### 1. Acceder au dashboard

Route : `/admin`

Actions :

1. Se connecter avec le compte administrateur.
2. Ouvrir le dashboard.

Resultat attendu : les indicateurs principaux sont visibles.

### 2. Consulter les statistiques

Route : `/admin/statistiques`

Actions :

1. Lire les commandes par menu.
2. Lire le chiffre d'affaires par menu.
3. Filtrer par menu ou periode.

Resultat attendu : les statistiques sont lisibles. MongoDB est prepare pour les
agregats ; un fallback SQL peut etre explique si l'environnement local ne charge
pas MongoDB directement.

### 3. Gerer les employes

Route : `/admin/employes`

Actions :

1. Creer un compte employe.
2. Activer ou desactiver un compte.

Resultat attendu : l'administrateur gere les comptes employes sans creer
d'administrateur depuis l'application.

### 4. Gerer les horaires

Route : `/admin/horaires`

Actions :

1. Modifier les horaires.
2. Verifier leur affichage cote public.

Resultat attendu : les horaires sont mis a jour et restent accessibles dans
l'interface.

### 5. Gerer les menus et plats

Routes : `/admin/menus`, `/admin/plats`

Actions :

1. Creer ou modifier un menu.
2. Associer des plats au menu.
3. Creer ou modifier un plat.
4. Associer des allergenes a un plat.

Resultat attendu : l'administrateur peut maintenir l'offre visible par les
clients.

## Securite a verifier pendant la demonstration

| Test | Resultat attendu |
|---|---|
| Visiteur ouvre `/commandes` | Redirection vers `/connexion`. |
| Client ouvre `/admin` | Acces refuse. |
| Client ouvre `/employe` | Acces refuse. |
| Employe ouvre route admin stricte | Acces refuse. |
| Admin ouvre `/admin` | Acces autorise. |
| Formulaire POST | Token CSRF present. |

## Captures a ajouter apres deploiement

Pour le PDF final, conserver au minimum :

- accueil public ;
- liste des menus ;
- detail d'un menu ;
- formulaire de commande ;
- liste des commandes client ;
- espace employe commandes ;
- moderation des avis ;
- dashboard administrateur ;
- statistiques administrateur ;
- gestion des employes.

## Phrase pour le jury

Le manuel suit les quatre roles du sujet : le visiteur consulte les menus, le
client commande et suit ses commandes, l'employe traite les commandes et les
avis, et l'administrateur pilote les menus, les employes, les horaires et les
statistiques.
