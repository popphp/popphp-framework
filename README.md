Pop PHP Framework
=================

<img src="http://www.popphp.org/img/pop-php-logo.png" width="180" height="180" />

[![Join the chat at https://gitter.im/popphp/2](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/popphp/2?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

RELEASE INFORMATION
-------------------
Pop PHP Framework 3.0.0  
Released July 9, 2016

OVERVIEW
--------
This repository contains the composer.json file to install the full Pop PHP Framework.
The core Pop PHP components and the additional 31 components will be installed:

|                                                      | Components                                         |                                                          |
|------------------------------------------------------|----------------------------------------------------|----------------------------------------------------------|
| [pop-acl](https://github.com/popphp/pop-acl)         | [pop-dom](https://github.com/popphp/pop-dom)       | [pop-nav](https://github.com/popphp/pop-nav)             |                                                                                                         
| [pop-archive](https://github.com/popphp/pop-archive) | [pop-feed](https://github.com/popphp/pop-feed)     | [pop-paginator](https://github.com/popphp/pop-paginator) |                                                                                                                              
| [pop-auth](https://github.com/popphp/pop-auth)       | [pop-file](https://github.com/popphp/pop-file)     | [pop-payment](https://github.com/popphp/pop-payment)     |                                                                                                                    
| [pop-cache](https://github.com/popphp/pop-cache)     | [pop-form](https://github.com/popphp/pop-form)     | [pop-pdf](https://github.com/popphp/pop-pdf)             |                                                                                                              
| [pop-code](https://github.com/popphp/pop-code)       | [pop-ftp](https://github.com/popphp/pop-ftp)       | [popphp](https://github.com/popphp/popphp)               |                                                                                                        
| [pop-config](https://github.com/popphp/pop-config)   | [pop-http](https://github.com/popphp/pop-http)     | [pop-session](https://github.com/popphp/pop-session)     |                                                                                                                        
| [pop-console](https://github.com/popphp/pop-console) | [pop-i18n](https://github.com/popphp/pop-i18n)     | [pop-shipping](https://github.com/popphp/pop-shipping)   |                                                                                                                            
| [pop-cookie](https://github.com/popphp/pop-cookie)   | [pop-image](https://github.com/popphp/pop-image)   | [pop-validator](https://github.com/popphp/pop-validator) |                                                                                                                              
| [pop-crypt](https://github.com/popphp/pop-crypt)     | [pop-loader](https://github.com/popphp/pop-loader) | [pop-version](https://github.com/popphp/pop-version)     |                                                                                                                          
| [pop-data](https://github.com/popphp/pop-data)       | [pop-log](https://github.com/popphp/pop-log)       | [pop-view](https://github.com/popphp/pop-view)           |                                                                                                            
| [pop-db](https://github.com/popphp/pop-db)           | [pop-mail](https://github.com/popphp/pop-mail)     |                                                          |                                                            

NEW FEATURES
------------

* The Cache component now supports Redis and Session adapters.
* The Session and Cookie classes of the deprecated `pop-web` component have been broken out into their own individual components.
* The Record sub-component of the Db component has been refactored. Functionality with this should remain largely the same,
  but there may be some backward compatibility breaks in older code.

DEPRECATED FEATURES
-------------------

* The Rar adapter for the `pop-archive` component has been removed.
* The `pop-filter` component has been removed.
* The `pop-geo` component has been removed.
* The `pop-web` component has been removed. The new `pop-cookie` and `pop-session` components replace it.

PHP 7
-----

The Pop PHP Framework has been fully tested for and works with PHP 7. However, as of July 1, 2016, due to
instability or deprecation of a few PHP extensions, the following components will have some sub-components
that are either not available or will not function properly in a PHP 7 environment:

#### pop-cache

- Due to the unavailability or instability of the **apc/apcu/apc_bc** extensions, the APC class adapter may not function properly in PHP 7.
- Due to the unavailability or instability of the **memcache** extension, the Memcached class adapter will not function properly in PHP 7.

INSTALL
-------
There are multiple ways you can get Pop PHP Framework into your project.

You can add it to an existing project:

```console
$ composer require popphp/popphp-framework
```

You can add it your project's `composer.json` file:

    "require": {
        "popphp/popphp-framework": "3.0.*"
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

There is a Gitter chat room for Pop PHP over at https://gitter.im/popphp/2
