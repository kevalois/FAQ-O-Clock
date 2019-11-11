# FAQ-O-Clock

## Objectif

Développer un site de questions/réponses sur le modèle de [Quora](https://www.quora.com/), ou encore [StackOverflow](https://stackoverflow.com) : La FAQ-O-Clock !

- Le site répertorie des **questions** (auteur, intitulé, corps de la question), et plusieurs **réponses** (auteur, corps de la réponse).
- **Les visiteurs doivent pouvoir créer un compte sur le site**  (revient à créer un nouvel utilisateur), ils deviennent alors **utilisateurs**.
- Les **utilisateurs inscrits posent les questions**, et peuvent également **proposer des réponses** aux questions déjà posées.
- **Tout le monde peut consulter** les contenus, mais **il faut être identifié pour pouvoir poser une question ou proposer une réponse**.

## Instructions

1. **Lisez bien la totalité de l'énoncé** avant de commencer, **prenez le temps de décomposer ce qui est demandé** et d'identifier quels outils peuvent vous aider à obtenir ce résultat simplement.
2. Allez aussi loin que possible, mais **commencez par implémenter ce que vous savez faire, quitte à simplifier dans un premier** temps, pour y revenir et ajouter des fonctionnalités ensuite.
3. Tout peut être fait à l'aide des composants de Symfony que vous avez déjà vus en cours (ou presque :grimacing:).
4. Pensez à l'ergonomie :nerd_face: => Flash messages, navigation spécifique le cas échéant.
5. N'oubliez pas de **fournir un export de votre base de données** (les données seules) OU **fournir des Fixtures** (mieux, mais pas prioritaire). **S'il y'a des users/mots de passe indiquez-les dans un README à l'intention du correcteur.**
6. **Utilisez Trello pour organiser vos tâches !** Vous y verrez plus clair, _vraiment_.

## Structure du site

### Pages accessibles à tous les visiteurs
- **Accueil** : voir toutes les questions (les plus récentes d'abord),
- **Question** (voir la question, avec toutes ses réponses),
- **Inscription** (formulaire d'inscription),
- **Connexion** (formulaire de login).

### Pages accessibles aux utilisateurs identifiés
- **Poser une question**,
- Sur la page **Question**, un formulaire permettant d'**Ajouter une réponse** à cette question (directement dans la page, sous la question),
    - :hand: => Dans la liste des réponses, un bouton permet de valider une réponse. **Seul l'utilisateur qui a posé la question** peut indiquer une réponse comme étant **valide. La réponse validée doit s'afficher de façon distincte et en premier dans la liste des réponses**.
- **Mon Profil** : permet de consulter ses propres informations utilisateur, la liste de ses questions, et de ses réponses, avec un lien pour s'y rendre.
    - Sur cette page, un lien **Modifier mon Profil** qui permet de modifier ses propres informations (au minimum username, email, mot de passe).

### Fonctions de modération

Certains utilisateurs sont aussi **modérateurs**. Les modérateurs peuvent **bloquer une question ou une réponse** non-conforme à la charte du site. **Un admin peut donner les droits de modérateur à un utilisateur**.

- Sur la liste des questions (page Accueil) et la liste des réponses (page Question), **un bouton permet aux modérateurs de bloquer ou débloquer facilement une question ou une réponse**. Quand elle est bloquée, celle-ci ne s'affiche plus (ni depuis les listes, ni en accès direct), **BONUS : sauf pour les modérateurs et les admins**.
- Page **Admin users** permet à l'admin de changer le statut d'un utilisateur.

### Tags

Les questions peuvent être associées à plusieurs **tags** (mots-clé).

- page **Admin tags** accessible par l'admin et les modérateurs, leur permettant de créer/modifier/supprimer des tags.
- dans la page **Poser une Question**, **ajouter la possibilité de choisir les tags associés à la question** (liste de checkboxes, ou autre).
- **Afficher les tags associés** à une question sur la _liste des questions (Accueil)_ et sur la _page Question_ (**avec un lien sur chaque tag** qui affiche les questions filtrées par tag) => renvoie vers page Question/Tag.
- **page Question/Tag** : consulter les questions associées à ce tag (accessible également depuis un nuage de tags sur la page d'accueil ou dans une sidebar). Afficher le tag sélectionné en haut de page.

### Navigation

- Afficher un menu permanent, cohérent, qui permet de naviguer où c'est possible en fonction du rôle de l'utilisateur.

## BONUS

#### Ajouter un système de vote

- Sur la page de liste des questions (Accueil et Question/Tag), **les utilisateurs connectés peuvent voter "+1" pour une question**.
- Sur la page d'une question, **les utilisateurs connectés peuvent voter "+1" pour une réponse**.
- **Afficher alors les questions** (page Accueil et Question/Tag) **et les réponses** d'une question (page Question) **par nombre de votes décroissant**.

#### Réaliser au moins l'une des opérations suivante en AJAX

- Voter "+1",
- Valider une réponse,
- Bloquer/débloquer une question ou une réponse.

#### Ajouter une pagination

On veut afficher maxi 7 questions sur une page présentant une liste de questions (Accueil, Question/Tag). Vous pouvez faire une requête Google du genre https://www.google.fr/search?q=doctrine+paginator pour trouver des informations utiles.

Vous pouvez soit utiliser un Bundle tout fait, soit le faire à la main avec Doctrine Paginator et un peu de code custom.

## Le mot de la fin

Bon courage :slightly_smiling_face: :muscle: et n'oubliez pas d'écrire une petite note dans un README à l'intention du prof' pour décrire ce que vous avez fait, ou pas fait.

Tout schéma ou document (même scanné !) ayant servi à créer l'appli sera le bienvenu dans le repo final !

Merci.

Et _Think Trello :wink:_
