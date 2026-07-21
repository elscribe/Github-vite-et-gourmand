# Audit 01 - Copie a rendre Studi

Date : 21 juillet 2026.

Document audite :

```text
/Users/jordanmf/Documents/Programation/studi/Projets/ECF/Vite&Gourmand/Enonce/Evaluations Studi.doc
```

## Role du document

Ce document est le premier livrable operationnel cite dans l'enonce : la copie
Word/Excel a completer et deposer sur Studi.

Il ne s'agit pas d'une documentation annexe. C'est le document de synthese qui
centralise :

- les informations candidat ;
- les liens obligatoires ;
- les identifiants administrateur ;
- les reponses redigees attendues pour l'analyse, la technique, la securite et
  la recherche anglophone.

Point critique observe dans le document :

```text
SANS CES ELEMENTS, VOTRE COPIE SERA REJETEE
```

Les elements vises sont :

- lien du git ;
- lien de l'outil de gestion de projet ;
- lien du deploiement ;
- login et mot de passe administrateur.

## Controle format et rendu

| Element | Observation |
|---|---|
| Format source | Microsoft Word 97-2004 `.doc`. |
| Taille | 51 Ko environ. |
| Pages | 3 pages A4. |
| Auteur metadonnees | Anne Champeaux. |
| Source indiquee | Ressource Studi. |
| Rendu PDF de controle | OK, 3 pages converties via LibreOffice. |
| Pages visuelles | Page 1 couverture, page 2 liens + parties 1/2, page 3 parties 3/4. |
| Probleme visuel observe | Aucun clipping majeur sur le template vide. |
| Risque format | Le vieux format `.doc` est moins fiable pour une version finale longue. |

Recommendation : conserver l'original, mais travailler la version finale en
`.docx` si Studi l'accepte, avec le nom demande par l'enonce :

```text
ECF_TPDeveloppeurWebEtWebMobile_copiearendre_NOM_Prenom.docx
```

Le document actuel n'a pas de grandes zones de reponse. Les contenus devront
etre inseres sous chaque question, ce qui fera probablement passer la copie au
dela de 3 pages. Ce n'est pas un probleme si la mise en page reste lisible.

## Structure du document

| Zone | Contenu demande | Statut actuel |
|---|---|---|
| Identite | NOM, Prenom, Date de naissance. | Vide. A fournir par le candidat. |
| Liens obligatoires | Git, outil gestion projet, deploiement, login/mot de passe admin. | Vide. Bloquant. |
| Partie 1 | Resume projet 200 a 250 mots. | Vide. Redigeable avec les sources locales. |
| Partie 1 | Cahier des charges, besoin ou specifications fonctionnelles. | Vide. Redigeable avec enonce + docs locales. |
| Partie 2 | Technologies utilisees et justification. | Vide. Redigeable avec README + docs techniques. |
| Partie 2 | Environnement de travail et justification. | Vide. Redigeable avec README + `.env.example`. |
| Partie 2 | Mecanismes de securite front/formulaires/back. | Vide. Redigeable avec docs securite + code. |
| Partie 2 | Veille technologique sur vulnerabilites de securite. | Vide. Source locale prete. |
| Partie 3 | Situation de recherche anglophone + source. | Vide. Source locale prete. |
| Partie 3 | Extrait anglais + traduction francaise. | Vide. Source locale prete. |
| Partie 4 | Autres ressources et informations complementaires. | Vide. A utiliser pour liens, reserves ou annexes. |

## Etat de remplissage possible aujourd'hui

### Champs non remplissables immediatement

| Champ | Pourquoi c'est bloque |
|---|---|
| Lien du git | Le depot GitHub existe mais il est encore prive (`visibility: PRIVATE`). |
| Lien du deploiement | Aucune URL publique n'est encore renseignee. |
| Lien Notion jury | Le lien de partage jury n'est pas renseigne dans le README/local. Il doit etre cree et teste hors session. |
| Login/mot de passe administrateur | Les identifiants de demo existent localement, mais ils doivent etre testes sur l'application deployee avant copie finale. |
| Date de naissance | Information personnelle a fournir par le candidat. |

### Champs deja redigeables

| Question | Sources pretes |
|---|---|
| Resume projet 200-250 mots | Enonce page 3, README, docs projet. |
| Cahier des charges / expression du besoin | Enonce pages 3 a 9, README, Notion, matrice de recette. |
| Technologies et justification | README, docs/database, docs/deployment, architecture MVC. |
| Environnement de travail | README, `.env.example`, Composer, PHP, MariaDB, MongoDB, Git/GitHub, Figma, Notion. |
| Securite | `docs/security/README.md`, code middlewares, models PDO, CSRF, hash password. |
| Veille securite | `docs/security/security-watch.md`. |
| Recherche anglophone | `docs/project-management/recherche-anglophone.md`. |
| Informations complementaires | Reserves : SMTP en mode log local, MongoDB/fallback SQL, audit RGAA final, deploiement a finaliser. |

## Points critiques par rapport a la copie

### 1. Rejet possible si les liens restent vides

Le document annonce explicitement que la copie sera rejetee sans les elements de
l'entete. La priorite de fin de projet doit donc etre :

1. rendre GitHub public ;
2. deployer l'application ;
3. partager Notion ;
4. tester les trois liens sans session connectee ;
5. tester le compte administrateur sur l'URL deployee ;
6. remplir la copie.

### 2. Le GitHub actuel n'est pas encore utilisable comme lien final

Etat observe :

```text
GitHub : https://github.com/elscribe/Github-vite-et-gourmand
Visibilite : PRIVATE
Branche par defaut : main
```

Le lien peut etre prepare dans la copie, mais il ne doit pas etre considere
final tant que le depot n'est pas public et que `main` ne contient pas la version
a rendre.

### 3. Le deploiement reste la plus grosse dependance

La documentation locale de deploiement existe, mais elle contient encore :

```text
URL publique : A renseigner apres deploiement.
```

La copie Studi ne doit etre finalisee qu'apres un test de l'URL publique.

### 4. Les textes de reponse existent en morceaux mais pas encore sous forme Studi

Les documents locaux contiennent deja beaucoup de matiere, mais la copie demande
des reponses synthetiques. Il faudra eviter de coller les docs telles quelles.

Format recommande :

- Partie 1 : texte clair et metier, pas trop technique.
- Partie 2 : explication structuree des choix avec justification.
- Partie 3 : une situation concrete, une source anglophone, un court extrait,
  une traduction et l'apport au projet.
- Partie 4 : liens annexes, reserves assumables, documents consultables.

## Plan de remplissage recommande

### Entete

| Champ | Valeur cible |
|---|---|
| NOM | A fournir par le candidat. |
| Prenom | A fournir par le candidat. |
| Date de naissance | A fournir par le candidat. |
| Lien du git | `https://github.com/elscribe/Github-vite-et-gourmand` apres passage public et merge final. |
| Lien outil gestion | Lien Notion partage jury apres verification hors session. |
| Lien deploiement | URL publique apres deploiement. |
| Login administrateur | `admin.jose@vitegourmand.test` si teste en production. |
| Mot de passe administrateur | `AdminVite2026!` si teste en production. |

### Partie 1 - Analyse des besoins

Contenu attendu :

- resume du contexte Vite & Gourmand ;
- probleme initial : menus envoyes par mail, visibilite limitee, commandes peu
  structurees ;
- solution : application web consultable, responsive, avec commande et espaces
  par roles ;
- acteurs : visiteur, client, employe, administrateur ;
- objectifs : menus, commande, suivi, moderation avis, back-office,
  statistiques.

### Partie 2 - Specifications techniques

Contenu attendu :

- stack : HTML5, CSS3, Bootstrap 5, JavaScript vanilla, PHP 8.3 MVC, PDO,
  MariaDB/MySQL, MongoDB, Composer ;
- justification : simplicite, lisibilite, adequation ECF, maitrise SQL/NoSQL ;
- environnement : macOS, editeur, Git/GitHub, Composer, `.env`, serveur PHP,
  MariaDB, MongoDB, Figma, Notion ;
- securite : roles, middlewares, CSRF, hash, PDO prepare, echappement XSS,
  `.env`, sessions, erreurs production ;
- veille : OWASP, ANSSI, CNIL.

### Partie 3 - Recherche

Contenu local deja pret :

- situation : controle d'acces par role ;
- source : OWASP Top 10:2021 - A01 Broken Access Control ;
- extrait court anglais ;
- traduction ;
- apport au projet : middleware d'authentification, middleware de role,
  protection serveur des routes client/employe/admin.

### Partie 4 - Informations complementaires

Utilisation conseillee :

- rappeler les liens utiles : GitHub, Figma, Notion, application ;
- indiquer les documents annexes dans le depot ;
- mentionner les reserves honnetes :
  - SMTP reel a brancher selon hebergeur ;
  - audit RGAA complet a finaliser ;
  - MongoDB documente avec fallback SQL si environnement incomplet ;
  - recette production a refaire apres deploiement.

## Verdict

La copie Studi est actuellement non prete.

Ce n'est pas un probleme de contenu de fond : la majorite des reponses peut etre
redigee a partir des documents locaux. Le vrai blocage est administratif et
public :

- GitHub doit etre public ;
- l'application doit etre deployee ;
- Notion doit etre partage ;
- les liens doivent etre testes ;
- le compte admin doit etre teste sur l'URL publique.

Une fois ces points faits, la copie pourra etre remplie rapidement avec les
contenus deja prepares dans le projet.
