# Audit accessibilite RGAA - Vite & Gourmand

Date : 22 juillet 2026.

Type : audit interne de recette accessibilite sur l'application locale.

Version testee : application PHP locale sur `http://127.0.0.1:8001`, base SQL
locale et MongoDB local disponibles.

Verdict : **partiel solide / defendable ECF**. Les parcours principaux respectent
les bases attendues : langue de page, titres, H1, labels, textes alternatifs,
nom accessible des boutons/liens, associations ARIA, captions de tableaux,
navigation vers le contenu principal et preference `prefers-reduced-motion`.

Limite importante : cet audit **ne vaut pas audit expert exhaustif des 106
criteres RGAA**. Il constitue une trace de recette locale documentee pour le
jury et devra etre complete par une passe manuelle clavier/lecteur d'ecran sur
l'URL deployee avant rendu final.

## Sources RGAA utilisees

- RGAA, criteres et tests : <https://accessibilite.numerique.gouv.fr/methode/criteres-et-tests/>
- Kit d'audit RGAA : <https://accessibilite.numerique.gouv.fr/ressources/kit-audit/>
- Evaluation de conformite : <https://accessibilite.numerique.gouv.fr/obligations/evaluation-conformite/>
- Mentions et pages obligatoires : <https://accessibilite.numerique.gouv.fr/obligations/mentions-et-pages-obligatoires/>

Points retenus pour cette recette interne :

- echantillon representatif de pages ;
- methode de test communicable ;
- resultats associes a des criteres applicables ;
- absence de declaration "totalement conforme" sans audit exhaustif.

## Echantillon teste

| Role | Page | Route | Objectif |
|---|---|---|---|
| Visiteur | Accueil | `/` | Navigation publique, images, sections, avis, liens. |
| Visiteur | Catalogue menus | `/menus` | Filtres, cartes menus, images, CTA. |
| Visiteur | Detail menu | `/menus/6` | Galerie, composition, conditions, bouton commande. |
| Visiteur | Contact | `/contact` | Formulaire public et messages d'erreur. |
| Visiteur | Connexion | `/connexion` | Authentification. |
| Visiteur | Inscription | `/inscription` | Creation compte client. |
| Client | Mon compte | `/mon-compte` | Formulaire profil. |
| Client | Commandes | `/commandes` | Tableau de suivi. |
| Client | Creation commande | `/commandes/creation/6` | Formulaire de commande et apercu dynamique. |
| Employe | Tableau de bord | `/employe` | Navigation interne. |
| Employe | Commandes | `/employe/commandes` | Filtres, formulaires de statut, modification et annulation. |
| Employe | Avis | `/employe/avis` | Moderation des avis. |
| Admin | Tableau de bord | `/admin` | Navigation administration. |
| Admin | Employes | `/admin/employes` | Creation et activation/desactivation employe. |
| Admin | Statistiques | `/admin/statistiques` | Tableau de donnees, graphiques, filtres. |
| Admin | Menus / plats | `/admin/menus` | Formulaires complexes, selection, composition. |

## Methode de test

1. Lancement local de l'application :

```bash
APP_URL=http://127.0.0.1:8001 MAIL_MAILER=log php -S 127.0.0.1:8001 -t public
```

2. Connexion avec les comptes de demonstration :

| Role | Email |
|---|---|
| Client | `claire.martin@example.test` |
| Employe | `lucas.employee@vitegourmand.test` |
| Administrateur | `admin.jose@vitegourmand.test` |

3. Recuperation HTTP des pages de l'echantillon.
4. Controle structurel HTML via `DOMDocument` / `DOMXPath`.
5. Controle CSS par recherche statique des regles de focus et de reduction de
   mouvement.
6. Corrections appliquees, puis reexecution de la meme passe.

## Resultats automatises apres correction

```text
pages_tested=16
http_not_200=[]
lang_not_fr=[]
missing_title=[]
bad_h1_count=[]
missing_alt_total=0
controls_missing_labels_total=0
buttons_missing_names_total=0
links_missing_names_total=0
duplicate_ids=[]
aria_controls_missing=[]
tables_without_th=0
tables_without_caption=0
missing_skip_link=[]
missing_main_target=[]
css_focus_rules=48
css_prefers_reduced_motion=1
```

Interpretation :

- les 16 pages de l'echantillon repondent en `200` ;
- toutes les pages testees declarent `lang="fr"` ;
- toutes les pages testees ont un `<title>` et exactement un `<h1>` ;
- aucune image sans attribut `alt` n'a ete detectee ;
- aucun champ visible sans nom accessible n'a ete detecte ;
- aucun bouton ou lien sans nom accessible n'a ete detecte ;
- aucune cible `aria-controls` manquante n'a ete detectee ;
- les tableaux de l'echantillon ont des entetes `<th>` et une caption ;
- le lien d'evitement `Aller au contenu principal` pointe vers
  `#main-content` ;
- une regle `@media (prefers-reduced-motion: reduce)` est presente ;
- les styles contiennent des regles de focus visibles.

## Corrections appliquees pendant l'audit

| Fichier | Correction | Critere / risque vise |
|---|---|---|
| `app/Views/layouts/main.php` | Ajout du lien d'evitement `Aller au contenu principal`. | Navigation clavier, acces rapide au contenu. |
| `app/Views/layouts/main.php` | Ajout de `id="main-content"` sur le `<main>` public et back-office. | Cible fiable pour le lien d'evitement. |
| `public/assets/css/style.css` | Style visible au focus pour `.skip-link`. | Focus visible et navigation clavier. |
| `public/assets/css/style.css` | Ajout de `@media (prefers-reduced-motion: reduce)`. | Respect des preferences utilisateur sur les animations. |
| `app/Views/home/index.php` | Suppression du doublon `id="top"` sur la hero. | Robustesse HTML, ancres et technologies d'assistance. |
| `app/Views/orders/index.php` | Caption masquee sur le tableau des commandes. | Tableaux de donnees comprehensibles. |
| `app/Views/admin/statistics.php` | Caption masquee sur le tableau des statistiques. | Tableaux de donnees comprehensibles. |
| `app/Views/admin/dishes.php` | Caption masquee sur le tableau des plats. | Cohesion avec les autres tableaux back-office. |
| `app/Views/admin/schedules.php` | Caption masquee sur le tableau des horaires. | Cohesion avec les autres tableaux back-office. |

## Controle par thematique RGAA

| Thematique | Etat | Preuve locale | Reserve |
|---|---|---|---|
| Images | Conforme sur echantillon | `missing_alt_total=0`; images decoratives avec `alt=""`, images informatives avec alt. | Pertinence fine des textes alternatifs a relire visuellement. |
| Cadres | Non applicable observe | Aucun iframe detecte dans l'echantillon. | Recontroler si un service tiers est ajoute. |
| Couleurs | Partiel | Focus visible, information importante souvent doublee par texte. | Contrastes non mesures avec un outil dedie ; passe manuelle a faire. |
| Multimedia | Non applicable observe | Pas de video/audio dans l'echantillon. | Recontroler si media ajoute. |
| Tableaux | Conforme sur echantillon apres correction | `tables_without_th=0`, `tables_without_caption=0`. | Les tableaux complexes doivent etre retestes si colonnes ajoutees. |
| Liens | Conforme sur echantillon | `links_missing_names_total=0`. | Pertinence contextuelle des libelles a verifier manuellement. |
| Scripts | Partiel solide | `aria-controls` valides, boutons nommes, `aria-expanded` present sur menus/overlays. | Tests clavier complets des overlays a faire sur navigateur. |
| Elements obligatoires | Conforme sur echantillon | `lang="fr"`, `<title>`, un H1 par page. | Une declaration d'accessibilite officielle n'est pas fournie dans le MVP. |
| Structuration | Conforme sur echantillon | Sections, titres, nav/aside/main ; un H1 par page. | Ordre des titres a verifier visuellement page par page si contenu evolue. |
| Presentation | Partiel | Responsive deja teste dans les audits de pages ; captions et skip-link ajoutes. | Zoom 200 %, reflow et contrastes a tester manuellement. |
| Formulaires | Conforme sur echantillon | `controls_missing_labels_total=0`; erreurs de formulaire liees aux champs sur les vues principales. | Valider au clavier les erreurs dynamiques et focus apres soumission. |
| Navigation | Conforme de base | Skip-link present, navigation principale constante, liens nommes. | Test manuel Tab/Shift+Tab/Escape a finaliser. |
| Consultation | Partiel solide | `prefers-reduced-motion`, pages HTTP OK, contenus accessibles sans dependance critique au JS pour les routes principales. | Test lecteur d'ecran non realise. |

## Tests manuels a faire avant rendu

Ces tests sont les derniers controles manuels conseilles sur l'URL deployee,
idealement dans Chrome ou Firefox :

1. Navigation clavier complete :
   - `Tab` et `Shift+Tab` sur pages publiques, commande, employe, admin ;
   - ouverture/fermeture du menu mobile ;
   - ouverture/fermeture des filtres menus ;
   - ouverture/fermeture de la galerie image ;
   - actions admin menus/employes/statistiques.
2. Focus visible :
   - verifier que chaque lien, bouton, champ, checkbox et select a un focus
     visible.
3. Zoom et reflow :
   - zoom navigateur 200 % ;
   - largeur mobile autour de 360 px ;
   - absence de chevauchement texte/boutons.
4. Contrastes :
   - verifier les textes blancs sur bordeaux ;
   - verifier les textes secondaires gris ;
   - verifier badges, boutons secondaires et messages d'erreur.
5. Lecteur d'ecran :
   - lire les pages principales avec VoiceOver ou NVDA ;
   - verifier ordre de lecture, noms de boutons, menus, overlays et tableaux.
6. Formulaires :
   - soumettre contact/connexion/inscription/commande avec erreurs ;
   - verifier que les messages sont compréhensibles et associes aux champs.

## Statut recommande pour le rendu

Ne pas annoncer "Accessibilite : totalement conforme".

Formulation conseillee dans la documentation ou a l'oral :

```text
Une recette accessibilite interne a ete realisee sur un echantillon representatif
des parcours visiteur, client, employe et administrateur. Les bases RGAA sont en
place : langue, titres, labels, textes alternatifs, navigation clavier de base,
focus, tableaux et reduction de mouvement. Un audit expert exhaustif RGAA reste
hors perimetre de l'ECF ; les tests clavier, contrastes et lecteur d'ecran sont
identifies comme passe finale avant livraison.
```

Si une page de declaration d'accessibilite est ajoutee plus tard, elle devra
s'appuyer sur un audit officiel de l'URL publique et ne pas reprendre tel quel
ce document local comme une declaration reglementaire.
