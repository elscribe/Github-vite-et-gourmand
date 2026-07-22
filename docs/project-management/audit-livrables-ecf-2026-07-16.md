# Audit livrables ECF - Vite & Gourmand

Date d'audit : 16 juillet 2026.

Sources controlees :

- Enonce local : `Enonce/Enonce.pdf`.
- Copie a rendre : `Enonce/Evaluations Studi.doc`.
- Notion connecte : workspace `Notion de O.G SCRIBE`.
- Figma connecte : `sMkvVuvOyBkMvlTIsq2eCY`.
- Depot local : branche `feature/email-notifications`.
- Depot GitHub distant : `elscribe/Github-vite-et-gourmand`.
- Documentation locale : `README.md`, `docs/`, `database/`, `Maquettes/`.

## Verdict global

Le projet est avance cote conception, base de donnees, maquettes Figma et MVP
PHP local. Il n'est pas encore pret pour le rendu final ECF, principalement a
cause des points bloquants suivants :

1. Le depot GitHub distant est encore prive.
2. La branche distante `main` ne contient pas l'application complete.
3. Le travail le plus recent est local, avec des fichiers modifies, supprimes
   et non suivis.
4. L'application n'est pas encore deployee.
5. La documentation de deploiement, le manuel utilisateur PDF, la securite, la
   veille securite et la recherche anglophone sont vides ou placeholders.
6. Les emails obligatoires ne sont que partiellement branches.
7. MongoDB est documente et seedable, mais le dashboard PHP lit encore des
   agregats SQL locaux.

## Etat par livrable obligatoire

| Livrable exige | Etat | Preuves verifiees | Reste a faire |
|---|---|---|---|
| Lien GitHub public | Bloquant | Remote `https://github.com/elscribe/Github-vite-et-gourmand.git`, `gh repo view` indique `PRIVATE`. | Rendre le depot public, verifier en navigation privee. |
| Code applicatif sur GitHub | Bloquant | `origin/main` contient surtout docs/BDD; `origin/develop` contient le squelette MVC; local contient le MVP avance. | Commit/push du travail local, merge stable vers `main`. |
| Application deployee | Bloquant | README et Notion indiquent lien a completer; page Notion `Deploiement` vide. | Choisir cible, deployer, tester URL publique, documenter. |
| Outil de gestion projet | Partiel bon | Notion accessible; pages analyse, CDC, gestion projet, UX/UI, BDD remplies. | Partager le lien jury, mettre a jour statuts et pages vides. |
| README installation locale | Partiel | README present avec installation, SQL, MongoDB, comptes demo. | Supprimer les restes Sprint 0/501, synchroniser avec l'etat reel. |
| Bonnes pratiques Git | Partiel | Branches `main`, `develop`, `feature/*` existent. | Utilisation Git Flow incomplete; main obsolete; nombreux fichiers locaux non commites. |
| Scripts SQL creation + donnees | Conforme | `create_database.sql`, `seed_database.sql`; DB locale OK. | Reexecuter avant rendu propre et documenter version finale. |
| Base NoSQL | Partiel | `database/mongodb` contient collections, seed, sample data; Figma/Notion/BDD documentent MongoDB. | Connecter PHP a MongoDB ou documenter explicitement le fallback SQL comme limite. |
| MCD/MLD/MPD | Conforme | `docs/database/*.drawio` et `*.png`. | Reexport final si diagrammes modifies. |
| UML | Conforme | Cas d'utilisation, classe, sequences auth/commande/employe/avis/dashboard. | Relecture coherence finale avec code/statuts. |
| Wireframes 3 desktop + 3 mobile | Conforme | Figma page `02 - Wireframes` : 3 mobile, 3 desktop; exports PDF locaux. | Joindre ou lier clairement les PDF. |
| Mockups 3 desktop + 3 mobile | Conforme + | Figma page `03 - Maquettes` : 31 frames mobile, 27 desktop; exports PDF locaux. | Nettoyer/nommer les frames finales si besoin. |
| Charte graphique PDF | Conforme | Figma charte + export PDF local. | Mettre a jour docs placeholders `docs/deliverables`. |
| Manuel utilisateur PDF | Bloquant | Notion `Manuel utilisateur` vide; docs/manual placeholder; aucune PDF manuel trouvee. | Rediger PDF avec parcours et comptes demo. |
| Documentation gestion projet | Partiel bon | Notion `Gestion de projet` riche; journal de bord partiel; docs projet locaux. | Mettre a jour user stories avec etat reel et nettoyer contradictions. |
| Documentation technique | Partiel | Architecture, BDD, UML, README presents. | Completer securite, deploiement, recherche anglophone, manuel, livrables finaux. |
| Documentation securite + veille | Bloquant | Code a des protections; page Notion `Securite` et `docs/security` vides. | Rediger mecanismes reels + veille OWASP/ANSSI/CNIL. |
| Recherche anglophone | Bloquant | Pages Notion recherche anglophone vides ou en cours. | Citer source anglaise, extrait, traduction et apport projet. |
| RGAA/accessibilite | Partiel | Figma et code indiquent labels, contrastes, alt, responsive; pas d'audit final. | Faire passe clavier/lecteur, corriger et documenter. |
| Copie Studi | Bloquant | Doc Word demande lien GitHub, Notion, deploiement, login/mot de passe admin. | Renseigner liens finaux et identifiants testes. |

## Constats Notion

Points solides :

- Analyse du besoin et cahier des charges rediges.
- Gestion de projet riche : methode, planning, personas, user stories,
  priorisation, risques, matrice de tracabilite.
- UX/UI documente et globalement conforme.
- Base de donnees documentee et reliee aux scripts locaux.
- Page Informations de rendu utile pour la checklist finale.

Ecarts :

- Plusieurs pages importantes sont vides : `Securite`, `Recherche anglophone`,
  `Deploiement`, `Manuel utilisateur`, `Livrables finaux`.
- La checklist du 8 juillet n'est plus a jour : elle sous-estime le code local
  et le design system Figma, mais elle reste juste sur les gros blocages.
- La page GitHub Notion est obsolete : elle decrit un etat ancien ou `develop`
  etait la branche de travail, alors que le travail le plus avance est plus loin
  en local.

## Constats Figma

Figma est un point fort.

- Le fichier contient 7 pages : Design System, Charte graphique, Wireframes,
  Maquettes, Prototype, Exports PDF, proposition Design System MVP.
- Wireframes : 3 mobiles et 3 desktop confirmes.
- Maquettes : 31 frames mobiles et 27 frames desktop directes sur la page
  Maquettes, couvrant public, menus, detail menu, auth, contact, compte,
  commande, avis, employe et admin.
- Design system : 45 composants ou variantes visibles sur la page, 15 styles
  typographiques et 23 variables.
- Charte graphique : presente dans Figma et exportee localement en PDF.

Points a corriger :

- Mettre a jour `docs/design.md`, qui decrit un ancien etat Figma.
- Mettre a jour les README placeholders dans `docs/deliverables`.
- Harmoniser les noms/personnages et statuts entre Figma, Notion, SQL et code.

## Constats GitHub et Git local

Etat GitHub distant :

- Depot : `elscribe/Github-vite-et-gourmand`.
- Visibilite : prive.
- Branche par defaut : `main`.
- `origin/main` ne contient pas le code applicatif complet.
- `origin/develop` contient un squelette MVC plus avance que `main`, mais pas
  le dernier MVP local.

Etat local :

- Branche active : `feature/email-notifications`.
- Worktree sale : fichiers modifies, suppressions d'anciennes images, nouveaux
  assets, nouveau `MailService`, nouvelle partial overlay, nouveau document
  d'audit email.
- `.env` existe localement et est ignore par Git.
- Des `.DS_Store` et `storage/logs/mail.log` existent localement, a ne pas
  livrer.

Action prioritaire :

1. Stabiliser la branche actuelle.
2. Corriger les quelques ecarts bloquants.
3. Committer les fichiers necessaires.
4. Pousser sur GitHub.
5. Merger vers `develop`, puis `main`.
6. Rendre le depot public.

## Constats application locale

Validations realisees pendant l'audit :

- `composer check` : OK.
- `node --check public/assets/js/app.js` : OK.
- Connexion DB locale : OK.
- Donnees locales : 18 utilisateurs, 6 menus, 24 plats, 26 commandes, 11 avis,
  7 horaires.
- Coherence statuts commande/historique : 0 incoherence.
- Avis sur commande non terminee : 0 incoherence.
- Pages publiques testees en HTTP : OK.
- Redirection visiteur vers connexion sur pages protegees : OK.
- Connexions client, employe et admin : OK.
- Controle de role : client bloque admin/employe, employe bloque admin.

Couverture fonctionnelle locale :

- Public : accueil, menus, filtres, detail, contact, pages legales.
- Auth : inscription, login, reset password.
- Client : compte, commandes, suivi, modification/annulation avant acceptation,
  avis apres commande terminee.
- Employe : commandes, filtres, statuts, annulation motivee, moderation avis.
- Admin : dashboard, statistiques, employes, horaires, menus, plats, associations menus/plats et plats/allergenes.

Reserves fonctionnelles :

- La gestion d'images depuis l'admin n'est pas encore disponible ; les images
  restent alimentees par les assets publics et les donnees de presentation.
- Les mails requis sont branches en mode log local ; un SMTP reel reste a
  configurer pour la production.
- Le dashboard admin ne lit pas encore MongoDB depuis PHP.
- Pas de tests automatises applicatifs, seulement checks syntaxe et recette
  manuelle documentee.

## Points techniques a corriger

- `AdminController::menuData()` contient deux lignes `stock_disponible`
  identiques.
- `OrderModel::updatePendingForUser()` assigne deux fois `date_prestation`.
- Configurer un SMTP reel avant de promettre l'envoi effectif hors mode log.
- `config/database.php` et `database/mongodb/README.md` disent encore que
  MongoDB est pour plus tard ; il faut reformuler selon l'etat reel.
- `README.md` contient encore des passages Sprint 0 et `501 Not Implemented`.

## Priorites recommandees

### Priorite 1 - Rendu bloquant

1. Committer/pousser le MVP local.
2. Fusionner une version stable vers `main`.
3. Rendre GitHub public.
4. Deployer l'application.
5. Completer la copie Studi : GitHub, Notion, URL deploiement, login/mot de
   passe admin.

### Priorite 2 - Documents obligatoires

1. Rediger le manuel utilisateur PDF.
2. Rediger la documentation de deploiement.
3. Rediger la page securite + veille securite.
4. Rediger la recherche anglophone.
5. Completer `Livrables finaux`.

### Priorite 3 - Cohérence et qualite

1. Mettre a jour Notion selon l'etat reel.
2. Mettre a jour `README.md`, `docs/design.md`, `docs/deliverables/*`.
3. Finaliser les emails obligatoires.
4. Clarifier MongoDB : connexion reelle ou fallback SQL justifie.
5. Faire une recette finale mobile/desktop/RGAA.
6. Nettoyer les artefacts locaux.

## Conclusion

Le projet est en bon etat pour une phase pre-finale : le coeur applicatif local
fonctionne, Figma est riche, la base est solide et les documents de conception
sont bien avances. En revanche, il reste des livrables administratifs et
documentaires obligatoires qui bloquent encore le rendu ECF. Le vrai risque
aujourd'hui n'est pas le manque de code local, mais la synchronisation finale :
GitHub public, branche `main`, deploiement, documentation finale et copie Studi.
