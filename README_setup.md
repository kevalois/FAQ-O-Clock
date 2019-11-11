# Sprint Setup

## Installer Symfony

- `composer create-project symfony/website-skeleton faq`

Le cas échéant remonter tout le dossier Symfo dans le repo git cloné depuis l'invitation.  
=> Par ex. depuis le dossier Symfo créé `mv . ..` (déplace tout du dossier courant au dossier supérieur).

## Configurer une BDD

- Modifier le `.env` selon votre configuration et ajouter le nom de la future base de données.
    - Par ex. `DATABASE_URL=mysql://root:password@127.0.0.1:3306/faq`
- On crée la base `php bin/console doctrine:database:create`

## Implémenter le MCD

Nous allons créer toutes les entités du MCD avec la ligne de commande `php bin/console make:entity`, définir les champs puis les relations.

Nous omettrons à ce stade les votes (bonus) et ferons juste attention à implémenter des champs corrects pour le futur `User`, à savoir à minima `username` et `password`. Le rôle sera géré plus tard.

**On s'appuie sur le dico de données et sur le MCD pour réaliser cela.**

On effectue et on applique la migration : 
- `php bin/console make:migration`
- `php bin/console doctrine:migrations:migrate`
- On vérifie que notre BDD est correcte (via le concepteur de PMA par exemple).

![](docs/assets/MLD-PMA.png)

## Créer des fixtures

A ce stade et avant l'intégration, cela peut-être judicieux d'avoir des données de test.

On installe DoctrineFixturesBundle que nous utiliserons conjointement avec Faker :
- `composer require --dev doctrine/doctrine-fixtures-bundle`
- `composer require fzaninotto/faker`

Nous bootstrapons un fichier de fixtures via :
- `php bin/console make:fixtures`

Nous y intégrons Faker, le populator Doctrine et réglons les détails pour chaque entité.
- On commente la ligne qui pose pb via `Call to a member function contains() on null` dans `Entity\Question.php`.
- On associe à la main les ManyToMany (lien buggué entre Doctrine et Faker).
- On vérifie que nos données sont cohérentes via PMA.