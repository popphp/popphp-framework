Pop PHP Framework
=================

<img src="http://www.popphp.org/assets/img/pop-php-logo.png" width="180" height="180" />

[![Join the chat at https://gitter.im/pop-php-framework/Lobby](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/pop-php-framework/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

RELEASE INFORMATION
-------------------
Pop PHP Framework 3.7.0  
Released June 26, 2018

OVERVIEW
--------
This repository contains the composer.json file to install the full Pop PHP Framework.
The core Pop PHP components and the additional 28 components will be installed:

|                                                      | Components                                               |                                                          |
|------------------------------------------------------|----------------------------------------------------------|----------------------------------------------------------|
| [pop-acl](https://github.com/popphp/pop-acl)         | [pop-debug](https://github.com/popphp/pop-debug)         | [pop-mail](https://github.com/popphp/pop-mail)           |
| [pop-auth](https://github.com/popphp/pop-auth)       | [pop-dir](https://github.com/popphp/pop-dir)             | [pop-nav](https://github.com/popphp/pop-nav)             |
| [pop-cache](https://github.com/popphp/pop-cache)     | [pop-dom](https://github.com/popphp/pop-dom)             | [pop-paginator](https://github.com/popphp/pop-paginator) |
| [pop-code](https://github.com/popphp/pop-code)       | [pop-form](https://github.com/popphp/pop-form)           | [pop-pdf](https://github.com/popphp/pop-pdf)             |
| [pop-config](https://github.com/popphp/pop-config)   | [pop-ftp](https://github.com/popphp/pop-ftp)             | [popcorn](https://github.com/popphp/popcorn)             |
| [pop-console](https://github.com/popphp/pop-console) | [pop-http](https://github.com/popphp/pop-http)           | [popphp](https://github.com/popphp/popphp)               |
| [pop-cookie](https://github.com/popphp/pop-cookie)   | [pop-i18n](https://github.com/popphp/pop-i18n)           | [pop-session](https://github.com/popphp/pop-session)     |
| [pop-css](https://github.com/popphp/pop-css)         | [pop-image](https://github.com/popphp/pop-image)         | [pop-validator](https://github.com/popphp/pop-validator) |
| [pop-csv](https://github.com/popphp/pop-csv)         | [pop-loader](https://github.com/popphp/pop-loader)       | [pop-view](https://github.com/popphp/pop-view)           |
| [pop-db](https://github.com/popphp/pop-db)           | [pop-log](https://github.com/popphp/pop-log)             |                                                          |


NEW FEATURES
------------

* Bootstrap functionality has been added to provide basic application scaffolding.
* The new CSS component has been added to the framework.
* The DOM component has been updated to include parsing capabilities.
* The new Debug component has been added to the framework.
* The Database component has been significantly refactored for v4.
* The Cache component now supports Redis and Session adapters.
* The Data component has been deprecated and the CSV functionality has been moved into its own component, `pop-csv`.
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
* The `pop-payment` component has been removed.
* The `pop-shipping` component has been removed.
* The `pop-version` component has been removed.
* The `pop-web` component has been removed (see above.)


INSTALL
-------
There are multiple ways you can get Pop PHP Framework into your project.

You can add it to an existing project:

```console
$ composer require popphp/popphp-framework
```

You can add it your project's `composer.json` file:

    "require": {
        "popphp/popphp-framework": "^3.6.5"
    }

You can create a new project and install it into that project:

```console
$ composer create-project popphp/popphp-framework project-folder
```

Or, you can clone this repository and install it directly:

```console
$ composer install
```

BOOTSTRAPPING
-------------
You can quickly bootstrap a small application project by running the following command:

```console
$ boostrap/pop install MyApp
```

The above command will create the necessary basic application scaffolding to run a simple
web application. You will see an `app` folder with your namespaced codebase in it and a
`public` folder with the application's front controller in it. If you point a web server
at the public folder, you will see a basic index page.

 ```console
 $ php -S localhost:8000 -t public
 ```

#### Console Support

You can also bootstrap an application with console support as well. If you run the
following command:

```console
$ boostrap/pop install --cli MyApp
```

The codebase created will also include a `script` folder with the CLI application script.
A default `help` command is set up be default:

 ```console
 $ script/app help
 ```

## DISCUSSION

There is a Gitter chat room for Pop PHP over at https://gitter.im/pop-php-framework/Lobby
