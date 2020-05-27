Pop PHP Framework
=================

<img src="http://www.popphp.org/assets/img/pop-php-logo.png" width="180" height="180" />

[![Join the chat at https://gitter.im/pop-php-framework/Lobby](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/pop-php-framework/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

RELEASE INFORMATION
-------------------
Pop PHP Framework 4.5.0 (beta-5)  
Released February 15, 2019

OVERVIEW
--------
This repository contains the composer.json file to install the full Pop PHP Framework.
The core Pop PHP components and the additional components will be installed:

|                                                      | Components                                               |                                                          |
|------------------------------------------------------|----------------------------------------------------------|----------------------------------------------------------|
| [pop-acl](https://github.com/popphp/pop-acl)         | [pop-dir](https://github.com/popphp/pop-dir)             | [pop-mime](https://github.com/popphp/pop-mime)           |
| [pop-audit](https://github.com/popphp/pop-audit)     | [pop-dom](https://github.com/popphp/pop-dom)             | [pop-nav](https://github.com/popphp/pop-nav)             |
| [pop-auth](https://github.com/popphp/pop-auth)       | [pop-filter](https://github.com/popphp/pop-filter)       | [pop-paginator](https://github.com/popphp/pop-paginator) |
| [pop-cache](https://github.com/popphp/pop-cache)     | [pop-form](https://github.com/popphp/pop-form)           | [pop-pdf](https://github.com/popphp/pop-pdf)             |
| [pop-code](https://github.com/popphp/pop-code)       | [pop-ftp](https://github.com/popphp/pop-ftp)             | [popcorn](https://github.com/popphp/popcorn)             |
| [pop-config](https://github.com/popphp/pop-config)   | [pop-http](https://github.com/popphp/pop-http)           | [popphp](https://github.com/popphp/popphp)               |
| [pop-console](https://github.com/popphp/pop-console) | [pop-i18n](https://github.com/popphp/pop-i18n)           | [pop-queue](https://github.com/popphp/pop-queue)         |
| [pop-cookie](https://github.com/popphp/pop-cookie)   | [pop-image](https://github.com/popphp/pop-image)         | [pop-session](https://github.com/popphp/pop-session)     |
| [pop-css](https://github.com/popphp/pop-css)         | [pop-kettle](https://github.com/popphp/pop-kettle)       | [pop-utils](https://github.com/popphp/pop-utils)         |
| [pop-csv](https://github.com/popphp/pop-csv)         | [pop-loader](https://github.com/popphp/pop-loader)       | [pop-validator](https://github.com/popphp/pop-validator) |
| [pop-db](https://github.com/popphp/pop-db)           | [pop-log](https://github.com/popphp/pop-log)             | [pop-view](https://github.com/popphp/pop-view)           |
| [pop-debug](https://github.com/popphp/pop-debug)     | [pop-mail](https://github.com/popphp/pop-mail)           |                                                          |

NEW FEATURES
------------
* A number of components have been improved and refactored. 
* Support for PHP 7.1+ only.
* PHPUnit tests refactored for PHPUnit 7.0+.
* Reference the `CHANGELOG.md` for further details.

INSTALL
-------
There are multiple ways you can get Pop PHP Framework into your project.

You can create a new project, which is recommended. This way, you will have
access to the CLI-helper script `pop-kettle` in the main project folder:

```console
$ composer create-project -s beta popphp/popphp-framework project-folder
```

Alternatively, you can add it to an existing project with this command:

```console
$ composer require popphp/popphp-framework
```

Or, you can add it your project's `composer.json` file:

    "require": {
        "popphp/popphp-framework": "^4.5.0-beta-5"
    },
    "minimum-stability": "beta"

Also, you can clone this repository and install it directly:

```console
$ composer install
```

### Kettle CLI-Helper

If choose to install the framework in a way that the `pop-kettle` CLI-helper
script is not available in the main project folder, you can place a copy of
the script from the `vendor/popphp/pop-kettle/kettle` location:

```bash
$ cp vendor/popphp/popphp-framework/kettle .
```
Once you've copied the script over, you have to change the reference to the script's
config file from:

```php
include __DIR__ . '/config/app.console.php'
```

to

```php
include __DIR__ . '/vendor/popphp/pop-kettle/config/app.console.php'
```

and make sure the newly copied `kettle` script is set to execute (755)

```bash
$ chmod 755 kettle
```

## DISCUSSION

There is a Gitter chat room for Pop PHP over at https://gitter.im/pop-php-framework/Lobby

