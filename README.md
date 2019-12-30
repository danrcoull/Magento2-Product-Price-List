# Magento 2 - Product Price List 

Magento 2 product price lists allows you to simply create different categorisations of prices, based on customer, allowing you to five specific customers, specific products at a specific price. 
Simply login the admin after insalling and Go to Catalog -> Price Lists, and Click add new. 

Fill in the very simple form, and the rest will be taken care off.  

Its composed highly of plugins no core overides, so you can safly continue to dev, without risk of overwritting, 

We have also made sure to store the original data in extension attribute at the point of product getById and get and getList on search results, so you can always access it to modify it further. 


Future:
1. To add config option to turn of category restriction for non logged in users
2. To modify the price override to always use the lowest price, to comply with the pricing render logic. 
3. To add the ability to lock down the users in the list to only see products within there assigned lists, by filtering down the categories... In Progress.

Installation:

```bash
composer config repositories.productpricelist vcs https://github.com/danrcoull/product-price-list.git
composer require suttonsilver/module-pricelists:dev-master
php bin/magento module:enable SuttonSilver_PriceLists
php bin/magento setup:upgrade
php bin/magento setup:di:compile #yes do this we use extension attributes so you can see the original price and the custom price.
php bin/magento setup:static-content:deploy en_GB en_US -f 
php bin/magento cache:flush
 
```

Yes i work hard, plenty more modules to come feel free to by me a coffee below. 



[![Buy Me A Coffee](https://cdn.buymeacoffee.com/buttons/lato-black.png)](https://www.buymeacoffee.com/BHaNOMl)


