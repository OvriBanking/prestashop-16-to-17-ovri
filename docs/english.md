##### [<< Back to the plugins home page](https://plugins.moneytigo.com/english/)

## MoneyTigo Payment Module for PrestaShop 1.6 & 1.7

This payment module allows you to accept credit card payments through MoneyTigo.com payment solution (https://www.moneytigo.com).
This payment module is compatible with all versions of prestashop 1.6 & 1.7

* Module version: 1.1.2

# INSTALLATION FOR THE FIRST TIME

To install MoneyTigo plugin we invite you first : [Download the archive of the module by clicking here](https://github.com/moneytigo/prestashop_moneytigo/releases/download/v1.1.1/moneytigo-1617-v_1_1_1.zip)

### For prestashop V1.6

* 1 - Go to your PRESTASHOP dashboard
* 2 - Go to the module manager (modules and services > modules and services)
* 3 - At the top right, click on "Add a new module" then "select the Moneytigo module archive" then click on "Load the module".
* 4 - The module is loaded, you must click on the button "install" then "continue installation".
* 5 - Fill in your API key and your SECRET key (which you will find in your MoneyTigo space) then click on "Update configuration".
* 6 - Then go to (modules and services > payment) in the section "country restriction" check the countries in which you want to display MoneyTigo then save
* 7 - The installation is finished and functional!

### For prestashop V1.7

* 1 - Go to your PRESTASHOP dashboard
* 2 - Go to the module manager (modules > module managers)
* 3 - At the top right, click on "Install a module" then "select the Moneytigo module archive".
* 4 - The module is loaded, you must click on the button "Configure".
* 5 - Fill in your API key and your SECRET key (which you will find in your MoneyTigo space) then click on "Update configuration".
* 6 - Then go to (payment > preferences) in the section "country restrictions" check the countries in which you want to display MoneyTigo then save
* 7 - The installation is finished and functional!

# UPDATE OF AN OLD MONEYTIGO MODULE

The procedure of update is the same in both version of prestashop you just have to follow the points 1 to 4 indicated above, prestashop will automatically update the module with the new version and keeping all your data so it will not be necessary to reconfigure it, if the module is not displayed after update we just invite you to check the prestashop restrictions mentioned in the point 6.

## TEST MODE

The activation of the test mode is done directly in the management of your sites on your MoneyTigo dashboard.

(**Note:** To test transactions, don't forget to switch your website (in your MoneyTigo interface, in test/demo mode) and switch it to production when your tests are finished.)

If you use the test mode you must use the following virtual credit cards:
* **Payment approved** : Card n° 4000 0000 0000 0002 , Expiry 12/22 , Cvv 123
* **Payment declined** : Card n° 4000 0000 0000 0036 , Expiry 12/22, Cvv 123
* **(Virtual cards do not work in production mode)**
