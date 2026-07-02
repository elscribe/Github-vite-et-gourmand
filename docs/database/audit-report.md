# Rapport d'audit de coherence

## Perimetre controle

- MCD, MLD et MPD generes dans `docs/database/`.
- Scripts SQL dans `database/sql/`.
- Documentation MongoDB dans `database/mongodb/`.
- Diagrammes UML dans `docs/uml/`.
- Contraintes de l'enonce ECF Vite & Gourmand.

## Coherence MCD -> MLD -> MPD

Statut : conforme.

- Les entites metier principales sont conservees : utilisateur, role, commande, menu, plat, avis, regime, theme et allergene.
- Les associations plusieurs-a-plusieurs sont transformees en tables d'association : `menu_plats` et `plat_allergenes`.
- Les relations un-a-plusieurs deviennent des cles etrangeres dans le MLD et le MPD.
- Les ajouts `menu_images`, `commande_statuts`, `horaires`, `contact_messages` et `password_resets` sont justifies par des besoins explicites du parcours applicatif.

## Coherence UML -> MCD

Statut : conforme.

- Le diagramme de classes reprend les entites principales du MCD et les classes de service necessaires au parcours applicatif.
- Les cas d'utilisation couvrent les acteurs de l'enonce : visiteur, utilisateur, employe et administrateur.
- Les sequences traitent les parcours attendus : authentification, consultation/commande, gestion commande employe, gestion avis et dashboard administrateur MongoDB.

## Coherence SQL -> MPD

Statut : conforme.

- `create_database.sql` reprend les tables, cles primaires, cles etrangeres, contraintes, controles de domaine et index du MPD.
- `seed_database.sql` contient les donnees de demonstration completes pour roles, utilisateurs, menus, plats, commandes, historiques, avis, contacts, horaires et resets de mot de passe.

## Coherence MongoDB -> besoins administrateur

Statut : conforme.

- Les collections principales sont `menu_statistics`, `monthly_statistics` et `dashboard_statistics`.
- `menu_statistics` contient notamment `menuId`, `menuTitle`, `orders`, `revenue`, `averageBasket`, `averageRating`, `lastOrder` et `updatedAt`.
- `monthly_statistics` et `dashboard_statistics` fournissent les donnees deja agregees pour les graphiques administrateur.
- MongoDB est limite aux agregats statistiques ; la source de verite reste SQL.

## Conformite avec l'enonce ECF

Statut : conforme avec points a surveiller.

- Base relationnelle : couverte par MariaDB/MySQL et les scripts SQL.
- Base non relationnelle : couverte par MongoDB pour les statistiques administrateur.
- Historique des statuts : couvert par `commande_statuts`.
- Avis valides uniquement : couvert par `avis.statut`.
- Horaires : couverts par `horaires`.
- Contact : couvert par `contact_messages`.
- Reinitialisation de mot de passe : couverte par `password_resets`.
- Tableau de bord administrateur : couvert par `menu_statistics`.

## Incoherences ou risques restants

- Le MCD fourni dans l'enonce ne contient pas la galerie d'images, les horaires, le contact ni l'historique des statuts. Correction proposee : les conserver comme extensions justifiees, car ces besoins sont ecrits dans l'enonce.
- Le calcul de distance hors Bordeaux n'est pas detaille dans l'enonce. Correction proposee : documenter l'algorithme choisi au moment de l'implementation, par exemple distance saisie manuellement en MVP ou API de geocodage si disponible.
- Le statut `en_attente` est ajoute comme statut initial technique, meme si la liste imposee commence a `acceptee`. Correction proposee : l'expliquer comme etat avant validation employe, necessaire au droit d'annulation client avant acceptation.
- Les exports PNG sont generes localement pour le dossier ; si les diagrammes sont modifies dans diagrams.net, il faudra re-exporter les PNG.

## Validation finale

Le modele est pret pour une presentation ECF. Les choix ajoutes au MCD initial sont tous rattaches a des exigences explicites de l'enonce et non a des hypotheses non signalees.
