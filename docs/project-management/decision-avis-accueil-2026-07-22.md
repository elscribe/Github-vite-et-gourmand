# Decision de perimetre - avis affiches sur l'accueil

Date : 2026-07-22
Statut : decision projet documentee

## Decision

L'application Vite & Gourmand affiche sur la page d'accueil des avis clients
valides, issus du parcours de commande et de moderation.

La selection manuelle d'avis "mis en avant" par l'administrateur est placee hors
perimetre ECF et documentee comme evolution future.

## Justification

L'enonce demande que la page d'accueil presente les avis clients valides. Le
besoin principal est donc de prouver que :

- un client peut deposer un avis apres une commande terminee ;
- l'avis reste en attente tant qu'il n'est pas modere ;
- un employe ou un administrateur peut valider ou refuser l'avis ;
- seuls les avis valides sont visibles publiquement.

Ajouter une selection editoriale manuelle demanderait une regle supplementaire :
champ ou table de mise en avant, ecran d'administration, ordre manuel, tests de
priorisation et documentation dediee. Cette fonctionnalite releve davantage d'un
module CMS ou marketing que du coeur obligatoire du projet.

## Choix retenu pour le MVP ECF

- Afficher automatiquement un nombre limite d'avis valides sur l'accueil.
- Garder la moderation comme filtre obligatoire avant publication.
- Ne pas ajouter de bouton "mettre en avant" dans l'administration pour eviter
  une complexite inutile avant la recette finale.

## Evolution possible apres ECF

Une version future pourrait ajouter :

- un champ `mis_en_avant` sur la table `avis` ;
- un ordre d'affichage manuel ;
- un filtre admin pour choisir les avis publics prioritaires ;
- un compteur limitant le nombre d'avis mis en avant.

Cette evolution n'est pas bloquante pour la conformite ECF tant que l'affichage
des avis valides est bien operationnel sur la page d'accueil.
