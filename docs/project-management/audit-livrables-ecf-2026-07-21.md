# Audit livrables ECF - Vite & Gourmand - 2026-07-21

Date de controle : 21 juillet 2026.
Perimetre controle : enonce local, document Studi, documentation locale, depot Git/GitHub, Notion, Figma, application locale et base de donnees.

## Verdict rapide

Le projet a fortement avance cote application locale : les parcours publics, client, employe et administrateur sont presents et repondent aux tests HTTP de base. La base SQL est chargee, MongoDB fournit les agregats statistiques, les emails metier sont branches en mode log et les roles sont controles cote serveur.

En revanche, l'etat livrable ECF n'est pas encore securise. Les blocages principaux sont :

1. Le depot GitHub est encore prive.
2. La branche par defaut `origin/main` ne contient pas l'application complete.
3. La branche locale la plus avancee, `feature/back-office`, n'existe pas sur le remote. Le code applicatif est commit localement, mais les derniers documents de suivi doivent encore etre integres au commit documentaire.
4. L'application n'est pas deployee.
5. Les pages Notion `Securite`, `Recherche anglophone`, `Deploiement`, `Manuel utilisateur` et `Livrables finaux` sont vides.
6. Le manuel utilisateur PDF n'existe pas encore.
7. La copie Studi n'a pas encore les liens publics et identifiants verifies.

Conclusion : le code local est proche d'une version demonstrable, mais les livrables obligatoires de rendu restent incomplets tant que GitHub, deploiement, Notion et documentation finale ne sont pas alignes.

## Sources controlees

- Enonce local : `Enonce/Enonce.pdf`
- Copie Studi : `Enonce/Evaluations Studi.doc`
- Dossier maquettes : `Maquettes/export pdf`
- Depot local : branche `feature/back-office`
- Remote GitHub : `https://github.com/elscribe/Github-vite-et-gourmand`
- Notion : workspace `Notion de O.G SCRIBE`
- Figma : fichier `sMkvVuvOyBkMvlTIsq2eCY`
- Application locale : serveur PHP integre sur `127.0.0.1:8017`
- Bases locales : MariaDB/MySQL et MongoDB

## Grille des livrables obligatoires

| Livrable obligatoire | Etat | Preuve controlee | Action restante |
| --- | --- | --- | --- |
| Lien GitHub public | Bloquant | `gh repo view` indique `visibility: PRIVATE`, `isPrivate: true`. | Rendre le depot public et tester le lien sans session GitHub. |
| Code present sur GitHub | Bloquant | `origin/main` contient surtout docs/BDD; `origin/develop` contient un squelette; `feature/back-office` local n'est pas sur le remote. | Committer, pousser, fusionner la version testee vers `main`. |
| Application deployee | Bloquant | Aucun URL de production, aucun fichier de deploiement detecte (`Dockerfile`, `fly.toml`, `render.yaml`, etc.). | Choisir l'hebergeur, deployer, tester l'URL publique. |
| Lien outil de gestion projet | Partiel | Notion existe et contient backlog, gestion, UX/UI, docs. Acces jury non verifie. | Partager la page finale et tester en navigation privee. |
| README installation locale | Partiel | README local complet; README `origin/main` obsolet et indique que le code PHP n'est pas implemente. | Mettre `main` a jour avec le README local final. |
| Bonnes pratiques Git | Partiel / bloquant | Branches `main`, `develop`, `feature/*` existent, mais `main` local est ahead 2, `develop` local ahead 11, HEAD ahead 16 de `origin/main`, et branche courante non poussee. | Stabiliser le workflow : feature -> develop -> main, puis push. |
| Script SQL creation | OK | `database/sql/create_database.sql` present. | Revalider apres dernier schema si modification. |
| Script SQL insertion | OK | `database/sql/seed_database.sql` present et base locale chargee. | Garder les identifiants de demo documentes. |
| Base NoSQL | Partiel | Collections presentes dans MongoDB `vite_gourmand`; code lit les agregats MongoDB. `.env` configure `vite_gourmand_stats`, vide, puis fallback code vers `vite_gourmand`. | Aligner `.env.example`, docs et seed sur la base Mongo finale. |
| Manuel utilisateur PDF | Bloquant | Notion `Manuel utilisateur` vide; `docs/manual/README.md` est un placeholder. | Rediger le manuel, ajouter captures et identifiants, exporter en PDF. |
| Charte graphique PDF | OK | PDF local present : `Charte graphique complete - Vite & Gourmand - ECF.pdf`. | Verifier nom final et accessibilite du fichier a joindre. |
| Wireframes et mockups | OK avec reserve | 13 PDF locaux : charte + 6 wireframes + 6 maquettes. Figma tres fourni. | Reconciler la page Figma `02 - Wireframes`, qui montre seulement 4 ecrans directs. |
| Documentation gestion projet | Partiel | Notion `Gestion de projet` riche; statut `Termine`, mais matrice et statuts user stories encore datent/contredisent le code. | Mettre a jour les statuts reels et liens de preuve. |
| Documentation technique | Partiel | BDD, UML, architecture, README existent. Securite/deploiement/recherche/manuel restent vides ou placeholders. | Finaliser docs obligatoires. |
| MCD ou diagramme de classes | OK | MCD/MLD/MPD + diagramme de classes presents en drawio/png. | Verifier exports finals. |
| Diagramme d'utilisation | OK | `docs/uml/use-case-diagram.*` present. | Verifier lisibilite export PNG/PDF. |
| Diagrammes de sequence | OK | Auth, consultation/commande, gestion commande employe, avis, dashboard MongoDB presents. | Verifier lisibilite export PNG/PDF. |
| Documentation de deploiement | Bloquant | Notion `Deploiement` vide; `docs/deployment/README.md` placeholder. | Rediger la procedure reelle apres deploiement. |
| Securite + veille | Bloquant documentaire | Le code implemente plusieurs protections, mais Notion `Securite` est vide et doc locale placeholder. | Documenter mesures code + veille OWASP/ANSSI/CNIL. |
| Recherche anglophone | Bloquant | Notion `Recherche anglophone` vide. | Ajouter situation, source anglaise, extrait court, traduction et apport projet. |
| Identifiants administrateur | Partiel | README local donne `admin.jose@vitegourmand.test` / `AdminVite2026!`, teste localement. | Tester sur app deployee et renseigner la copie Studi. |
| Copie Studi finale | Bloquant | Document Word demande Git, gestion projet, deploiement, admin login/mot de passe. | Completer apres publication/deploiement. |

## Etat GitHub et Git

GitHub distant :

- Depot : `elscribe/Github-vite-et-gourmand`
- Visibilite : `PRIVATE`
- Branche par defaut : `main`
- Dernier push remote : 2026-07-20 03:31:12 UTC
- Permission locale via `gh` : `ADMIN`
- Le depot etant prive, le controle fiable vient de l'outil GitHub local.

Etat local avant commit du present audit :

- Branche courante : `feature/back-office`
- `origin/feature/back-office` : absente
- HEAD vs `origin/main` : 17 commits locaux d'avance
- HEAD vs `origin/develop` : 11 commits locaux d'avance
- `main` local vs `origin/main` : 2 commits locaux d'avance
- `develop` local vs `origin/develop` : 11 commits locaux d'avance
- Fichiers documentaires a integrer : 5

Fichiers documentaires a integrer :

- `docs/manual/README.md`
- `docs/project-management/README.md`
- `docs/PROJECT_STATE.md`
- `docs/manual/final-user-story-test-matrix.md`
- `docs/project-management/audit-livrables-ecf-2026-07-21.md`

Lecture : le code applicatif teste localement est commit dans `feature/back-office`, mais le jury ne le verra toujours pas sur `origin/main` tant que la branche n'est pas fusionnee et poussee.

## Etat Notion

Pages et bases controlees :

- `Checklist - Vite & Gourmand`
- `ECF - Vite & Gourmand`
- Base `Livrables`
- `Gestion de projet`
- `UX/UI`
- `Documentation technique`
- `Informations de rendu`
- `GitHub`
- `Securite`
- `Recherche anglophone`
- `Deploiement`
- `Manuel utilisateur`
- `Livrables finaux`
- `Journal de bord`

Constats :

- La structure Notion est bonne : page projet, base livrables, gestion de projet, UX/UI, documentation technique et journal existent.
- La page `Gestion de projet` est riche : methode, backlog, personas, user stories, planning, risques, matrice de tracabilite, workflow Git.
- La page `UX/UI` est consideree terminee et documente les exports Figma/PDF.
- La page `Documentation technique` est seulement un hub; ses sous-pages critiques restent a remplir.
- `Securite`, `Recherche anglophone`, `Deploiement`, `Manuel utilisateur` et `Livrables finaux` sont encore blanches.
- `Informations de rendu` est incomplete et contient une affirmation devenue fausse : "Tous les commits locaux sont pousses sur origin/main".
- `GitHub` est obsolete : elle parle encore d'un etat du 7 juillet, avant l'avancee actuelle de l'application.
- `Journal de bord` existe mais reste incomplet pour un livrable final : il faut dater les sessions, problemes, decisions et solutions jusqu'au 21 juillet.

Action cle : Notion doit devenir une preuve finale, pas seulement une memoire de travail.

## Etat Figma et UX/UI

Inventaire Figma au 21 juillet :

- Pages : 8
- Pages principales :
  - `00 - Design System`
  - `01 - Charte graphique`
  - `02 - Wireframes`
  - `03 - Maquettes test`
  - `03 - Maquettes 2`
  - `04 - Prototype`
  - `05 - Exports PDF`
  - `00 bis - Design System MVP ECF - Proposition`
- Styles texte locaux : 15
- Variables locales : 40
- Collections de variables : 3
- Composants / component sets : 51

Points forts :

- La charte graphique existe dans Figma et en PDF.
- Les maquettes sont beaucoup plus completes que le minimum attendu : pages publiques, client, employe, admin, desktop, mobile et tablette.
- Le design system technique existe maintenant : composants, styles et variables sont presents.
- Les exports locaux couvrent bien les 6 wireframes et 6 mockups attendus.

Reserve :

- La page Figma `02 - Wireframes` montre directement 2 wireframes mobile et 2 desktop :
  - `Mon-espace-gourmand-mobile`
  - `Espace-employe-mobile`
  - `Mon-espace-gourmand-bureau`
  - `Espace-administrateur-bureau`
- Les deux wireframes publics existent bien en PDF local, mais ils ne sont pas visibles comme frames directs dans cette page Figma au moment du controle.
- `docs/design.md` est obsolet : il indique encore 15 frames et dit que styles/variables ne sont pas formalises, alors que Figma contient maintenant 51 composants et 40 variables.

Exports PDF locaux :

- 13 PDF dans `Maquettes/export pdf`
- 1 charte graphique PDF
- 6 wireframes PDF
- 6 mockups PDF

## Etat application locale

Controle syntaxe :

- `composer check` : OK
- `node --check public/assets/js/app.js` : OK
- 70 fichiers PHP controles dans `app/`, `config/`, `public/`

Base SQL locale :

- `roles` : 3
- `utilisateurs` : 18
- `menus` : 12
- `plats` : 24
- `commandes` : 27
- `commande_statuts` : 105
- `avis` : 11
- `horaires` : 7
- `contact_messages` : 14
- `password_resets` : 7
- Repartition utilisateurs : `Administrator=2`, `Customer=13`, `Employee=3`
- Menus actifs : 6
- Incoherence statut courant vs historique : 0
- Avis rattaches a commandes non terminees : 0
- Menus actifs sans plat : 0

MongoDB :

- `mongosh` disponible.
- Collections presentes dans la base `vite_gourmand` :
  - `menu_monthly_statistics`
  - `monthly_statistics`
  - `dashboard_statistics`
  - `menu_statistics`
- Le code `StatisticsModel` lit les agregats MongoDB depuis `vite_gourmand`.
- Resultat controle : `menuStats=6`, `monthlyStats=12`, `totalOrders=191`.
- Point a aligner : `.env` local cible `vite_gourmand_stats`, vide, puis le code bascule sur `vite_gourmand`.

Routes publiques testees :

- `/` : 200
- `/menus` : 200
- `/menus/1` : 200
- `/contact` : 200
- `/connexion` : 200
- `/inscription` : 200
- `/mentions-legales` : 200
- `/cgv` : 200
- `/confidentialite` : 200
- `/commandes`, `/employe`, `/admin` non connecte : redirection vers `/connexion`

Routes connectees testees :

- Admin `admin.jose@vitegourmand.test` / `AdminVite2026!` :
  - `/admin` : 200
  - `/admin/statistiques` : 200
  - `/admin/employes` : 200
  - `/admin/menus` : 200
  - `/admin/horaires` : 200
  - `/admin/commandes` : 200
  - `/admin/avis` : 200
- Client `claire.martin@example.test` / `ClientVite2026!` :
  - `/mon-compte` : 200
  - `/commandes` : 200
  - `/admin` : 403
  - `/employe` : 403
- Employe `lucas.employee@vitegourmand.test` / `EmployeVite2026!` :
  - `/employe` : 200
  - `/employe/commandes` : 200
  - `/employe/avis` : 200
  - `/admin` : 403

Emails :

- `MailService` centralise les envois.
- Mode local : `MAIL_MAILER=log`, ecriture dans `storage/logs/mail.log`.
- Cas couverts dans le code :
  - bienvenue inscription client ;
  - reinitialisation mot de passe ;
  - confirmation de commande ;
  - invitation avis apres commande terminee ;
  - notification retour materiel ;
  - annulation client par email ;
  - demande de contact vers l'entreprise ;
  - creation compte employe sans transmettre le mot de passe.

## Documentation locale

Points solides :

- README local tres complet pour installation locale.
- Documentation BDD riche : dictionnaire, choix, audit, MCD/MLD/MPD.
- UML complet : use case, classes, sequences principales.
- Documentation projet ajoutee dans `docs/project-management`.
- Checklist manuelle MVP presente dans `docs/manual/mvp-test-checklist.md`.

Fichiers a finaliser :

- `docs/deployment/README.md` : placeholder.
- `docs/manual/README.md` : placeholder.
- `docs/security/README.md` : placeholder.
- `docs/deliverables/wireframes/README.md` : placeholder.
- `docs/deliverables/mockups/README.md` : placeholder.
- `docs/deliverables/graphic-charter/README.md` : placeholder.
- `tests/README.md` : indique qu'aucun framework de test n'est installe.
- `docs/design.md` : obsolete par rapport au Figma actuel.
- README local : section securite/tests encore formulee comme "prevue" alors que du code existe maintenant.

Fichiers parasites :

- `.DS_Store` detecte localement, ignore par Git.
- `storage/logs/app.log` et `storage/logs/mail.log` detectes localement, ignores par Git.
- Aucun de ces fichiers n'est suivi par Git.

## Ecarts majeurs par rapport a l'enonce

1. GitHub n'est pas public.
2. La version fonctionnelle n'est pas sur `origin/main`.
3. L'application n'est pas en ligne.
4. La documentation de deploiement n'existe pas.
5. Le manuel utilisateur PDF n'existe pas.
6. La securite est implementee partiellement dans le code, mais non documentee comme livrable.
7. La veille securite n'est pas redigee.
8. La recherche anglophone n'est pas redigee.
9. La copie Studi n'est pas prete : liens et identifiants non verifies sur une URL publique.
10. Notion contient des informations obsoletes sur GitHub et l'etat des user stories.

## Priorites recommandees

### Priorite 0 - Debloquer le rendu

1. Geler l'etat local actuel.
2. Relire les 5 fichiers documentaires, puis commit propre de `feature/back-office`.
3. Pousser `feature/back-office` ou merger dans `develop`, puis pousser.
4. Fusionner la version stable dans `main`.
5. Pousser `main` et verifier que GitHub affiche bien l'application complete.
6. Rendre le depot public.
7. Deployer l'application.
8. Tester l'URL publique avec les comptes de demo.
9. Completer la copie Studi avec GitHub, Notion, URL deploiement, login et mot de passe admin.

### Priorite 1 - Finaliser les livrables documentaires

1. Rediger `docs/security/README.md` et la page Notion `Securite`.
2. Rediger la veille securite : OWASP Top 10, ANSSI ou CNIL.
3. Rediger `Recherche anglophone` : situation, source, extrait court, traduction, apport au projet.
4. Rediger `docs/deployment/README.md` a partir de la vraie plateforme de deploiement.
5. Rediger le manuel utilisateur avec captures et identifiants, puis exporter en PDF.
6. Remplir `Livrables finaux` dans Notion avec liens et preuves.
7. Mettre a jour `Informations de rendu` et `GitHub` dans Notion.

### Priorite 2 - Nettoyage et coherence

1. Aligner `.env.example`, docs MongoDB et code sur le nom de base Mongo final.
2. Corriger `docs/design.md` pour refleter Figma actuel : 8 pages, 51 composants, 15 styles, 40 variables.
3. Replacer ou documenter les deux wireframes publics dans la page Figma `02 - Wireframes`.
4. Mettre a jour README : securite reelle, tests reels, limites restantes, deploiement.
5. Nettoyer les placeholders `docs/deliverables/*`.
6. Faire une recette manuelle complete avec captures pour le manuel.

## Decision de readiness

Etat actuel : pas pret pour depot ECF.

Raison : les preuves techniques existent en local, mais le rendu impose des liens publics et des documents finaux. Tant que GitHub reste prive, que `main` reste obsolete, que l'application n'est pas deployee et que les pages Notion/documentation obligatoires restent vides, le dossier peut etre rejete ou fortement penalise.

Prochaine cible realiste : transformer l'etat local teste en version publiable, puis verrouiller les livrables de preuve.
