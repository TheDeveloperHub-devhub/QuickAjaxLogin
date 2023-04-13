1.0.1
==========
* Features
   * Created new xml file DeveloperHub/QuickAjaxLoginetc/frontend/di.xml
   * Created new php class DeveloperHub/QuickAjaxLogin/Model/ExtEmailNotification.php
   * Override the Magento\Customer\Model\EmailNotification with DeveloperHub/QuickAjaxLogin/Model/ExtEmailNotification



* Reason of overriding file
   * We override ```Magento\Customer\Model\EmailNotification``` class with ```DeveloperHub/QuickAjaxLogin/Model/ExtEmailNotification``` because in magento2.4.5 version there is a bug ```\Magento\Customer\Model\EmailNotification::getWebsiteStoreId``` function return type is ```int``` but it should return ```int or string or Null```.
   * We fixed that bug in our this version to make our extension compatible to latest version i.e. ```magento2.4.5```


1.0.2 = Oct 7th, 2022
==========
* Made changes to work with Hyvä compatible module
