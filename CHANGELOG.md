# Craft-Multi-Environment Changelog

## 1.0.6 - 2017.12.07
### Changed
* Updated the default CME config to be in line with the Craft 3 version, in terms of the `custom` sub-array
* Updated the `.gitignore` for popular editors

## 1.0.5 - 2017.02.20
### Changed
* Handle load balancing and shared environments better via a check for `HTTP_X_FORWARDED_PROTO` in the protocol
* General code cleanup

## 1.0.4 - 2017.01.02
### Changed
* Refactored the `example.env.php` to set the `CRAFTENV` vars in an array, and auto-prefix them programatically
* Updated README.md

## 1.0.3 - 2016.11.30
### Added
* Added `CRAFTENV_CRAFT_ENVIRONMENT` so that the `CRAFT_ENVIRONMENT` constant is set via `.env.php`
* Renamed `env` to `craftEnd`, accessible via `{{ craft.config.craftEnv }}`
* Added an example Forge configuration in `forge-example`

### Changed
* Updated README.md

## 1.0.2 - 2016.11.09
### Added
* Added the `env` variable to `general.php`, accessible via `{{ craft.config.env }}`

### Changed
* Updated README.md

## 1.0.1 - 2016.11.02
### Added
* Added support for `CRAFTENV_SITE_URL`

### Changed
* Clarified the usage for `CRAFTENV_BASE_URL` & `CRAFTENV_BASE_PATH`
* Updated README.md

## 1.0.0 - 2016.11.01
### Changed
* Initial release
