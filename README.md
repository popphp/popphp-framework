Pop PHP Framework
=================

<img src="http://www.popphp.org/img/pop-php-logo.png" width="180" height="180" />

[![Join the chat at https://gitter.im/pop-php-framework/Lobby](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/pop-php-framework/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

RELEASE INFORMATION
-------------------
Pop PHP Framework 3.5.1  
Released May 18, 2017

OVERVIEW
--------
This repository contains the composer.json file to install the full Pop PHP Framework.
The core Pop PHP components and the additional 25 components will be installed:

|                                                      | Components                                               |                                                          |
|------------------------------------------------------|----------------------------------------------------------|----------------------------------------------------------|
| [pop-acl](https://github.com/popphp/pop-acl)         | [pop-dom](https://github.com/popphp/pop-dom)             | [pop-pdf](https://github.com/popphp/pop-pdf)             |
| [pop-auth](https://github.com/popphp/pop-auth)       | [pop-form](https://github.com/popphp/pop-form)           | [popcorn](https://github.com/popphp/popcorn)             |
| [pop-cache](https://github.com/popphp/pop-cache)     | [pop-ftp](https://github.com/popphp/pop-ftp)             | [popphp](https://github.com/popphp/popphp)               |
| [pop-code](https://github.com/popphp/pop-code)       | [pop-http](https://github.com/popphp/pop-http)           | [pop-session](https://github.com/popphp/pop-session)     |
| [pop-config](https://github.com/popphp/pop-config)   | [pop-image](https://github.com/popphp/pop-image)         | [pop-validator](https://github.com/popphp/pop-validator) |
| [pop-console](https://github.com/popphp/pop-console) | [pop-loader](https://github.com/popphp/pop-loader)       | [pop-view](https://github.com/popphp/pop-view)           |
| [pop-cookie](https://github.com/popphp/pop-cookie)   | [pop-log](https://github.com/popphp/pop-log)             |                                                          |
| [pop-csv](https://github.com/popphp/pop-csv)         | [pop-mail](https://github.com/popphp/pop-mail)           |                                                          |
| [pop-db](https://github.com/popphp/pop-db)           | [pop-nav](https://github.com/popphp/pop-nav)             |                                                          |
| [pop-dir](https://github.com/popphp/pop-dir)         | [pop-paginator](https://github.com/popphp/pop-paginator) |                                                          |


NEW FEATURES
------------

* The Database component has been significantly refactored for v4.
* The Cache component now supports Redis and Session adapters.
* The Data compoenent has been deprecated and the CSV functionality has been moved into its own component, `pop-csv`.
* The Session and Cookie classes of the deprecated `pop-web` component have been broken out into their own individual components.
* The File Component has been deprecated and the upload functionality has been moved to the Http component and the directory
  functionality has been moved into its own component, `pop-dir`.


DEPRECATED FEATURES
-------------------

* The `pop-archive` component has been removed.
* The `pop-crypt` component has been removed.
* The `pop-data` component has been removed (see above.)
* The `pop-feed` component has been removed.
* The `pop-file` component has been removed (see above.)
* The `pop-filter` component has been removed.
* The `pop-geo` component has been removed.
* The `pop-i18n` component has been removed.
* The `pop-payment` component has been removed.
* The `pop-shipping` component has been removed.
* The `pop-version` component has been removed.
* The `pop-web` component has been removed (see above.)


PHP 7
-----

The Pop PHP Framework has been fully tested for and works with PHP 7. However, as of January 1, 2017, due to
instability or deprecation of a few PHP extensions, the following components will have some sub-components
that are either not available or will not function properly in a PHP 7 environment:

#### pop-cache

- Due to the unavailability or instability of the **apc/apcu/apc_bc** extensions, the APC class adapter may not function properly in PHP 7.
- Due to the unavailability or instability of the **memcache/memcached** extensions, the Memcache & Memcached class adapter may not function properly in PHP 7

INSTALL
-------
There are multiple ways you can get Pop PHP Framework into your project.

You can add it to an existing project:

```console
$ composer require popphp/popphp-framework
```

You can add it your project's `composer.json` file:

    "require": {
        "popphp/popphp-framework": "3.5.*"
    }

You can create a new project and install it into that project:

```console
$ composer create-project popphp/popphp-framework project-folder
```

Or, you can clone this repository and install it directly:

```console
$ composer install
```

## DISCUSSION

There is a Gitter chat room for Pop PHP over at https://gitter.im/pop-php-framework/Lobby
