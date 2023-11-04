CHANGELOG
=========

## 5.0.0
**(10/30/2023)**
- Upgraded to PHP 8.1+
- `pop-http`
  + Better separation of Client and Server functionality
    - Added standalone Client class
    - Added client handlers to support curl, streams and curl multi
    - Added standalone Server class
  + Improved response content negotiation and handling
  + Added Promises
  + Added Curl CLI conversion functionality
  + Added ability to create raw strings of a client request
  + Added factories to the client Stream and Curl classes
  + Added factories to the request, response and upload classes
  + Improved Auth header object, added support for digest auth
- `pop-mail`
  + Expanded the available mail transports to include:
    - Mailgun
    - Sendgrid
    - Office 365
    - AWS SES
    - Google
  + Expanded the available mail clients to include:
    - Office 365
    - Google
- `pop-storage`
  + Added Azure adapter
  + Refactored the S3 and Local adapters
  + Added top-level normalized Storage class
- `pop-queue`
  + Created a task object that extends the job object and has scheduling functionality
  + Deprecated the scheduler object in favor if the task object and a single worker object 
  + Added a cron object to manage scheduling; greatly improved scheduling functionality
  + Added the ability to have sub-minute scheduling
  + Added max attempts
  + Improved the "run until" functionality
- `popphp`
  + Improved CLI route syntax and handling
- `pop-auth`
  + Refactored for `pop-http` v5.0.0
- `pop-db`
  + Added the functionality to store DB migrations in a DB table.
- `pop-code`
  + Added support for return types and better support for type hints
- `pop-cache`
  + Renamed `Db` adapter `Database` to limit possible namespace conflicts 
- `pop-color`
  + New component for color value management, parsing and conversion
- `pop-console`
  + Added support for 4th console color to visually separate parameters and options
- `pop-css`
  + Added `writeToFile($to)` method
  + Improved comment functionality
  + Improved the CSS object constructor
- `pop-debug`
  + Improved get/retrieval of stored debug content
  + Renamed `Db` storage adapter `Database` to limit possible namespace conflicts
  + Added `timestamp` column to database storage adapter
  + Deprecated/removed Redis adapter
- `pop-image`
  + Deprecated and removed the following methods:
    - `setAdjust()`, `setDraw()`, `setEffect()`, `setFilter()`, `setLayer()`, `setType()`
  + The following existing methods now serve as the constructor factories for their respective objects:
    - `adjust()`, `draw()`, `effect()`, `filter()`, `layer()`, `type()`
- `pop-kettle`
  + Added the ability to store and manage migrations from a database table
  + Added the ability to export and import raw SQL files (MySQL only.)
- `pop-log`
  + Renamed `Db` writer `Database` to limit possible namespace conflicts
- `pop-utils`
  + Added File helper class
  + Deprecated and removed abstract error class and error interface

### Deprecated Features
- PHP 7.4 no longer supported
- The `pop-loader` component is no longer available
- Removed abstract error class and error interface in `pop-utils`

## 4.8.0
**(9/3/2023)**
* Added API-based adapters for `MailGun` and `SendGrid` in the `pop-mail` component 
* Added new `Auth` header class to the `pop-http` component
  + Provides easier access to auth header information for outbound client requests and inbound server requests
* Added ability to track full state in the `pop-audit` component

## 4.7.0
**(11/16/2022)**
* Added support for PHP 8.0+. Backwards compatible to PHP 7.4.
* Added new `pop-storage` component
    + Provides interchangeable adapters to easily switch between storage resources, e.g., local disk, AWS S3, etc.

## 4.6.0
**(2/12/2021)**
* Migrated unit tests from Travis CI to GitHub Actions
* Changed the minimum version support for PHP to 7.3
* Changed the minimum version support for PHPUnit to 9.0
* `pop-image` updated to v3.6.0
    + Full removal of the Gmagick adapter
* `pop-queue` updated to v1.2.0
    + Migrated from the SuperClosure library to the OPI Closure library

## 4.5.0
**(5/28/2020)**
* `pop-db`updated to v5.0.5
    + Refactored the predicate set parser/generator classes
    + Refactored "helper" parser classes (expressions, tables, etc.)
    + Improved SQL builder and schema builder classes
    + Improved connect/disconnect functionality of adapter classes
    + Improved support for standard SQL functions
    + Remove references to ArrayObjects
    + Improved unit tests/code coverage
* `pop-pdf` updated to v4.0.0
    + Fixed field issues
    + Improved text support
    + Improved HTML support
    + Code review/clean up
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
* `pop-http` updated to v4.0.0
    + Refactored code-base, better class structure (abstract classes, interfaces, etc.)
    + Better support for the HTTP client classes, including better support for form data
    + Moved server request, response and upload classes under their own new namespace
      `Pop\Http\Server\` to better organize the code and differentiate between the server
      and client classes.
    + Incorporated the new `pop-mime` component for better multipart form data generation
    + Incorporated the newly refactored `pop-filter` component for input data filtering
    + Better support of large raw data streams storing to file (instead of in memory)
* `pop-filter` reinstated and re-purposed in v3.0.0
    + Refactored the previously deprecated `pop-filter` component to handle filtering
      of data and values for multiple components that need filtering functionality
* `pop-kettle` updated to v1.5.0
    + Updated to work with newly refactored and updated components of Pop PHP v4.5
    + Added `db:create-seed` command
    + Added better support for Windows
    + Added support for MVC commands
    + Added support for include helper file to hook other apps into Kettle
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
* `pop-image` updated to v3.4.0
    + Added support for animated GIFs under the Imagick adapter
    + Deprecated the Gmagick adapter
* `popphp` updated to v3.6.0
    + Added better support for dynamic array params in the HTTP and CLI route objects
    + Refactored to use the new `pop-utils` component, including the array and callable features
    + Added support for multi-byte routes
    + Added support for named routes and URL generation
* `popcorn` updated to v3.3.0
    + Added support for custom HTTP methods
    + Add `any()` method
    + Better exception error messaging

## 4.1.0
**(10/17/2019)**
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
**(4/9/2019)**
* Updated to pop-csv v3.1.0, which includes static helper methods

## 4.0.2
**(3/12/2019)**
* Updated to pop-db v4.5.0, which includes support for the encoded record class

## 4.0.1
**(2/9/2019)**
* Added pop-kettle component for CLI-based helper functionality

## 4.0.0
**(2/5/2019)**
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
