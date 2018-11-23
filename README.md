# apiArea
Simple REST APIs to calculate areas.

## Getting Started
After cloning the project, you have to run 
```
composer install
```
to install all the dependencies.

## Running the tests
Before running the testsuite you should set your own base address into the variable
```
$GLOBALS['base_url_address']
```
into the config_local.php file. It is used by the HttpClient 
to call the APIs and check for the returned value.
Then you can run the testsuite with 
```
vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/
```
