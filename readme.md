# My BIG PET-Project with Symfony

## Description

This project is a reinterpretation of my [first project](https://github.com/eduardevdokimov746/myBigProject),
which was written in early 2019. The essence of this project is:
* authentication (login, registration, email confirmation and password recovery);
* editing the user profile;
* display of news distributed by category;
* leaving comments.

A simple admin panel with CRUD of the main entities is also implemented: users, news, categories and comments.

The aim of the project is to consolidate theoretical knowledge about:
* Symfony
* Doctrine
* Symfony Forms
* TDD
* PSR-1,3,11,12
* PHP CSFixer, PHPStan

## Requirements

* PHP >= 8.1
* Postgres *

## Installation

1. Clone a project
```
git clone https://github.com/eduardevdokimov746/symfony_mybigproject.git 
```
2. Go to the project folder
```
cd symfony_mybigproject
```
3. Install dependencies
```
composer intall
```
4. Copy the env file and redefine the environment variables
```
cp .env .env.local
```
5. Create a database
```
bin/console doctrine:database:create
```
6. Perform migrations
```
bin/console doctrine:migrations:migrate -n
```
7. Upload initial data
```
bin/console doctrine:fixture:load -n
```
## Using
The initial data contains 2 users:
* with the role of the base user:
> Login: ens
> 
> Password: ens
* with the administrator role (access to the administrative panel by http://localhost/admin):
> Login: admin
> 
> Password: admin
## Running tests and static analysis
To run the tests, you need to:
1. Copy the env file and redefine environment variables for tests
```
cp .env.test .env.test.local
```
2. Create a database for tests
```
bin/console doctrine:database:create --env=test
```
3. Perform migrations for the test database
```
bin/console doctrine:migrations:migrate -n --env=test
```
4. Upload the initial data to the test database
```
bin/console doctrine:fixture:load -n --env=test --purger=purger
```
5. Run tests
```
bin/phpunit
```
When running the command without additional parameters, the phpunit.xml.dist configuration file in the root of the
project is used. It contains the configuration of an HTML report on code coverage by tests. The report is in
*tests/reports/html-coverage*.

The project uses the PHPStan static analysis library. The configuration file for it lies in the root of the project
*phpstan.neon*. To run the code analysis, you need to run the command:
```
vendore/bin/phpstan
```
## Author

* Email: **eduard.evdokimov@inbox.ru**