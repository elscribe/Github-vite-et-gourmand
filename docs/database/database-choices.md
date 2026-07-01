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
