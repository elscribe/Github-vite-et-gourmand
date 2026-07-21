# Contexte permanent des audits ECF

Date de creation : 2026-07-21

## Source de verite

Pour les audits du projet Vite & Gourmand, la source de verite principale est
le depot local :

```text
/Users/jordanmf/Documents/Programation/studi/Projets/ECF/Vite&Gourmand/Github
```

GitHub sert ensuite a verifier la publication, la coherence des branches et les
livrables disponibles pour le jury. Il ne doit pas remplacer l'analyse locale
tant que des modifications ne sont pas poussees.

## Methode d'audit

Chaque audit doit partir des pages de l'enonce et verifier les demandes dans un
ordre applicatif logique. Pour chaque demande, le statut doit distinguer :

- fonctionnalite operationnelle en local ;
- fonctionnalite testee avec preuve ;
- fonctionnalite documentee ;
- code pret a etre pousse sur la branche de travail ;
- code pret a etre integre sur `develop` ou `main`.

## Regle de decision

Une fonctionnalite peut etre consideree prete pour `main` seulement si elle est
operationnelle, testee, documentee et coherentement integree avec l'architecture
du projet. Les ecarts doivent separer les bloqueurs de conformite des simples
ameliorations de finition.
