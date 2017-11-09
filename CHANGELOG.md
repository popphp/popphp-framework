CHANGELOG
=========

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
* The Data compoenent has been deprecated and the CSV functionality has been moved into its own component, `pop-csv`.
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
