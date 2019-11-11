# Sprint Bonus

## Voter +1 sur question et réponse

- On ajoute les relations entre vote et question dans l'entité User.
    - Afin que le couple User/Question soit unique, créons une entité UserQuestionVote donc la clé primaire sera composée sur les 2 ids.
- Ajout d'un bouton pour voter sur le template question, qui va renvoyer vers une méthode pour créer une nouveau vote et y associer le user et la question.
    - En cas de doublon on intercepte l'exception et on affiche un Flash Message comme quoi on a déjà voté sur cette question.
    - => attention si on oublie le `use` de l'Exception ça ne le précise par et l'erreur n'est pas catchée.

## Questions/réponses bloquées visibles par modérateur

- Ajout d'une `AuthorizationCheckerInterface` sur Question `list()` et `show()`.
- Conditionnement du critère de recherche selon si modérateur ou non.
- Modification de la requête custom `findByTag()` pour traiter ce cas.
- Rien à faire côté template, tout se situe au niveau des requêtes.

## Ajouter une pagination

- On va mutualiser la requête de liste avec le tag et le rôle du modérateur.
    - Attention on a omis le `leftJoin()` qui permet d'obtenir les questoins sans tag ! On l'ajoute.
- On ajoute le Paginator "en dur" (on teste avec les valeurs 0 et 7 par ex.).
- On gère les pages :
    - On ajoute `$start` et `$perPage` à la méthode `findByIsBlockedAndTagOrderByVotes()`.
    - Ajoutons un paramètre `questionsPerPage` dans `services.yaml` afin de configurer cette valeur hors du contrôleur.
    - `setFirstResult()` prend donc en argument `$start * $perPage`.
    - Ajout du template de pagination (un sans tag, un avec => peut probablement être optimisé...).

## Bonus AJAX

> Sur Vote +1

- On transforme la route `question/vote` pour recevoir du POST et renvoyer du JSON.
- On ajoute un block Twig js pour y mettre, seulement sur la page question/show.
    - On installe jQuery pour plus de facilité.
    - On crée un code JS qui appelle `/question/vote/{id}` en POST depuis un clic sur le bouton de vote.
    - Sur ce bouton de vote on met l'id de la question en attribut HTML pour la récupérer depuis JS.