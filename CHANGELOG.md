# Craft-Multi-Environment Changelog

## 1.0.5 -- 2017.02.20
### Changed
* Handle load balancing and shared environments better via a check for `HTTP_X_FORWARDED_PROTO` in the protocol
* General code cleanup

## 1.0.4 -- 2017.01.02

* [Improved] Refactored the `example.env.php` to set the `CRAFTENV` vars in an array, and auto-prefix them programatically
* [Improved] Updated README.md

## 1.0.3 -- 2016.11.30

* [Added] Added `CRAFTENV_CRAFT_ENVIRONMENT` so that the `CRAFT_ENVIRONMENT` constant is set via `.env.php`
* [Added] Renamed `env` to `craftEnd`, accessible via `{{ craft.config.craftEnv }}`
* [Added] Added an example Forge configuration in `forge-example`
* [Improved] Updated README.md

## 1.0.2 -- 2016.11.09

* [Added] Added the `env` variable to `general.php`, accessible via `{{ craft.config.env }}`
* [Improved] Updated README.md

## 1.0.1 -- 2016.11.02

* [Added] Added support for `CRAFTENV_SITE_URL`
* [Improved] Clarified the usage for `CRAFTENV_BASE_URL` & `CRAFTENV_BASE_PATH`
* [Improved] Updated README.md

## 1.0.0 -- 2016.11.01

* [Improved] Initial release
