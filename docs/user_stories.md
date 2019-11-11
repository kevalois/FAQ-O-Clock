# User stories

Via la hiérachie des rôles, chaque rôle suivant accède aux actions des rôles précédents (cela fonctionnera bien sur ce type d'application).

L'étape suivante sera de créer pour chacune des stories, une à plusieurs tâches, idéalement dans un tableau kanban (Trello, Github, autre).

## Utilisateur anonyme

En tant qu'**utilisateur anonyme**, je veux...
- Afficher la liste des questions.
    - Afficher la liste des question selon un tag cliqué.
        - Depuis la question.
        - Depuis un nuage de tags sur la home page.
- Afficher une page question.
    - Lire les réponses présentes.
- M'inscrire sur le site.
- Me connecter au site.

## Utilisateur inscrit

En tant qu'**utilisateur connecté**, je veux...
- Poser une question.
    - Y ajouter des tags.
- Proposer des réponses aux questions des autres membres.
- Accepter une réponse proposée à une de mes questions.
    - La réponse acceptée s'affiche en premier dans la liste et son style est spécifique.
- Afficher ma page profil.
    - Consulter mes informations.
    - Modifier mes informations.
    - Voir mes questions posées.
    - Voir mes réponses proposées

## Modérateur

En tant que **modérateur**, je veux...
- Bloquer ou débloquer une question.
- Bloquer ou débloquer une réponse.
- Gérer les tags du site.

## Administrateur
En tant qu'**admin** je veux...
- Changer le rôle d'un membre en modérateur.

## Application

> Ici nous indiquons les user stories relatives à l'environnement de l'application et au fonctionnalités du framework utilisé.

En tant qu'**application**, je veux...
- Fonctionner avec un framework (Symfony).
- Utiliser une base de données.
- Gérer des droits d'accès (système de sécurité).
- Appliquer des contraintes de validation sur les formulaires.
- Afficher des flash messages selon l'action effectuée.
- Permettre la création de données factices (fixtures).

## Bonus
- Les utilisateurs connectés **peuvent voter** +1 ou -1 sur question et réponse
- Les questions et réponses bloquées **sont visibles par les modérateurs** (style spécifique).
- Réaliser au moins l'une des opérations suivante **en AJAX**.
    - Voter "+1",
    - Valider une réponse,
    - Bloquer/débloquer une question ou une réponse.
- **Ajouter une pagination** sur les listes de questions.

