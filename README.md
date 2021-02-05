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

Import the BD using the file `jobsity_challenge.sql` into `bd_script`:

*Note: I'm using the phpMyAdmin*

***

### Settings

The configuration files for each environment are found in the `environments` folder.

If there is any specific configuration per environment (such as a database), changes should be made to `main-local.php` or `params-local.php`. Both located in `environments/<ENVIRONMENT>/common/config`