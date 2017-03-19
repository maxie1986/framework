Summary

This MVC Framework has been done following psr-4 standards for autoloading files and uses SOLID principles. 
It uses third-party tools such as standalone Eloquent ORM to make the DB connection and handling queries, and Model usages.
For Handling HTTP Requests and Responses It used Http Foundation from Symfony framework. Making OOP oriented all the requests/responses 
For Routing It uses Fast Route which provides a regular expression based router

Instructions to Run example

Install
Clone this repository
Install Composer
run composer install
edit db.global.php and set db connection parameters

Usage
Controllers are defined in /app/controllers and they must extend Framework\Base\Controller and name convention must use 
Models are defined in /app/models and they must extend Eloquent model Illuminate\Database\Eloquent\Model
For more info https://github.com/illuminate/database

views need to be defined within /app/views/{controllerId} where controllerId is the lowercase Controller class name without "Controller". E.g for BooksController the view folder has to be called "books"

Config files are defined in /app/config and for development any file with *.local.php overrides *.global.php config. *.local.php files are gitignored. In order to get a config value acrooss the app you can use Framework\Base\Config::get('foo') and it will return foo value from the config files

If you want to change the layout you can change /app/views/layout/main.php or use a different file within the folder and change the 'layout' setting in main.global.php file

Routes are defined in /app/config/routes and have to be defined following order [method, routerPattern, handler]. E.g A GET route for BooksController with any action and default "index" action can be defined like this ['GET', '/books[/{action}]', ['App\Controllers\BooksController', 'index']]
For more info: https://github.com/nikic/FastRoute

