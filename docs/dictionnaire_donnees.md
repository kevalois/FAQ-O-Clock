# Dictionnaire de données

> Entités avec champs, description et type de donnée associée.

## Question
|Nom|Description|Type|
|-|-|-|
|**title**|titre de la question|texte court
|**body**|corps de texte la question|texte long
|**created_at**|date de création de la question|date/heure
|**votes**|nombre de votes|entier
|**is_blocked**|question bloquée par un modérateur|booléen
|**is_solved**|la questoin a-t-elle une réponse acceptée ?|booléen
|**user**|auteur de la question|_relation_
|**tags**|liste des catégories associées|_relation_

## Answer
|Nom|Description|Type|
|-|-|-|
|**body**|corps de texte la réponse|texte long
|**created_at**|date de création de la réponse|date/heure
|**votes**|nombre de votes|entier
|**is_validated**|réponse validée par l'auteur de la question|booléen
|**is_blocked**|réponse bloquée par un modérateur|booléen
|**question**|question associée la réponse|_relation_
|**user**|auteur de la réponse|_relation_

## User
|Nom|Description|Type|
|-|-|-|
|**username**|nom d'utilisateur de la personne inscrite|texte court
|**password**|mot de passe de l'utilisateur|texte court
|**email**|email de l'utilisateur|texte court
|**role**|rôle de l'utilisateur|_relation_
|**questions_voted**|liste des questions votées|_relation_
|**answers_voted**|liste des réponses votées|_relation_

## Tag
|Nom|Description|Type|
|-|-|-|
|**name**|nom du mot-clé|texte court

## Role
|Nom|Description|Type|
|-|-|-|
|**name**|nom du rôle|texte court
