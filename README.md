# PHP-MYSQL-CRUD-HELPER
A MYSQL CRUD helper base class to make data interaction extendable and easy.

## Setup

The first thing you will need to do is create 'secret.php' in your root folder. This file isn't tracked within github and holds secret information you don't want to push into a repository.

> /secret.php

    define( 'DB_NAME', 'DATABASE NAME' );
    define( 'DB_USER', 'DATABASE USER' );
    define( 'DB_PASSWORD', 'RANDOM PASSWORD' );
    define( 'DB_HOST', 'localhost' );
    define( 'DB_CHARSET', 'utf8mb4' );

This file is required to make successful connections to the database.