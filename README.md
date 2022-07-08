## Ovri Payment Module for PrestaShop 1.6 & 1.7

This payment module allows you to accept credit card payments through ovri.com payment solution (https://www.ovri.com).
This payment module is compatible with all versions of prestashop 1.6 & 1.7
Last version testing : Prestashop 1.6.1.24 and 1.7.8.6

* Module version: 1.2.5

# INSTALLATION FOR THE FIRST TIME

To install OVRI plugin we invite you first : 

### For prestashop V1.6

* As a priority - [Download the archive of the module by clicking here](https://api.ovri.app/plugins/ovribanking-prestashop-1-2-5.zip)
* 1 - Go to your PRESTASHOP dashboard
* 2 - Go to the module manager (modules and services > modules and services)
* 3 - At the top right, click on "Add a new module" then "select the Moneytigo module archive" then click on "Load the module".
* 4 - The module is loaded, you must click on the button "install" then "continue installation".
* 5 - Fill in your API key and your SECRET key (which you will find in your MoneyTigo space) then click on "Update configuration".
* 6 - Then go to (modules and services > payment) in the section "country restriction" check the countries in which you want to display MoneyTigo then save
* 7 - The installation is finished and functional!

(**Note:** To test transactions, don't forget to switch your website (in your MoneyTigo interface, in test/demo mode) and switch it to production when your tests are finished.

If you use the test mode you must use the following virtual credit cards:
* **Payment approved** : Card n° 4000 0000 0000 0002 , Expiry 12/22 , Cvv 123
* **Payment declined** : Card n° 4000 0000 0000 0036 , Expiry 12/22, Cvv 123
* **(Virtual cards do not work in production mode)**

### For prestashop V1.7

* As a priority - [Download the archive of the module by clicking here](https://api.ovri.app/plugins/ovribanking-prestashop-1-2-5.zip)
* 1 - Go to your PRESTASHOP dashboard
* 2 - Go to the module manager (modules > module managers)
* 3 - At the top right, click on "Install a module" then "select the Moneytigo module archive".
* 4 - The module is loaded, you must click on the button "Configure".
* 5 - Fill in your API key and your SECRET key (which you will find in your MoneyTigo space) then click on "Update configuration".
* 6 - Then go to (payment > preferences) in the section "country restrictions" check the countries in which you want to display MoneyTigo then save
* 7 - The installation is finished and functional!

(**Note:** To test transactions, don't forget to switch your website (in your MoneyTigo interface, in test/demo mode) and switch it to production when your tests are finished.

If you use the test mode you must use the following virtual credit cards:
* **Payment approved** : Card n° 4000 0000 0000 0002 , Expiry 12/22 , Cvv 123
* **Payment declined** : Card n° 4000 0000 0000 0036 , Expiry 12/22, Cvv 123
* **(Virtual cards do not work in production mode)**

# UPDATE OF AN OLD OVRI MODULE

The update procedure from OVRI 1.2.5 version is automatic in prestashop 1.6,
For prestashop 1.7, you just have to click on "Update" in the module manager in front of the Ovri module when an alert will be displayed

# CHANGE MODULE MONEYTIGO TO OVRI

If you are using our old moneytigo plugin, you just need to install the new module (no need to delete moneytigo), just remember to deactivate it (MoneyTigo module will stop working on November 1st 2022)