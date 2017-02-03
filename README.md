TLVFLATS

1. get and install composer ( https://getcomposer.org/doc/00-intro.md )
2. get and install nodejs ( https://nodejs.org/en/download/ )
3. create database
4. copy env.example to .env end edit accordingly
5. execute `composert install`
6. execute `npm install`
7. make apache2 point to ./web subfolder
8. run `php app/console cache:clear --env=prod`

After code update

1. run `npm install` -- updates js/css packages
2. run `gulp install` -- makes frontend
3. run `composer install` -- updates php packages
4. run `php app/console doctrine:schema:update --force` -- updates database schema
5. run `php app/console cache:clear --env=prod` -- clears symfonys' caches and updates routing/templates/services 


- Contact Info
coneits@outlook.com