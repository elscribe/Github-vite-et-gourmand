# Dossier MongoDB

Ce dossier contient la partie non relationnelle du projet.

MongoDB sert les agregats statistiques du tableau de bord administrateur :
commandes par menu, chiffre d'affaires par menu et evolution mensuelle. Les
donnees metier restent dans SQL ; les collections MongoDB sont des agregats
recalculables et l'application garde un secours SQL si MongoDB est indisponible
en local.
