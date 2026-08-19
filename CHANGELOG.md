CHANGELOG
=========

## 7.0.0
**(08/17/2026)**
- Many new features and upgrades
- Updated for PHP 8.4+
- Added `declare(strict_types=1);` throughout all components
- Added PHPStan coverage throughout all components 
- Improved test code coverage throughout all components
- New Package:
  + `framework`
    - The repo `popphp/framework` now becomes the official "installer" repository to provide the correct abstracted
      layer to the underlying `popphp-framework` repo installation. Your project composer file just gets one `requires`
      entry of `popphp/framework` instead of 30+ `requires` entries from original `popphp-framework` repo.
  + `pop-parser`
    - A package that provides simple parsing of name and address strings into individual data points.
- Upgrades & Improvements to the following packages:
  + `popphp`
    - Added `Dispatch` functionality, to allow for other things besides controllers to be dispatched by the application object.
    - Added PSR-14 interoperability to the Event functionality
    - Added PSR-15 interoperability to the Middleware functionality
    - Added PSR-11 interoperability to the Service functionality
    - Added the ability to directly consume a CallableObject as a controller
    - Changed the thrown error in Pop\Application::run() from Pop\Exception to Throwable
    - Patched a number of bugs and general issues throughout.
    - Moved AbstractModel to `pop-utils`
    - Moved AbstractDataModel to `pop-db`
  + `pop-acl`
    - Added role/resource removal for better management
    - Added introspection to get current permissions
    - Added "wildcard" permissions
  + `pop-auth`
    - Security-hardening pass on all adapters, including a new `needsRehash()` contract and adapters throwing
      `Pop\Auth\Exception` on infrastructure failures instead of silently returning `0`
    - Removed the LDAP adapter
  + `pop-cache`
    - Added PSR Interoperability
      + PSR-6/PSR-16: Caching interface
    - Improvements to cache retrieval
    - Addition of modern caching features (e.g. remember())
    - Injectable clock
  + `pop-code`
    - General improvements/additions to catch up to PHP 8.4
    - Improvements/additions for modifiers
    - Added support for enums
    - Added support for attributes
  + `pop-color`
    - Added support for new color formats:
      + Hsb
      + Hsv
      + Hwb
      + Lab
      + Lch
      + Oklab
      + Oklch
  + `pop-config`
    - Added `dot.notation` support
    - Switched to `symfony/yaml` for YAML support
    - Improved exception handling
    - Improved collision behavior
  + `pop-console`
    - Expanded the Command class to use the dispatchable functionality. This is now used to add executable
      application commands directly to pop-kettle (without the need to use a Controller.)
    - Added table rendering
    - Added progress bar functionality
    - Added a multi-select prompt
    - Added ability to display help for sub-commands to filter a help screen down to a smaller set of commands
  + `pop-crypt`
    - Added Sodium/XChaCha20 support
    - Improved security fixes
  + `pop-css`
    - Improved color support via upgraded `pop-color` component
    - Improved CSS support
  + `pop-csv`
    - Added support for escaped formulas
    - Improved file streaming
  + `pop-db`
    - Improved and upgraded shorthand syntax across the board
    - Improved and upgraded relationships
    - Before/after hook support for save/update/delete
    - JSON support
    - Subquery support
    - EXISTS support
    - Moved AbstractDataModel to `pop-db` (from `popphp`)
    - Fillable/guarded support
    - Removed circular dependency to `pop-debug`
  + `pop-debug`
    - Refactor for new `pop-log` compatibility
    - Support NDJson
    - Removed circular dependency to `pop-db`
  + `pop-form`
    - Improved CSRF
    - Removed the Captcha field (outdated and ineffective in today's modern web landscape.)
    - Improved file uploads
    - Added Aria support
  + `pop-http`
    - Added PSR Interoperability
      + PSR-7: HTTP message interfaces
      + PSR-17: HTTP factories
      + PSR-18: HTTP Client
      + PSR-3: Logging (Middleware)
    - Core internals refactor
      + Dropped use of `pop-mime` body for multipart handling in favor of newly built native `Body` class
      + Improved data handling and parsing
      + Improvements to the Curl and Stream handler classes
      + Improvements to the Client functionality
      + Improvements to the Server functionality
    - Improvements to the Curl command functionality
    - Added Mock Transport Handler
  + `pop-image`
    - Removed the Captcha class (outdated and ineffective in today's modern web landscape.)
  + `pop-kettle`
    - Added ability to install a front-end system from a selection of popular JS frameworks + TailwindCSS (via Vite)
      + AlpineJS
      + Vue.js
      + React
    - Added the `web:watch` and `web:build` commands to rebuild front-end assets from the kettle helper script
    - Moved the `serve` command into the new `web:` command group as `web:serve`
    - Added ability to add executable commands directly to the kettle helper script, as opposed to having to
      build a separate application script and console controllers
    - Added support to manage queues directly from the kettle helper script
    - Added autoloading of commands in the application namespace
    - Added new `pop-console` functionality to display help for sub-commands
    - Moved the `app:init` command options and `<namespace>` parameter into prompts within the command;
      application type is now a multi-select, and the namespace is normalized into a valid PHP namespace,
      a script slug and a display name
    - Added the `--set` flag to the `app:env` command to change the application environment;
      `app:init` no longer prompts for it
    - Removed the `kettle.inc.php` file; `app:init` now registers the application namespace in
      `composer.json` and runs `composer dump-autoload` instead
    - Renamed the `orig.env` template file to `.env.example`
    - Added Composer-based install hook to run the `app:init` command post-install
  + `pop-log`
    - Added PSR Interoperability
      + PSR-3: Logger interface
    - Improved RFC-3164 support and compatibility
    - Added processors
    - Added formatters
    - Added streams
  + `pop-mail`
    - Removed native message/part functionality and replaced with refactored functionality in `pop-mime`
    - `Message::parse()` now throws `Pop\Mail\Exception` on content with no header/body delimiter
      instead of surfacing a `TypeError` from `pop-mime`
  + `pop-mime`
    - Built out native message/part functionality, to be consumed by `pop-mail`
    - Patched `Message::parseMessage()` throwing a `TypeError` on content with no `\r\n\r\n`
      header/body delimiter; such content is now parsed as a header-less, body-only message
  + `pop-pdf`
    - Patched known error bug that displayed in strict readers (e.g., Adobe Acrobat)
    - Added native text extract
    - Added native PDF merge
    - Finished HTML-to-PDF functionality, including HTML tables
    - CID font support (Cyrillic, Greek, Arabic, etc.)
  + `pop-queue`
    - Full refactor
      + New adapter contracts
      + Improved dead-letter storage
      + New memory adapter
      + Delay, backoff and timeout added to jobs
      + Improvements to concurrent workers
      + Worker registry and observability
      + Improved schedule fairness
      + Improved worker orchestration
      + Improved security
  + `pop-session`
    - Improved session clean up
    - Improved session security
  + `pop-storage`
    - Support for presigned/temporary URLs
    - Improved recursive listing
    - Improved streaming
  + `pop-utils`
    - Added debug interfaces (to break circular dependency between `pop-db` and `pop-debug`)
    - Moved AbstractModel to `pop-utils` (from `popphp`)
  + `pop-view`
    - Major refactor/upgrade to the stream template
- Deprecated/Removed:
  + `popcorn` - no longer supported. The functionality has officially been baked directly into the `Pop\Application` class.
  + `pop-ftp` - no longer supported.
  + `pop-auth` - LDAP adapter removed and no longer supported.
  + `popphp-skeleton` - no longer supported. Replaced by pop-kettle as for defacto/easy application scaffolding
  + `popphp-tutorial` - no longer supported. Please see the documentation for code examples.

## 6.0.0
**(11/03/2025)**
- Updated for PHP 8.3+
- Updated test for PHPUnit 12.0+
- New/Refactored Package:
    + `pop-crypt`
        - An older package that was sunset a few years ago. It has been refactored with modern support
          for one-way hashing and two-way encryption
- Updates & Improvements to the following packages:
    + `popphp` - Added support for middleware
    + `pop-db` - Refactored the `Pop\Db\Record\Encoded` class to work with the newly refactored `pop-crypt` component
    + `pop-debug` - Refactored and streamlined the `pop-debug` component
    + `pop-util` - Added the `Pop\Utils\Num` helper class

## 5.5.0
**(02/12/2025)**
- Updated for PHP 8.2+
- Updated test for PHPUnit 11.5+
- Updates & Improvements to the following packages:
  + `pop-db`
    - Added support `latest()` and `oldest()` methods with `hasMany` relationships
    - Added support for `UPDATE` SQL in the Sql Data class
  + `pop-csv` - Added support to map array and multi-dimensional array values to a single cell value
  + `pop-mail` - Bug fixes
  + `pop-debug` - Added support for adding loggers to the debugger
  + `pop-form` - Improved support for i18n for the required message
  + `pop-http` - Improved support for custom content-type headers
  + `pop-kettle` - Added bash and zsh completion
  + `pop-log` - Added serialization support for context
  + `pop-storage` - Improved the Azure adapter
  + `pop-validator` - Added optional results property

## 5.4.0
**(9/10/2024)**
- Updates & Improvements to the following packages:
  + `popphp` - Bug fix
  + `pop-dom` - Bug fix
  + `pop-form` - Bug fix
  + `pop-http` - Bug fix
  + `pop-pdf` - Added support for text extraction
  + `pop-utils` - Added DateTimeTrait
  + `pop-validator` - Added DateTime validators

## 5.3.1
**(5/28/2024)**
- Small improvements to the following packages:
  + `popphp`
  + `pop-db`
  + `pop-form`
  + `pop-http`
  + `pop-mail`
  + `pop-utils`

## 5.3.0
**(4/1/2024)**
- `pop-utils`
  + Added helper class and functions
  + Added autoloading of helper functions to main application object
  + Added array helper class
  + Refactored existing array classes for better interoperability

## 5.2.0
**(3/4/2024)**
- Added bash completion to `pop-kettle`
- Added style object and functionality to `pop-pdf`
- `popcorn`
  + Added support for custom methods in the config
  + Improved route prefixes
- `pop-form`
  + Improved error message grouping and display
  + Improved append/prepend functionality with form field elements
- Improved ACL policy support in `pop-nav`
- Upgraded the "options" parameter - renamed "omit" to "exclude" and added "include" in `pop-csv`
- Added `outputToRawString` method in `pop-image`

## 5.1.0
**(12/18/2023)**
- Improved transaction support in `pop-db`
- Added individual query methods to the database adapters in `pop-db`
- Added the `Pop\App` helper class in `popphp`
- Added the `Pop\Model\AbstractDataModel` class in `popphp`
- Incorporated `vlucas/phpdotenv` in `popphp` to track application-specific variables
- Improved application handling and support with `pop-kettle`
- Improved color and styling support in `pop-console`

## 5.0.0
**(11/08/2023)**
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
  + Deprecated the scheduler object and refactored worker object
    - Reworked hierarchy of job/task -> queue -> worker
  + Added a cron object to manage scheduling; greatly improved scheduling functionality
  + Added the ability to have sub-minute scheduling
  + Added max attempts
  + Improved the "run until" functionality
  + Added AWS SQS adapter
- `popphp`
  + Improved CLI route syntax and handling
- `pop-auth`
  + Refactored for `pop-http` v5.0.0
- `pop-db`
  + Added Seeder class with `create()` and `run()` methods
  + Added the functionality to store DB migrations in a DB table
  + Improved debugger functionality with the profiler
- `pop-code`
  + Added support for return types and better support for type hints
- `pop-cache`
  + Renamed `Db` adapter `Database` to limit possible namespace conflicts 
- `pop-color`
  + New component for color value management, parsing and conversion
- `pop-config`
  + Add support for YAML
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
  + Moved seed functionality over to new `Pop\Db\Sql\Seeder` class
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
