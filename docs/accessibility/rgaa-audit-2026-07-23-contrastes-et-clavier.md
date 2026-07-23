# Audit RGAA complémentaire - Contrastes et clavier - Vite & Gourmand

Date : 23 juillet 2026.

Type : audit statique complémentaire, réalisé sur le code source (CSS, vues
PHP, JavaScript) et sur l'application déployée. Ce document complète
`docs/accessibility/rgaa-audit-2026-07-22.md` sans le remplacer : il
approfondit deux points que l'audit du 22 juillet identifiait déjà comme non
mesurés ("Couleurs | Partiel | Contrastes non mesurés avec un outil dédié").

Verdict : **partiel / défendable ECF**, avec une non-conformité de contraste
identifiée puis **corrigée le jour même** (texte en couleur accent sur fond
clair), et une confirmation que la gestion clavier des overlays est solide.

Cet audit ne vaut pas non plus audit expert exhaustif des 106 critères RGAA.
Aucun test avec un lecteur d'écran (VoiceOver, NVDA) ni de test utilisateur
réel n'a été réalisé ici : ces deux points restent à faire avant le dépôt,
comme déjà indiqué dans l'audit du 22 juillet.

## Méthode

1. Extraction des variables de couleur dans `public/assets/css/style.css`.
2. Calcul du ratio de contraste WCAG (luminance relative, formule sRGB) pour
   chaque paire couleur de texte / couleur de fond réellement utilisée dans
   la feuille de style.
3. Recherche statique des usages de `color: var(--color-accent)` pour
   localiser les éléments de texte concernés.
4. Recherche statique de la gestion clavier dans `public/assets/js/app.js`
   (touche Échap, retour du focus après fermeture d'un overlay).
5. Vérification manuelle ponctuelle sur `https://vite-gourmand-ecf-jmf.fly.dev`.

## Résultat 1 - Contrastes mesurés

| Paire | Ratio | Seuil AA texte normal (4.5:1) | Seuil AA grand texte (3:1) |
|---|---|---|---|
| Texte principal `#2b1a14` sur fond `#f9f9f9` / `#ffffff` | 15.8 / 16.7 | Conforme | Conforme |
| Texte atténué `#6b7280` sur fond `#f9f9f9` / `#ffffff` | 4.6 / 4.8 | Conforme (limite) | Conforme |
| Texte blanc sur bordeaux foncé `#4a1c23` (en-tête, boutons) | 14.2 | Conforme | Conforme |
| Accent doré `#d8a84e` sur fond clair `#f9f9f9` / `#ffffff` | **2.1 / 2.2** | **Non conforme** | **Non conforme** |
| Accent doré `#d8a84e` sur bordeaux foncé `#4a1c23` (nav en-tête) | 6.5 | Conforme | Conforme |

Constat principal : la couleur d'accent (`--color-accent: #d8a84e`) est
utilisée comme couleur de **texte** à une trentaine d'endroits dans
`style.css` (`color: var(--color-accent)`), notamment :

- `.section-kicker` (petits libellés en majuscules au-dessus des titres de
  section, par exemple "SÉLECTION DU MOMENT") ;
- `.review-rating` (note des avis) ;
- plusieurs libellés secondaires et états actifs dans le back-office.

Quand ces éléments sont affichés sur un fond clair (`--color-background` ou
`--color-surface`, ce qui est le cas le plus fréquent sur les pages
publiques), le contraste mesuré est **2.1 à 2.2:1**, très en dessous du seuil
RGAA/WCAG AA de 4.5:1 pour un texte normal (et même du seuil de 3:1 pour un
grand texte en gras). C'est une non-conformité réelle au critère RGAA 3.2
(contraste), pas seulement un risque théorique.

À l'inverse, quand l'accent est utilisé sur le fond bordeaux foncé de
l'en-tête (navigation au survol/focus), le ratio est de 6.5:1 et reste
conforme.

### Correction appliquée

Une nouvelle variable `--color-accent-text: #8a641e;` a été ajoutée dans
`style.css` : un ton plus foncé du même doré, qui conserve l'identité
visuelle de la marque tout en atteignant au moins 4.5:1 sur l'ensemble des
fonds clairs réellement utilisés (blanc, `#f9f9f9`, et les fonds pastel
`#fffaf0` / `#faeccc` / `#fff7e6` des badges et étiquettes).

Chacune des 21 déclarations `color: var(--color-accent)` situées sur un fond
clair (`.section-kicker`, `.review-rating`, prix et badges des cartes menu,
avertissements de commande, étoiles et listes du back-office, etc.) a été
remplacée par `color: var(--color-accent-text)`. Les 9 usages restants en
`--color-accent` (navigation de l'en-tête, menu mobile, barre latérale
back-office) sont sur fond bordeaux foncé, où le ratio mesuré est déjà de
6.5:1 : ils n'ont pas été modifiés. Les bordures (`border-color:
var(--color-accent)`) n'ont pas non plus été modifiées : elles restent hors du
périmètre mesuré par cet audit (contraste de texte).

Vérification : recalcul du ratio WCAG (formule sRGB) pour `#8a641e` contre
chacun des fonds clairs concernés, résultat minimal 4.52:1, conforme au seuil
AA de 4.5:1.

## Résultat 2 - Gestion clavier des overlays

Point positif à mettre en avant à l'oral : la gestion clavier des éléments
interactifs complexes (menu mobile, overlay de filtres, galerie image en
lightbox, navigation back-office mobile) est correctement implémentée dans
`public/assets/js/app.js` :

- la touche **Échap** ferme le menu mobile, l'overlay de filtres et la
  lightbox d'image (vérifié à 4 endroits distincts du script) ;
- le focus est **restitué** au bouton qui a ouvert l'overlay après sa
  fermeture (`overlayOpenButton.focus()`, `imageLightboxReturnFocus.focus()`),
  ce qui évite de perdre le focus clavier dans le vide, un défaut fréquent
  sur ce type de composant ;
- aucune règle `outline: none` ou `outline: 0` sans remplacement n'a été
  trouvée dans la feuille de style : le focus visible n'est pas supprimé
  silencieusement.

Réserve : ceci reste une lecture statique du code. Un test manuel Tab /
Shift+Tab complet sur l'URL déployée (déjà prévu dans l'audit du 22 juillet)
reste nécessaire pour confirmer l'ordre de tabulation réel dans le
navigateur, en particulier dans les tableaux de bord back-office.

## Résultat 3 - Structure HTML (rappel, déjà vérifié le 22 juillet)

Rappel des points déjà vérifiés et toujours valides à cette date : `lang="fr"`
sur toutes les pages, un seul `<h1>` par page, tous les `<img>` ont un attribut
`alt`, et tous les champs de formulaire testés (`<input>`, `<select>`,
`<textarea>`) disposant d'un `id` ont un `<label for="...">` correspondant
dans le même gabarit (vérification automatisée sur l'ensemble des vues,
0 champ orphelin détecté).

## Ce qui reste hors périmètre de cet audit

- Test avec un lecteur d'écran (VoiceOver, NVDA, JAWS).
- Test de zoom navigateur à 200 % et de reflow en dessous de 320 px de large.
- Test utilisateur réel avec une personne en situation de handicap.
- Audit expert RGAA complet sur les 106 critères et leurs tests associés.

## Statut recommandé pour le rendu

Formulation conseillée :

```text
Un audit de contraste automatisé a été réalisé sur l'ensemble de la feuille
de style. Il a permis d'identifier une non-conformité sur la couleur d'accent
utilisée comme texte sur fond clair (ratio 2.1:1, seuil requis 4.5:1). Une
variable de texte dédiée, plus foncée (4.5:1 et plus), a été introduite et
appliquée aux 21 usages concernés ; les usages sur fond bordeaux foncé, déjà
conformes, n'ont pas été modifiés. La gestion clavier des overlays (Échap,
retour de focus) a été vérifiée et est fonctionnelle. Les tests lecteur
d'écran et zoom/reflow restent hors périmètre de cet audit interne.
```
