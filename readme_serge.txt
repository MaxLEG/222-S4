----------------------------------------------------------DOCUMENTATION DES AMÉLIORATIONS - PROJET SYMFONY (BRANCHE SERGE)
----------------------------------------------------------

Ce document récapitule les modifications majeures apportées au projet pour 
répondre aux exigences de personnalisation Back-end et Front-end.

---------------------------------------------------------- AMÉLIORATIONS BACK-END (LOGIQUE & PERFORMANCE)
----------------------------------------------------------
Le moteur du projet a été optimisé pour une gestion de contenu professionnelle :

1. Entité Article enrichie :
   - Champ `views` (Integer) : Suivi réel de l'audience.
   - Champ `publishedAt` (DateTime) : Horodatage automatique à la publication.
   - Correction du mapping de la relation `category` (correction de casse).

2. Repository Intelligent :
   - Recherche Multi-critères : Le moteur cherche désormais dans les titres, 
     les textes ET les noms de catégories.
   - Pagination Native : Limitation automatique à 6 articles pour optimiser 
     le temps de chargement.

3. Contrôleur Articles :
   - Gestion de l'audience : Incrémentation automatique des vues.
   - Suppression Réelle : Correction de la méthode pour effacer vraiment les 
     entrées en base (au lieu d'un simple toggle).

---------------------------------------------------------- PERSONNALISATION FRONT-END (DESIGN PREMIUM)
----------------------------------------------------------
L'interface a été entièrement repensée pour offrir une expérience moderne :

1. Mise en page en Grille (Cards) :
   - Remplacement du vieux tableau austère par une grille responsive de 
     "cartes" au design épuré (Ombres portées, bordures fines).
   - Utilisation de la police "Inter" pour une typographie professionnelle.

2. Composants Interactifs (JS) :
   - Animations "Fade-in" : Les articles apparaissent avec un léger fondu 
     et un mouvement vers le haut lors du défilement.
   - Confirmations dynamiques : Utilisation de JS pour sécuriser les actions 
     critiques (ex: changement d'état).

3. Navigation & Recherche :
   - Barre de recherche stylisée avec icônes FontAwesome intégrées.
   - pagination visuelle sous forme de boutons modernes et centrés.

----------------------------------------------------------
SYNCHRONISATION BASE DE DONNÉES
----------------------------------------------------------
Si vous installez le projet, n'oubliez pas de mettre à jour votre schéma :
   php bin/console doctrine:schema:update --force

----------------------------------------------------------
LISTE DES FICHIERS MODIFIÉS OU CRÉÉS
----------------------------------------------------------
 BACK-END :
- src/Entity/Article.php (Logique métier & champs)
- src/Repository/ArticleRepository.php (Recherche & Pagination)
- src/Controller/ArticleController.php (Logique de contrôle)

 FRONT-END :
- templates/base.html.twig (Structure globale & Fonts)
- templates/article/index.html.twig (Design des cartes)
- public/js/article.js (Animations & JS)

 MAINTENANCE & DOC :
- .gitignore (Optimisation Git)
- readme_serge.txt (Documentation actuelle)
