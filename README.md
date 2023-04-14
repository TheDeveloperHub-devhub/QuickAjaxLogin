[//]: # (File format based on https://www.makeareadme.com/)

# Quick Ajax Login
### Features
* Customer(s) can Create an Account directly while browsing the website.
* Customer(s) can Sign in quickly while browsing the website.
* Customer(s) can go to the Sign-in pop-up from Create an Account pop-up.
* Customer(s) can go to Create an Account pop-up from the Sign-in pop-up.
* Customer account validations are also working as Magento’s default behaviour.
* Welcome email will be sent to the customer(s) after registration success.


## Installation

1. Please run the following command
```shell
composer require devhub/quick-ajax-login
```

2. Update the composer if required
```shell
composer update
```

3. Enable module
```shell
php bin/magento module:enable DeveloperHub_Core
php bin/magento module:enable DeveloperHub_QuickAjaxLogin
php bin/magento setup:upgrade
php bin/magento cache:clean
php bin/magento cache:flush
```
4.If your website is running in product mode the you need to deploy static content and
then clear the cache
```shell
php bin/magento setup:static-content:deploy
php bin/magento setup:di:compile
```



#####This extension is compatible with all the versions of Magento 2.3.* and 2.4.*. 
###Tested on following instances:
#####multiple instances i.e. 2.3.7-p3 and 2.4.5
