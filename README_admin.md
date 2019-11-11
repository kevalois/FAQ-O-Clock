# Sprint admin

## Configurer les accès et les rôles

### Ajout d'une entité Role.

- `make:entity` pour créer `Role` avec `name` et `roleString`. Le premier contiendra le nom du droit en clair, disons `Utilisateur` et le second la chaine du rôle, `ROLE_USER`.
- L'entité `User` est déjà prête il ne reste qu'à ajouter une propriété `role` (au singulier !) avec `make:entity`. On modifie ensuite `getRoles()` pour retourner `getRole()` dans le tableau attendu.

### Modifier les fixtures en conséquence.

> En cas de galère avec les migrations, on drop toutes les tables et on éxécute à nouveau les migrations.

- On crée un groupe de rôles.
- On groupe les users dans ces rôles.
- On modifie le code pour boucler sur les groupes de users, créer les rôles à la volée et les associer aux users concernés.
- On éxécute les fixtures => on obtient des users avec de droits :muscle:

### Assignation de `ROLE_USER` sur le user qui s'inscrit.

- Pour boucler la boucle, tout user qui s'inscrit doit recevoir le droit `ROLE_USER`. On va donc chercher ce droit spécifiquement via `$roleRepository->findOneByRoleString('ROLE_USER')` et non par `id` comme certains élèves font, car avec les fixtures on ne peut pas se fier aux ids... On en profite pour utiliser une méthode magique du Repository de Doctrine.
- On associe ce droit au nouveau user avant de flusher.

## Gérer les tags du site

- On passe par `make:crud` ou à la mano pour créer les pages de list, add, edit, show, delete pour les tags.
    - Si à la mano on crée un contrôleur et un formulaire via `make`.
- On préfixe la route du contrôleur `@Route("/admin/tag")` afin de sécuriser nos URLs plus facilement.
- On modifie l'`access_control` en conséquence.
- On ajoute un lien conditionné dans le menu du header.
- On supprime les questions associées dans le form, on ajoute le `novalidate` dans les options par défaut et une contrainte de `NotBlank()` sur le champ.
- On peaufine l'affichage du CRUD selon ses préférences, ici par ex. :
    - Remonter lien "Create new", le renommer en "Nouveau Tag" et ajouter une class de bouton.
    - Mettre des classes de bouton small sur les liens show et edit.
    - Supprimer la colonne `Id`.
    - Traduire les textes en français.
    - Ajout de Flash Messages.

## Changer le rôle d'un membre en modérateur

- Ici on veut juste une liste de users et une page pour modifier leur statut => on va éviter le CRUD et passer par `make:form` et utiliser le contrôleur `User` existant. Créons une route `/admin/user`.
- On met à jour l'`access_control` sur le rôle `ROLE_ADMIN`.
- On ajoute le lien conditionné dans le header.
- On ajoute une `__toString()` ou on spécifie le champ à afficher dans le `ChoiceType`.

## Oubli : Accepter la réponse de l'auteur seulement

- user doit être connecté (ACL) => update de l'`access_control`.
- user connecté = auteur question => on ajoute cette vérification sinon `throw 403` dans `AnswerController`.

## Bloquer ou débloquer une question
## Bloquer ou débloquer une réponse

> Nos entités ont déjà les champs requis, `isBlocked`. Gérons la partie modération puis la partie affichage.

- Créons une route qui permet de _toggle_ le statut de la question, ainsi on simplifie l'écriture du code.
- Ajout d'un lien conditionné par le droit `ROLE_MODERATOR` (ACL + template).
    - On ajoute une classe CSS pour conditionner l'affichage.
- On fait de même pour les réponses.
- Les questions bloquées ne doivent pas apparaitrent aux users.
- Idem réponses bloquées.