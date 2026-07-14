# Journal d'echecs et solutions par user story

Date de mise a jour : 14 juillet 2026.

Objectif : garder une trace des difficultes rencontrees, des corrections
appliquees et des explications simples a donner au jury. Quand une user story
n'a pas eu d'echec bloquant, le journal note quand meme le principal point de
vigilance.

## Methode de correction

```text
1. Reproduire le probleme.
2. Identifier la couche touchee : route, controleur, modele, vue, base, CSS ou session.
3. Corriger le plus petit morceau possible.
4. Tester dans le navigateur ou avec une requete locale.
5. Relancer `composer check`.
6. Noter l'erreur et la solution dans ce journal.
```

## Tableau par user story

| US | Echec ou point de vigilance | Cause probable | Solution appliquee | Phrase pour le jury |
|---|---|---|---|---|
| US-001 Accueil avis valides | Ne pas afficher tous les avis sans moderation. | La page d'accueil ne devait pas lire les avis non valides. | Ajout de `ReviewModel::findValidated` et affichage limite aux avis `valide`. | "Les avis publics passent par une moderation avant d'etre visibles." |
| US-002 Liste menus | Certains menus de la base ne correspondaient pas encore aux titres Figma. | Les donnees de demo SQL et la maquette evoluent separement. | Garder la structure fonctionnelle et reporter l'alignement final du contenu apres Figma. | "Le code est pret, les contenus peuvent etre ajustes ensuite en base." |
| US-003 Filtres menus | Les filtres demandaient plus qu'une simple liste : il fallait aussi charger themes et regimes. | La vue avait besoin de donnees de reference en plus des menus. | Ajout des methodes `findThemes`, `findRegimes` et des filtres dans `MenuModel`. | "Le controleur prepare les listes, le modele applique les conditions SQL." |
| US-004 Detail menu | La zone presentation semblait mal cadree sur certaines largeurs. | La grille detail/menu devait passer en une colonne selon la taille ecran. | Ajustement CSS responsive avec grille adaptee et espacements fixes. | "J'ai corrige le responsive pour garder une lecture propre sur tablette/mobile." |
| US-005 Contact | La route contact n'etait pas facile a retrouver dans `routes.php`. | Le fichier avait surtout des placeholders et la navigation dans Nano etait confuse. | Ajout de `ContactController`, `ContactModel`, vue contact et routes GET/POST. | "Un formulaire suit le cycle GET formulaire, POST validation, INSERT, redirection." |
| US-006 Creation compte | Risque de creer un utilisateur avec un mauvais role. | Le role vient de la base et ne doit pas etre choisi librement par le visiteur. | Creation forcee en role client dans `UserModel::createCustomer`. | "Le role client est impose cote serveur pour eviter une elevation de privileges." |
| US-007 Connexion/deconnexion | Il fallait tester trois profils differents. | Le menu et les routes dependent du role en session. | Connexions client, employe et admin testees avec redirections et pages privees. | "La session stocke l'identite et le role, puis les middlewares protegent les routes." |
| US-008 Mot de passe oublie | Pas d'envoi email reel en local. | Le projet local n'a pas de service mail configure. | Mise en place d'un token hash avec expiration et affichage d'un lien de demo local. | "La logique securisee existe ; l'envoi email reel serait branche au deploiement." |
| US-009 Modification profil | Risque d'autoriser la modification du mauvais compte. | Une page compte doit toujours utiliser l'utilisateur connecte. | Le controleur recupere l'id depuis la session, pas depuis un parametre libre. | "Un client ne peut modifier que son propre profil car l'id vient de la session." |
| US-010 Commander un menu | Il fallait creer la commande et son premier historique ensemble. | Une commande sans historique rend le suivi incomplet. | `OrderModel::create` insere la commande puis le statut initial. | "A la creation, j'enregistre aussi la premiere ligne d'historique." |
| US-011 Calcul prix/remise/livraison | Le calcul devait etre identique cote affichage et cote serveur. | Le JavaScript peut aider l'utilisateur, mais ne doit pas etre la source de verite. | Calcul principal dans `OrderModel::calculateTotals`, apercu en JS seulement. | "Le serveur recalcule toujours le prix final pour eviter une manipulation du navigateur." |
| US-012 Historique commandes client | Risque d'afficher les commandes d'un autre client. | La requete doit filtrer par utilisateur connecte. | `findForUser` filtre par `id_user`. | "La liste client est limitee par l'id de session." |
| US-013 Suivi statut commande | Un simple statut actuel ne suffit pas pour expliquer le parcours. | Le sujet demande un suivi, donc il faut l'historique. | Lecture de `commande_statuts` et affichage en timeline. | "Je garde une trace datee de chaque changement de statut." |
| US-014 Modifier/annuler avant acceptation | Risque de modifier une commande deja acceptee. | La regle metier limite les changements au statut `en_attente`. | Verification du statut avant modification ou annulation client. | "La regle est verifiee cote serveur, pas seulement par le bouton affiche." |
| US-015 Liste commandes employe | Les employes devaient filtrer par statut ou client. | La requete doit construire des conditions optionnelles. | Ajout de filtres dans `OrderModel::findAll` et formulaire employe. | "Les filtres sont optionnels et traduits en conditions SQL preparees." |
| US-016 Mise a jour statut employe | Changer le statut sans historique ferait perdre la tracabilite. | Un simple UPDATE ne suffit pas. | Mise a jour du statut courant et insertion dans `commande_statuts`. | "Chaque changement garde qui l'a fait et quand." |
| US-017 Annulation employe avec motif | Risque d'annuler sans justification. | Le sujet demande un contact client et un motif. | Champs obligatoires mode contact + motif avant annulation. | "L'annulation interne est documentee pour garder une preuve du contact client." |
| US-018 Moderation avis | Ne pas publier automatiquement les avis. | Les avis doivent passer par un employe. | Avis cree en attente, puis validation/refus par espace employe. | "La moderation protege la page publique contre les avis non verifies." |
| US-019 Gestion menus | Version admin complete trop large si on ajoute images, plats et allergenes. | Les associations avancent impliquent plusieurs tables et ecrans. | Version simple : creation/modification des champs principaux du menu. | "J'ai livre le CRUD principal, les associations avancees sont identifiees comme amelioration." |
| US-020 Gestion plats | Les plats sont lies a d'autres donnees comme type et allergenes. | Un CRUD complet allergenes/plats demande des tables de liaison. | Version simple : creation/modification des plats, affichage des plats dans detail menu. | "La gestion des plats existe ; les associations allergenes peuvent etre enrichies ensuite." |
| US-021 Gestion horaires | Page admin simple mais protegee. | Il fallait eviter l'acces client. | Route protegee admin, modele horaires, formulaire creation/modification. | "Les horaires sont geres par l'administrateur via une route protegee." |
| US-022 Comptes employes | Risque de creer un compte employe avec mauvais role ou mot de passe faible. | Creation sensible reservee admin. | Formulaire admin, hash bcrypt, activation/desactivation. | "Seul l'admin peut creer ou desactiver un employe." |
| US-023 Dashboard admin | Les statistiques doivent fonctionner meme si MongoDB local n'est pas branche a PHP. | `mongosh` existe mais l'extension PHP MongoDB n'est pas installee. | Agregats SQL locaux transparents + scripts MongoDB documentes. | "MongoDB est prepare pour la cible, et le dashboard local reste fonctionnel en SQL." |
| US-024 Comparer commandes par menu | Il fallait produire une donnee lisible pour graphique. | Les donnees brutes de commandes ne sont pas suffisantes. | Aggregation par menu dans `StatisticsModel::menuStatistics`. | "Le modele transforme les commandes en indicateurs exploitables." |
| US-025 CA par menu/periode | Reserve technique sur MongoDB local. | Extension PHP MongoDB absente. | Filtre menu/periode en SQL local, scripts MongoDB conserves pour la documentation NoSQL. | "La logique metier est testable localement, MongoDB reste documente pour l'architecture cible." |
| US-026 Accessibilite RGAA | Audit complet non termine. | L'accessibilite demande une passe visuelle et clavier finale. | Labels, contrastes, textes alternatifs et responsive mis en place ; audit final a planifier. | "J'ai applique les bases RGAA et j'ai identifie l'audit final comme reste a faire." |
| US-027 Securite par role | Ne pas se contenter de cacher les liens dans le menu. | Un utilisateur peut taper une URL directement. | Protection par `AuthMiddleware` et `RoleMiddleware` sur les routes. | "La securite est cote serveur : meme avec l'URL, un mauvais role est bloque." |

## Retours d'experience design Figma

### Page d'accueil

Objectif : rapprocher la page d'accueil du visuel Figma, pas seulement rendre
une page fonctionnelle.

| Echec ou ecart constate | Cause | Solution appliquee | A retenir pour le jury |
|---|---|---|---|
| Le header etait fonctionnel mais pas fidele a Figma. | Le layout initial Bootstrap placait la navigation trop bas ou trop proche du logo selon la largeur. | Reprise du header global avec logo image, navigation horizontale en flex, lien actif et soulignement dore. | "J'ai distingue une page qui marche d'une page fidele a la maquette." |
| Le header devait etre transparent au chargement de l'accueil puis fonce au scroll. | Le header global avait un fond fixe, identique sur toutes les pages. | Ajout d'une classe `page-home`, puis JavaScript qui applique `header-scrolled` apres defilement. | "Le comportement visuel depend de l'etat de scroll, gere en JavaScript leger." |
| La barre doree sous le lien actif etait trop longue. | Le pseudo-element prenait trop de largeur par rapport au texte. | Reduction de la largeur du `::after` pour que la ligne soit plus courte que le mot. | "Un detail de micro-design peut suffire a casser la fidelite Figma." |
| Le lien `Connexion` ne correspondait pas au wording Figma. | Le libelle technique de la route avait ete repris tel quel. | Remplacement par `Mon espace` dans la navigation publique. | "Le texte d'interface doit suivre la maquette, pas seulement la route technique." |
| Des sections temporaires etaient encore visibles alors qu'elles n'existaient pas dans Figma. | La page d'accueil avait ete construite d'abord comme page MVP avec blocs de demonstration. | Suppression/replacement des blocs inutiles : statistiques, CTA intermediaire, parcours simple, anciennes cartes. | "J'ai nettoye le prototype MVP pour passer a une integration maquette." |
| Les images de la section histoire paraissaient croppees dans le mode commentaire. | Le mode commentaire capturait une largeur et un scroll differents du navigateur normal ; en plus les images avaient une hauteur trop contrainte. | Passage a un ratio d'image stable proche de Figma et ajustements responsive. | "Je verifie toujours dans la capture et dans le navigateur, car les outils n'affichent pas toujours le meme viewport." |
| Les cartes engagements et avis semblaient coupees dans les commentaires. | Sur petit viewport, les cartes etaient en ligne horizontale avec defilement ; la capture ne montrait que le debut de la rangee. | Conservation du scroll horizontal quand il correspond au besoin mobile/tablette, avec controle visuel. | "Un element coupe dans une capture peut etre normal si c'est une zone scrollable." |
| Les images `Notre Histoire` et `Notre Equipe` restaient a droite du texte en 582px. | Le breakpoint mobile gardait encore deux colonnes, ce qui compressait les images. | Passage des lignes `home-about-row` en une seule colonne des 760px : texte puis image. | "Quand le responsive reduit trop une image, je change la structure plutot que de forcer une miniature." |
| Les filtres visuels des menus de l'accueil ne filtraient pas sans rechargement. | Les pastilles etaient de simples elements statiques sans donnees ni ecouteur JavaScript. | Ajout de boutons accessibles, attributs `data-*` sur les cartes, et filtrage JS cote navigateur. | "L'enonce demandait une mise a jour automatique : j'ai donc teste l'interaction sans rechargement." |
| La section `Nos engagements` etait mal espacee selon la largeur. | Le desktop avait un padding bas trop faible, et le breakpoint 582px gardait une rangee horizontale scrollable avec une carte partiellement visible. | Ajout d'une vraie respiration basse en desktop, puis passage en grille 2 colonnes sur tablette et 1 colonne sur mobile. | "J'ai mesure la section avant de corriger : padding, largeur visible et scrollWidth." |
| Le footer n'avait pas le bon logo et ses colonnes n'etaient pas alignees comme Figma. | Le footer precedent etait une version simple, pas le composant final. | Ajout du logo compact et passage a une grille en colonnes `Horaires`, `Contact`, `Informations`. | "Le footer est un composant partage : le corriger une fois corrige toutes les pages." |
| Le bouton retour en haut etait local a une section. | Il avait ete pense pour l'accueil, alors que le besoin etait global. | Deplacement dans le layout principal avec ancre `#top`, affichage au scroll via JS. | "Quand un composant sert partout, il appartient au layout global." |

### Page liste des menus

Objectif : reproduire la frame Figma `Liste menus - Index - Desktop`
node `460:19068`.

| Echec ou ecart constate | Cause | Solution appliquee | A retenir pour le jury |
|---|---|---|---|
| La page `/menus` etait fonctionnelle mais pas fidele a Figma. | Elle affichait les donnees SQL brutes avec des cartes sans images et un formulaire classique. | Refonte de `menus/index.php` avec hero, filtres en pastilles, images, badges, prix, statuts et CTA. | "Une user story peut etre validee fonctionnellement puis necessiter une passe UI Figma." |
| Les contenus de la base ne correspondaient pas aux menus Figma. | Les seeds SQL utilisaient encore des titres anglais et des prix differents. | Ajout d'une couche de presentation dans la vue pour afficher les libelles/prix/visuels Figma tout en gardant les liens vers les vrais menus. | "J'ai preserve la navigation existante tout en synchronisant l'affichage avec la maquette." |
| Le hero Figma contenait deja le texte dans l'export image. | L'export du noeud `hero menu` incluait image + typographie. | Utilisation de l'export comme fond pour coller visuellement, avec titre et sous-titre en `visually-hidden` pour garder l'accessibilite. | "Quand l'export contient du texte, je garde une alternative accessible dans le HTML." |
| Les filtres Figma etaient des pastilles, mais les filtres reels etaient des champs select/input. | Le MVP avait privilegie la fonctionnalite technique. | Creation de filtres rapides en pastilles et conservation d'un panneau `Tous les filtres` pour les criteres avances. | "J'ai garde la fonctionnalite tout en respectant le composant visuel Figma." |
| Les cartes devaient rester cliquables vers les details existants. | Une refonte UI peut casser le parcours si les liens disparaissent. | Chaque bouton `Voir le menu` continue de pointer vers `/menus/{id}`. | "La fidelite visuelle ne doit pas casser le parcours utilisateur." |
| La grille etait legerement decalee par rapport a la frame. | Le conteneur Bootstrap ajoutait du padding lateral non present dans la frame Figma. | Conteneur catalogue fixe a `1280px` avec padding horizontal retire en desktop. | "Les ecarts de quelques pixels viennent souvent du container, pas des cartes." |
| La page finale etait trop haute de quelques pixels. | L'espacement avant footer etait plus grand que dans la frame. | Ajustement du padding bas de la section catalogue jusqu'a retrouver une capture `1440 x 2763`. | "Je valide la fidelite par comparaison de dimensions et capture, pas au juger." |
| Une commande de capture avec une URL contenant `&` a echoue dans le terminal. | Le shell interprete `&` comme une instruction de lancement en arriere-plan. | Utiliser des guillemets autour de l'URL ou tester sans query string. | "Dans le terminal, certains caracteres d'URL doivent etre proteges." |
| Le test Playwright en mode module n'etait pas disponible via une commande `npx` classique. | La dependance etait accessible pour les captures CLI, mais pas importable dans ce contexte. | Utilisation de `node_repl` pour importer Playwright et tester les interactions DOM. | "Quand un outil de test bloque, je garde l'objectif de test et je change seulement l'outil." |
| Il fallait prouver que les filtres fonctionnaient encore apres la refonte visuelle. | Les pastilles remplacaient l'ancien formulaire visible. | Test DOM : 6 cartes au depart, `Moins de 150 €` affiche 2 cartes, `Disponible` affiche 3 cartes, panneau avance ouvert. | "Apres une refonte graphique, je reteste les interactions metier." |
| Le filtre `Disponible` semblait rester selectionne apres avoir clique un autre filtre. | La classe visuelle doree etait appliquee en dur sur le bouton, au lieu de venir seulement de l'etat actif `.is-active`. | Suppression des classes de couleur fixes sur les filtres rapides ; seul le bouton actif devient dore via CSS et JavaScript. | "J'ai separe l'etat fonctionnel du style : la couleur selectionnee depend maintenant uniquement de `.is-active`." |
| Le panneau `Theme / Regime / Prix maximum / Convives` n'etait plus coherent avec la maquette. | La future interface `Tous les filtres` doit devenir une overlay Figma, pas un formulaire ouvert sous les pastilles. | Suppression du panneau avance visible, nettoyage du controleur et du JavaScript, conservation des filtres rapides cote navigateur. | "J'ai separe les filtres rapides deja valides de la future overlay `Tous les filtres`." |
| Le hero de la page menus affichait deux titres superposes. | L'image de fond exportee depuis Figma contenait deja l'ancien titre en texte incruste. | Creation d'une version propre de l'image sans texte, puis utilisation du titre en HTML/CSS pour rester responsive. | "Pour un hero responsive, le texte important doit rester en HTML, pas etre bloque dans une image." |
| Le hero de la page menus donnait encore l'impression d'afficher un gros texte non conforme. | L'image de fond etait redevenue la bonne, mais le titre HTML etait encore trop massif et `du moment` se comportait comme un deuxieme gros titre dore. | Passage sur l'image nettoyee `hero-menu-clean.png`, reduction du H1, baisse de la hauteur du hero responsive et allegement du voile sombre. | "J'ai distingue le fond Figma du texte HTML : l'image donne l'ambiance, le H1 reste lisible mais ne doit pas dominer tout le visuel." |
| Le fond du hero menus devait venir de Figma, mais sans l'ancien texte incruste. | Le node de page complet contenait encore l'ancienne image exportee avec texte, tandis que l'interface devait garder un texte HTML modifiable. | Conservation d'une version nettoyee du fond dans `public/images/menus/hero-menu-clean.png`, puis utilisation en CSS comme image de fond. | "J'ai choisi le bon niveau de source Figma : l'image seule pour le fond, et le texte en HTML pour l'accessibilite et le responsive." |
| Le footer contenait un lien `Confidentialite` qui menait vers une 404. | La route `/confidentialite` n'etait pas declaree dans `config/routes.php`. | Ajout de `PlaceholderController::privacy()` et de la route publique `/confidentialite`. | "Chaque lien visible dans la navigation publique doit correspondre a une route, meme si le contenu final est encore simple." |
| Les pages footer legales existaient mais pouvaient encore etre vues comme des pages techniques en preparation. | Le controleur temporaire mettait un statut HTTP `501`, qui n'est pas adapte a un lien public de footer. | Ajout d'un rendu informatif public en `200 OK` pour Mentions legales, CGV et Confidentialite. | "Une page simple mais navigable doit renvoyer `200 OK`; `501` signifie que la fonctionnalite n'est pas disponible." |
| Les pages legales ne ressemblaient pas aux frames Figma. | Elles utilisaient encore la vue placeholder generique avec un simple panneau `Information`. | Ajout d'un rendu public legal parametrable : titre, intro, carte blanche, lignes de contenu et encadre `Point cle UX`. | "Le controleur prepare les textes, la vue affiche un composant reutilisable, le footer reste commun." |
| Le header mobile affichait un logo horizontal illisible par rapport aux frames Figma. | Les frames mobiles utilisent un petit carre `VG`, tandis que le code utilisait le logo desktop reduit. | Creation de `logo-mobile-vg.png` depuis la capture Figma mobile, affichage du logo desktop en desktop et du carre VG en mobile. | "Un logo responsive peut avoir deux assets : un lockup complet desktop et une marque compacte mobile." |
| Le header mobile etait trop haut. | Les pages publiques mobiles Figma utilisent un header d'environ 60px, alors que le code gardait 72px. | Passage du header mobile a 60px, logo 38px et bouton burger centre verticalement. | "J'ajuste les dimensions globales du layout avant de retoucher chaque page." |
| Le bouton `Tous les filtres` sur la page menus etait uniquement visuel. | Le formulaire avance precedent avait ete retire en attendant l'overlay Figma, donc il n'y avait plus d'interaction derriere le bouton. | Creation d'une overlay simple avec les champs deja prevus par le modele : theme, regime, prix maximum et convives, puis filtrage cote JavaScript sans rechargement. | "Le modele fournit les donnees, la vue affiche l'overlay, et le JavaScript applique les criteres sur les cartes deja presentes." |
| Les options de l'overlay sortaient en anglais (`Christmas`, `Classic`). | La base de donnees locale contient des libelles anglais, alors que l'interface publique Figma est en francais. | Ajout d'une traduction d'affichage dans la vue, sans modifier les valeurs ni la base. | "Je n'ai pas change la donnee source ; j'ai seulement adapte son libelle pour l'interface utilisateur." |
| L'overlay `Tous les filtres` ne ressemblait pas encore a la frame Figma finale. | La premiere version etait un formulaire technique avec selects et inputs, pas la carte blanche a pastilles de Figma. | Reprise du composant `614:89721` : deux colonnes, pastilles, bouton fermer rond, ligne doree, boutons reset/appliquer, tout en gardant le filtrage JavaScript sans rechargement. | "J'ai separe l'habillage Figma du comportement : les pastilles remplissent des champs caches que le JavaScript sait deja lire." |
| Le premier test Playwright de fermeture de l'overlay a bloque. | Le selecteur `[data-filter-overlay-close]` ciblait d'abord le fond noir, partiellement couvert par le panneau, au lieu du bouton rond. | Test relance avec le selecteur exact `[aria-label=\"Fermer les filtres\"]`, puis verification : overlay cachee, pas d'erreur JavaScript. | "Un test peut echouer parce que le selecteur est trop vague ; je dois cibler l'element interactif exact." |
| L'overlay filtres mobile ne ressemblait pas au bottom-sheet Figma. | La version mobile etait seulement une adaptation du formulaire desktop, avec un panneau mal place et un contenu qui ne scrollait pas comme la frame. | Ajout d'une hauteur mobile `100dvh - 84px`, d'une poignee, d'un header fixe, d'une zone centrale scrollable, d'actions fixes et d'un resume visible apres scroll. | "Le responsive ne consiste pas seulement a empiler : parfois il faut changer le comportement du composant." |
| Le bouton mobile affichait `Appliquer les filtres` sur deux lignes. | Le libelle desktop etait trop long pour le bouton mobile Figma. | Deux spans de libelle : texte complet en desktop, `Appliquer` en mobile via CSS. | "Je garde un seul bouton fonctionnel, mais j'adapte le libelle affiche selon le viewport." |
| La navigation publique etait encore horizontale sur telephone. | Le header desktop avait ete reduit au lieu d'etre remplace par l'overlay mobile Figma. | Ajout d'un bouton burger mobile et d'une overlay `mobile-public-menu` dans le layout global, avec fermeture bouton/Echap. | "Sur mobile, la navigation est un composant different, pas juste une version serree du header desktop." |
| Les pages publiques avaient un debordement horizontal en 390px. | Le footer gardait une grille trop large et les carrousels horizontaux augmentaient le `scrollWidth` global. | Footer en une colonne sur mobile, `overflow-x: hidden` sur le body et scroll interne conserve pour les zones horizontales. | "Je teste le `scrollWidth` pour prouver qu'une page mobile ne deborde pas." |
| Le detail `/menus/1` etait fonctionnel mais ressemblait a une fiche technique, pas a la maquette Figma. | La vue affichait directement les images et plats SQL, dont certaines images etaient des placeholders de demonstration. | Export des images du frame Figma `Détail menu - Noël Tradition - Desktop`, ajout des contenus de presentation dans `MenuPresentation`, puis refonte de `menus/show.php` en grille Figma. | "J'ai garde la route et les donnees metier, mais j'ai ajoute une couche de presentation pour ne pas afficher des assets SQL non finalises au public." |
| Les details `/menus/2` a `/menus/6` retombaient sur l'image de carte, pas sur les galeries Figma. | Seul le menu Noel avait encore des `detail_images`, `detail_sections` et conditions detaillees. | Export des cinq frames detail restantes, decoupage des grandes images et miniatures, puis ajout des contenus dans `MenuPresentation`. | "J'ai factorise la logique : la vue detail reste unique, et chaque menu fournit seulement ses donnees de presentation." |
| Les captures Figma detail demandaient beaucoup d'exports individuels. | Chaque page detail contient une grande image et cinq miniatures, donc 30 assets au total. | Export de la frame complete puis decoupage des rectangles images avec l'extension image PHP deja disponible localement. | "J'ai evite d'ajouter une dependance : j'ai utilise la stack PHP presente pour produire les assets." |
| Le script de decoupage a affiche `imagedestroy() is deprecated`. | La version PHP locale signale que cette fonction n'a plus d'effet depuis PHP 8.0. | Les assets ont bien ete crees ; pour un script durable, supprimer simplement les appels `imagedestroy()`. | "Un warning deprecation n'est pas une erreur bloquante, mais je le note pour nettoyer si le script devient permanent." |
| Le test HTTP avec `HEAD` sur `/menus/1` renvoyait 404 alors que la page marchait dans le navigateur. | Le routeur du projet ne gere pas la methode `HEAD`, seulement les routes `GET` declarees. | Verification avec une vraie requete `GET`, qui retourne `200 OK`. | "Un test doit utiliser la meme methode HTTP que le navigateur ; ici, `HEAD` etait un faux negatif." |
| La maquette contact contient `Nom` et `Téléphone`, mais la table `contact_messages` ne contient que `titre`, `email`, `description`. | Le design Figma et le schema SQL ne sont pas totalement alignes. | Ajout des champs dans la vue et le controleur, puis concatenation du nom et du telephone dans la description stockee. | "J'ai respecte la maquette sans migration risquee : l'information est gardee dans le message tant que la base n'a pas de colonnes dediees." |
| La page contact mobile ne correspondait pas a la frame Figma. | Le champ telephone et les libelles `Adresse`, `Telephone`, `Email`, `Horaires` etaient visibles en mobile, alors que la frame mobile simplifie ces blocs. | Champ telephone masque uniquement en mobile, coordonnees compactees, titre mobile `Message` et bouton mobile `Envoyer`. | "Je garde le formulaire desktop complet mais j'adapte la version mobile quand Figma simplifie le parcours." |
| Il fallait prouver que le nouveau formulaire contact fonctionnait encore avec CSRF. | Ajouter des champs peut casser l'envoi si le controleur ou les anciens champs ne suivent plus. | Test POST complet : recuperation du token CSRF, envoi du formulaire, redirection `302` vers `contact?success=1`, puis verification du dernier message en base. | "Je teste le parcours complet, pas seulement l'affichage du formulaire." |
| La page connexion Figma montre des boutons `Client`, `Employé`, `Admin`, mais le backend n'attend pas de champ role au login. | Le role de l'utilisateur est deja determine par le compte trouve en base, pas par un choix dans le formulaire. | Ajout d'un selecteur visuel qui change d'etat en JavaScript, sans modifier la logique d'authentification. | "Je ne laisse pas l'utilisateur choisir son role côté formulaire ; le role reste une propriete securisee du compte." |
| La page connexion mobile affichait la carte role avant le formulaire. | La carte etait imbriquee dans l'intro, ce qui empechait un ordre different entre desktop et mobile. | Carte role sortie comme bloc frere, grille CSS desktop `intro/form/role`, ordre mobile `intro`, `form`, `role`. | "Quand l'ordre responsive change, il faut parfois corriger la structure HTML, pas seulement le CSS." |
| Les boutons `Client`, `Employe`, `Admin` etaient empiles sur mobile. | Le breakpoint mobile forcait `auth-role-switch` en une colonne. | Retour a trois colonnes compactes avec tailles reduites pour rester dans la carte. | "Un composant mobile n'est pas toujours empile ; je suis la frame Figma." |
| La refonte login pouvait casser l'affichage des erreurs d'identifiants. | Le message d'erreur etait dans l'ancien formulaire generique. | Test POST invalide avec token CSRF : la page revient en `200` et affiche `Identifiants invalides.` dans la nouvelle carte. | "Apres une refonte de formulaire, je teste aussi le chemin d'erreur." |
| La frame inscription ne montre pas les champs `ville` et `pays`, mais le controleur les exige. | Le schema utilisateur est plus detaille que la maquette publique. | Conservation de `ville=Bordeaux` et `pays=France` en champs caches pour respecter la maquette tout en satisfaisant la validation backend. | "Quand Figma simplifie un formulaire, je garde les champs techniques necessaires sans les imposer visuellement a l'utilisateur." |
| La page inscription mobile affichait toute la carte benefices desktop. | La frame mobile garde seulement un petit encadre d'information, sans titre ni liste. | Masquage mobile du titre et de la liste de la carte benefices, conservation du texte de confidentialite en encadre dore. | "Je reutilise le meme HTML, mais le contenu visible change selon le support quand Figma le demande." |
| La refonte inscription pouvait casser les validations existantes. | Le formulaire changeait de structure et d'ordre de champs. | Test POST invalide avec token CSRF : la page revient en `200` et affiche les erreurs obligatoires dans la nouvelle carte. | "Je verifie toujours que les erreurs restent visibles apres une refonte UI." |
| La page mot de passe oublie etait fonctionnelle mais pas alignee a Figma. | Elle utilisait le formulaire generique et un texte de demonstration trop technique. | Reprise de la frame `Auth - Forgot Password - Desktop` : intro a gauche, carte a droite, bouton et lien retour. | "Je garde la logique de reset, mais je retire les formulations techniques de l'interface publique." |
| La page mot de passe oublie mobile n'avait pas la carte `Besoin d'aide ?`. | La frame mobile contient un bloc d'aide sous le formulaire, absent en desktop. | Ajout d'une carte d'aide visible seulement en mobile. | "Le contenu secondaire peut etre specifique au mobile quand il rassure l'utilisateur dans un parcours court." |
| La page reinitialisation n'avait pas de frame desktop separee retrouvee. | Figma liste clairement le forgot password, mais pas une frame desktop dediee au formulaire de nouveau mot de passe. | Harmonisation avec la meme structure visuelle que le forgot password, sans inventer un nouveau style. | "Quand une frame manque, je reutilise le composant Figma le plus proche au lieu de creer une nouvelle direction graphique." |
| La fidelite Figma devait etre documentee. | Les captures seules ne suffisent pas pour expliquer la demarche. | Ajout de `design-qa.md` avec source Figma, capture locale, points verifies et resultat final. | "Je garde une trace de verification pour justifier mes choix devant le jury." |

## Echecs techniques transverses

### Connexion MariaDB root refusee

- Symptome : `Access denied for user 'root'@'localhost'`.
- Cause : le mot de passe local n'etait pas vide.
- Solution : utiliser `mysql -uroot -proot ...`, conforme au `.env`.
- A retenir : toujours verifier `.env` avant d'accuser le code PHP.

### Mauvaise commande PHP

- Symptome : `php -1 config/routes.php` renvoie une erreur d'option.
- Cause : confusion entre le chiffre `1` et la lettre `l`.
- Solution : utiliser `php -l config/routes.php` ou `composer check`.
- A retenir : `php -l` sert a verifier la syntaxe d'un fichier PHP.

### Erreur de parsing dans `MenuModel`

- Symptome : `unexpected token "*", expecting "function"`.
- Cause : commentaire PHPDoc mal ferme ou morceau de commentaire laisse dans la classe.
- Solution : nettoyer le bloc `/** ... */` et relancer `composer check`.
- A retenir : une petite erreur de commentaire peut casser tout le fichier PHP.

### SQL duplique dans le modele menu

- Symptome : methode difficile a lire, avec deux blocs SQL identiques.
- Cause : copier-coller pendant l'ajout de filtres.
- Solution : garder un seul bloc SQL clair et construire les filtres proprement.
- A retenir : apres un copier-coller, relire la methode entiere.

### Tests HTTP sans `curl`

- Symptome : `command not found: curl`.
- Cause : l'outil `curl` n'est pas disponible dans ce terminal.
- Solution : utiliser PHP `get_headers` pour tester les routes locales.
- A retenir : le test compte plus que l'outil utilise.

### Donnees Figma et base pas encore parfaitement alignees

- Symptome : titres ou contenus de menus differents de la maquette.
- Cause : la maquette continue d'evoluer pendant le codage.
- Solution : coder la structure puis faire une passe finale de synchronisation des contenus.
- A retenir : separer le developpement fonctionnel et le remplissage editorial.

## Checklist quand une erreur arrive

```text
Erreur visuelle -> regarder la vue puis le CSS.
Erreur page 404 -> regarder la route.
Erreur page blanche ou parse error -> lancer composer check.
Erreur SQL -> tester la requete dans MySQL.
Erreur de donnees manquantes -> regarder le modele.
Erreur de droit -> regarder les middlewares et la session.
Erreur apres formulaire -> verifier CSRF, validation, redirection et insertion base.
```
