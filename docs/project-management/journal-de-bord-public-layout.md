# Journal de bord - integration layout public

Date : 14 juillet 2026.

## Contexte

La session a servi a transformer les premieres user stories publiques du MVP en
pages reellement navigables, puis a rapprocher ces pages des maquettes Figma.

L'objectif n'etait plus seulement d'avoir des routes qui fonctionnent, mais
d'obtenir une base publique assez solide pour lancer les tests, preparer le
deploiement et expliquer la demarche devant le jury.

## Travail realise

### Partie publique

- reprise du header public avec logo, lien actif et effet transparent au scroll ;
- ajout du menu mobile public dans le layout global ;
- ajout du bouton global `Retour en haut` ;
- reprise du footer commun avec colonnes horaires, contact et informations ;
- refonte de la page accueil selon les sections Figma ;
- refonte de la liste des menus avec filtres visuels ;
- ajout des filtres rapides dynamiques sans rechargement ;
- preparation de l'overlay `Tous les filtres` ;
- refonte des pages detail de menus avec images, galerie, conditions et CTA ;
- harmonisation des pages contact, connexion, inscription et mot de passe oublie ;
- ajout des pages legales navigables depuis le footer.

### Partie gestion de projet

- mise a jour du rapport d'implementation des user stories ;
- ajout du journal des echecs et solutions ;
- ajout de l'inventaire public Figma / Code ;
- creation de cette entree de journal de bord ;
- preparation du premier commit des user stories codees.

## Methode suivie

Le cycle de travail a ete le meme pour chaque user story.

```text
1. Identifier la route.
2. Verifier ou creer le controleur.
3. Lire ou creer le modele si la page utilise la base.
4. Coder la vue.
5. Ajuster le layout et le CSS.
6. Ajouter le JavaScript seulement si une interaction est necessaire.
7. Tester dans le navigateur.
8. Relancer composer check.
9. Documenter l'ecart, l'erreur ou la decision.
```

## User stories concernees

| US | Avancement |
|---|---|
| US-001 Accueil | Codee, responsive, encore a comparer finement a Figma mobile. |
| US-002 Liste menus | Codee avec cartes visuelles et navigation vers les details. |
| US-003 Filtres menus | Codee sans rechargement de page. |
| US-004 Detail menu | Codee pour les 6 menus publics. |
| US-005 Contact | Codee et testee avec insertion en base. |
| US-006 Inscription | Codee avec role client impose. |
| US-007 Connexion | Codee avec session et role serveur. |
| US-008 Mot de passe oublie | Codee en mode local avec token de reinitialisation. |
| US-026 Accessibilite | Bases en place, audit final restant. |
| US-027 Securite roles | Middlewares et routes protegees. |

## Echecs rencontres et solutions

| Probleme | Solution |
|---|---|
| Les pages fonctionnaient mais ne suivaient pas assez Figma. | Separation entre validation fonctionnelle et passe d'integration graphique. |
| Les images Figma ne correspondaient pas toujours aux donnees SQL. | Ajout d'un service `MenuPresentation` pour adapter l'affichage sans casser la base. |
| Le header desktop et mobile demandaient deux comportements differents. | Layout commun, deux logos, classes body et JavaScript de scroll. |
| Les filtres en select/input ne correspondaient plus a la maquette. | Remplacement par des pastilles rapides et preparation d'une overlay avancee. |
| Le bouton `Disponible` restait visuellement selectionne. | Suppression des styles fixes, etat actif gere uniquement par `.is-active`. |
| Le footer n'avait pas le bon composant. | Reprise du footer dans le layout global pour toutes les pages. |
| Les pages legales pouvaient renvoyer une impression de placeholder. | Ajout de contenus publics simples en `200 OK`. |
| Les captures et le navigateur ne montraient pas toujours la meme chose. | Verification combinee : navigateur, captures, tests responsive et journal d'ecarts. |

## Tests effectues

Commandes :

```text
composer check
node --check public/assets/js/app.js
```

Routes verifiees :

```text
/
/menus
/menus/1
/menus/2
/menus/3
/menus/4
/menus/5
/menus/6
/contact
/connexion
/inscription
/mot-de-passe/oublie
/mot-de-passe/reinitialisation
/mentions-legales
/cgv
/confidentialite
```

Points verifies :

- pas d'erreur de syntaxe PHP ;
- pas d'erreur de syntaxe JavaScript ;
- les routes publiques repondent ;
- le formulaire contact garde le CSRF ;
- les filtres rapides changent les resultats sans rechargement ;
- les pages publiques restent navigables en mobile.

## Decision de gestion de projet

Le premier commit regroupe les premieres user stories publiques et MVP deja
codees. Ce choix est volontaire : les routes, controleurs, modeles, vues et
assets sont lies entre eux. Les separer maintenant risquerait de creer un commit
incomplet qui ne fonctionnerait pas apres checkout.

Pour la suite, les prochaines features pourront etre decoupees plus finement :

- une branche ou un commit par correction Figma ;
- une branche ou un commit pour l'overlay filtres finale ;
- une branche ou un commit pour l'audit responsive ;
- une branche ou un commit pour le deploiement.

## A expliquer au jury

Phrase possible :

```text
J'ai commence par livrer un socle public fonctionnel, puis j'ai fait une passe
Figma pour rapprocher l'interface des maquettes. J'ai garde une trace des ecarts,
des erreurs et des solutions dans mon journal de bord, afin de montrer que le
projet a ete pilote et teste progressivement.
```

## Prochaines actions

- finaliser la comparaison visuelle stricte avec Figma ;
- stabiliser les espacements mobiles de l'accueil et de la liste menus ;
- valider l'overlay `Tous les filtres` quand la maquette sera terminee ;
- lancer un deploiement test ;
- refaire une recette fonctionnelle sur l'URL de preproduction.
