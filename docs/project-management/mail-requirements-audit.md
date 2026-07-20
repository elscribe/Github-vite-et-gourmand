# Audit des exigences email

Date de verification : 16 juillet 2026.

Source controlee : enonce PDF local, pages 5 a 9.

## Verdict

L'enonce demande plusieurs emails automatiques. Le code contient maintenant un
`MailService` centralise. En local, le mode `log` ecrit les notifications dans
`storage/logs/mail.log`, ce qui permet de tester les emails sans serveur SMTP.

Les variables `MAIL_*` sont documentees dans `.env.example`. Un vrai SMTP peut
etre branche ensuite sans changer les controleurs.

## Exigences relevees dans l'enonce

| Parcours | Exigence email | Etat actuel | Priorite |
|---|---|---|---|
| Creation de compte visiteur | Envoyer automatiquement un mail de bienvenue apres inscription | Implemente en mode log | Obligatoire |
| Mot de passe oublie | Envoyer un lien de reinitialisation par mail | Implemente en mode log avec token expire | Obligatoire |
| Commande client | Envoyer un mail confirmant la commande apres validation du formulaire | Implemente en mode log | Obligatoire |
| Commande terminee | Notifier le client par mail qu'il peut donner son avis | Implemente sur passage au statut `terminee` | Obligatoire |
| Retour de materiel | Notifier le client par mail qu'il a 10 jours ouvres avant frais de 600 EUR | Implemente sur statut `en_attente_retour_materiel` | Obligatoire si materiel prete |
| Creation compte employe | Envoyer un mail a l'employe pour notifier la creation du compte, sans transmettre le mot de passe | Implemente, mot de passe non affiche et non envoye | Obligatoire admin |
| Contact public | Envoyer la demande de contact par mail a l'entreprise | Implemente en mode log + stockage base | Obligatoire |

## Nuance importante

L'enonce demande un mail de confirmation apres la commande client. Il ne dit pas
explicitement qu'un mail doit partir quand un employe passe la commande au statut
`acceptee`. A ce stade, le statut accepte est visible dans le suivi de commande.

## Solution appliquee pour le MVP

Le `MailService` utilise deux modes :

- mode `log` en local : les emails sont ecrits dans un fichier de log ou en base ;
- mode `smtp` en production : les emails partent avec les variables `MAIL_*`.

Cela permet de tester les emails sans dependance externe pendant le developpement
et de brancher un vrai SMTP au deploiement.

## Tests a prevoir

| Test | Resultat attendu |
|---|---|
| Inscription client | Un email de bienvenue est trace ou envoye |
| Mot de passe oublie | Un email contient un lien de reinitialisation |
| Creation commande | Un email de confirmation est trace ou envoye au client |
| Passage commande a `terminee` | Un email d'invitation a laisser un avis est trace ou envoye |
| Passage commande a `en_attente_retour_materiel` | Un email de rappel materiel est trace ou envoye |
| Creation employe | Un email de creation de compte est trace ou envoye sans mot de passe |
| Contact public | Un email de demande de contact est trace ou envoye a l'entreprise |

## Phrase pour le jury

> L'enonce exige plusieurs notifications email. J'ai centralise ces envois dans
> un service mail testable en mode log local. En production, il suffira de
> renseigner les variables SMTP pour envoyer les memes notifications.
