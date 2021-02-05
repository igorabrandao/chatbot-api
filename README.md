# Chatbot - API (1.0.1)

In this repository, there are codes related to the Chatbot API application.

## Prerequisites

* LAMP stack

## Dev Environment

Here I'm using the [`Scotch Box - Vagrant LAMP Stack`](https://box.scotch.io/)

✅ OS Ubuntu 14.04.5 LTS (Trusty Tahr)
✅ Web Server Apache 2.4
✅ PHP 7.0
✅ MySQL 5.5
✅ phpMyAdmin 4.5.4.1deb2ubuntu2.1

## Running the API

First navigate into the project folder.

To start the project in development or production mode, run:

    php init

Choose the desired environment:

```
    Yii Application Initialization Tool v1.0

    Which environment do you want the application to be initialized in?

      [0] Development
      [1] Production

      Your choice [0-1, or "q" to quit]
```

If prompted, replace the files.

Install the dependencies with `composer`:

```
    <composer> install
```

or if necessary:

```
    <composer> update
```

Import the BD using the file `jobsity_challenge.sql` into `bd_script`:

*Note: I'm using the phpMyAdmin*

***

### Settings

The configuration files for each environment are found in the `environments` folder.

If there is any specific configuration per environment (such as a database), changes should be made to `main-local.php` or `params-local.php`. Both located in `environments/<ENVIRONMENT>/common/config`

***

### Note on file permissions

When creating the dev/prod environment, set the following file and folder permissions:

** Folders like 755 **
    find -type d -exec chmod 755 {} \;

** Files like 644 **
    find -type f -exec chmod 644 {} \;

***

### Automated tests

Yii 2 has officially maintained integration with Codeception testing framework that allows you to create the following test types:

* Unit - verifies that a single unit of code is working as expected;
* Functional - verifies scenarios from a user's perspective via browser emulation;
* Acceptance - verifies scenarios from a user's perspective in a browser.

To run the automated tests, install the codeception dependencies:

```
    composer require --dev codeception/codeception
    composer require --dev codeception/specify
    composer require --dev codeception/verify
```

*Note: Codeception requires "CURL" extension installed to make tests run.*

To perform the automated tests it is necessary to use the command line terminal in your OS. 

Use the following commands (inside the API project root folder) without quotes:

```
     • “cd api /” to navigate to the API folder, as the tests can be performed in a global and / or local context. In this case, the idea is to test only the API and not the other modules of the project, so it is necessary to navigate to the API folder;
     
     • “../vendor/bin/codecept run” or “../vendor/bin/codecept run --coverage --coverage-xml --coverage-html”, the first command executes only the Codeception patterns, while that the second includes coverage tests.
```
