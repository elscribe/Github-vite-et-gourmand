# Collections MongoDB

MongoDB est utilise uniquement pour les statistiques du tableau de bord administrateur. Les donnees metier restent dans la base SQL afin de conserver les cles etrangeres, les contraintes et les transactions.

## Collection `menu_statistics`

Objectif : fournir des donnees deja agregees pour les graphiques administrateur.

Exemple de structure :

| Champ | Type | Description |
| --- | --- | --- |
| `_id` | ObjectId | Identifiant du document. |
| `menuId` | Number | Identifiant SQL du menu. |
| `menuName` | String | Nom du menu au moment de l'agregation. |
| `totalOrders` | Number | Nombre de commandes sur la periode. |
| `revenue` | Number | Chiffre d'affaires sur la periode. |
| `averagePersons` | Number | Nombre moyen de personnes par commande. |
| `period.start` | Date/String ISO | Debut de periode. |
| `period.end` | Date/String ISO | Fin de periode. |
| `filters.theme` | String | Theme du menu pour filtrage graphique. |
| `filters.regime` | String | Regime du menu pour filtrage graphique. |
| `updatedAt` | Date/String ISO | Date de recalcul. |

## Pourquoi MongoDB plutot que SQL pour ces donnees ?

L'enonce demande explicitement que les statistiques administrateur viennent d'une base non relationnelle. Les statistiques sont des donnees de lecture, agregees et recalculables depuis SQL. Les stocker en documents MongoDB permet de servir rapidement un tableau de bord et de conserver des snapshots par periode sans alourdir le modele transactionnel SQL.

## Regle de synchronisation

La source de verite reste SQL. Les documents `menu_statistics` sont recalcules apres validation, modification ou annulation d'une commande, ou par une tache planifiee. En cas d'ecart, SQL est prioritaire et MongoDB doit etre regenere.
