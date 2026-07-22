# Checklist finale ECF - Vite & Gourmand

Date : 22 juillet 2026.

Objectif : lister ce qu'il reste a faire pour transformer le projet local en
dossier ECF pret a etre rendu au jury.

L'enonce local contient 12 pages. Les audits pages 10, 11 et 12 montrent que le
fond du projet est majoritairement present, mais que la fermeture finale du
rendu reste a faire : preuves publiques, deploiement, liens definitifs,
documentation synchronisee et recette production.

## Priorite 0 - Bloquants avant rendu

- [ ] Stabiliser le code local actuel : verifier les changements recents sur
  compte utilisateur, avis, menus, images et CSS avant de les considerer comme
  version finale.
- [ ] Lancer la verification technique complete du projet sur la version finale.
- [ ] Faire une recette manuelle locale des parcours obligatoires :
  accueil, menus, detail menu, inscription, connexion, compte client, commande,
  espace employe, espace admin, statistiques, avis, contact et deconnexion.
- [ ] Produire un audit RGAA/accessibilite final et le relier depuis le README
  et les livrables finaux.
- [ ] Recontroler l'ecran Figma admin Menus/Plats avec le dernier code, puis
  reexporter les maquettes si l'interface codee a encore evolue.
- [ ] Regrouper les exports UX/UI obligatoires dans le rendu :
  charte graphique, wireframes desktop/mobile et maquettes desktop/mobile.
- [ ] Mettre a jour le manuel utilisateur PDF avec l'URL finale ou retirer les
  mentions temporaires si le PDF est genere avant deploiement.
- [ ] Rendre le depot GitHub public et tester l'URL sans session connectee.
- [ ] Partager Notion au jury et tester le lien sans session connectee.
- [ ] Deployer l'application sur Fly.io via Docker.
- [ ] Tester l'application deployee sans session connectee.
- [ ] Tester les comptes demo client, employe et administrateur sur l'URL
  deployee.
- [ ] Renseigner dans la copie Studi les liens definitifs :
  GitHub public, application deployee, Notion jury, Figma et identifiants demo.

## Priorite 1 - Documentation a fermer

- [ ] Mettre a jour `README.md` avec les liens definitifs et le statut reel du
  deploiement.
- [ ] Mettre a jour `docs/deliverables/final-deliverables.md` avec les vrais
  liens et les vrais statuts.
- [ ] Mettre a jour `docs/deployment/README.md` apres deploiement avec :
  hebergeur, URL publique, date, commit deploye, variables principales, SQL,
  MongoDB et resultat de recette production.
- [ ] Relire `docs/project-management/user-story-implementation-report.md` pour
  que les statuts correspondent exactement a la version finale du site.
- [ ] Relire `docs/manual/final-user-story-test-matrix.md` apres la recette
  finale.
- [ ] Verifier que les diagrammes Merise et UML restent alignes avec le code
  final apres les dernieres modifications.
- [ ] Verifier que la documentation securite couvre encore les formulaires
  ajoutes ou modifies recemment.
- [ ] Ajouter dans Notion les liens finaux et, si besoin, une page de synthese
  tres courte pour le jury.

## Priorite 2 - Git et branches

- [ ] Identifier les changements locaux qui appartiennent vraiment au rendu
  final.
- [ ] Commiter la version stable avec un message clair.
- [ ] Fusionner les branches utiles vers `develop`.
- [ ] Tester `develop`.
- [ ] Fusionner `develop` vers `main`.
- [ ] Pousser `main` et `develop` sur GitHub.
- [ ] Verifier que `main` contient la meme version que l'application deployee.
- [ ] Verifier que `.env`, fichiers systeme et secrets ne sont pas versionnes.
- [ ] Tester le depot GitHub public dans une fenetre sans connexion.

## Priorite 3 - Deploiement Fly.io via Docker

- [ ] Ajouter ou finaliser les fichiers de deploiement Docker/Fly.io.
- [ ] Configurer les variables de production :
  `APP_ENV`, `APP_DEBUG`, `APP_URL`, `APP_KEY`, sessions, SQL, MongoDB et email.
- [ ] Choisir et configurer la base SQL utilisee par l'application deployee.
- [ ] Initialiser cette base SQL de production avec les scripts de creation et
  de donnees, une fois l'environnement Fly.io pret.
- [ ] Choisir et configurer MongoDB pour les statistiques de l'application
  deployee.
- [ ] Initialiser les collections MongoDB et les donnees de demonstration sur
  l'environnement de production, une fois le service MongoDB accessible.
- [ ] Verifier que le dossier de logs est accessible en ecriture.
- [ ] Verifier que le serveur pointe bien vers `public/`.
- [ ] Lancer la recette post-deploiement sur l'URL Fly.io.
- [ ] Noter le commit deploye dans la documentation.

## Priorite 4 - Recette finale

- [ ] Tester les pages publiques sans connexion.
- [ ] Tester l'inscription client.
- [ ] Tester la connexion client.
- [ ] Tester une commande client.
- [ ] Tester l'espace compte client.
- [ ] Tester les avis si cette fonctionnalite est retenue dans la version finale.
- [ ] Tester l'espace employe.
- [ ] Tester le changement de statut d'une commande employe.
- [ ] Tester l'espace administrateur.
- [ ] Tester la gestion admin des menus/plats si elle est dans le perimetre
  final.
- [ ] Tester le dashboard statistiques admin avec MongoDB.
- [ ] Tester le comportement de secours si MongoDB est indisponible, si cette
  reserve est conservee dans la documentation.
- [ ] Tester les protections de role : client vers admin, client vers employe,
  employe vers admin.
- [ ] Tester la deconnexion.
- [ ] Tester les formulaires avec donnees invalides.
- [ ] Verifier l'affichage mobile des parcours principaux.
- [ ] Verifier les contrastes, labels, focus clavier et textes alternatifs sur
  les pages principales.

## Priorite 5 - Copie Studi et oral

- [ ] Preparer le texte final a coller dans Studi avec :
  lien GitHub, lien application, lien Notion, lien Figma et comptes demo.
- [ ] Verifier tous les liens depuis un navigateur non connecte.
- [ ] Verifier que les documents obligatoires sont faciles a trouver depuis le
  README.
- [ ] Preparer un fil d'oral court :
  besoin client, architecture MVC, SQL/MongoDB, securite, accessibilite,
  gestion projet, UX/UI, deploiement et limites connues.
- [ ] Preparer une explication simple de `StatisticsModel` :
  modele actuellement code pour lire les statistiques MongoDB avec secours SQL.
- [ ] Preparer une explication simple de `AggregationService` :
  service futur possible pour recalculer/aggreger les statistiques, non retenu
  comme composant central du MVP tant qu'il n'est pas code.
- [ ] Garder une liste honnete des reserves restantes si elles existent encore
  au moment du rendu.

## Etat global

Le projet n'est pas loin d'un dossier ECF presentable. Les plus gros sujets ne
sont plus la conception ou le fond fonctionnel, mais la fermeture :

- preuves publiques ;
- deploiement Fly.io ;
- documentation finale synchronisee ;
- accessibilite/RGAA ;
- recette finale ;
- GitHub public a jour ;
- Notion partage au jury.

Decision pratique : ne pas pousser `main` et ne pas remplir la copie Studi tant
que les priorites 0 et 1 ne sont pas traitees.
