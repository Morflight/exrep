# Exrep - Expense Report API

**Author: Adrien JAMMES**

Exrep is an expense report API built using Symfony 6.2 and PHP 8.1. The goal of this application is to provide a hands-on API that can be easily extended and customized using API Platform.

As of December 2023, I'm also using Exrep as a standalone application for students to deploy using cloud technologies.

Some sample code is also available using the AWS SDK, more on that later.

## Prerequisites

To run this application, you need the following:

- Docker
- Docker Compose
- Make
- Git

## Getting Started

1. Clone the repository and cd to the project folder:

   ```shell
   git clone git@github.com:Morflight/exrep.git && cd exrep
   ```

2. Run Docker Compose inside of the Make target init

   ```shell
   make init
   ```

   This will take some time, especially if it's your first run.
   You can grab some coffee or read the rest of the README.

   The database installation might fail the first time you run it, don't worry, just run `make init` again.

If you want to use the AWS feature, you must configure your .env and set the AWS variables there

The app is now running. You can access is on this url: http://localhost:8080 (locally)
If you encounter any problem, please visit the Useful Commands section and try to rebuild the project.

## A quick rundown on the infrastructure

1. Docker

   Inside the docker folder, we can find configuration and some volumes

   - mysql

   A volume that basically gives you access to the database.

   - nginx

   The server's configuration, only supports http ! For develoment purposes, the port is 8080. Yo might want to change that to make it
   work on a server.

   - php

   The Dockerfile containing all the packages required to run the app.

   On top of that, the entire app directory is a volume linked to the PHP container. Any change there is immediately replicated onto the php instance.

   Any change you make is live, no need to rebuild. At most you might have to clear the Symfony cache.

2. Mysql

   This project's SGBD is MySQL. There are 3 entities: ExpenseReport, User and Company. The ORM is Doctrine.
   The database is bound to your host machine's port 4306, meaning you can log into it one of two ways:

   From your host machine (if you have MySql installed)

   ```bash
    mysql -uroot -proot -h127.0.0.1 -P4306
   ```

   From the container

   ```bash
   docker exec -it exrep_database mysql -proot
   ```

3. PHP and Symfony

   PHP 8.1 and Symfony 6.2. I picked PHP-fpm because I'm more familiar with it. The coding standard I followed is PSR-2. As for the file structure, I went with a standard Symfony project skeleton and built on top of it.

   It's important to keep in mind that if you want to run a PHP script, composer or the symfony console, you must run it inside of the container.

   Such commands look like this:

   ```bash
   docker exec -it exrep_php bin/console debug:config
   ```

4. Controllers

   So far there is just a AWSController that exposes a new endpoint: GET/ api/upload-reports-to-s3 that serializes all reports of the database
   and send a single .csv file to a bucket with the AWS action PutContent.

## Introducing the API

Assuming the installation went smoothly, you can now navigate to the API summary.
Go to http://localhost:8080/api and you will be greeted by the openapi specification of the API.

You may use this page to test the API or your favorite HTTP Client like Postman or Insomnia.

1. Authentication

   Authentication in the Exrep API is handled through JWT Tokens using [Lexik's Authentication Bundle](https://github.com/lexik/LexikJWTAuthenticationBundle). This ensures secure access to the API endpoints.

   You can retrieve your token by using the /api/login_check endpoint. The default user has the following credentials:

   ```json
   {
     "email": "admin@gmail.com",
     "password": "root"
   }
   ```

   You can now copy the token and paste it in the Authorization header of your subsequent requests. Don't forget to prefix it with "Bearer " or it will not work !

   If you use the /api page, you want to click the "Authorize" button and paste your bearer token there (also prefixed with "Bearer ").

   From now on, you can freely fiddle with the API.

2. Query the endpoints

   Now that you are authenticated, you may use the /api page to try out the API or, as I said before, use Postman and/or Insomnia.

   If you pick the latter, make sure you send the right headers.

   READ endpoints only need "Accept: application/json" and "Authorization: Bearer $your_token" and WRITE endpoints that require a body will also require you to add "Content-Type: application/json" to your headers.

   Postman usually adds the right headers on the fly, but Insomnia doesn't, please double check before flipping your keyboard and cursing me for making something that doesn't work :)

   The GET endpoints support a variety of filters that you can play with. You can find examples in the tests or in the API UI.

3. Important information about nested entities

   This project uses the latest version of API Platform. As such, you may find oddities when referencing nested entities with json objects.

   The preferred way of referencing a nested entity is by using the resource's [IRI](https://en.wikipedia.org/wiki/Internationalized_Resource_Identifier).

   You may find that sometimes, using nested objects work, sometimes it doesn't, this is the reason why.

## Testing

1. Fixtures

   This project has fixtures. They are used to give you something to work with when you install the app.
   These fixtures are also loaded at the beginning of every test. In fact, the entire testing database is created before the test and dropped after.

   If you want to manually run fixtures, you can run the following command:

   ```bash
   docker exec -it exrep_php bin/console doctrine:fixtures:load
   ```

2. Functional testing

   You can run the API tests with PHP Unit.

   ```bash
   docker exec -it exrep_php bin/phpunit
   ```

   Feel free to check the tests if you can't make the API work from the schema.
   You can also see examples of the symfony commands.

## Useful commands

1. Make targets

   - make init: to intialize the project
   - make reset: to reset the project. You must init it again (in case you cause yourself some trouble)

2. Manipulate the database/schema

   - Create/Drop database

   ```bash
   docker exec -it exrep_php bin/console doctrine:database:create
   ```

   ```bash
   docker exec -it exrep_php bin/console doctrine:database:drop --force
   ```

   - Create schema

   ```bash
   docker exec -it exrep_php bin/console doctrine:schema:create
   ```

   - Update schema

   ```bash
   docker exec -it exrep_php bin/console doctrine:schema:update --force
   ```

3. Docker Compose

   Stop and remove containers

   ```bash
   docker-compose down
   ```

## Caveats

If you were to use this project for yourself, PLEASE DO NOT PUT IN PRODUCTION AS-IS. All the passwords are in clear to simplify the installation steps and troubleshooting. This is obviously a huge vulnerability so you MUST do some work to get the passwords out of the config

## License

This project is licensed under the [Apache License](https://www.apache.org/licenses/LICENSE-2.0.txt).

Feel free to explore and extend the Exrep API according to your needs. If you encounter any issues or have suggestions for improvement, please don't hesitate to reach out.

Happy coding!
