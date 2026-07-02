from __future__ import annotations

import html
import json
import math
import textwrap
import xml.etree.ElementTree as ET
from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


ROOT = Path(__file__).resolve().parents[1]


def ensure_dirs() -> None:
    for path in [
        ROOT / "database/sql",
        ROOT / "database/mongodb",
        ROOT / "docs/database",
        ROOT / "docs/uml",
        ROOT / "docs/notion",
    ]:
        path.mkdir(parents=True, exist_ok=True)


def write(path: str, content: str) -> None:
    target = ROOT / path
    target.parent.mkdir(parents=True, exist_ok=True)
    cleaned = textwrap.dedent(content).strip()
    cleaned = "\n".join(
        line[8:] if line.startswith("        ") else line
        for line in cleaned.splitlines()
    )
    target.write_text(cleaned + "\n", encoding="utf-8")


def font(size: int, bold: bool = False) -> ImageFont.FreeTypeFont | ImageFont.ImageFont:
    candidates = [
        "/System/Library/Fonts/Supplemental/Arial Bold.ttf" if bold else "/System/Library/Fonts/Supplemental/Arial.ttf",
        "/System/Library/Fonts/Supplemental/Helvetica.ttc",
        "/Library/Fonts/Arial.ttf",
    ]
    for candidate in candidates:
        try:
            return ImageFont.truetype(candidate, size=size)
        except OSError:
            continue
    return ImageFont.load_default()


TABLES = {
    "roles": [
        ("id_role", "INT", "PK", "Identifiant du role"),
        ("libelle", "VARCHAR(80)", "UNIQUE NOT NULL", "utilisateur, employe ou administrateur"),
    ],
    "utilisateurs": [
        ("id_utilisateur", "INT", "PK", "Identifiant du compte"),
        ("id_role", "INT", "FK NOT NULL", "Role du compte"),
        ("email", "VARCHAR(120)", "UNIQUE NOT NULL", "Identifiant de connexion"),
        ("password_hash", "VARCHAR(255)", "NOT NULL", "Mot de passe hache"),
        ("nom", "VARCHAR(80)", "NOT NULL", "Nom"),
        ("prenom", "VARCHAR(80)", "NOT NULL", "Prenom"),
        ("telephone", "VARCHAR(30)", "NOT NULL", "GSM"),
        ("adresse_postale", "VARCHAR(255)", "NOT NULL", "Adresse"),
        ("ville", "VARCHAR(80)", "NOT NULL", "Ville"),
        ("pays", "VARCHAR(80)", "NOT NULL", "Pays"),
        ("actif", "BOOLEAN", "NOT NULL", "Desactivation employe"),
        ("created_at", "DATETIME", "NOT NULL", "Date de creation"),
        ("updated_at", "DATETIME", "NULL", "Derniere modification"),
    ],
    "regimes": [
        ("id_regime", "INT", "PK", "Identifiant du regime"),
        ("libelle", "VARCHAR(80)", "UNIQUE NOT NULL", "Classique, vegetarien, vegan..."),
    ],
    "themes": [
        ("id_theme", "INT", "PK", "Identifiant du theme"),
        ("libelle", "VARCHAR(80)", "UNIQUE NOT NULL", "Noel, Paques, classique..."),
    ],
    "menus": [
        ("id_menu", "INT", "PK", "Identifiant du menu"),
        ("id_regime", "INT", "FK NOT NULL", "Regime alimentaire"),
        ("id_theme", "INT", "FK NOT NULL", "Theme du menu"),
        ("titre", "VARCHAR(120)", "NOT NULL", "Titre commercial"),
        ("description", "TEXT", "NOT NULL", "Presentation du menu"),
        ("conditions", "TEXT", "NOT NULL", "Conditions de commande/conservation"),
        ("nombre_personnes_minimum", "INT", "NOT NULL", "Minimum obligatoire"),
        ("prix_minimum", "DECIMAL(10,2)", "NOT NULL", "Prix pour le minimum"),
        ("stock_disponible", "INT", "NOT NULL", "Commandes restantes"),
        ("actif", "BOOLEAN", "NOT NULL", "Affichage catalogue"),
        ("created_at", "DATETIME", "NOT NULL", "Date de creation"),
        ("updated_at", "DATETIME", "NULL", "Derniere modification"),
    ],
    "menu_images": [
        ("id_image", "INT", "PK", "Identifiant image"),
        ("id_menu", "INT", "FK NOT NULL", "Menu illustre"),
        ("url", "VARCHAR(255)", "NOT NULL", "Chemin ou URL image"),
        ("texte_alternatif", "VARCHAR(180)", "NOT NULL", "Accessibilite"),
        ("position", "INT", "NOT NULL", "Ordre galerie"),
    ],
    "plats": [
        ("id_plat", "INT", "PK", "Identifiant du plat"),
        ("titre_plat", "VARCHAR(120)", "NOT NULL", "Nom du plat"),
        ("type_plat", "VARCHAR(30)", "NOT NULL", "entree, plat, dessert"),
        ("description", "TEXT", "NULL", "Details du plat"),
    ],
    "menu_plats": [
        ("id_menu", "INT", "PK FK", "Menu"),
        ("id_plat", "INT", "PK FK", "Plat"),
        ("position", "INT", "NOT NULL", "Ordre dans le menu"),
    ],
    "allergenes": [
        ("id_allergene", "INT", "PK", "Identifiant allergene"),
        ("libelle", "VARCHAR(80)", "UNIQUE NOT NULL", "Gluten, lait, oeufs..."),
    ],
    "plat_allergenes": [
        ("id_plat", "INT", "PK FK", "Plat concerne"),
        ("id_allergene", "INT", "PK FK", "Allergene present"),
    ],
    "commandes": [
        ("id_commande", "INT", "PK", "Identifiant commande"),
        ("id_utilisateur", "INT", "FK NOT NULL", "Client"),
        ("id_menu", "INT", "FK NOT NULL", "Menu commande"),
        ("date_commande", "DATETIME", "NOT NULL", "Date de saisie"),
        ("date_prestation", "DATE", "NOT NULL", "Date evenement"),
        ("heure_livraison", "TIME", "NOT NULL", "Heure souhaitee"),
        ("adresse_livraison", "VARCHAR(255)", "NOT NULL", "Lieu prestation"),
        ("ville_livraison", "VARCHAR(80)", "NOT NULL", "Ville livraison"),
        ("distance_km", "DECIMAL(6,2)", "NOT NULL", "Distance hors Bordeaux"),
        ("nombre_personnes", "INT", "NOT NULL", "Convives"),
        ("prix_menu", "DECIMAL(10,2)", "NOT NULL", "Prix menu calcule"),
        ("remise", "DECIMAL(10,2)", "NOT NULL", "Reduction appliquee"),
        ("prix_livraison", "DECIMAL(10,2)", "NOT NULL", "Frais livraison"),
        ("prix_total", "DECIMAL(10,2)", "NOT NULL", "Total TTC"),
        ("statut_actuel", "VARCHAR(40)", "NOT NULL", "Etat courant"),
        ("materiel_prete", "BOOLEAN", "NOT NULL", "Materiel a restituer"),
        ("materiel_retourne", "BOOLEAN", "NOT NULL", "Retour materiel"),
        ("mode_contact_modification", "VARCHAR(20)", "NULL", "Appel ou email"),
        ("motif_annulation", "TEXT", "NULL", "Motif employe/client"),
    ],
    "commande_statuts": [
        ("id_statut", "INT", "PK", "Identifiant historique"),
        ("id_commande", "INT", "FK NOT NULL", "Commande"),
        ("id_utilisateur", "INT", "FK NULL", "Auteur changement"),
        ("statut", "VARCHAR(40)", "NOT NULL", "Statut applique"),
        ("commentaire", "TEXT", "NULL", "Precision"),
        ("created_at", "DATETIME", "NOT NULL", "Date et heure"),
    ],
    "avis": [
        ("id_avis", "INT", "PK", "Identifiant avis"),
        ("id_utilisateur", "INT", "FK NOT NULL", "Auteur"),
        ("id_commande", "INT", "FK NOT NULL", "Commande terminee"),
        ("note", "TINYINT", "NOT NULL", "Note de 1 a 5"),
        ("commentaire", "TEXT", "NOT NULL", "Commentaire client"),
        ("statut", "VARCHAR(30)", "NOT NULL", "en_attente, valide, refuse"),
        ("created_at", "DATETIME", "NOT NULL", "Date avis"),
        ("moderated_at", "DATETIME", "NULL", "Date moderation"),
        ("moderated_by", "INT", "FK NULL", "Employe moderateur"),
    ],
    "horaires": [
        ("id_horaire", "INT", "PK", "Identifiant horaire"),
        ("jour_semaine", "TINYINT", "UNIQUE NOT NULL", "1 lundi a 7 dimanche"),
        ("ouverture_matin", "TIME", "NULL", "Ouverture matin"),
        ("fermeture_matin", "TIME", "NULL", "Fermeture matin"),
        ("ouverture_apres_midi", "TIME", "NULL", "Ouverture apres-midi"),
        ("fermeture_apres_midi", "TIME", "NULL", "Fermeture apres-midi"),
        ("ferme", "BOOLEAN", "NOT NULL", "Jour ferme"),
    ],
    "contact_messages": [
        ("id_contact_message", "INT", "PK", "Identifiant message"),
        ("titre", "VARCHAR(160)", "NOT NULL", "Sujet"),
        ("email", "VARCHAR(120)", "NOT NULL", "Email visiteur"),
        ("description", "TEXT", "NOT NULL", "Message"),
        ("created_at", "DATETIME", "NOT NULL", "Date envoi"),
        ("traite", "BOOLEAN", "NOT NULL", "Suivi interne"),
    ],
    "password_resets": [
        ("id_reset", "INT", "PK", "Identifiant du jeton"),
        ("id_utilisateur", "INT", "FK NOT NULL", "Compte concerne"),
        ("token_hash", "VARCHAR(255)", "UNIQUE NOT NULL", "Jeton hache"),
        ("expires_at", "DATETIME", "NOT NULL", "Date d'expiration"),
        ("used_at", "DATETIME", "NULL", "Date d'utilisation eventuelle"),
        ("created_at", "DATETIME", "NOT NULL", "Date de creation"),
    ],
}

RELATIONS = [
    ("roles", "utilisateurs", "1,n", "1,1", "possede"),
    ("utilisateurs", "commandes", "0,n", "1,1", "passe"),
    ("menus", "commandes", "0,n", "1,1", "concerne"),
    ("regimes", "menus", "0,n", "1,1", "adapte"),
    ("themes", "menus", "0,n", "1,1", "propose"),
    ("menus", "menu_images", "0,n", "1,1", "illustre"),
    ("menus", "menu_plats", "1,n", "1,1", "compose"),
    ("plats", "menu_plats", "0,n", "1,1", "compose"),
    ("plats", "plat_allergenes", "0,n", "1,1", "contient"),
    ("allergenes", "plat_allergenes", "0,n", "1,1", "qualifie"),
    ("commandes", "commande_statuts", "1,n", "1,1", "historise"),
    ("commandes", "avis", "0,1", "1,1", "donne_lieu"),
    ("utilisateurs", "avis", "0,n", "1,1", "publie"),
    ("utilisateurs", "commande_statuts", "0,n", "0,1", "modifie"),
    ("utilisateurs", "password_resets", "0,n", "1,1", "demande"),
]


def make_markdown_docs() -> None:
    entities = "\n".join(
        f"- `{name}` : {TABLE_DESCRIPTIONS.get(name, 'Donnee metier du projet.')}"
        for name in TABLES
    )
    relations = "\n".join(
        f"- `{left}` ({lc}) - {label} - `{right}` ({rc})."
        for left, right, lc, rc, label in RELATIONS
    )
    attrs = []
    for table, cols in TABLES.items():
        attrs.append(f"### `{table}`")
        attrs.extend([f"- `{c}` ({t}) : {d}" for c, t, _k, d in cols])
    write(
        "database/business-rules.md",
        f"""
        # Regles metier et analyse des donnees

        Ce document formalise la partie base de donnees de Vite & Gourmand a partir de l'enonce ECF et du MCD fourni en annexe. Les ajouts au MCD initial sont signales comme des extensions necessaires pour couvrir les fonctionnalites demandees explicitement dans l'enonce.

        ## Entites identifiees

        {entities}

        ## Attributs principaux

        {chr(10).join(attrs)}

        ## Relations et cardinalites

        {relations}

        ## Contraintes metier

        - Un visiteur peut consulter les menus sans compte, mais doit se connecter ou creer un compte avant de commander.
        - A la creation publique d'un compte, le role attribue est obligatoirement `utilisateur`.
        - Un administrateur ne doit pas pouvoir etre cree depuis l'application publique. Le compte de Jose est cree par le developpeur ou par une operation d'administration hors parcours public.
        - Un administrateur peut creer et desactiver des comptes employes.
        - Un menu doit afficher un titre, une galerie d'images, une description, un theme, un regime, une liste de plats, des conditions, un nombre minimum de personnes, un prix pour ce minimum et un stock disponible.
        - Un plat peut appartenir a plusieurs menus.
        - Un plat peut posseder plusieurs allergenes ; la table `plat_allergenes` conserve uniquement l'association plat/allergene.
        - Le nombre de personnes commande doit etre superieur ou egal au minimum du menu.
        - Une reduction de 10 % s'applique lorsque la commande contient au moins 5 personnes de plus que le minimum du menu.
        - La livraison dans Bordeaux n'ajoute pas la majoration hors Bordeaux ; hors Bordeaux, l'enonce indique 5 EUR plus 0,59 EUR par kilometre parcouru. La methode de calcul de distance reste a implementer et doit etre documentee dans le code.
        - Un client peut modifier ou annuler une commande tant qu'elle n'est pas acceptee. Le choix du menu n'est pas modifiable.
        - Un employe ne peut modifier ou annuler une commande qu'apres contact client par appel GSM ou mail ; le mode de contact et le motif doivent etre conserves.
        - Les statuts de commande doivent etre historises avec une date et une heure.
        - Les avis ne sont possibles qu'apres une commande terminee et ne sont publics qu'apres validation par un employe.
        - Les horaires doivent etre visibles dans le pied de page et modifiables par l'espace employe/administrateur.
        - Les statistiques administrateur doivent provenir d'une base NoSQL : nombre de commandes par menu, comparaison entre menus, chiffre d'affaires par menu et filtres temporels.

        ## Justification des choix

        - Le MCD de l'enonce sert de socle : `utilisateur`, `role`, `commande`, `menu`, `plat`, `avis`, `regime`, `theme`, `allergene`.
        - `menu_images` est ajoute car l'enonce demande une galerie d'images par menu. Ce besoin n'est pas visible dans le MCD fourni mais il est explicite dans le texte.
        - `commande_statuts` est ajoute car l'enonce demande un suivi de commande listant tous les etats avec date et heure de modification.
        - `horaires` est ajoute car les horaires doivent etre visibles dans le pied de page et modifiables par les employes.
        - `contact_messages` est ajoute car l'enonce demande un formulaire de contact avec titre, description et email.
        - `password_resets` est ajoute pour couvrir le parcours de recuperation d'acces sans stocker de jeton en clair.
        - `avis.id_commande` est ajoute pour garantir qu'un avis correspond bien a une commande terminee.
        - `utilisateurs.actif` permet de rendre inutilisable un compte employe sans supprimer l'historique des commandes ou moderations.
        - Les statistiques ne sont pas modelisees comme source de verite SQL : elles sont stockees dans MongoDB sous forme d'agregats pour respecter l'obligation NoSQL.
        """,
    )

    rows = []
    for table, cols in TABLES.items():
        rows.append(f"## `{table}`\n")
        rows.append(f"{TABLE_DESCRIPTIONS.get(table, 'Table du modele relationnel.')}\n")
        rows.append("| Colonne | Type | Contraintes | Description |")
        rows.append("| --- | --- | --- | --- |")
        for col, typ, constraints, desc in cols:
            rows.append(f"| `{col}` | `{typ}` | {constraints} | {desc} |")
        rows.append("")
    write(
        "docs/database/data-dictionary.md",
        "# Dictionnaire de donnees\n\n" + "\n".join(rows),
    )

    write(
        "docs/database/database-choices.md",
        """
        # Justification technique des choix base de donnees

        ## Pourquoi SQL ?

        La base relationnelle est le meilleur choix pour les donnees operationnelles de Vite & Gourmand : comptes, roles, menus, plats, commandes, statuts et avis. Ces donnees ont des relations fortes et doivent rester coherentes. Les cles etrangeres evitent par exemple une commande sans client ou un avis sans commande terminee. Les transactions SQL sont aussi adaptees au parcours de commande, car la creation de commande et l'ajout du premier statut doivent former une operation coherente.

        ## Pourquoi MongoDB ?

        L'enonce impose que les statistiques administrateur viennent d'une base non relationnelle. MongoDB est utilise pour stocker des agregats prets pour le tableau de bord : nombre de commandes par menu, chiffre d'affaires par menu, comparaison entre menus et filtres temporels. Ces documents sont denormalises volontairement pour etre rapides a lire et faciles a presenter dans des graphiques.

        ## Pourquoi Merise ?

        Merise est tres lisible pour expliquer les donnees a un jury : le MCD presente les objets metier et leurs cardinalites sans detail technique, le MLD montre la transformation en tables et cles etrangeres, puis le MPD precise les types SQL et contraintes. Cette progression montre que le modele n'est pas une simple liste de tables mais une construction justifiee.

        ## Pourquoi UML ?

        UML complete Merise en montrant le comportement de l'application : acteurs, cas d'utilisation, classes applicatives et sequences principales. Merise explique surtout les donnees ; UML explique les interactions entre visiteurs, clients, employes, administrateurs, controleurs, services et bases de donnees.

        ## Pourquoi ne pas avoir choisi uniquement UML ?

        Un diagramme de classes UML ne suffit pas a presenter clairement les cardinalites, les associations porteuses de donnees et la transformation relationnelle attendue dans un dossier base de donnees. Merise est plus adapte pour justifier le passage MCD, MLD, MPD et les scripts SQL demandes par l'ECF.

        ## Pourquoi ne pas avoir choisi uniquement MongoDB ?

        Les donnees principales sont tres relationnelles. Une commande reference un utilisateur, un menu, des statuts et eventuellement un avis. Utiliser uniquement MongoDB forcerait a dupliquer beaucoup de donnees et rendrait plus difficile le controle d'integrite. SQL reste la source de verite ; MongoDB sert aux statistiques exigees par l'enonce.

        ## Pourquoi ne pas avoir choisi une base graphe ?

        Une base graphe est utile quand la valeur principale vient de parcours complexes entre entites, par exemple reseaux sociaux, recommandations ou dependances profondes. Vite & Gourmand a surtout besoin de transactions, de contraintes relationnelles et de statistiques simples par periode. Une base graphe ajouterait de la complexite sans benefice clair pour le MVP.

        ## Formulation courte pour l'oral

        J'ai separe la base SQL et la base MongoDB par responsabilite. SQL conserve les donnees metier fiables et normalisees. MongoDB conserve des statistiques denormalisees pour le tableau de bord administrateur, comme l'exige l'enonce. Merise me sert a prouver la coherence des donnees, UML me sert a expliquer les usages et les interactions applicatives.
        """,
    )

    write(
        "docs/database/audit-report.md",
        """
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
        - `seed_database.sql` contient les donnees de demonstration completes.

        ## Coherence MongoDB -> besoins administrateur

        Statut : conforme.

        - La collection principale `menu_statistics` contient `menuId`, `menuTitle`, `orders`, `revenue`, `averageBasket`, `period` et `updatedAt`.
        - Les documents sont filtres par periode et exploitables pour comparer les menus.
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
        """,
    )

    write(
        "docs/notion/database-documentation.md",
        """
        # Documentation Notion - Modelisation et bases de donnees

        Cette page peut etre collee dans Notion pour presenter la partie modelisation du projet Vite & Gourmand.

        ## Objectif

        Documenter la transformation de l'enonce ECF en modele de donnees, scripts SQL et collections MongoDB.

        ## Livrables disponibles

        - `database/business-rules.md` : regles metier, entites, cardinalites et justifications.
        - `docs/database/MCD.drawio` et `docs/database/MCD.png` : modele conceptuel de donnees.
        - `docs/database/MLD.drawio` et `docs/database/MLD.png` : modele logique de donnees.
        - `docs/database/MPD.drawio` et `docs/database/MPD.png` : modele physique de donnees.
        - `docs/database/data-dictionary.md` : dictionnaire de donnees.
        - `database/sql/create_database.sql` : creation complete de la base relationnelle.
        - `database/sql/seed_database.sql` : donnees de demonstration completes.
        - `database/mongodb/collections.md` : collections MongoDB.
        - `database/mongodb/sample-data.json` : exemples de documents statistiques.
        - `docs/database/database-choices.md` : justification orale.
        - `docs/database/audit-report.md` : controle final de coherence.

        ## Decision principale

        SQL est la source de verite pour les donnees metier. MongoDB est reserve aux statistiques du tableau de bord administrateur parce que l'enonce impose une base non relationnelle pour ces donnees.

        ## Points a presenter au jury

        - Le MCD de l'enonce est conserve comme base, avec suppression de l'ancienne entite de qualification des allergenes devenue non necessaire au modele final.
        - Les ajouts sont justifies par le texte de l'enonce et les parcours applicatifs : galerie d'images, horaires, contact, reinitialisation de mot de passe, historique des statuts et avis rattache a une commande.
        - Les statistiques MongoDB sont des agregats recalculables depuis SQL.
        - Le modele evite de supprimer les donnees historiques : on desactive un employe au lieu de le supprimer.
        """,
    )


TABLE_DESCRIPTIONS = {
    "roles": "Roles applicatifs : utilisateur, employe, administrateur.",
    "utilisateurs": "Comptes clients, employes et administrateurs.",
    "regimes": "Categories alimentaires associees aux menus.",
    "themes": "Themes de menus tels que Noel, Paques ou classique.",
    "menus": "Offres de menus traiteur commandables.",
    "menu_images": "Galerie d'images des menus.",
    "plats": "Entrees, plats et desserts reutilisables dans plusieurs menus.",
    "menu_plats": "Association entre menus et plats.",
    "allergenes": "Referentiel des allergenes.",
    "plat_allergenes": "Association entre plats et allergenes.",
    "commandes": "Commandes passees par les utilisateurs.",
    "commande_statuts": "Historique date et heure des statuts de commande.",
    "avis": "Avis clients moderes par les employes.",
    "horaires": "Horaires affiches dans le pied de page.",
    "contact_messages": "Messages envoyes depuis la page contact.",
    "password_resets": "Jetons de reinitialisation de mot de passe.",
}


def make_mongodb() -> None:
    write(
        "database/mongodb/collections.md",
        """
        # Collections MongoDB

        MongoDB est utilise uniquement pour les statistiques du tableau de bord administrateur. Les donnees metier restent dans la base SQL afin de conserver les cles etrangeres, les contraintes et les transactions.

        ## Collection `menu_statistics`

        Objectif : fournir des donnees deja agregees pour les graphiques administrateur.

        Exemple de structure :

        | Champ | Type | Description |
        | --- | --- | --- |
        | `_id` | ObjectId | Identifiant du document. |
        | `menuId` | Number | Identifiant SQL du menu. |
        | `menuTitle` | String | Nom du menu au moment de l'agregation. |
        | `orders` | Number | Nombre de commandes sur la periode. |
        | `revenue` | Number | Chiffre d'affaires sur la periode. |
        | `averageBasket` | Number | Nombre moyen de personnes par commande. |
        | `averageRating` | Number | Note moyenne des avis valides. |
        | `lastOrder` | Date/String ISO | Derniere commande du menu. |
                | `updatedAt` | Date/String ISO | Date de recalcul. |

        ## Pourquoi MongoDB plutot que SQL pour ces donnees ?

        L'enonce demande explicitement que les statistiques administrateur viennent d'une base non relationnelle. Les statistiques sont des donnees de lecture, agregees et recalculables depuis SQL. Les stocker en documents MongoDB permet de servir rapidement un tableau de bord et de conserver des snapshots par periode sans alourdir le modele transactionnel SQL.

        ## Regle de synchronisation

        La source de verite reste SQL. Les documents `menu_statistics` sont recalcules apres validation, modification ou annulation d'une commande, ou par une tache planifiee. En cas d'ecart, SQL est prioritaire et MongoDB doit etre regenere.
        """,
    )

    sample = [
        {
            "menuId": 1,
            "menuTitle": "Menu Terroir Bordelais",
            "orders": 12,
            "revenue": 2280.0,
            "averageBasket": 7.5,
            "period": {"start": "2026-06-01", "end": "2026-06-30", "granularity": "month"},
            "filters": {"theme": "classique", "regime": "classique"},
            "updatedAt": "2026-06-30T23:00:00Z",
        },
        {
            "menuId": 2,
            "menuTitle": "Menu Jardin de Saison",
            "orders": 8,
            "revenue": 1080.0,
            "averageBasket": 5.25,
            "period": {"start": "2026-06-01", "end": "2026-06-30", "granularity": "month"},
            "filters": {"theme": "anniversaire", "regime": "vegetarien"},
            "updatedAt": "2026-06-30T23:00:00Z",
        },
        {
            "menuId": 3,
            "menuTitle": "Menu Noel Gourmand",
            "orders": 18,
            "revenue": 9360.0,
            "averageBasket": 14.2,
            "period": {"start": "2026-12-01", "end": "2026-12-31", "granularity": "month"},
            "filters": {"theme": "noel", "regime": "classique"},
            "updatedAt": "2026-12-31T23:00:00Z",
        },
    ]
    (ROOT / "database/mongodb/sample-data.json").write_text(
        json.dumps(sample, ensure_ascii=False, indent=2) + "\n", encoding="utf-8"
    )


def drawio_file(title: str, nodes: list[dict], edges: list[dict], path: str) -> None:
    mxfile = ET.Element("mxfile", host="app.diagrams.net")
    diagram = ET.SubElement(mxfile, "diagram", name=title)
    model = ET.SubElement(
        diagram,
        "mxGraphModel",
        dx="1200",
        dy="900",
        grid="1",
        gridSize="10",
        guides="1",
        tooltips="1",
        connect="1",
        arrows="1",
        fold="1",
        page="1",
        pageScale="1",
        pageWidth="2200",
        pageHeight="1250",
        math="0",
        shadow="0",
    )
    root = ET.SubElement(model, "root")
    ET.SubElement(root, "mxCell", id="0")
    ET.SubElement(root, "mxCell", id="1", parent="0")
    for node in nodes:
        cell = ET.SubElement(
            root,
            "mxCell",
            id=node["id"],
            value=node["label"],
            style=node.get(
                "style",
                "rounded=0;whiteSpace=wrap;html=1;fillColor=#fff7ed;strokeColor=#9a3412;fontSize=12;align=left;spacing=8;",
            ),
            vertex="1",
            parent="1",
        )
        ET.SubElement(
            cell,
            "mxGeometry",
            x=str(node["x"]),
            y=str(node["y"]),
            width=str(node["w"]),
            height=str(node["h"]),
            **{"as": "geometry"},
        )
    for i, edge in enumerate(edges, 1):
        cell = ET.SubElement(
            root,
            "mxCell",
            id=f"e{i}",
            value=edge.get("label", ""),
            style="endArrow=block;html=1;rounded=0;strokeColor=#444444;fontSize=11;",
            edge="1",
            parent="1",
            source=edge["source"],
            target=edge["target"],
        )
        ET.SubElement(cell, "mxGeometry", relative="1", **{"as": "geometry"})
    ET.ElementTree(mxfile).write(ROOT / path, encoding="utf-8", xml_declaration=True)


def wrap_lines(text: str, width: int) -> list[str]:
    lines: list[str] = []
    for raw in text.split("\n"):
        if not raw:
            lines.append("")
        else:
            lines.extend(textwrap.wrap(raw, width=width) or [""])
    return lines


def png_diagram(title: str, nodes: list[dict], edges: list[dict], path: str, size=(1600, 1000)) -> None:
    img = Image.new("RGB", size, "#fffaf3")
    draw = ImageDraw.Draw(img)
    title_font = font(30, True)
    head_font = font(15, True)
    body_font = font(13)
    line_font = font(12)
    draw.text((40, 28), title, fill="#7c2d12", font=title_font)
    by_id = {n["id"]: n for n in nodes}
    for edge in edges:
        a = by_id[edge["source"]]
        b = by_id[edge["target"]]
        ax, ay = a["x"] + a["w"] / 2, a["y"] + a["h"] / 2
        bx, by = b["x"] + b["w"] / 2, b["y"] + b["h"] / 2
        draw.line((ax, ay, bx, by), fill="#57534e", width=2)
        if edge.get("label"):
            mx, my = (ax + bx) / 2, (ay + by) / 2
            draw.rectangle((mx - 80, my - 12, mx + 80, my + 12), fill="#fffaf3")
            draw.text((mx - 76, my - 9), edge["label"], fill="#44403c", font=line_font)
    for node in nodes:
        x, y, w, h = node["x"], node["y"], node["w"], node["h"]
        fill = node.get("fill", "#ffffff")
        outline = node.get("outline", "#9a3412")
        draw.rounded_rectangle((x, y, x + w, y + h), radius=6, fill=fill, outline=outline, width=2)
        lines = node["plain"].split("\n")
        draw.text((x + 10, y + 8), lines[0], fill="#7c2d12", font=head_font)
        yy = y + 30
        for line in lines[1:]:
            for wrapped in wrap_lines(line, max(18, int(w / 8))):
                draw.text((x + 10, yy), wrapped, fill="#292524", font=body_font)
                yy += 16
    img.save(ROOT / path)


def entity_label(name: str, cols: list[tuple[str, str, str, str]], physical: bool = False) -> tuple[str, str]:
    html_lines = [f"<b>{html.escape(name)}</b>"]
    plain = [name]
    for col, typ, constraints, _desc in cols[:8]:
        line = f"{col} : {typ} {constraints}" if physical else f"{col} ({constraints})"
        html_lines.append(html.escape(line))
        plain.append(line)
    if len(cols) > 8:
        html_lines.append("...")
        plain.append("...")
    return "<br>".join(html_lines), "\n".join(plain)


def make_database_diagrams() -> None:
    make_mcd_diagram()
    layout = {
        "roles": (70, 140),
        "utilisateurs": (70, 360),
        "avis": (70, 760),
        "menus": (440, 140),
        "commandes": (440, 540),
        "regimes": (840, 80),
        "themes": (840, 230),
        "menu_images": (840, 390),
        "commande_statuts": (840, 620),
        "menu_plats": (840, 880),
        "plats": (1220, 340),
        "plat_allergenes": (1220, 620),
        "allergenes": (1580, 520),
        "horaires": (1840, 150),
        "contact_messages": (1840, 440),
        "password_resets": (1840, 700),
    }
    base_tables = list(layout)
    for kind, title, physical in [
        ("MLD", "MLD - Vite & Gourmand", False),
        ("MPD", "MPD - Vite & Gourmand", True),
    ]:
        nodes = []
        for table in base_tables:
            label, plain = entity_label(table, TABLES[table], physical=physical)
            x, y = layout[table]
            height = 150
            if table in {"utilisateurs", "menus", "avis"}:
                height = 240
            if table == "commandes":
                height = 300
            if table == "horaires":
                height = 190
            nodes.append({"id": table, "label": label, "plain": plain, "x": x, "y": y, "w": 250, "h": height})
        edges = [{"source": l, "target": r, "label": f"{lc} / {rc} {label}"} for l, r, lc, rc, label in RELATIONS if l in layout and r in layout]
        drawio_file(title, nodes, edges, f"docs/database/{kind}.drawio")
        png_diagram(title, nodes, edges, f"docs/database/{kind}.png", size=(2200, 1250))


def mcd_entity(label: str, attrs: list[str], x: int, y: int, w: int = 230, h: int = 130) -> dict:
    plain = label + "\n" + "\n".join(attrs)
    html_label = f"<b>{html.escape(label)}</b><br>" + "<br>".join(html.escape(a) for a in attrs)
    return {
        "id": label,
        "plain": plain,
        "label": html_label,
        "x": x,
        "y": y,
        "w": w,
        "h": h,
        "fill": "#ffffff",
        "outline": "#9a3412",
    }


def mcd_assoc(label: str, x: int, y: int) -> dict:
    return {
        "id": label,
        "plain": label,
        "label": f"<b>{html.escape(label)}</b>",
        "x": x,
        "y": y,
        "w": 130,
        "h": 54,
        "fill": "#fef3c7",
        "outline": "#a16207",
    }


def make_mcd_diagram() -> None:
    nodes = [
        mcd_entity("role", ["libelle"], 70, 120),
        mcd_entity("utilisateur", ["email", "password_hash", "nom", "prenom", "telephone", "adresse_postale", "actif"], 70, 360, h=190),
        mcd_entity("avis", ["note", "commentaire", "statut"], 70, 760),
        mcd_entity("commande", ["date_commande", "date_prestation", "heure_livraison", "distance_km", "nombre_personnes", "prix_total", "statut_actuel"], 470, 520, h=210),
        mcd_entity("menu", ["titre", "description", "conditions", "minimum_personnes", "prix_minimum", "stock_disponible"], 470, 120, h=190),
        mcd_entity("regime", ["libelle"], 880, 80),
        mcd_entity("theme", ["libelle"], 880, 250),
        mcd_entity("image_menu", ["url", "texte_alternatif", "position"], 880, 470),
        mcd_entity("historique_statut", ["statut", "commentaire", "created_at"], 880, 720),
        mcd_entity("plat", ["titre_plat", "type_plat", "description"], 1260, 300),
        mcd_entity("allergene", ["libelle"], 1600, 460),
        mcd_entity("horaire", ["jour_semaine", "ouvertures", "ferme"], 1840, 150),
        mcd_entity("contact_message", ["titre", "email", "description", "traite"], 1840, 420),
        mcd_entity("password_reset", ["token_hash", "expires_at", "used_at", "created_at"], 1840, 690),
        mcd_assoc("possede", 120, 285),
        mcd_assoc("passe", 330, 465),
        mcd_assoc("concerne", 560, 385),
        mcd_assoc("publie", 120, 650),
        mcd_assoc("donne lieu", 330, 720),
        mcd_assoc("adapte", 750, 135),
        mcd_assoc("propose", 750, 270),
        mcd_assoc("illustre", 750, 430),
        mcd_assoc("historise", 750, 740),
        mcd_assoc("compose", 980, 320),
        mcd_assoc("contient", 1360, 520),
        mcd_assoc("demande_reset", 1510, 720),
    ]
    edges = [
        {"source": "role", "target": "possede", "label": "1,n"},
        {"source": "possede", "target": "utilisateur", "label": "1,1"},
        {"source": "utilisateur", "target": "passe", "label": "0,n"},
        {"source": "passe", "target": "commande", "label": "1,1"},
        {"source": "menu", "target": "concerne", "label": "0,n"},
        {"source": "concerne", "target": "commande", "label": "1,1"},
        {"source": "utilisateur", "target": "publie", "label": "0,n"},
        {"source": "publie", "target": "avis", "label": "1,1"},
        {"source": "commande", "target": "donne lieu", "label": "0,1"},
        {"source": "donne lieu", "target": "avis", "label": "1,1"},
        {"source": "regime", "target": "adapte", "label": "0,n"},
        {"source": "adapte", "target": "menu", "label": "1,1"},
        {"source": "theme", "target": "propose", "label": "0,n"},
        {"source": "propose", "target": "menu", "label": "1,1"},
        {"source": "menu", "target": "illustre", "label": "0,n"},
        {"source": "illustre", "target": "image_menu", "label": "1,1"},
        {"source": "commande", "target": "historise", "label": "1,n"},
        {"source": "historise", "target": "historique_statut", "label": "1,1"},
        {"source": "menu", "target": "compose", "label": "1,n"},
        {"source": "compose", "target": "plat", "label": "0,n"},
        {"source": "plat", "target": "contient", "label": "0,n"},
        {"source": "contient", "target": "allergene", "label": "0,n"},
        {"source": "utilisateur", "target": "demande_reset", "label": "0,n"},
        {"source": "demande_reset", "target": "password_reset", "label": "1,1"},
    ]
    drawio_file("MCD - Vite & Gourmand", nodes, edges, "docs/database/MCD.drawio")
    png_diagram("MCD - Vite & Gourmand", nodes, edges, "docs/database/MCD.png", size=(2200, 1250))


def use_case_diagram() -> None:
    nodes = [
        {"id": "visiteur", "plain": "Visiteur", "label": "<b>Visiteur</b>", "x": 70, "y": 180, "w": 160, "h": 60, "fill": "#eff6ff", "outline": "#1d4ed8"},
        {"id": "utilisateur", "plain": "Utilisateur", "label": "<b>Utilisateur</b>", "x": 70, "y": 420, "w": 160, "h": 60, "fill": "#eff6ff", "outline": "#1d4ed8"},
        {"id": "employe", "plain": "Employe", "label": "<b>Employe</b>", "x": 1320, "y": 300, "w": 160, "h": 60, "fill": "#eff6ff", "outline": "#1d4ed8"},
        {"id": "admin", "plain": "Administrateur", "label": "<b>Administrateur</b>", "x": 1320, "y": 560, "w": 180, "h": 60, "fill": "#eff6ff", "outline": "#1d4ed8"},
    ]
    cases = [
        ("uc_menus", "Consulter et filtrer les menus", 420, 110),
        ("uc_contact", "Envoyer une demande de contact", 420, 230),
        ("uc_register", "Creer un compte utilisateur", 420, 350),
        ("uc_order", "Commander un menu", 700, 350),
        ("uc_follow", "Suivre une commande", 700, 470),
        ("uc_review", "Donner un avis apres commande terminee", 700, 590),
        ("uc_manage_menus", "Gerer menus, plats et horaires", 980, 170),
        ("uc_manage_orders", "Mettre a jour les commandes", 980, 310),
        ("uc_moderate", "Valider ou refuser les avis", 980, 450),
        ("uc_employees", "Creer/desactiver un employe", 980, 590),
        ("uc_stats", "Consulter statistiques NoSQL", 980, 730),
    ]
    for cid, text, x, y in cases:
        nodes.append({"id": cid, "plain": text, "label": html.escape(text), "x": x, "y": y, "w": 230, "h": 70, "fill": "#ffffff", "outline": "#9a3412"})
    edges = [
        ("visiteur", "uc_menus"), ("visiteur", "uc_contact"), ("visiteur", "uc_register"),
        ("utilisateur", "uc_order"), ("utilisateur", "uc_follow"), ("utilisateur", "uc_review"),
        ("employe", "uc_manage_menus"), ("employe", "uc_manage_orders"), ("employe", "uc_moderate"),
        ("admin", "uc_manage_menus"), ("admin", "uc_manage_orders"), ("admin", "uc_moderate"), ("admin", "uc_employees"), ("admin", "uc_stats"),
    ]
    edge_objs = [{"source": a, "target": b, "label": ""} for a, b in edges]
    drawio_file("Diagramme de cas d'utilisation", nodes, edge_objs, "docs/uml/use-case-diagram.drawio")
    png_diagram("Diagramme de cas d'utilisation", nodes, edge_objs, "docs/uml/use-case-diagram.png")


def class_diagram() -> None:
    names = ["Utilisateur", "Role", "Menu", "MenuPlat", "Plat", "PlatAllergene", "Commande", "CommandeStatut", "Avis", "Regime", "Theme", "Allergene", "ContactMessage", "PasswordReset", "StatistiqueMenu"]
    positions = [(80,120),(80,360),(360,120),(620,120),(880,120),(1140,360),(360,420),(620,420),(360,720),(1140,80),(1140,220),(1400,360),(1400,620),(80,620),(1400,120)]
    attrs = {
        "Utilisateur": ["id", "email", "nom", "prenom", "actif", "createdAt"],
        "Role": ["id", "libelle"],
        "Menu": ["id", "titre", "prixMinimum", "stock", "createdAt"],
        "MenuPlat": ["idMenu", "idPlat", "position"],
        "Plat": ["id", "titre", "typePlat", "description"],
        "PlatAllergene": ["idPlat", "idAllergene"],
        "Commande": ["id", "datePrestation", "distanceKm", "statutActuel", "prixTotal"],
        "CommandeStatut": ["id", "statut", "createdAt"],
        "Avis": ["id", "note", "statut", "moderatedBy"],
        "Regime": ["id", "libelle"],
        "Theme": ["id", "libelle"],
        "Allergene": ["id", "libelle"],
        "ContactMessage": ["id", "titre", "email", "traite"],
        "PasswordReset": ["id", "tokenHash", "expiresAt", "usedAt"],
        "StatistiqueMenu": ["<<Document MongoDB>>", "menuId", "orders", "revenue", "period"],
    }
    nodes = []
    for name, (x,y) in zip(names, positions):
        plain = name + "\n" + "\n".join(attrs[name])
        label = f"<b>{name}</b><br>" + "<br>".join(attrs[name])
        nodes.append({"id": name, "plain": plain, "label": label, "x": x, "y": y, "w": 220, "h": 140})
    rels = [
        ("Role", "Utilisateur", "1 / 0..*"),
        ("Utilisateur", "Commande", "1 / 0..*"),
        ("Utilisateur", "Avis", "auteur 1 / 0..*"),
        ("Utilisateur", "Avis", "moderateur 0..1 / 0..*"),
        ("Utilisateur", "PasswordReset", "1 / 0..*"),
        ("Commande", "Menu", "1"),
        ("Commande", "CommandeStatut", "1 / 1..*"),
        ("Commande", "Avis", "1 / 0..1"),
        ("Menu", "MenuPlat", "1 / 0..*"),
        ("MenuPlat", "Plat", "0..* / 1"),
        ("Plat", "PlatAllergene", "1 / 0..*"),
        ("PlatAllergene", "Allergene", "0..* / 1"),
        ("Menu", "Regime", "1"),
        ("Menu", "Theme", "1"),
        ("Menu", "StatistiqueMenu", "agregat MongoDB"),
    ]
    edges = [{"source": a, "target": b, "label": lab} for a,b,lab in rels]
    drawio_file("Diagramme de classes", nodes, edges, "docs/uml/class-diagram.drawio")
    png_diagram("Diagramme de classes", nodes, edges, "docs/uml/class-diagram.png")


def sequence(name: str, title: str, steps: list[tuple[str, str, str]]) -> None:
    actors = sorted(set([a for a, _, _ in steps] + [b for _, b, _ in steps]), key=lambda x: ["Acteur", "Navigateur", "Controleur", "Service", "SQL", "MongoDB", "Mail"].index(x) if x in ["Acteur", "Navigateur", "Controleur", "Service", "SQL", "MongoDB", "Mail"] else 99)
    xmap = {actor: 120 + i * 210 for i, actor in enumerate(actors)}
    nodes = []
    for actor in actors:
        nodes.append({"id": actor, "plain": actor, "label": f"<b>{actor}</b>", "x": xmap[actor], "y": 100, "w": 150, "h": 50, "fill": "#eff6ff", "outline": "#1d4ed8"})
    edges = []
    y = 210
    for idx, (a,b,msg) in enumerate(steps, 1):
        nodes.append({"id": f"s{idx}", "plain": msg, "label": html.escape(msg), "x": min(xmap[a], xmap[b]) + 45, "y": y, "w": 260, "h": 44, "fill": "#ffffff", "outline": "#d6d3d1"})
        edges.append({"source": a, "target": f"s{idx}", "label": ""})
        edges.append({"source": f"s{idx}", "target": b, "label": ""})
        y += 90
    drawio_file(title, nodes, edges, f"docs/uml/{name}.drawio")
    png_diagram(title, nodes, edges, f"docs/uml/{name}.png")


def make_uml() -> None:
    use_case_diagram()
    class_diagram()
    sequence("sequence-authentication", "SEQ - Authentification", [
        ("Acteur","Navigateur","Saisit email et mot de passe ou demande une reinitialisation"),
        ("Navigateur","Controleur","POST authentification"),
        ("Controleur","SQL","Recherche utilisateur actif et role"),
        ("Controleur","Service","Verifie le hash ou cree un jeton password_resets"),
        ("Service","Mail","Envoie le lien si recuperation demandee"),
        ("Controleur","Navigateur","Ouvre la session ou confirme l'envoi"),
    ])
    sequence("sequence-consultation-commande", "SEQ - Consultation + Commande", [
        ("Acteur","Navigateur","Consulte et filtre les menus"),
        ("Navigateur","Controleur","Demande detail menu"),
        ("Controleur","SQL","Lit menu, plats, allergenes, images, theme et regime"),
        ("Acteur","Navigateur","Saisit prestation, adresse et nombre de personnes"),
        ("Navigateur","Service","Calcule prix, remise, distance et livraison"),
        ("Service","SQL","Cree commande et statut en_attente"),
        ("Service","Mail","Envoie confirmation de commande"),
        ("Controleur","Navigateur","Affiche le recapitulatif"),
    ])
    sequence("sequence-gestion-commande-employe", "SEQ - Gestion commande employe", [
        ("Acteur","Navigateur","Employe ouvre les commandes filtrees"),
        ("Navigateur","Controleur","Demande changement de statut"),
        ("Controleur","SQL","Controle commande, role et transitions autorisees"),
        ("Controleur","Service","Applique le statut et conserve le contact client si besoin"),
        ("Service","SQL","Met a jour commande et historique"),
        ("Service","MongoDB","Declenche la synchronisation des statistiques si besoin"),
        ("Service","Mail","Notifie le client"),
        ("Controleur","Navigateur","Confirme la mise a jour"),
    ])
    sequence("sequence-gestion-avis", "SEQ - Gestion avis", [
        ("Acteur","Navigateur","Client ouvre une commande terminee"),
        ("Navigateur","Controleur","POST avis note/commentaire"),
        ("Controleur","SQL","Verifie commande terminee et absence d'avis"),
        ("Controleur","SQL","Cree avis en_attente rattache a la commande"),
        ("Acteur","Navigateur","Employe modere l'avis"),
        ("Navigateur","Controleur","Valide ou refuse"),
        ("Controleur","SQL","Met a jour statut, moderated_at et moderated_by"),
        ("Controleur","Navigateur","Avis valide visible sur accueil"),
    ])
    sequence("sequence-dashboard-admin-mongodb", "SEQ - Dashboard administrateur MongoDB", [
        ("Acteur","Navigateur","Administrateur consulte le dashboard"),
        ("Navigateur","Controleur","Demande statistiques filtrees"),
        ("Controleur","Service","Controle role administrateur"),
        ("Service","MongoDB","Lit menu_statistics"),
        ("MongoDB","Service","Retourne orders, revenue, averageBasket, period"),
        ("Service","Navigateur","Construit le graphique depuis MongoDB"),
        ("Service","SQL","Synchronisation separee : lit commandes validees"),
        ("Service","MongoDB","Met a jour les agregats menu_statistics"),
    ])


def make_diagrams() -> None:
    make_database_diagrams()
    make_uml()


def main() -> None:
    ensure_dirs()
    make_markdown_docs()
    make_mongodb()
    make_diagrams()


if __name__ == "__main__":
    main()
