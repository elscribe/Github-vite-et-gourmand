# Audit page 12 - Documentation gestion projet et technique

Date : 22 juillet 2026.

Source controlee : page 12 de l'enonce ECF, rendue depuis
`Enonce/Enonce.pdf`.

## Exigences page 12

La page 12 demande :

- une documentation traitant de la gestion de projet ;
- une explication de la gestion de projet ;
- une documentation technique de l'application ;
- les reflexions initiales technologiques ;
- la configuration de l'environnement de travail ;
- un modele conceptuel de donnees ou un diagramme de classe ;
- un diagramme d'utilisation et un diagramme de sequence ;
- une documentation de deploiement expliquant la demarche et les etapes.

## Verdict court

La page 12 est **conforme localement avec reserves finales**.

Le projet possede les documents attendus : README, dossier `docs/`, gestion de
projet locale, documentation base de donnees, diagrammes Merise, diagrammes UML,
documentation de deploiement, documentation securite, manuel et matrice de
recette.

Les reserves ne portent pas sur l'existence du fond documentaire, mais sur sa
fermeture pour le rendu :

- la documentation de deploiement contient encore les emplacements a renseigner
  apres publication de l'application ;
- l'accessibilite est documentee comme base appliquee, mais l'audit RGAA complet
  final reste a faire ;
- certains documents de suivi datent d'avant les dernieres corrections locales
  et doivent etre relus avant push ;
- le lien Notion partage au jury n'est pas encore teste ;
- la version locale documentee doit encore etre committee, fusionnee et poussee
  sur la branche finale visible par le jury.

## Controle par exigence

| Exigence page 12 | Statut | Preuve locale / Notion | Reserve |
|---|---:|---|---|
| Documentation de gestion de projet | Conforme | `docs/project-management/README.md`, `user-story-implementation-report.md`, `user-story-debug-log.md`, `final-user-story-test-matrix.md`. Notion contient une page `Gestion de projet`. | Mettre a jour les statuts apres les dernieres corrections client/admin/images. |
| Explication de la gestion de projet | Conforme avec reserve | Notion `Gestion de projet` explique la demarche de cadrage, priorisation et suivi. Le depot local explique les US, tests et corrections. | Ajouter une phrase de synthese plus directe dans le README ou le dossier `project-management` aiderait le jury. |
| Documentation technique de l'application | Conforme avec reserve | `README.md`, `docs/README.md`, `docs/project-structure.md`, `docs/repository.md`, `docs/database/`, `docs/security/`, `docs/deployment/`. | Verifier que la documentation finale reflete la version qui sera poussee sur `main`; audit RGAA complet encore absent. |
| Reflexions initiales technologiques | Conforme | `README.md` documente la stack ; `docs/database/database-choices.md` justifie SQL, MongoDB, Merise et UML. Notion contient `Architecture technique`. | Un document local dedie `architecture-technique.md` serait plus lisible, mais le contenu existe. |
| Configuration de l'environnement de travail | Conforme | `README.md` decrit prerequis, `.env`, Composer, SQL, MongoDB ; `.env.example` est present. | Rien de bloquant localement. |
| MCD ou diagramme de classe | Conforme | `docs/database/MCD.drawio`, `MCD.png`, `MLD`, `MPD`; `docs/uml/class-diagram.drawio`, `class-diagram.png`. | Rien de bloquant. Le projet fournit meme les deux options. |
| Diagramme d'utilisation | Conforme | `docs/uml/use-case-diagram.drawio` et `docs/uml/use-case-diagram.png`. Notion retourne aussi une page `UML`. | Rien de bloquant. |
| Diagramme de sequence | Conforme | Plusieurs sequences existent : authentification, consultation/commande, commande employe, avis, dashboard admin MongoDB. | Rien de bloquant ; choisir 1 ou 2 sequences principales a presenter au jury. |
| Documentation de deploiement | Partielle finale | `docs/deployment/README.md` decrit prerequis, variables, installation, SQL, MongoDB, permissions et tests post-deploiement. | Bloquant final tant que l'URL, l'hebergeur, le commit deploye et la recette production ne sont pas renseignes. |

## Controle Notion

Recherche Notion effectuee sur les termes `ECF Vite Gourmand gestion de projet
livrables documentation technique deploiement`.

Pages retrouvees :

- `Gestion de projet` ;
- `ECF - Vite & Gourmand` ;
- `Informations de rendu` ;
- `Checklist - Vite & Gourmand` ;
- `Documentation technique` ;
- `Architecture technique` ;
- `Deploiement` ;
- `Base de donnees` ;
- `UML` ;
- `GitHub`.

Interpretation : Notion est bien utilise comme documentation projet et
technique. La reserve reste le partage jury et la coherence finale des liens.

## Points a mettre a jour avant rendu

1. Relire `docs/project-management/user-story-implementation-report.md` avant
   push final pour verifier que les statuts suivent la version vraiment rendue.
2. Garder cet audit page 12 reference dans `docs/project-management/README.md`.
3. Mettre a jour `docs/deliverables/final-deliverables.md` avec le vrai statut
   de la documentation page 12.
4. Produire ou completer un audit RGAA final, puis le relier depuis README et
   livrables finaux.
5. Apres deploiement, completer `docs/deployment/README.md` avec :
   hebergeur, URL publique, date, commit deploye, mode email, SQL, MongoDB et
   resultat de recette production.
6. Partager Notion au jury et tester le lien sans connexion.
7. Committer puis pousser la documentation finale sur la branche visible par le
   jury.

## Decision

On peut considerer que le **fond de la page 12 est bon**.

Il ne faut pas encore la marquer "terminee sans reserve" car elle depend de la
fermeture finale du rendu : liens publics, deploiement, Notion partage et
documentation mise a jour apres la derniere recette.
