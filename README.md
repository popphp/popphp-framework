Pop PHP Framework
=================

<img src="http://www.popphp.org/assets/img/pop-php-logo.png" width="180" height="180" />

[![Join the chat at https://discord.gg/8DUdCr9x](https://www.popphp.org/assets/img/discord-chat.svg)](https://discord.gg/8DUdCr9x)

* [Overview](#overview)
* [New Features](#new-features)
* [Install](#install)
* [Kettle](#kettle)
* [Support](#support)

Release Information
-------------------
Pop PHP Framework 5.0.0  
Released October 30, 2023

Overview
--------
This repository contains the `composer.json` file to install the full Pop PHP Framework.
The core Pop PHP components and the additional components listed below will be installed:

|                                                      | Components                                         |                                                          |
|------------------------------------------------------|----------------------------------------------------|----------------------------------------------------------|
| [pop-acl](https://github.com/popphp/pop-acl)         | [pop-debug](https://github.com/popphp/pop-debug)   | [pop-mime](https://github.com/popphp/pop-mime)           |
| [pop-audit](https://github.com/popphp/pop-audit)     | [pop-dir](https://github.com/popphp/pop-dir)       | [pop-nav](https://github.com/popphp/pop-nav)             |
| [pop-auth](https://github.com/popphp/pop-auth)       | [pop-dom](https://github.com/popphp/pop-dom)       | [pop-paginator](https://github.com/popphp/pop-paginator) |
| [pop-cache](https://github.com/popphp/pop-cache)     | [pop-filter](https://github.com/popphp/pop-filter) | [pop-pdf](https://github.com/popphp/pop-pdf)             |
| [pop-code](https://github.com/popphp/pop-code)       | [pop-form](https://github.com/popphp/pop-form)     | [popcorn](https://github.com/popphp/popcorn)             |
| [pop-color](https://github.com/popphp/pop-color)     | [pop-ftp](https://github.com/popphp/pop-ftp)       | [popphp](https://github.com/popphp/popphp)               |
| [pop-config](https://github.com/popphp/pop-config)   | [pop-http](https://github.com/popphp/pop-http)     | [pop-queue](https://github.com/popphp/pop-queue)         |
| [pop-console](https://github.com/popphp/pop-console) | [pop-i18n](https://github.com/popphp/pop-i18n)     | [pop-session](https://github.com/popphp/pop-session)     |
| [pop-cookie](https://github.com/popphp/pop-cookie)   | [pop-image](https://github.com/popphp/pop-image)   | [pop-storage](https://github.com/popphp/pop-storage)     |
| [pop-css](https://github.com/popphp/pop-css)         | [pop-kettle](https://github.com/popphp/pop-kettle) | [pop-utils](https://github.com/popphp/pop-utils)         |
| [pop-csv](https://github.com/popphp/pop-csv)         | [pop-log](https://github.com/popphp/pop-log)       | [pop-validator](https://github.com/popphp/pop-validator) |
| [pop-db](https://github.com/popphp/pop-db)           | [pop-mail](https://github.com/popphp/pop-mail)     | [pop-view](https://github.com/popphp/pop-view)           |

New Features
------------
* Support for PHP 8.1+
* Support for PHP <=7.4 has been deprecated.
* PHPUnit tests refactored for PHPUnit 10.0+.
* Reference the [CHANGELOG.md](https://github.com/popphp/popphp-framework/blob/v5/CHANGELOG.md) for further details.

[Top](#pop-php-framework)

Install
-------
There are multiple ways you can get Pop PHP Framework into your project.

##### Option 1

You can create a new project with the `composer create-project` command, which is recommended.
This way, you will have access to the CLI-helper script `kettle` in the main project folder:

```console
$ composer create-project popphp/popphp-framework project-folder
```

##### Option 2

You can clone this repository directly, which will also install the `kettle` script
in the main project folder:

```console
$ git clone https://github.com/popphp/popphp-framework.git popphp
$ cd popphp
$ composer install
```

##### Option 3

You can add it to an existing project with the `composer require` command:

```console
$ composer require popphp/popphp-framework
```

##### Option 4

You can add it your project's `composer.json` file:

    "require": {
        "popphp/popphp-framework": "^5.0.0"
    }


[Top](#pop-php-framework)

Kettle
------

### CLI Helper

`pop-kettle`

If choose to install the framework in a way that the `pop-kettle` CLI-helper script is not available
in the main project folder (options 3 and 4), you can place a copy of the script from the
`vendor/popphp/pop-kettle/kettle` location:

```bash
$ cp vendor/popphp/popphp-framework/kettle .
```
Once you've copied the script over, you have to change the reference to the script's
config file from:

```php
    $app = new Pop\Application(
        $autoloader, include __DIR__ . '/config/app.console.php'
    );
```

to

```php
    $app = new Pop\Application(
        $autoloader, include __DIR__ . '/vendor/popphp/pop-kettle/config/app.console.php'
    );
```

and make sure the newly copied `kettle` script is set to execute (755)

```bash
$ chmod 755 kettle
```

[Top](#pop-php-framework)

Support
-------

There is a Discord server for the Pop PHP community and support. The invite link is https://discord.gg/8DUdCr9x
Also, there is a account on X at https://x.com/popphpframework

