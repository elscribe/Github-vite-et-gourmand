# Design QA - Partie publique et espace client

Date : 2026-07-15.

## Sources

- Figma file : `sMkvVuvOyBkMvlTIsq2eCY`.
- Accueil desktop : node `66:5`.
- Liste menus desktop : node `460:19068`.
- Image propre du hero menus : node `559:112050`.
- Dashboard client mobile : node `407:34611`.
- Profil client desktop : node `639:139833`.
- Mon compte client desktop : node `649:428011`.
- Modification profil client desktop : node `649:430364`.
- Suivi commande : node `639:138227`.
- Avis client desktop : node `639:143495`.
- Export public desktop : `Maquettes/export pdf/Espace-publique-bureau.pdf`.
- Export public mobile : `Maquettes/export pdf/Espace-publique-mobile.pdf`.

## Etat verifie

- `composer check` passe.
- `node --check public/assets/js/app.js` passe.
- Equilibre CSS : `929 open / 929 close`.
- Routes publiques testees en `200 OK` : `/`, `/menus`, `/menus/1`, `/contact`, `/connexion`, `/inscription`, `/mot-de-passe/oublie`, `/mot-de-passe/reinitialisation`, `/mentions-legales`, `/cgv`, `/confidentialite`.
- Routes detail menu testees en `200 OK` avec contenu Figma attendu : `/menus/1`, `/menus/2`, `/menus/3`, `/menus/4`, `/menus/5`, `/menus/6`.
- Les liens footer `/mentions-legales`, `/cgv` et `/confidentialite` repondent en `200 OK`.
- Le fond du hero de la page menus utilise maintenant l'image nettoyee `public/images/menus/hero-menu-clean.png`, sans texte incruste, avec un titre HTML reduit et un accent dore plus discret.
- Le header mobile utilise maintenant l'asset compact `public/images/brand/logo-mobile-vg.png`, avec une hauteur de 60px, pour correspondre aux frames publiques mobiles.
- Les filtres rapides menus et accueil sont geres en JavaScript sans rechargement de page.
- Le bouton `Tous les filtres` de `/menus` ouvre une overlay alignee sur la frame Figma `614:89721` avec budget, prix, theme, regime, personnes, disponibilite et allergenes.
- Test navigateur automatise overlay : ouverture OK, `Moins de 100 €` affiche uniquement `Menu Saint-Valentin`, `Reinitialiser` remet les 6 menus, fermeture OK, aucune erreur JavaScript.
- Overlay filtres mobile comparee aux frames Figma `614:89728` et `614:93447` : bottom-sheet a 84px du haut, actions fixes, zone centrale scrollable et resume de scroll.
- Menu mobile public compare a la frame Figma `612:89723` : overlay plein ecran, liens publics, CTA inscription, liens legaux et horaires.
- Scan mobile 390px effectue sur `/`, `/menus`, `/menus/1`, `/menus/6`, `/contact`, `/connexion`, `/inscription`, `/mot-de-passe/oublie`, `/mot-de-passe/reinitialisation`, `/mentions-legales`, `/cgv`, `/confidentialite` : aucun debordement horizontal global et aucune erreur JavaScript constatee sur les captures locales.
- `/menus/1` a `/menus/6` reprennent maintenant la structure Figma des details menu desktop : fil d'Ariane, image principale, galerie, description, composition, informations, conditions et actions.
- Les images detail Figma ont ete exportees puis decoupees en assets publics : `public/images/menu-details/{noel,cocktail,vege,terre-mer,saint-valentin,paques}/`.
- `/contact` reprend la structure Figma desktop : bloc coordonnees et formulaire en deux colonnes.
- `/contact` mobile reprend la version simplifiee Figma : champ telephone masque, coordonnees sans libelles, titre `Message`, bouton `Envoyer`.
- Test POST contact effectue avec token CSRF : redirection `302` vers `/contact?success=1` et insertion du message `Test QA contact` en base.
- `/connexion` reprend la structure Figma desktop : colonne explicative, carte roles, formulaire et liens secondaires.
- `/connexion` mobile respecte l'ordre Figma : intro, formulaire, puis carte role. Les boutons Client/Employe/Admin restent cote a cote.
- Test POST login invalide effectue avec token CSRF : retour `200` et message `Identifiants invalides.` visible.
- `/inscription` reprend la structure Figma desktop : hero, carte informations personnelles, carte benefices.
- `/inscription` mobile garde seulement l'encadre information, sans la liste benefices desktop.
- Test POST inscription invalide effectue avec token CSRF : retour `200` et erreurs de validation visibles.
- `/mot-de-passe/oublie` reprend la structure Figma desktop : intro a gauche, carte reset a droite.
- `/mot-de-passe/oublie` mobile affiche la carte `Besoin d'aide ?` visible dans la frame Figma.
- `/mot-de-passe/reinitialisation` est harmonisee avec la meme structure visuelle.
- Test POST mot de passe oublie invalide effectue avec token CSRF : retour `200` et erreur email visible.
- `/mentions-legales` et `/cgv` reprennent le composant legal Figma : kicker, titre Playfair, intro, carte blanche bordee et contenu public finalise.

## Etat verifie - espace client prive

- `composer check` passe apres refonte des pages client.
- `node --check public/assets/js/app.js` passe apres ajout de l'apercu prix et de la notation par etoiles.
- Equilibre CSS verifie : `989 open / 989 close`.
- Connexion locale testee avec `claire.martin@example.test` / `ClientVite2026!` : redirection `302` apres login, session client utilisable en HTTP local.
- Routes client testees en `200 OK` avec ce compte : `/mon-compte`, `/mon-compte/modifier`, `/commandes`, `/commandes/creation`, `/commandes/9`, `/commandes/9/modifier`.
- `/mon-compte` est realigne sur la frame Figma `649:428011` : conteneur desktop sans decalage lateral, sidebar gauche, carte commande en cours, informations client, historique tableau et bloc avis.
- `/mon-compte/modifier` est maintenant une page dediee alignee sur la frame Figma `649:430364` : formulaire de profil, alertes succes/erreur, preference de contact et carte securite.
- `/commandes` utilise une liste de cartes client responsive au lieu d'un rendu type tableau.
- `/commandes/9`, statut `en_attente`, affiche la timeline, le recapitulatif, les actions `Modifier la commande` et `Annuler la commande`.
- `/commandes/1`, commande terminee avec avis deja existant, n'affiche pas les actions de modification/annulation client.
- `/commandes/creation` et `/commandes/9/modifier` exposent les attributs `data-*` necessaires a l'apercu prix dynamique : menu, remise, livraison et total estime.
- Le champ distance de livraison est masque quand la ville est `Bordeaux`, puis redevient visible avec une aide explicative hors Bordeaux. Le MVP ne fait pas encore de geolocalisation automatique : le client indique une distance approximative, l'equipe la verifie avant validation.
- Le formulaire commande separe maintenant l'adresse en `Adresse`, `Code postal` et `Ville`, avec sauvegarde du code postal dans `commandes.code_postal_livraison`.
- Le formulaire commande contient maintenant un champ optionnel `Commentaire pour l'équipe`, sauvegarde dans `commandes.commentaire_client` et affichage dans le detail client et la liste employe.
- `/commandes/creation/{menuId}` affiche maintenant `Validation de votre commande`, une accroche orientee demande client, les conditions importantes du menu selectionne et un recapitulatif enrichi : menu, description, prix estime par personne, nombre de personnes, sous-total, remise, livraison, total, minimum requis et stock disponible.
- Le header global signale maintenant `Mon espace` comme lien actif sur `/mon-compte`, `/commandes` et `/avis`.
- Les breakpoints client sont ajoutes : cartes en une colonne sur mobile, boutons pleine largeur, sidebars non sticky et timeline lisible.
- La route `/avis/creation/1` renvoie `404`, ce qui est attendu car la commande `1` possede deja un avis.
- Une commande terminee temporaire sans avis a ete creee puis supprimee pour QA : `/avis/creation/{id_temporaire}` a repondu `200 OK` et affichait `Laisser un avis gourmand`, `Prestation dégustée`, le badge `Livrée`, la notation, le commentaire et `Envoyer mon avis`.
- `/commandes` affiche maintenant un bouton direct `Laisser un avis` uniquement pour une commande terminee qui n'a pas encore d'avis.
- Captures Figma recuperees pour comparaison client : `tmp/client-visual-qa/figma-dashboard-mobile.png`, `tmp/client-visual-qa/figma-profile-desktop.png`, `tmp/client-visual-qa/figma-order-tracking.png`, `tmp/client-visual-qa/figma-review-desktop.png`.
- Captures locales WebKit generees en mobile et desktop pour `/mon-compte`, `/mon-compte/modifier`, `/commandes`, `/commandes/creation`, `/commandes/9`, `/commandes/9/modifier`, `/commandes/21` et `/avis/creation/{id_eligible}`.
- Le fond des pages client est maintenant beige continu (`#f2e8d4`), comme les frames Figma privees, au lieu d'un fond blanc apres le hero.
- `/mon-compte` mobile ne conserve plus la sidebar desktop : le contenu passe en pleine largeur, les boutons deviennent empiles et l'historique passe en cartes lisibles.
- Retours client verifies : `/commandes` revient vers `/mon-compte`, `/commandes/creation` revient vers `/commandes`, `/commandes/{id}` revient vers `/commandes`, `/commandes/{id}/modifier` et `/avis/creation/{id}` reviennent vers la commande concernee.
- `/mon-compte` contient maintenant un lien clair `Voir toutes mes commandes` vers `/commandes` dans le bloc historique.
- La sidebar de `/mon-compte` a ete retravaillee : carte identite client, icones, compteurs, separation de la deconnexion et etat actif synchronise avec l'ancre courante (`#mes-commandes`, `#mes-informations`, `#mes-avis`).
- La carte `Mes informations` evite les chevauchements email/telephone : l'email prend toute la largeur utile et les valeurs longues peuvent revenir a la ligne.
- Le premier champ du formulaire de commande a une vraie respiration sous le titre `Informations de prestation`.
- La timeline client respecte le code couleur Figma : etapes terminees en vert, etape courante en dore, etapes futures en beige.
- Sur mobile, la carte `Commande en cours` garde le titre et le badge de statut sur une meme ligne, comme la frame dashboard client.
- Le bloc `Donnez votre avis` reprend le traitement Figma : icone etoile, bordure doree et fond creme.
- La page `/commandes/9/modifier` initialise maintenant correctement l'estimation : le script cherche la carte recapitulatif dans le layout parent et non dans le formulaire seul.
- Une commande terminee temporaire `25` sans avis a ete creee pour tester la vraie route `/avis/creation/25` en `200 OK`, puis supprimee de `commandes` et `commande_statuts`.

## Ecarts / blocages connus

- La frame Figma `460:19068` contient encore un ancien hero avec texte incruste dans l'image. Le code utilise volontairement une image nettoyee + texte HTML pour respecter la derniere demande utilisateur.
- L'overlay filtres desktop et mobile est comparee aux frames Figma connues. Il reste a refaire une passe visuelle utilisateur dans le navigateur integre pour valider le ressenti reel.
- Les details menu sont branches avec leurs textes et images Figma, mais une comparaison visuelle navigateur doit encore confirmer les espacements exacts.
- La page `/mot-de-passe/reinitialisation` doit encore etre testee avec token invalide puis token valide.
- La page `/mot-de-passe/oublie` doit encore etre testee avec un compte existant pour verifier le lien de demonstration.
- Une inscription valide doit encore etre testee avec une strategie de compte de test.
- Le login valide doit encore etre teste avec un compte de test connu.
- La capture automatisee du navigateur de test a rencontre une erreur technique
  `Cannot redefine property: process`. Des captures locales Playwright ont ete
  utilisees en fallback pour avancer, mais une passe finale dans le navigateur
  utilisateur reste necessaire avant de declarer la fidelite visuelle a 100%.
