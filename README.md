# Craft-Multi-Environment

Efficient and flexible multi-environment config for Craft CMS

## Overview

[Multi-Environment Configs](https://craftcms.com/docs/multi-environment-configs) let you easily run a Craft CMS project in local dev, staging, and production.  They allow different people to work in different environments without painful setup or coordination.

### Why another multi-environment config?

There are a number of good approaches to implementing a multi-environment config in Craft CMS, but they each have drawbacks.  There are two main approaches typically used are:

1. [Multi-Environment Configs](https://craftcms.com/docs/multi-environment-configs) - The problem with this approach is that it often results in data stored in a git repo (such as passwords, Stripe keys, etc.) that really shouldn't be.
2. [PHP dotenv](https://github.com/vlucas/phpdotenv) - The problem with this approach is that PHP dotenv is fairly heavy, and indeed the authors warn against using it in production.  Instantiating the Composer auto-loader and reading in the `.env` file for every request adds unnecessary overhead.

Craft-Multi-Environment (CME) is my attempt to create something that finds a middle-ground between the two approaches.

### How does it work?

CME works by including a `.env.php` file (which is never checked into git) via the Craft `index.php` file that is loaded for every non-static request.

The `.env.php` file sets some globally-accessible settings via `putenv()` for common things like the database password, database user, base URL, etc.  You're also free to add your own as you see fit.  The `craft/config/general.php` and `craft/config/db.php` can thus remain abstracted, and each environment can have their own local settings.

This is more performant than auto-loading a class and reading a `.env` file for each request, but maintains the same flexibility.  Additionally, since we are using `getenv()` to access the settings, these can be set directly by the webserver (without using the `.env.php` file at all) for additional security and performance.

Also, since we're using `getenv()`, these settings are globally accessible in the PHP session, and can for instance be used in a Craft Commerce `commerce.php` file, accessed via plugins, etc.

## Using Craft-Multi-Environment

### Assumptions

CME assumes that you have a folder structure such as this for your project root:

    .env.php
    craft/
    public/
        index.php

If your folder structure is different, that's fine.  But you may need to adjust the path to `.env.php` in the `index.php` file, and you may need to adjust the way `CRAFTENV_BASE_PATH` is constructed in your `.env.php` (or just hardcode the path).

CME will also work fine with localized sites as well, you'll just need to adjust the aforementioned paths as appropriate.

### Setting it up

1. Copy `craft/config/general.php` and `craft/config/db.php` to your project's `craft/config` folder
2. Copy `public/index.php` to your project's public folder
3. Copy `example.env.php` to your project's root folder, then duplicate it, and rename the copy `.env.php`
4. Edit the `.env.php` file, replacing instances of `REPLACE_ME` with your appropriate settings
5. Add `/.env.php` to your `.gitignore` file

The `public/index.php` file included with CME just has the following added at the top of it (it is otherwise unchanged):

    // Load the local Craft environment
    if (file_exists('../.env.php'))
        require_once('../.env.php');
    // Default environment
    if (!defined('CRAFT_ENVIRONMENT'))
        define('CRAFT_ENVIRONMENT', getenv('CRAFTENV_CRAFT_ENVIRONMENT'));

You will need to create an `.env.php` file for each environment on which your Craft CMS project will be used (other team member's local dev, staging, production, etc.), but the `db.php`, `general.php`, and `index.php` are the same on all environments.

It's recommended that the `example.env.php` **is** checked into your git repo, so others can use it for a guide when creating their own local `.env.php` file.

### Local environments

CME suggests the following environments, each of which can have different Craft settings per environment, independent of the private settings defined in `.env.php`:

1. `*` - applies globally to all environments
2. `live` - your live production environment
3. `staging` - your staging or pre-production environment for client review, external testing, etc.
4. `local` - your local development environment

The `db.php` and `config.php` define each environment, and you can put whatever [Craft Config Settings](https://craftcms.com/docs/config-settings) you desire for each environment in each.  The names of the environments and the default settings for each are just suggestions, however.  You can change them to be whatever you like.

### Extending it

If you have additional settings that need to be globally accessible, you can just add them to the `.env.php`.  For example, let's say we need a private key for Stripe, you can add this to `.env.php`:

    // The private Stripe key.
    putenv('CRAFTENV_STRIPE_KEY=' . 'REPLACE_ME');

By convention, all CME settings should be prefixed with `CRAFTENV_` for semantic reasons, and to avoid namespace collisions.

You should also update the `example.env.php` to include any settings you add, for reference.

### Accessing the settings in `general.php`

You can access any variables defined in the `general.php` file in Twig via `{{ craft.config }}`.  e.g.:

    {% if craft.config.craftEnv == "local" %}
    {% endif %}

### Production via webserver config

It's perfectly fine to use CME as discussed above in a production environment.  However, if you want an added measure of security and performance, you can set up your webserver to set the same globally accessible settings via webserver config.

It's slightly more secure, in that only a user with admin privileges should have access to the server config files.  It's ever so slightly more performant, in that there's no extra `.env.php` file that is being processed with each request.

This is entirely optional, but if you're interested in doing it, here's how.

1. Keep the original `public/index.php` file as it came with Craft CMS on your production environment instead of using the `index.php` that comes with CME.
2. Configure your webserver as described below, and then restart it

#### Apache

Inside the `<VirtualHost>` block:

    SetEnv CRAFTENV_CRAFT_ENVIRONMENT "REPLACE_ME"
    SetEnv CRAFTENV_DB_HOST "REPLACE_ME"
    SetEnv CRAFTENV_DB_NAME "REPLACE_ME"
    SetEnv CRAFTENV_DB_USER "REPLACE_ME"
    SetEnv CRAFTENV_DB_PASS "REPLACE_ME"
    SetEnv CRAFTENV_SITE_URL "REPLACE_ME"
    SetEnv CRAFTENV_BASE_URL "REPLACE_ME"
    SetEnv CRAFTENV_BASE_PATH "REPLACE_ME"

(...and any other custom config settings you've added)

#### Nginx

Inside the `server {}` or `location ~ \.php {}` block or in the `fastcgi_params` file:

    fastcgi_param CRAFTENV_CRAFT_ENVIRONMENT "REPLACE_ME";
    fastcgi_param CRAFTENV_DB_HOST "REPLACE_ME";
    fastcgi_param CRAFTENV_DB_NAME "REPLACE_ME";
    fastcgi_param CRAFTENV_DB_USER "REPLACE_ME";
    fastcgi_param CRAFTENV_DB_PASS "REPLACE_ME";
    fastcgi_param CRAFTENV_SITE_URL "REPLACE_ME";
    fastcgi_param CRAFTENV_BASE_URL "REPLACE_ME";
    fastcgi_param CRAFTENV_BASE_PATH "REPLACE_ME";

(...and any other custom config settings you've added)

## Craft-Multi-Environment Changelog

### 1.0.3 -- 2016.11.30

* [Added] Added `CRAFTENV_CRAFT_ENVIRONMENT` so that the `CRAFT_ENVIRONMENT` constant is set via `.env.php`
* [Added] Renamed `env` to `craftEnd`, accessible via `{{ craft.config.craftEnv }}`
* [Added] Added an example Forge configuration in `forge-example`
* [Improved] Updated README.md

### 1.0.2 -- 2016.11.09

* [Added] Added the `env` variable to `general.php`, accessible via `{{ craft.config.env }}`
* [Improved] Updated README.md

### 1.0.1 -- 2016.11.02

* [Added] Added support for `CRAFTENV_SITE_URL`
* [Improved] Clarified the usage for `CRAFTENV_BASE_URL` & `CRAFTENV_BASE_PATH`
* [Improved] Updated README.md

### 1.0.0 -- 2016.11.01

* [Improved] Initial release

Brought to you by [nystudio107](https://nystudio107.com/)
