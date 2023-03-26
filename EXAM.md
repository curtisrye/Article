# Test PHP 

## Sujet

Développer une petite application web qui permet de gérer des articles à partir d’une API. Il faut s’imaginer que c’est le début d’une grosse application qui sera amenée à évoluer. Chaque élément doit être pensé pour pouvoir évoluer, être maintenu facilement et être performant.

- L’API doit nécessiter une authentification pour fonctionner
- L’API doit être RESTful et supporter le format JSON au minimum
- Un article doit être composé au minimum des éléments suivants :
    - Titre (texte - max 128 caractères)
    - Contenu (texte)
    - Auteur (user)
    - Date de publication (date et heure)
    - Statut = brouillon, publié, supprimé
        - Si on passe en statut “publié” on ne peut pas mettre de date de publication car elle se renseigne automatiquement à aujourd’hui
        - Si on passe en statut “brouillon” on peut mettre une date (future) de publication facultativement
        - Si nécessaire l’état “supprimé” peut être lié autrement que via le statut, mais les données doivent restées en bdd (mais plus dispo dans l’API) lorsqu’un article est supprimé
- L’API doit contenir un système de validation
- Un article doit pouvoir être créé (avec le statut brouillon ou publié uniquement)
- Un article doit pouvoir être modifié (uniquement s’il est en brouillon)
- Un article doit pouvoir être passé de brouillon vers publié ou supprimé
- Un article doit pouvoir être passé de publié vers supprimé ou brouillon
- Un article supprimé n’est plus accessible
- Il est nécessaire d’avoir un moyen de lister les articles brouillon et publiés (les articles supprimés ne doivent jamais apparaître dans l’API).
- Il est nécessaire d’avoir des tests unitaires.
- Vous devrez inclure un fichier `README.md` avec la documentation de l’API et qui détaille aussi les dépendances et les étapes nécessaires pour faire fonctionner le projet.

Imaginez que ce README sera utilisé par les développeurs de votre équipe mais aussi pour le déploiement sur les environnements de staging et de production de l’application.

La partie front n’est pas requise. L’évaluation portera uniquement sur le côté back/API.

## Quelques notes et contraintes supplémentaires

- Vous êtes libre d’utiliser le framework de votre choix, mais il est nécessaire d’utiliser un framework open-source avec une documentation disponible librement sur internet.
- Vous ne devez pas utiliser d’outil/framework permettant de générer une API presque sans avoir à coder ([API Platform](https://api-platform.com/) par exemple). Eh oui ! Le but c’est de voir comment vous codez 🙂.
- Toute fonctionnalité supplémentaire sera appréciée (gestion des droits, recherche, pagination, données de test, script d’installation, format html/markdown dans les articles, …).
- Il n’y a aucune contrainte sur les modules, base de données, librairies, outils, *etc*.

## Évaluation

- L’évaluation portera principalement sur deux points :
    - La qualité du code et le fait qu’il n’y ai pas de redondance ou de code inutile (le code doit être lisible facilement sans commentaire)
    - Le choix des technos et outils qui devra être justifié à l’issue du test
    - Le fait qu’il n’y ai pas (ou peu) de logique métier dans les contrôleurs, mais que celle-ci soit déportée dans des services spécialisés par exemple
- Les éléments secondaires qui pèseront de manière non significative dans l’évaluation :
    - Le type d’authentification de l’API
    - La structure de la base de données, les relations et les éventuels indexes utilisés