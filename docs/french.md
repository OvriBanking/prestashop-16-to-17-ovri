##### [<< Retour à la page d'accueil des plugins](https://plugins.moneytigo.com/french/)

## Module de paiement MoneyTigo pour PrestaShop 1.6 & 1.7

Ce module de paiement vous permet d'accepter les paiements par carte de crédit via la solution de paiement MoneyTigo.com. (https://www.moneytigo.com).
Ce module de paiement est compatible avec toutes les versions de prestashop 1.6 & 1.7

* Version du module: 1.1.2

# INSTALLATION

Pour installer le plugin MoneyTigo, nous vous invitons d'abord à : [Télécharger l'archive du module en cliquant ici](https://github.com/moneytigo/prestashop_moneytigo/releases/download/v1.1.1/moneytigo-1617-v_1_1_1.zip)

### Pour prestashop V1.6

* 1 - Accédez à votre tableau de bord PRESTASHOP
* 2 - Allez dans le gestionnaire de modules (modules et services > modules et services)
* 3 - En haut à droite, cliquez sur "Ajouter un nouveau module" puis "sélectionnez l'archive du module Moneytigo" puis cliquez sur "Charger le module".
* 4 - Le module est chargé, vous devez cliquer sur le bouton "installer" puis "continuer l'installation".
* 5 - Remplissez votre clé API et votre clé SECRET (que vous trouverez dans votre espace MoneyTigo) puis cliquez sur "Mettre à jour la configuration".
* 6 - Puis allez dans (modules et services > paiement) dans la section "restriction par pays" cochez les pays dans lesquels vous souhaitez afficher MoneyTigo puis sauvegardez.
* 7 - L'installation est terminée et fonctionnelle !

### Pour prestashop V1.7

* 1 - Accédez à votre tableau de bord PRESTASHOP
* 2 - Allez dans le gestionnaire de modules (modules > gestionnaires de modules)
* 3 - En haut à droite, cliquez sur "Installer un module" puis "sélectionnez l'archive du module Moneytigo".
* 4 - Le module est chargé, vous devez cliquer sur le bouton "Configurer".
* 5 - Remplissez votre clé API et votre clé SECRET (que vous trouverez dans votre espace MoneyTigo) puis cliquez sur "Mettre à jour la configuration".
* 6 - Puis allez dans (paiement > préférences) dans la section "restrictions par pays" cochez les pays dans lesquels vous souhaitez afficher MoneyTigo puis enregistrez.
* 7 - L'installation est terminée et fonctionnelle !

# MISE À JOUR D'UN ANCIEN MODULE MONEYTIGO

La procédure de mise à jour est la même dans les deux versions de prestashop il suffit de suivre les points 1 à 4 indiqués ci-dessus, prestashop mettra automatiquement à jour le module avec la nouvelle version et en gardant toutes vos données donc il ne sera pas nécessaire de le reconfigurer, si le module ne s'affiche pas après la mise à jour nous vous invitons simplement à vérifier les restrictions prestashop mentionnées dans le point 6.

## MODE TEST

L'activation du mode test se fait directement dans la gestion de vos sites sur votre tableau de bord MoneyTigo.

(**Note:** Pour tester les transactions, n'oubliez pas de basculer votre site web (dans votre interface MoneyTigo, en mode test/démo) et de le basculer en production lorsque vos tests sont terminés).

Si vous utilisez le mode test, vous devez utiliser les cartes de crédit virtuelles suivantes :
* **Paiement accepté** : Carte n° 4000 0000 0000 0002 , Expiration 12/22 , Cvv 123
* **Paiement refusé** : Carte n° 4000 0000 0000 0036 , Expiration 12/22, Cvv 123
* **(Les cartes virtuelles ne fonctionnent pas en mode production)**
