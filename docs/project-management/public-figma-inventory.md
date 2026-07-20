# Inventaire public Figma / Code

Date de suivi : 2026-07-14.

Objectif : relier les pages publiques du projet aux maquettes Figma, verifier les user stories visiteur et garder une trace claire pour l'oral.

## Sources de reference

| Source | Emplacement | Usage |
| --- | --- | --- |
| Figma principal | `sMkvVuvOyBkMvlTIsq2eCY` | Source officielle des frames et composants. |
| Export public bureau | `Maquettes/export pdf/Espace-publique-bureau.pdf` | Reference globale desktop quand Figma est trop lourd. |
| Export public mobile | `Maquettes/export pdf/Espace-publique-mobile.pdf` | Reference globale responsive mobile. |
| Capture Figma menus | `tmp/figma-reference/menu-index-desktop.png` | Capture de controle de la page liste menus desktop. |
| Capture Figma detail Noel | `tmp/figma-reference/menu-detail-noel-desktop.png` | Reference de controle pour `/menus/1`. |
| Captures Figma details menus | `tmp/figma-reference/menu-detail-{cocktail,vege,terre-mer,saint-valentin,paques}-desktop.png` | References de controle pour `/menus/2` a `/menus/6`. |
| Capture Figma contact | `tmp/figma-reference/contact-desktop.png` | Reference de controle pour `/contact`. |
| Capture Figma login | `tmp/figma-reference/login-desktop.png` | Reference de controle pour `/connexion`. |
| Capture Figma inscription | `tmp/figma-reference/register-desktop.png` | Reference de controle pour `/inscription`. |
| Capture Figma mot de passe oublie | `tmp/figma-reference/forgot-password-desktop.png` | Reference de controle pour `/mot-de-passe/oublie`. |
| Captures Figma pages legales | `tmp/figma-reference/legal-notice-desktop.png`, `tmp/figma-reference/legal-terms-desktop.png`, `tmp/figma-reference/legal-notice-mobile.png`, `tmp/figma-reference/legal-terms-mobile.png` | References de controle pour Mentions legales et CGV. |
| Capture Figma overlay filtres | `tmp/figma-reference/overlay-filters-desktop.png` | Reference de controle pour l'overlay `Tous les filtres`. |
| Captures Figma overlay filtres mobile | `tmp/figma-reference/overlay-filters-mobile-top.png`, `tmp/figma-reference/overlay-filters-mobile-bottom.png` | References de controle pour les deux etats mobiles de l'overlay filtres. |
| Capture Figma menu mobile public | `tmp/figma-reference/mobile-menu-public.png` | Reference de controle pour l'overlay de navigation mobile. |
| Capture locale overlay filtres | `tmp/figma-reference/local-overlay-filters-desktop.png` | Capture de verification locale apres integration. |
| Captures locales mobiles | `tmp/figma-reference/local-overlay-filters-mobile-top.png`, `tmp/figma-reference/local-overlay-filters-mobile-bottom.png`, `tmp/figma-reference/local-mobile-menu-public.png` | Captures de verification locale mobile. |
| Captures locales formulaires mobiles | `tmp/figma-reference/contact-local-mobile-final-2.png`, `tmp/figma-reference/login-local-mobile-final-2.png`, `tmp/figma-reference/register-local-mobile-final.png`, `tmp/figma-reference/forgot-local-mobile-final.png`, `tmp/figma-reference/legal-terms-local-mobile-final-2.png` | Captures de controle apres corrections responsive. |
| Image hero menus Figma | `public/images/menus/hero-menu-clean.png` | Ancien fond Figma nettoye, sans texte incruste dans l'image. |
| Images detail Noel Figma | `public/images/menu-details/noel/*.png` | Grande image et miniatures exportees depuis `Détail menu - Noël Tradition - Desktop`. |
| Images details menus Figma | `public/images/menu-details/{cocktail,vege,terre-mer,saint-valentin,paques}/*.png` | Grande image et miniatures decoupees depuis les frames detail menu Figma. |

## Pages publiques branchees

| Route | Vue / controleur | Source Figma connue | Etat actuel | Prochaine verification |
| --- | --- | --- | --- | --- |
| `/` | `HomeController::index`, `home/index.php` | `66:5` Home - Desktop | Structure principale codee : hero, qui sommes-nous, engagements, menus, avis, footer. | Comparer desktop et mobile avec les exports Figma. |
| `/menus` | `MenuController::index`, `menus/index.php` | `460:19068` Liste menus - Index - Desktop, image `559:112050` | Liste, filtres rapides, cartes et CTA branches. Fond hero remplace par l'image Figma propre sans texte incruste. | Verifier l'ecart entre la decision texte HTML et la frame Figma contenant l'ancien texte exporte. |
| `/menus/{id}` | `MenuController::show`, `menus/show.php` | Details desktop `599:77607`, `599:81335`, `599:85055`, `599:96986`, `599:100713`, `599:104438` | Les 6 details publics sont branches avec textes, statuts detail, images, galerie, composition, conditions et actions Figma. | Comparer les espacements exacts en navigateur desktop et mobile. |
| `/contact` | `ContactController::create/store`, `contact/create.php` | `470:37959` Contact - Index - Desktop, `470:38137` mobile | Page reprise en deux cartes Figma. Responsive mobile ajuste : 4 champs visibles, coordonnees compactes, bouton court. Test POST avec CSRF OK, insertion en base OK. | Refaire une passe visuelle dans le navigateur utilisateur. |
| `/connexion` | `AuthController::login/storeLogin`, `auth/login.php` | `466:31662` Auth - Login - Desktop, `467:35264` mobile | Page reprise : intro, formulaire, selecteur role, carte acces interne. Ordre mobile corrige. POST invalide teste OK. | Verifier un login valide avec un compte de test. |
| `/inscription` | `AuthController::register/storeRegister`, `auth/register.php` | `470:38002` Auth - Register - Desktop, `470:38170` mobile | Page reprise : carte formulaire desktop, encadre information mobile simplifie. POST invalide teste OK. | Tester une creation valide avec strategie de compte de test. |
| `/mot-de-passe/oublie` | `AuthController::forgotPassword/sendResetLink`, `auth/forgot-password.php` | `615:126171` Auth - Forgot Password - Desktop, `615:129793` mobile | Page reprise sur la frame desktop et carte aide ajoutee en mobile. POST email invalide teste OK. | Tester generation de lien avec un compte existant. |
| `/mot-de-passe/reinitialisation` | `AuthController::resetPassword/updatePassword`, `auth/reset-password.php` | Pas de frame desktop separee identifiee | Page harmonisee avec la frame mot de passe oublie. | Tester token invalide et token valide. |
| `/mentions-legales` | `PlaceholderController::legalNotice` | `470:38112` desktop, `470:38261` mobile | Page legale Figma codee : titre, intro, carte blanche, point cle, footer commun. | Refaire une passe visuelle navigateur utilisateur. |
| `/cgv` | `PlaceholderController::terms` | `470:38087` desktop, `470:38242` mobile | Page CGV Figma codee : contenu numerote, carte blanche, point cle, footer commun. | Refaire une passe visuelle navigateur utilisateur. |
| `/confidentialite` | `PlaceholderController::privacy` | Footer Figma `270:8730` | Page confidentialite harmonisee sur le composant legal Figma, faute de frame dediee retrouvee. | Completer si une frame confidentialite existe dans Figma. |

## Overlays publics

| Overlay | Declencheur code | Source Figma connue | Etat actuel | Prochaine action |
| --- | --- | --- | --- | --- |
| Tous les filtres menus | Bouton `Tous les filtres` sur `/menus` | Desktop `614:89721`, mobile `614:89728`, mobile scroll bas `614:93447` | Overlay desktop et mobile alignee avec les frames Figma. Filtrage teste sans rechargement. | Tester manuellement dans le navigateur utilisateur. |
| Menu mobile public | Bouton burger du header mobile | `612:89723` Overlay - Menu mobile - Public - 390 | Overlay plein ecran branchee dans le layout global, fermeture bouton/Echap, liens publics et CTA inscription. | Tester manuellement dans le navigateur utilisateur. |

## Decisions documentees

| Sujet | Decision | Raison |
| --- | --- | --- |
| Hero liste menus | Utiliser l'image Figma propre `559:112050` comme fond et garder le texte en HTML. | Le texte incruste dans l'ancienne image n'etait pas responsive et ne correspondait plus a la direction choisie. |
| Filtres rapides | Filtrage en JavaScript sans rechargement de page. | L'enonce demande une mise a jour dynamique des resultats. |
| Footer | Meme footer public sur les pages. | Le composant Figma est commun et les liens doivent etre navigables. |
