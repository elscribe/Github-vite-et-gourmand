# Collections MongoDB

MongoDB est utilise pour servir rapidement les graphiques du tableau de bord administrateur. Les donnees metier restent dans MariaDB/MySQL ; les collections ci-dessous contiennent des agregats recalculables depuis les commandes SQL.

## Base

- Base : `vite_gourmand`
- Scripts :
  - `create_collections.js` : creation des collections, validateurs et index.
  - `seed_mongodb.js` : donnees statistiques de demonstration.

## `menu_statistics`

Statistiques agregees par menu.

| Champ | Type | Description |
| --- | --- | --- |
| `menuId` | NumberInt | Identifiant SQL du menu. |
| `menuTitle` | String | Titre du menu. |
| `orders` | NumberInt | Nombre de commandes du menu. |
| `revenue` | Number | Chiffre d'affaires du menu. |
| `averageBasket` | Number | Panier moyen du menu. |
| `averageRating` | Number | Note moyenne des avis valides. |
| `lastOrder` | Date | Derniere commande du menu. |
| `updatedAt` | Date | Date de recalcul. |

Index :

- `menuId` unique.
- `revenue` descendant.
- `updatedAt` descendant.

## `monthly_statistics`

Statistiques agregees par mois.

| Champ | Type | Description |
| --- | --- | --- |
| `month` | String | Mois au format `YYYY-MM`. |
| `revenue` | Number | Chiffre d'affaires du mois. |
| `orders` | NumberInt | Nombre de commandes du mois. |
| `averageBasket` | Number | Panier moyen mensuel. |
| `bestSellingMenu` | String | Menu le plus vendu du mois. |
| `updatedAt` | Date | Date de recalcul. |

Index :

- `month` unique.
- `revenue` descendant.

## `menu_monthly_statistics`

Statistiques agregees par menu et par mois. Cette collection sert au tableau de bord administrateur pour filtrer par menu et par periode sans recalculer depuis SQL.

| Champ | Type | Description |
| --- | --- | --- |
| `menuId` | NumberInt | Identifiant SQL du menu. |
| `menuTitle` | String | Titre du menu. |
| `month` | String | Mois au format `YYYY-MM`. |
| `orders` | NumberInt | Nombre de commandes du menu sur le mois. |
| `revenue` | Number | Chiffre d'affaires du menu sur le mois. |
| `averageBasket` | Number | Panier moyen du menu sur le mois. |
| `averageRating` | Number | Note moyenne des avis valides. |
| `lastOrder` | Date | Derniere commande du menu sur le mois. |
| `updatedAt` | Date | Date de recalcul. |

Index :

- `menuId` + `month` unique.
- `month`.
- `revenue` descendant.

## `dashboard_statistics`

Snapshots statistiques du tableau de bord.

| Champ | Type | Description |
| --- | --- | --- |
| `generatedAt` | Date | Date de generation du snapshot. |
| `totalRevenue` | Number | Chiffre d'affaires de la periode affichee. |
| `totalOrders` | NumberInt | Nombre de commandes de la periode affichee. |
| `activeMenus` | NumberInt | Nombre de menus actifs. |
| `topMenu` | String | Menu dominant sur la periode affichee. |
| `averageBasket` | Number | Panier moyen de la periode affichee. |
| `averageRating` | Number | Note moyenne de la periode affichee. |

Index :

- `generatedAt` descendant.
- `topMenu`.

## Regle d'utilisation dans le MVP code

MariaDB/MySQL reste la source de verite metier. Dans le MVP code, les
collections MongoDB sont initialisees par les scripts `create_collections.js` et
`seed_mongodb.js`, puis lues par `StatisticsModel` via `mongosh`.

Le tableau de bord administrateur lit principalement
`menu_monthly_statistics` pour appliquer les filtres par menu et par periode.
`menu_statistics` sert notamment au comptage des menus actifs dans le resume.

Si MongoDB ou `mongosh` ne sont pas disponibles, `StatisticsModel` utilise un
secours SQL local pour garder le dashboard lisible. Un service d'agregation
dedie pourrait etre ajoute plus tard pour recalculer et ecrire automatiquement
les collections MongoDB apres chaque changement de commande.
