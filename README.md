# Test for OctoberCMS site

An easy way to use the power of Laravel testing inside your OctoberCMS site.

- Browser testing with Laravel Dusk  
example in `tests/browser/ExampleTest.php`   
documentation: https://laravel.com/docs/5.5/dusk

- HTTP request testing  
example in `tests/feature/ExampleTest.php`   
documentation: https://laravel.com/docs/5.5/http-tests

- Unit testing  
example in `tests/unit/ExampleTest.php`   

## Setup
1. Copy content of this repos in `plugins/inetis/testing`
2. Go to `plugins/inetis/testing` and run `composer install`
3. Run tests `composer test`

## Informations
There is default [laravel factories](https://laravel.com/docs/5.5/database-testing#writing-factories) for Backend Users
and RainLab Users in `factories` directory. You can adapt them or create new ones.

By default all is configured for the changes made during tests are not persistantes. For this work with Dusk test (that 
perform real HTTP request) a dump of the database is performed before tests and restored after.