<?php

return [
    'host' => 'localhost', // Change this to your database host
    'database' => 'fontmanager', // Change this to your database name
    'username' => 'root', // Change this to your database username
    'password' => '', // Change this to your database password
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
