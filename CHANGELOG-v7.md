CHANGELOG
=========

## 7.0.0 beta
**(As of 08/11/2026)**
- Updated for PHP 8.4+
- New Package:
  + `pop-parser` - A package that provides simple parsing of name and address strings into individual data points.
- Updates & Improvements to the following packages:
  + `pop-acl`
    - Added role/resource removal for better management
    - Added introspection to get current permissions
    - Added "wildcard" permissions
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
    - Expanded the Command class to use the dispatchable functionality. This is now used to add executable application commands directly to pop-kettle (without the need to use a Controller.)
    - Added table rendering
    - Added progress bar functionality
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
    - Added ability to add executable commands directly to the kettle helper script, as opposed to having to build a separate application script and console controllers.
    - Added autoloading of commands in the application namespace.
  + `pop-log`
    - Added PSR Interoperability
      + PSR-3: Logger interface
    - Improved RFC-3164 support and compatibility
    - Added processors
    - Added formatters
    - Added streams
  + `pop-mail`
    - Removed native message/part functionality and replaced with refactored functionality in `pop-mime`
  + `pop-mime`
    - Built out native message/part functionality, to be consumed by `pop-mail`
  + `pop-pdf`
    - Patched known error bug that displayed in strict readers (e.g., Adobe Acrobat)
    - Added native text extract
    - Added native PDF merge
    - Finished HTML-to-PDF functionality, including HTML tables
  + `pop-queue`
    - Full refactor
      + New adapter contracts
      + Improved dead-letter storage
      + New memory adapter
      + Delay, backoff and timeout added to jobs
      + Improvements to concurrent workers
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
  + `pop-view`
    - Major refactor/upgrade to the stream template
  + `popphp`
    - Added `Dispatch` functionality, to allow for other things besides controllers to be dispatched by the application object.
    - Added PSR-14 interoperability to the Event functionality
    - Added PSR-15 interoperability to the Middleware functionality
    - Added PSR-11 interoperability to the Service functionality
    - Added the ability to directly consume a CallableObject as a controller
    - Changed the thrown error in Pop\Application::run() from Pop\Exception to Throwable
    - Patched a number of bugs and general issues throughout.
- Deprecated:
  + `popcorn` - no longer supported. The functionality has officially been baked directly into the `Pop\Application` class.
  + `pop-ftp` - no longer supported.
