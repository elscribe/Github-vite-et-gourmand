# Audit final avant dépôt - Vite & Gourmand

Date : 23 juillet 2026.

Objet : dernier contrôle de cohérence avant envoi du projet à Studi, sur les
trois volets documentation, code et design. Ce document consolide les audits
précédents (énoncé, RGAA, cohérence Notion/GitHub) et l'état des corrections
appliquées aujourd'hui.

## 1. Documentation

| Point | Statut | Détail |
|---|---|---|
| Copie Studi finale (docx + PDF) | **Corrigé** | Le login administrateur affiché était faux (`admin@vitegourmand.fr`) dans le bloc obligatoire "SANS CES ÉLÉMENTS, VOTRE COPIE SERA REJETÉE". Corrigé en `admin.jose@vitegourmand.test` dans le `.docx`, PDF et images de page régénérés et vérifiés visuellement. |
| Copie Studi "travail" (21/07) | Vérifié, non modifié | Contenait déjà la bonne valeur, aucune action nécessaire. |
| Notion (crédentials démo, périmètre employé/admin, style) | Corrigé lors de la passe précédente | Voir les pages Informations de rendu, Livrables finaux, Cahier des charges, UML. |
| README GitHub et docs les plus visibles | Corrigé lors de la passe précédente | Accents restaurés, cohérent avec le code (périmètre admin). |
| Reste des ~50 fichiers GitHub non accentués | Laissé en l'état, décision assumée | Risque de régression cosmétique jugé supérieur au bénéfice ; le jury Studi est humain, pas un détecteur automatique. |
| Checklist de rendu (`checklist-rendu-final-2026-07-23.md`) | Partiellement coché, normal | Les cases restantes (infos candidat, test en navigation privée Notion/Figma/GitHub, dépôt final) demandent une action manuelle de ta part, pas un audit de code. |
| Audit RGAA du 23/07 | Mis à jour | Le statut "à corriger avant rendu" a été changé en "corrigé", cohérent avec le CSS modifié aujourd'hui. |

## 2. Code

| Point | Statut | Détail |
|---|---|---|
| Règles métier (prix, remise, statuts commande, mot de passe, rôles) | Conforme | Vérifié ligne à ligne contre l'énoncé lors de l'audit précédent, aucune régression depuis. |
| Formulaire de commande : champs nom/prénom/email/GSM auto-remplis | **Gap ouvert, non corrigé** | L'énoncé demande un pré-remplissage automatique depuis le compte ; `orders/create.php` ne les affiche pas du tout. Je n'ai pas touché ce fichier : ajouter ces champs implique de modifier le contrôleur et la vue à quelques jours du rendu, ce qui n'a pas été demandé explicitement. À trancher : je peux l'implémenter si tu me le confirmes. |
| Contraste RGAA (`--color-accent` comme texte) | **Corrigé aujourd'hui** | Nouvelle variable `--color-accent-text: #8a641e` (≥4.5:1 sur tous les fonds clairs mesurés). Appliquée aux 21 usages concernés ; les 9 usages sur fond bordeaux foncé (déjà conformes à 6.5:1) sont restés inchangés. |
| Gestion clavier (Échap, focus overlays) | Conforme | Vérifié dans `app.js`, rien à corriger. |
| MailService (pas d'envoi SMTP réel) | Comportement documenté, pas un bug | Le mode "log" écrit dans `storage/logs/mail.log`, cohérent avec le README. À assumer tel quel ou expliciter à l'oral si questionné. |

## 3. Design (Figma)

| Point | Statut | Détail |
|---|---|---|
| Comparaison frame par frame Figma ↔ code | **Bloqué, en attente de toi** | Le MCP Figma ne voit que la page "00 - Design System" ouverte dans l'app desktop ; aucun wireframe/maquette n'est accessible depuis ici. Il faut ouvrir les pages concernées dans Figma desktop pour que je puisse comparer, ou me donner des liens de frames directs. |
| Cohérence noms menus/statuts/boutons Figma ↔ Notion ↔ appli | Non vérifié | Dépend du point précédent. |

## Ce qu'il reste à faire de ton côté avant dépôt

1. Décider si le gap du formulaire de commande (auto-remplissage) doit être corrigé avant l'envoi.
2. Ouvrir les pages Figma pertinentes si tu veux que je fasse la comparaison frame par frame.
3. Compléter les cases manuelles de la checklist : infos personnelles candidat, test des liens Notion/Figma/GitHub en navigation privée, dépôt final sur Studi.

En dehors de ces trois points (deux hors de mon contrôle, un qui attend ta
décision), les incohérences identifiées lors de cet audit ont été corrigées :
identifiant admin de la copie Studi, cohérence Notion/GitHub, contraste RGAA.
