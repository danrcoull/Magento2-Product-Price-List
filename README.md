# Magento 2 - Product Price List 

Magento 2 product price lists allows you to simply create different categorisations of prices, based on customer, allowing you to five specific customers, specific products at a specific price. 
Simply login the admin after insalling and Go to Catalog -> Price Lists, and Click add new. 

Fill in the very simple form, and the rest will be taken care off.  

Its composed highly of plugins no core overides, so you can safly continue to dev, without risk of overwritting, 

We have also made sure to store the original data in extension attribute at the point of product getById and get and getList on search results, so you can always access it to modify it further. 

You can now configure the module how you so desire, 

Under System / Configuration / Sutton Silver / Price Lists
 - Enable Or Disable the module
 - Restrict the categories / products same option from custoemrs not in the product price lists
 - replace the add to cart form template if the customer is not in the list, its not restricted and you do not wish them to buy untill they have a quote
 - the lowest price from all lists is now used to give the price. 
 - you can now completly restrict the entire list from even showing the products at all, and redirect from the product if directly accessed
 - category resitrcition is now a configurable option.
 
Code now fully commnented aswell so it should make more sense :D 
 
Ideas: 

Any ideas feel free to post them in issues where to go next with this. 

Other Modules COming Soon:

- Company Hirachy

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


