DOCUMENTATION DES AMÉLIORATIONS - PROJET SYMFONY (SEMAINE 4) 


Ce document récapitule les modifications apportées au projet pour répondre
aux exigences de personnalisation Back-end.

------------------------------------------------------------------------
AMÉLIORATIONS BACK-END (LOGIQUE & PERFORMANCE)
------------------------------------------------------------------------
Le projet a été optimisé au niveau de la couche logique (Repository & Controller) :

1. Entité Article enrichie :
   - Champ `views` (Integer) : Compte les consultations unitaires.
   - Champ `publishedAt` (DateTime) : Date de publication automatique.
   - Méthode `incrementViews()` intégrée.

2. Repository Moteur de recherche & Pagination :
   - Recherche croisée : Le moteur cherche désormais dans les titres, les 
     contenus ET les noms de catégories (via une jointure SQL).
   - Pagination native : Ajout des méthodes `findPaginated()` et `countArticles()`
     pour gérer le flux de données sans charger toute la base en mémoire.

3. Contrôleur Articles optimisé :
   - Indexation paginée : Le contrôleur limite l'affichage à 6 articles par page.
   - Suivi d'audience : Chaque appel à la méthode `show()` déclenche une 
     mise à jour atomique du compteur de vues.
4. Affichage UI intelligent :
   - La colonne "ID" dans la liste des articles n'affiche plus l'identifiant technique 
     de la base de données (qui peut avoir des trous après une suppression).
   - Elle affiche désormais un numéro d'ordre séquentiel (1, 2, 3...) qui se 
     re-calcule automatiquement.

---------------------------------------------------------------
SYNCHRONISATION BASE DE DONNÉES
---------------------------------------------------------------
Rappel : Les modifications dans Article.php ne s'appliquent pas seules.
Pour ce projet, nous avons utilisé :
   php bin/console doctrine:schema:update --force

En production, il est recommandé d'utiliser les migrations :
   php bin/console make:migration
   php bin/console doctrine:migrations:migrate

---------------------------------------------------------------
LISTE DES FICHIERS MODIFIÉS OU CRÉÉS
---------------------------------------------------------------
BACK-END :
- src/Entity/Article.php (Logique métier & champs)
- src/Repository/ArticleRepository.php (Recherche & Pagination)
- src/Controller/ArticleController.php (Logique de contrôle)

INTERFACE (MODIFICATIONS LÉGÈRES) :
- templates/article/index.html.twig (Numérotation séquentielle)

MAINTENANCE & DOC :
- .gitignore (Optimisation Git)
- readme_max.txt (Documentation actuelle)
