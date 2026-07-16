# Design QA - Partie publique

Date : 2026-07-14.

## Sources

- Figma file : `sMkvVuvOyBkMvlTIsq2eCY`.
- Accueil desktop : node `66:5`.
- Liste menus desktop : node `460:19068`.
- Image propre du hero menus : node `559:112050`.
- Export public desktop : `Maquettes/export pdf/Espace-publique-bureau.pdf`.
- Export public mobile : `Maquettes/export pdf/Espace-publique-mobile.pdf`.

## Etat verifie

- `composer check` passe.
- `node --check public/assets/js/app.js` passe.
- Equilibre CSS : `677 open / 677 close`.
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

## Ecarts / blocages connus

- La frame Figma `460:19068` contient encore un ancien hero avec texte incruste dans l'image. Le code utilise volontairement une image nettoyee + texte HTML pour respecter la derniere demande utilisateur.
- L'overlay filtres desktop et mobile est comparee aux frames Figma connues. Il reste a refaire une passe visuelle utilisateur dans le navigateur integre pour valider le ressenti reel.
- Les details menu sont branches avec leurs textes et images Figma, mais une comparaison visuelle navigateur doit encore confirmer les espacements exacts.
- La page `/mot-de-passe/reinitialisation` doit encore etre testee avec token invalide puis token valide.
- La page `/mot-de-passe/oublie` doit encore etre testee avec un compte existant pour verifier le lien de demonstration.
- Une inscription valide doit encore etre testee avec une strategie de compte de test.
- Le login valide doit encore etre teste avec un compte de test connu.
- Le navigateur integre Codex bloque toujours la capture automatisee avec l'erreur technique `Cannot redefine property: process`. Des captures locales Playwright ont ete utilisees en fallback pour avancer, mais une passe finale dans le navigateur utilisateur reste necessaire avant de declarer la fidelite visuelle a 100%.

final result: blocked
