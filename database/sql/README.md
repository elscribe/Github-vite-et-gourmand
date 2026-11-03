# Dossier SQL

## Mise à jour du stock pour une base existante

Le fichier `ajouter_stock_reserve.sql` ajoute à `commandes` un indicateur
permettant de restituer uniquement les places réellement réservées. Sur une
base existante, sauvegarder la base, vérifier que la colonne n'existe pas,
puis interrompre les écritures de commandes, appliquer ce script **une seule
fois**, installer le code modifié et reprendre les écritures après contrôle.
L'ancien code ne doit pas créer de commandes entre la migration et la mise en
service du nouveau : elles ne réserveraient pas de place. Le nouveau code ne
doit pas être activé avant l'ajout de la colonne.
Ne pas réexécuter `create_database.sql` sur une base à conserver.

Les commandes déjà présentes reçoivent la valeur `0` : leur annulation ne
réaugmente pas le stock. Les stocks courants sont conservés comme point de
départ ; l'administrateur reste responsable de leur ajustement manuel. Si le
stock change pendant qu'il modifie une fiche, l'enregistrement est refusé et
il doit recharger le menu.
Pour une nouvelle base, `create_database.sql` crée déjà la colonne : ne pas
appliquer ensuite le script d'ajout.

Ce dossier contient les ressources MySQL ou MariaDB pour la base de donnees
relationnelle.

Les requetes metier sont ecrites avec PDO dans les classes de `app/Models/`.
