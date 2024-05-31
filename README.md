# Test ChooseMyCompany - dev back / fullstack #

Pré-requis :
---
Docker

Make (optionel)

Contexte :
---
ChooseMyCompany est un site d'information sur les employeurs.  
Grace aux enquêtes salariés que nous administrons, nous récoltons des notes par entreprises que nous pouvons mettre à la disposition des visiteurs.  
Les visiteurs de notre site étant souvent en recherche d'emploi, nous avons souhaité leur proposer des offres directement sur le site choosemycompany.com.  
Pour cela, notre partenaire - le site d'emploi Regionsjob.com - nous transmet ses offres d'emploi via un flux XML (*simulé ici par un fichier statique*).
 
Le code de ce petit projet permet l'import de ces offres dans notre système depuis les ligne de commande.

Ce projet permet d'importer les jobs de 2 partenaires:

- RegionJob
- JobTeaser

Commandes utiles :
---

Si vous n'avez pas installé la commande `make`
- `./init.sh` pour initialiser et lancer le projet
- `./run-import.sh` pour lancer l'import
- `./clean.sh` pour arrêter et nettoyer le projet

Si vous avez installé `make`
- `make start` pour lancer le projet et faire les imports
- `make import` uniquement pour lancer un nouvel import

- http://localhost:8000/ (`root` / `root`): interface phpMyAdmin pour visualiser le contenu de la base de donnée

Si vous aviez plus de temps :
---
Quelles seraient les évolutions que vous proposeriez pour améliorer ce code (découpage, optimisations, sécurisation...) ?

:warning: Le but de ce test est de vérifier votre capacité à organiser le code (pas seulement à le faire marcher).
---
Je pense avoir bien réussi la tâche demandée. Cependant, il y a effectivement quelques améliorations possibles à réaliser :

1 - Dans le `init.sh` il y a un `sleep 15`. 

J'imagine qu'il s'agit de laisser le temps à mariadb pour démarrer car j'ai aussi déjà rencontré le soucis de `mariadb qui n'est pas prêt à recevoir des requêtes`. 

Pour cela, il existe une méthode permettant de ping la base de données jusqu'à ce qu'elle réponde et rende la main au CLI. 

Cette méthode, je l'avais créé pour iGraal afin de réduire le temps d'attente du container au maximum car on utilisait aussi un `sleep` au départ.

Cette commande a été reprise par plusieurs collègues pour leurs projets personnels.

Cette commande se trouve dans le dossier `ExternalFiles/IsDbReadyCommand.php` et est resté au format `Command` de symfony par manque de temps pour l'adaptation. N'hésitez pas à l'utiliser si vous en avez besoin.

2 - Il n'y a pas de gestion d'erreurs dans le code actuel.

J'aurai aimé ajouter des `exceptions` afin de court-circuiter l'exécution et d'afficher un message d'erreur générique et non l'erreur d'exécution.

3 - Il reste encore un peu de duplicate code dans les JobTeaserImporter.php et RegionJobImporter.php

Une idée d'amélioration serait de déclarer une fonction abstraite `jobsFromFileContent` dans JobImporter afin que les deux autres classes puisse en définir son corps de fonction qui aurait pour but de transformer le contenu du fichier en objets `Job`

Grâce à cela, je déplacerai `$fileContent = $this->openFile(self::FILENAME);` et `return count($jobs);` dans `JobsImporter->import` et y ajouterai l'appel à la transformation des données.

Le but de cette mise à jour serait d'appeler `$count = $importer->import();` au lieu de `$count = $importer->importJobs();` pour faire le travail.

Cette réduction de 3 lignes dupliquées n'est pas énorme mais permet d'éviter les oublis lors de l'ajout de nouveaux partenaires

4 - Je rajouterai aussi par la suite des tests unitaires avec PHPUnit afin de m'assurer que le code fonctionne correctement

5 - J'utiliserai aussi PHPStan pour reformater le code correctement suivant des normes PSR bien définies.