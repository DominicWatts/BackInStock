# Back In Stock # 

Customers can register their interest for products when they are out of stock. An automated process periodically checks the requests and the customer will be sent a personalised email when the product comes back in stock.

# Install instructions #

`composer require dominicwatts/backinstock`

`php bin/magento setup:upgrade`

`php bin/magento setup:di:compile`

# Usage instructions #

Form will appear on product page where customer can register interest.

Managed within admin
  -  Marketing > Back In Stock Interest

Process to send emails is either cron based on can be triggered by console command.

`xigen:backinstock:check [--] <check>`

`php bin/magento xigen:backinstock:check check`

Or alternatively allow cron task to run