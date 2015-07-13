Pop PHP Framework
=================

<img style="float: right; width: 180px; height: 180px; margin: 0 0 20px 20px;" src="http://www.popphp.org/img/pop-php-logo.png" />

RELEASE INFORMATION
-------------------
Pop PHP Framework 2.0.0  
Release July 11, 2015

OVERVIEW
--------
This repository contains the composer.json file to install the full Pop PHP Framework.
The core Pop PHP components and the additional 32 components will be installed:

|                                                      | Pop PHP Framework Core Components                  |                                                          |
|------------------------------------------------------|----------------------------------------------------|----------------------------------------------------------|
| [pop-acl](https://github.com/popphp/pop-acl)         | [pop-feed](https://github.com/popphp/pop-feed)     | [pop-mail](https://github.com/popphp/pop-mail)           |
| [pop-archive](https://github.com/popphp/pop-archive) | [pop-file](https://github.com/popphp/pop-file)     | [pop-nav](https://github.com/popphp/pop-nav)             |
| [pop-auth](https://github.com/popphp/pop-auth)       | [pop-filter](https://github.com/popphp/pop-filter) | [pop-paginator](https://github.com/popphp/pop-paginator) |
| [pop-cache](https://github.com/popphp/pop-cache)     | [pop-form](https://github.com/popphp/pop-form)     | [pop-payment](https://github.com/popphp/pop-payment)     |
| [pop-code](https://github.com/popphp/pop-code)       | [pop-ftp](https://github.com/popphp/pop-ftp)       | [pop-pdf](https://github.com/popphp/pop-pdf)             |
| [pop-config](https://github.com/popphp/pop-config)   | [pop-geo](https://github.com/popphp/pop-geo)       | [popphp](https://github.com/popphp/popphp)               |
| [pop-console](https://github.com/popphp/pop-console) | [pop-http](https://github.com/popphp/pop-http)     | [pop-shipping](https://github.com/popphp/pop-shipping)   |
| [pop-crypt](https://github.com/popphp/pop-crypt)     | [pop-i18n](https://github.com/popphp/pop-i18n)     | [pop-validator](https://github.com/popphp/pop-validator) |
| [pop-data](https://github.com/popphp/pop-data)       | [pop-image](https://github.com/popphp/pop-image)   | [pop-version](https://github.com/popphp/pop-version)     |
| [pop-db](https://github.com/popphp/pop-db)           | [pop-loader](https://github.com/popphp/pop-loader) | [pop-view](https://github.com/popphp/pop-view)           |
| [pop-dom](https://github.com/popphp/pop-dom)         | [pop-log](https://github.com/popphp/pop-log)       | [pop-web](https://github.com/popphp/pop-web)             |

INSTALL
-------
There are multiple ways you can get Pop PHP Framework into your project.

You can clone this repository and install it directly:

```console
$ composer install
```

You can add it to an existing project:

```console
$ composer require popphp/popphp-framework
```

You can add it your project's `composer.json` file:

    "require": {
        "popphp/popphp-framework": "2.0.*"
    }

Or, you can create a new project and install it into that project:

```console
$ composer create-project popphp/popphp-framework project-folder
```


