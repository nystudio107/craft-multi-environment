<?php

/**
 * Database Configuration
 *
 * All of your system's database configuration settings go in here.
 * You can see a list of the default settings in craft/app/etc/config/defaults/db.php
 */

// $_ENV constants are loaded by craft-multi-environment from .env.php via public/index.php
return array(

    // All environments
    '*' => array(
        'tablePrefix' => 'craft',
        'server' => getenv('CRAFTENV_DB_HOST'),
        'database' => getenv('CRAFTENV_DB_NAME'),
        'user' => getenv('CRAFTENV_DB_USER'),
        'password' => getenv('CRAFTENV_DB_PASS'),
    ),

    // Live (production) environment
    'live'  => array(
    ),

    // Staging (pre-production) environment
    'staging'  => array(
    ),

    // Local (development) environment
    'local'  => array(
    ),
);
