CHANGELOG
=========

## 4.5.0 (Beta)
* `pop-db`updated to v5.0.3
    + Refactored the predicate set parser/generator classes
    + Refactored "helper" parser classes (expressions, tables, etc.)
    + Improved SQL builder and schema builder classes
    + Improved connect/disconnect functionality of adapter classes
    + Improved support for standard SQL functions
    + Remove references to ArrayObjects
    + Improved unit tests/code coverage
* `pop-acl`updated to v3.3.0
    + Added policy functionality
* `pop-audit`updated to v1.2.0
    + Refactored Http adapter to work with newly refactored `pop-http` component
    + Refactored Table adapter to include check and create table schema
    + General code review/clean up
* `pop-auth` updated to v3.2.0
    + Refactored Http class to use the pop-http component
* `pop-cache` updated to v3.3.0
    + Refactored the Sqlite adapter class into a full Db adapter
    + Deprecated and removed the Memcache adapter (in favor of the Memcached adapter)
    + Refactored the Apc adapter to only use the APCu extension
    + Refactored/improved unit tests
    + Code review/clean up
* `pop-code` updated to v4.0.0
    + Support for traits
    + Support for constants
    + Better support for namespaces
    + Improved reflection/parsing support
    + Refactored/improved unit tests
    + Code review/clean up
* `pop-debug`updated to v1.2.0
    + Refactored the Sqlite storage class into full Db storage class
    + Refactored the Request handler to use the newly refactored `pop-http` component
    + Code review/clean up
* `pop-mail` updated to v3.5.0
    + Incorporated the new `pop-mime` component to be used within the `pop-mail`
      component for better and more consistent parsing of mail messages
    + Refactored the attachment class
* `pop-form` updated to v3.5.0
    + Added `AclForm` class to enforce ACL-based roles and permissions for which form
      fields are accessible by certain users
    + Removed the native filter classes in favor of using the newly refactored
      `pop-filter` component
    + Added `FormValidator` class to simply perform field value validations without
      the weight of a full form object
    + Added `FormConfig` class for more robust support of form configuration
    + Better support for conditional validation
* `pop-http` updated to v3.5.5
    + Refactored code-base, better class structure (abstract classes, interfaces, etc.)
    + Better support for the HTTP client classes, including better support for form data
    + Incorporated the new `pop-mime` component for better multipart form data generation
    + Incorporated the newly refactored `pop-filter` component for input data filtering
    + Better support of large raw data streams storing to file (instead of in memory)
* `pop-filter` reinstated and re-purposed in v3.0.0
    + Refactored the previously deprecated `pop-filter` component to handle filtering
      of data and values for multiple components that need filtering functionality
* `pop-kettle` updated to v1.2.1
    + Updated to work with newly refactored and updated components of Pop PHP v4.5
    + Added `db:create-seed` command
    + Added better support for Windows
* `pop-log` updated to v3.2.0
    + Added log limits
* `pop-mime` new release v1.0.0
    + New component to handle generating and parsing MIME content
* `pop-queue` new release v1.0.0
    + New component to manage job queues
* `pop-utils` new release v1.1.0
    + New component to provide simple common utilities, classes and interfaces
* `pop-view` updated to v3.2.0
    + Added `pop-filter` component for the filtering functionality.
* `pop-session` updated to v3.2.0
    + Refactored code-base, better class structure (abstract classes, interfaces, etc.)
    + Added unit tests
* `pop-cookie` updated to v3.2.0
    + Added ArrayAccess, Countable and Iterator
    + Added unit tests
* `pop-dir` updated to v3.1.0
    + Added support for unlinking/unsetting files from directory object (if writable/accessible)
* `pop-i18n` updated to v3.1.0
    + Added support for output variations under one source.
* `popphp` updated to v3.4.0
    + Added better support for dynamic array params in the HTTP and CLI route objects
    + Refactored to use the new `pop-utils` component, including the array and callable features
* `popcorn` updated to v3.3.0
    + Added support for custom HTTP methods
    + Add `any()` method
    + Better exception error messaging

## 4.1.0
* Updated to pop-csv v3.1.4, better appending, newline, escape and limit support
* Updated tp pop-db v4.5.5, with support for export and creating large SQL
  queries from data sets
* Updated to pop-form v3.4.0, which includes:
    + ACL Form objects
    + Simple, light-weight form validator class for easy validation of
      form values without the weight of a full HTML form object 
* Update to pop-mail v3.2.2, which includes:
    + Better support for file attachments, both under the IMAP client
      and the Mailer classes
    + Support for auto-detection of content-type for attachments,
      better handling of newlines in message parts
* Update to popphp v3.3.1, adding a force route parameter to the `run()` method

## 4.0.3
* Updated to pop-csv v3.1.0, which includes static helper methods

## 4.0.2
* Updated to pop-db v4.5.0, which includes support for the encoded record class

## 4.0.1
* Added pop-kettle component for CLI-based helper functionality

## 4.0.0
* Support for PHP 7.1+ only
* PHPUnit tests refactored for PHPUnit 7.0+
* Refactored pop-auth
* Refactored pop-console, added better support for help command display
* Refactored pop-db, improved relationship functionality 
* Updated pop-debug, improved ExceptionHandler and QueryHandler
* Refactored pop-form:
    + Added ACL-enabled form capabilities
    + Moved filter functionality into separate set of classes
* Refactored pop-http, added separate HTTP response parser class
* Refactored pop-log, added HTTP log writer
* Refactored pop-nav, added NavBuilder class
* Refactored pop-pdf, improved text wrap & alignment functionality
* Refactored pop-view, added separate stream parser class
* Refactored popphp, improved Application and Module class relationships
* Removed bootstrap feature from main framework repository

## 3.8.0
* Added pop-audit
* Added the ability to track dirty attributes in pop-db

## 3.8.0
* Added bootstrap functionality to provide basic application scaffolding

## 3.6.5
* Updated pop-pdf

## 3.6.4

* Updated license & copyright
* Updated composer.json

## 3.6.3

* Updated pop-dom

## 3.6.2

* Added pop-css

## 3.6.1

* Reinstated pop-i18n

## 3.6.0

* Updated pop-db
* Updated pop-cache
* Added pop-debug

## 3.5.2

* Updated pop-config
* Updated pop-image
* Updated pop-pdf
* Updated pop-session
* Updated popphp
* Updated popcorn

## 3.5.1

* Updated pop-auth
* Updated popcorn
* Updated pop-http
* Updated pop-db

## 3.5.0

### New or Changed Features

* The Database component has been significantly refactored for v4.
* The Data component has been deprecated and the CSV functionality has been moved into its own component, `pop-csv`.
* The File Component has been deprecated and the upload functionality has been moved to the Http component and the directory
  functionality has been moved into its own component, `pop-dir`.

### Removed Features

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

## 3.0.1

### Changed

* The mail component was updated to version 3.0.0.

## 3.0

### New Features

* The Cache component now supports Redis and Session adapters.
* The Session and Cookie classes of the deprecated `pop-web` component
  have been broken out into their own individual components, `pop-session`
  and `pop-cookie`.
* The `pop-version` component now can pull its source from the Pop website
  or from GitHub.

### Changed Features

* The Record sub-component of the Db component has been refactored.
  Functionality with this should remain largely the same, but there
  may be some backward compatibility breaks in older code.

### Deprecated Features

* Due to the unavailability or instability of the **apc/apcu/apc_bc**
  extensions, the APC adapter in the `pop-cache` component may not
  function properly in PHP 7.
* Due to the unavailability or instability of the **memcache/memcached**
  extensions, the Memcache & Memcached adapters in the `pop-cache`
  component may not function properly in PHP 7

### Removed Features

* The `pop-web` component has been removed. The cookie and session
  sub-components have been ported into their own individual components
  respectively.
* The `pop-filter` component has been removed.
* The `pop-geo` component has been removed.
* The Rar adapter in the `pop-archive` component has been removed.
