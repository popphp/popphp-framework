# Pop PHP Framework v7.0.0

## Backward-Compatibility Breaks

Everything that will break when you upgrade an application from **Pop PHP Framework v6.0.0** to **v7.0.0**,
and what to change in your code for each one.

**352 breaks** across the bundled components — **129 high**, **126 medium**, **97 low**.

This is the companion to [`NEW-FEATURES.md`](NEW-FEATURES.md). This document tells you what will break; that
one tells you what you get for it.

---

## How to read an entry

Breaks are ordered **High → Medium → Low** within each component:

| Severity | Meaning |
|---|---|
| **High** | Fatals, throws, or silently misbehaves in a typical v6 application |
| **Medium** | Hits a common but not universal feature |
| **Low** | Edge cases, or only affects code that extended or implemented internals |

Every entry names the severity, who it affects, what changed, and — under **Migration:** — exactly what to do
about it. Most also show the same code before and after.

Pay closest attention to breaks described as **silent** — no error, changed behavior. Those are the ones that
reach production.

Entries marked **Bug fix** are a different case: the v6 behavior was defective and v7 corrects it. They are
listed because correct behavior is still *changed* behavior — if you wrote code around the v6 bug, that
workaround now breaks. If you never hit the bug, you can skip them. All of them are collected under
*Bug fixes you may have coded around* below.

A handful of entries are tagged *(uncertain)*. The change is real, but whether it reaches your code depends on
how you were calling it — check those against your own usage rather than assuming they apply.

---

## Framework-level changes

### PHP requirement

`>= 8.3.0` → **`>= 8.4.0`**. Every component requires it, so your servers need PHP 8.4 before you can install
any part of v7 — there is no partial upgrade path.

### Packages removed from the framework

| Package | v6       | v7 | Replacement                                                                                                 |
|---|----------|---|-------------------------------------------------------------------------------------------------------------|
| `popphp/popcorn` | `^4.1.5` | **dropped** | Absorbed into `popphp` core — see the popphp section                                                        |
| `popphp/pop-ftp` | `^4.0.3` | **dropped** | **None.** Dropped for security purposes — Please use more secure means of file transfer, such as HTTPS or SSH |

**Popcorn is the migration every v6 HTTP app must make.** `Popcorn\Pop` was the documented HTTP entry point,
and it will not be installed in v7. The core `Pop\Router\Match\Http` has absorbed Popcorn's method-grouped
route syntax, the fluent verb API, custom verbs and real 404-vs-405 handling — but the config shapes and
defaults differ in five specific ways, each of which fails **silently**. The `popphp` section documents all of
them; read that before touching `public/index.php`.

### Version bumps

**Every one of the components takes a major bump.** No component ships a breaking change behind a minor version
number, so a caret range on any v6 constraint will refuse the v7 release rather than take it silently.

| Component | v6 → v7 | Breaks (H/M/L) |
|---|---|---|
| popphp (core) | 4.4.4 → 5.0.0 | 21 (11/9/1) |
| pop-acl | 4.1.4 → 5.0.0 | 4 (2/2/0) |
| pop-audit | 2.0.3 → 3.0.0 | 6 (3/1/2) |
| pop-auth | 4.0.3 → 5.0.0 | 7 (3/2/2) |
| pop-cache | 4.0.3 → 5.0.0 | 12 (5/4/3) |
| pop-code | 5.0.8 → 6.0.0 | 10 (4/3/3) |
| pop-color | 1.0.3 → 2.0.0 | 6 (3/1/2) |
| pop-config | 4.0.4 → 5.0.0 | 8 (3/2/3) |
| pop-console | 4.2.6 → 5.0.0 | 9 (3/3/3) |
| pop-cookie | 4.0.4 → 5.0.0 | 6 (2/3/1) |
| pop-crypt | 3.0.1 → 4.0.0 | 9 (3/4/2) |
| pop-css | 2.0.3 → 3.0.2 | 8 (1/3/4) |
| pop-csv | 4.2.5 → 5.0.0 | 6 (0/2/4) |
| pop-db | 6.8.15 → 7.0.0 | 19 (7/9/3) |
| pop-debug | 3.0.0 → 4.0.0 | 4 (3/1/0) |
| pop-dir | 4.0.3 → 5.0.0 | 7 (1/3/3) |
| pop-dom | 4.0.7 → 5.0.0 | 6 (3/0/3) |
| pop-filter | 4.0.4 → 5.0.0 | 2 (0/2/0) |
| pop-form | 4.2.7 → 5.0.0 | 11 (5/1/5) |
| pop-http | 5.3.8 → 6.0.0 | 19 (5/9/5) |
| pop-i18n | 4.0.3 → 5.0.0 | 9 (0/3/6) |
| pop-image | 4.1.3 → 5.0.0 | 2 (1/0/1) |
| pop-kettle | 2.3.4 → 3.0.0 | 18 (4/10/4) |
| pop-log | 4.0.4 → 5.0.0 | 12 (6/4/2) |
| pop-mail | 4.0.7 → 5.0.0 | 22 (11/7/4) |
| pop-mime | 2.0.3 → 3.0.0 | 16 (10/4/2) |
| pop-nav | 4.1.5 → 5.0.0 | 7 (2/2/3) |
| pop-paginator | 4.0.3 → 5.0.0 | 3 (0/1/2) |
| pop-pdf | 5.2.12 → 6.2.0 | 21 (7/8/6) |
| pop-queue | 2.1.3 → 3.0.0 | 12 (7/5/0) |
| pop-session | 4.0.4 → 5.0.0 | 5 (0/3/2) |
| pop-storage | 2.1.3 → 3.0.0 | 17 (6/7/4) |
| pop-utils | 2.4.2 → 3.0.0 | 8 (2/1/5) |
| pop-validator | 4.8.1 → 5.0.0 | 15 (5/5/5) |
| pop-view | 4.0.4 → 5.0.0 | 5 (1/2/2) |

---

## Start here: the breaks most likely to take down a working v6 app

1. **`Popcorn\Pop` is gone**, and method-less routes that Popcorn restricted to `get,post` now match **every**
   HTTP verb. Silent. (`popphp`)
2. **`Application::run()` now rethrows every `Throwable`** instead of swallowing `Pop\Exception` into an
   `app.error` event. Every `public/index.php` needs a try/catch. (`popphp`)
3. **Model base classes moved packages** — `Pop\Model\AbstractModel` → `Pop\Utils\AbstractModel`,
   `Pop\Model\AbstractDataModel` → `Pop\Db\Model\AbstractDataModel`, and `popphp` no longer requires `pop-db`
   at all. (`popphp` / `pop-utils` / `pop-db`)
4. **The router's controller accessors were renamed** — `getController()` → `getDispatchable()`,
   `addControllerParams()` → `addDispatchableParams()`, and six more, on both `Router` and `Match`. No
   aliases; the old names surface as a *catchable* `Pop\Router\Exception` whose message reads like a native
   PHP fatal. (`popphp`)
5. **AES-CBC ciphertext from v6 cannot be decrypted by v7** — keys are now HKDF-derived. Requires a
   decrypt-and-re-encrypt migration *before* upgrading. (`pop-crypt`)
6. **Message part creation moved to `pop-mime`.** The refactored `Message\Text` and `Message\Html` no longer
   support the constructor — `new Text('Hello')` silently discards the content and sends blank email. Use
   `Text::create()` and `Html::create()` instead. (`pop-mail`)
7. **Cache keys containing `:` or `/` now throw**, and every adapter changed its on-backend key format, so
   the entire cache goes cold on deploy. (`pop-cache`)
8. **Log levels became strings** (`Logger::ERROR === 'error'`), changing every writer's output and requiring a
   `pop_log.level` column migration. (`pop-log`)
9. **The queue adapter contract was rewritten** to a lease/dead-letter model; v6 File-adapter jobs and
   Database/Redis failed jobs become invisible. (`pop-queue`)
10. **Attribute values in `pop-dom` are now HTML-escaped.** Correct by default, but anything you pre-escaped
    for v6 now double-encodes (`&amp;` → `&amp;amp;`) — stop pre-escaping. Also changes `pop-form` and
    `pop-nav` output. (`pop-dom`)

---

## Cross-cutting patterns

Four themes account for most of the 344 breaks. Recognizing them makes the per-component sections faster to read.

**1. `declare(strict_types=1)` everywhere.** Strict mode is decided by the *calling* file, so your own code is
mostly unaffected — but wherever a component passes your loosely-typed value into *its own* strict internals,
you now get a `TypeError`. Real instances: `app_date('Y-m-d', '1755302400')` (`pop-utils`), `new Dir($path,
['recursive' => 1])` (`pop-dir`), `Csv::serializeData($d, ['limit' => '3'])` (`pop-csv`),
`$td->setNodeValue(42)` (`pop-dom`), and the whole `Fields::create()` config path in `pop-form`.

**2. Output escaping was added.** `pop-dom` escapes attribute values, `pop-nav` escapes node labels, `pop-mime`
RFC 2047-encodes header values, `pop-i18n` escapes XML, `pop-paginator` escapes the request URI. Each is a genuine
security fix, and each **double-encodes input you were already escaping yourself**. The rule for v7 is: pass
raw values and let the component encode once.

**3. Silence became exceptions.** `pop-storage`, `pop-auth`, `pop-cookie`, `pop-config` and `pop-dir` all replaced
"return false / return [] / no-op" with typed exceptions. Code that treated a falsy return as "not found" now
propagates a fatal instead.

**4. Some breaks can't be fixed in code.** Seven components changed the format of data they persist — cache
keys and file layout (`pop-cache`), queue job directories (`pop-queue`), a log table column (`pop-log`), CSRF
session shape (`pop-form`), the session cookie path (`pop-session`), Azure blob URIs (`pop-storage`), and
AES-CBC ciphertext (`pop-crypt`). A correctly-migrated codebase deployed onto v6 data still breaks — and
mostly does so quietly: a cold cache, a mass logout, jobs that never run. These need action outside your
codebase, listed next.

---

## Bug fixes you may have coded around

22 breaks are corrections rather than redesigns: the v6 behavior was defective and v7 fixes it. They are
still breaks, because a workaround written against the v6 bug stops being correct once the bug is gone. If you
never hit the bug, skip them. Each is tagged **Bug fix** in its component section (the two `pop-i18n`
fixes share a row below).

| Component | What v6 did wrong | Severity |
|---|---|---|
| `pop-console` | `isWindows()` returned `true` on Linux/macOS and `false` on Windows — every branch was backwards | High |
| `popphp` | listeners fired only once — the first `trigger()` drained the queue, so every later trigger for that name ran nothing | High |
| `pop-form` | the required-field check iterated values instead of keys, so validating a field subset never enforced anything | High |
| `pop-storage` | under `chdir()` the sub-directory was treated as the container, so blobs were written to the wrong path | High |
| `pop-mail` | the Office365 `unread` filter selected the wrong set of messages | Medium |
| `pop-nav` | the `on`/`off` link class never matched once the request URI carried a query string | Medium |
| `pop-session` | the cookie `path` default was set from the session *lifetime*, not the path | Medium |
| `pop-storage` | an absolute Azure path silently escaped the configured base directory | Medium |
| `pop-view` | `{{parent}}` resolved only inside a block named `header`; elsewhere it leaked into output | Medium |
| `pop-code` | `addUse($trait, $alias)` rendered `use SomeTrait as Alias;` inside a class body — not valid PHP | Low |
| `pop-code` | `setDocblock()` / `setDesc()` on a namespace generator were silently overwritten at render time | Low |
| `pop-crypt` | `requiresRehash()` compared against `PASSWORD_ARGON2I`, so every argon2id hash reported as needing a rehash | Low |
| `pop-db` | a predicate set of only nested sets rendered `WHERE AND (...)` — invalid SQL | Low |
| `pop-pdf` | `setCharWrap()` measured its width in bytes, so accented text wrapped short of the width asked for | Low |
| `pop-dir` | `$dir['some-name']` always returned `null` instead of the matching entry | Low |
| `pop-http` | `Uri::hasUsername()` / `hasPassword()` tested the wrong properties | Low |
| `pop-i18n` | duplicate `<locale region>` blocks and repeated `<source>` strings resolved to the wrong match | Low |
| `pop-mail` | `Client\Google::getMessage()` mis-decoded raw base64url payloads | Low |
| `pop-session` | a session started by another library left `Session::__construct()` half-initialized, so sweeps never ran | Low |
| `pop-utils` | `CallableObject` with a `'new Class'` target and parameters returned `null` and constructed nothing | Low |
| `pop-view` | `Template\File` caught only `\Exception` (an `Error` bypassed it) and mismanaged the output buffer | Low |

---

## Deployment-time data migrations

These require action **outside** your codebase. Several must happen *before* the new code goes live.

| Component | What changed | Action |
|---|---|---|
| **pop-crypt** | AES-CBC keys are HKDF-derived; old ciphertext fails its MAC | **Before upgrading:** decrypt with v6, re-encrypt with v7 — or move those fields to `aes-256-gcm`, which is compatible across both |
| **pop-cache** | Key format changed on every adapter; File layout is now sharded; Database needs a unique index on `key` | `DROP TABLE pop_cache` before deploy; expect a fully cold cache (watch for thundering herd) |
| **pop-log** | `level` column is now `VARCHAR(20)`, not `INT(1)`; `createTable()` won't migrate an existing table | `ALTER TABLE pop_log MODIFY level VARCHAR(20)` and translate old integer rows |
| **pop-queue** | File adapter moved to `pending/`+`reserved/`; v6 `status = 2` failed jobs are unreachable | Drain the queue on v6 before upgrading; delete the orphaned Redis `<prefix>:status` key |
| **pop-form** | CSRF session storage went from a serialized string to a per-field array | Expect one round of token mismatches; consider forcing session regeneration |
| **pop-session** | Cookie `path` default fixed (v6 wrote the *lifetime* into `path`) | Expect a one-time logout of all active sessions |
| **pop-storage** | Azure blob URIs were built in the wrong order under `chdir()` | Audit and relocate blobs written by a v6 Azure app that used `chdir()` |
| **pop-audit** | Adapter reads now decode `old`/`new`; stored rows are unchanged | Remove `json_decode()` from your read path — no data migration needed |

---

## Suggested upgrade order

Dependencies flow downward, so upgrade in this order and run each layer's tests before moving on:

1. **Foundation** — `pop-utils`, `pop-color`, `pop-crypt`, `pop-config`, `pop-dom`, `pop-filter`, `pop-mime`
   *(do the `pop-crypt` re-encryption first, before anything is deployed)*
2. **Core** — `popphp`, then `pop-db`, `pop-http`, `pop-console`
3. **Services** — `pop-cache`, `pop-log`, `pop-session`, `pop-cookie`, `pop-storage`, `pop-queue`, `pop-debug`
4. **Application layer** — `pop-acl`, `pop-auth`, `pop-audit`, `pop-validator`, `pop-form`, `pop-view`,
   `pop-nav`, `pop-mail`, `pop-image`, `pop-pdf`, `pop-css`, `pop-csv`, `pop-i18n`, `pop-dir`, `pop-paginator`
5. **Tooling** — `pop-kettle`, and in each project re-copy `kettle`, delete `kettle.inc.php`, and move its
   PSR-4 line into `composer.json`

---

# Component-by-component breakdown

## popphp (framework core)

**Scope:** Controllers/models re-homed into a new `Pop\Dispatch` namespace (models leave the package entirely), the whole "controller" vocabulary in `Router`/`Match` renamed to "dispatchable", HTTP-method-aware + specificity-ordered routing absorbed from Popcorn, maintenance mode moved from the controller into `Application::run()`, `run()` now rethrows instead of swallowing, PSR-11/14/15 adopted, and every Pop dependency bumped a major.
**Break count:** 21 (11 high, 9 medium, 1 low)

### `Application::run()` no longer swallows exceptions — it rethrows every `Throwable`
**Severity:** High — **Affects:** every application, especially any that relies on an `app.error` listener as its error handler
In v6 `run()` caught only `Pop\Exception` and turned it into an `app.error` event, then returned normally. In v7 it catches `\Throwable`, fires `app.error` **and** the PSR-14 `ErrorEvent`, then `throw $exception;`. Exceptions that used to be quietly absorbed now escape `run()`, and `app.error` listeners now receive exception types they never saw before.

```php
// before — Pop\Exception was absorbed; script continued past run()
$app->on('app.error', function($exception, $application) { log($exception); });
$app->run();
echo "still here";

// after — app.error fires, then the exception propagates out of run()
$app->on('app.error', function(\Throwable $exception, $application) { log($exception); });
try {
    $app->run();
} catch (\Throwable $exception) {
    // must handle it here now
}
```
**Migration:** Wrap `$app->run()` in `try`/`catch` in `public/index.php` and your CLI entry script. Widen any `app.error` listener's parameter type from `Pop\Exception` to `\Throwable` — a `Pop\Exception` type hint will now TypeError on non-Pop exceptions.

### `Pop\Model\*` removed from the package
**Severity:** High — **Affects:** any app with a model class extending `Pop\Model\AbstractModel` or `Pop\Model\AbstractDataModel`
All four files under `src/Model/` are gone. `AbstractModel` now lives in `popphp/pop-utils` as `Pop\Utils\AbstractModel`; `AbstractDataModel` and `DataModelInterface` now live in `popphp/pop-db` as `Pop\Db\Model\*`. `popphp/pop-db` has also been **removed from `popphp`'s `require`**, so it is no longer pulled in transitively.

```php
// before
use Pop\Model\AbstractModel;
use Pop\Model\AbstractDataModel;
use Pop\Model\DataModelInterface;
class User extends AbstractDataModel {}

// after
use Pop\Utils\AbstractModel;
use Pop\Db\Model\AbstractDataModel;
use Pop\Db\Model\DataModelInterface;
class User extends AbstractDataModel {}
```
**Migration:** Rewrite the `use` statements. Add `"popphp/pop-db": "^7.0.0"` explicitly to your own `composer.json` if you use data models or `Pop\Db` at all. `Pop\Model\Exception` is gone with no replacement in that namespace.

### `Pop\Controller\HttpControllerTrait` / `ConsoleControllerTrait` deleted
**Severity:** High — **Affects:** every controller that injects the application/request/response/console
Both traits were removed and replaced by `Pop\Dispatch\HttpTrait` / `Pop\Dispatch\ConsoleTrait`. A `use Pop\Controller\HttpControllerTrait;` is a fatal class-load error. The new traits also make `$application` optional and add `getApplication()`/`setApplication()`/`hasApplication()`/`setRequest()`/`setResponse()`/`setConsole()`/`hasRequest()`/`hasResponse()`/`hasConsole()`.

```php
// before
use Pop\Controller\AbstractController;
use Pop\Controller\HttpControllerTrait;
class IndexController extends AbstractController { use HttpControllerTrait; }

// after
use Pop\Controller\AbstractController;
use Pop\Dispatch\HttpTrait;
class IndexController extends AbstractController { use HttpTrait; }
```
**Migration:** Search-and-replace `Pop\Controller\HttpControllerTrait` → `Pop\Dispatch\HttpTrait` and `Pop\Controller\ConsoleControllerTrait` → `Pop\Dispatch\ConsoleTrait`. Note `ConsoleTrait::application()` now returns `?Application`, so null-check it or use `hasApplication()`.

### `Pop\Controller\ControllerInterface` deleted; the router now requires `extends Pop\Dispatch\AbstractDispatcher`
**Severity:** High — **Affects:** any controller that implemented `ControllerInterface` directly instead of extending `AbstractController`
The interface was split into `Pop\Dispatch\DispatchableInterface` (`setDefaultAction`/`getDefaultAction`/`dispatch`) and `Pop\Dispatch\MaintenanceInterface` (which adds a new required `dispatchMaintenance` method). Critically, `Router::route()` no longer validates `instanceof ControllerInterface` after construction; it gates on `is_subclass_of($dispatchable, 'Pop\Dispatch\AbstractDispatcher', true)` *before* construction. A class that implements the interface but does not extend `AbstractDispatcher` falls into the new string-callable branch and is dispatched as a `Pop\Utils\CallableObject` — **silently**, with no exception and without ever calling `dispatch()`.

```php
// before — worked; router threw a clear exception if the interface was missing
class UsersController implements \Pop\Controller\ControllerInterface { /* ... */ }

// after — must extend the abstract dispatcher (AbstractController already does)
class UsersController extends \Pop\Controller\AbstractController { /* ... */ }
```
**Migration:** Make every routed controller extend `Pop\Controller\AbstractController` (or `Pop\Dispatch\AbstractDispatcher`). Replace `ControllerInterface` type hints with `Pop\Dispatch\DispatchableInterface`. Be aware this failure mode is silent rather than an exception.

### Router and Match: every `*Controller*` method renamed to `*Dispatchable*`
**Severity:** High — **Affects:** any code inspecting the router after routing, or registering per-controller constructor params
Renamed on both `Pop\Router\Router` and `Pop\Router\Match\AbstractMatch`/`MatchInterface`, with **no BC aliases**:

| v6 | v7 |
|---|---|
| `Router::getController()` | `Router::getDispatchable()` |
| `Router::hasController()` | `Router::hasDispatchable()` |
| `Router::getControllerClass()` | `Router::getDispatchableClass()` |
| `Router::addControllerParams()` | `Router::addDispatchableParams()` |
| `Router::appendControllerParams()` | `Router::appendDispatchableParams()` |
| `Router::getControllerParams()` | `Router::getDispatchableParams()` |
| `Router::hasControllerParams()` | `Router::hasDispatchableParams()` |
| `Router::removeControllerParams()` | `Router::removeDispatchableParams()` |
| `AbstractMatch::getController()` / `hasController()` / `*ControllerParams()` | same renames |

`Router::__call()` does **not** cover these — it throws `Pop\Router\Exception('Call to undefined method ...')`.

```php
// before
$app->router()->addControllerParams('MyApp\Controller\UsersController', [$svc]);
if ($app->router()->hasController()) { $c = $app->router()->getController(); }

// after
$app->router()->addDispatchableParams('MyApp\Controller\UsersController', [$svc]);
if ($app->router()->hasDispatchable()) { $c = $app->router()->getDispatchable(); }
```
**Migration:** Rename all call sites. The route array's `params` key is unchanged and still works.

### `popphp/popcorn` dropped from the framework; `Popcorn\Pop` bootstraps must migrate to `Pop\Application`
**Severity:** High — **Affects:** every v6 HTTP app whose `public/index.php` constructs `Popcorn\Pop`
`popphp/popcorn` is absent from the v7 metapackage and will not be installed. The core `Match\Http` now natively supports a `method` key, a fluent verb API, custom verbs, and 404-vs-405 — but **not all of Popcorn's config shapes**, and the defaults differ.

What the core now supports natively:
- `'options,get' => ['/users' => [...], '/roles' => [...]]` — Popcorn-style comma-joined method-group keys, expanded into per-route `method` keys. A bare single-method key (`'get' => [...]`) works too. The group's methods are applied to **every leaf config in the sub-tree**, so a group may nest route arrays to any depth (`'get,options' => ['/users' => ['[/]' => $ctrl, '/count' => $ctrl]]`), and a path that exists only under one group correctly 405s when requested via another.
- Per-route `'method' => 'get'` / `'get,post'` / `['get','post']` on any route config.
- Fluent `$app->get()/head()/post()/put()/delete()/trace()/options()/connect()/patch()` on `Application`, `Router` and `Match\Http`.
- `addCustomMethod()`/`addCustomMethods()`/`hasCustomMethod()` plus `__call()` for custom verbs — registered *before* the verb is used.
- Real `405 Method Not Allowed` with an `Allow:` header, distinct from 404.
- The same path registered under multiple methods with different controllers (the v6 regex-key collision that silently overwrote the first registration is fixed).

What a migrating app **must** change:
1. `new Popcorn\Pop($autoloader, $config)` → `new Pop\Application($autoloader, $config)`.
2. **Routes with no method info are no longer restricted to `get,post`.** Popcorn auto-assigned method-less routes to the `get,post` buckets; the core treats a missing `method` key as "matches any HTTP method". Every previously GET/POST-only route becomes reachable via PUT/DELETE/PATCH. This is silent.
3. **The `'*'` key changes meaning, and fails silently and destructively.** In Popcorn, `'*'` was a method-group key meaning "register these routes on every method." In the core it is the wildcard/default-route key, and `isMethodGroupKey()` explicitly rejects it as a method group. Feeding v7 a Popcorn `'*'` block **discards every route inside it** and registers a catch-all default route instead — so every URL matches, with no dispatchable, and no exception is raised. The replacement is a *method-less* route (see below), not a method group.
4. **Path-prefix keys nesting per-method sub-arrays are not supported** — only the reverse, a method group containing paths. Popcorn accepted `'/users' => ['get' => ['/list' => $ctrl]]`; the core treats `get` as a path segment and registers `/usersget/list`, which matches nothing you intended and raises no error. Invert it to `'get' => ['/users' => ['/list' => $ctrl]]`.
5. **The `custom_methods` config key is gone.** Call `$app->addCustomMethods([...])` explicitly.
6. **`$app->any($route, $ctrl)` is gone.** Use `$app->addRoute()` (a method-less route matches any verb).
7. **Popcorn-only API removed:** `setRoute()`, `setRoutes()`, `addToAll()`, `getRoutes(?string $method)`, `getRoute(string $method, string $route)`, `hasRoute(string $method, string $route)`, `isAllowed()`.
8. **Error behavior differs.** Popcorn threw `Popcorn\Exception` (405) and rendered the **404** page on a method mismatch; the core renders a real 405 with an `Allow` header and fires no `app.error`.

```php
// before — public/index.php
$app = new Popcorn\Pop($autoloader, include __DIR__ . '/../app/config/app.http.php');
// config: ['routes' => [
//     '*'      => ['/anything' => $c],          // all nine methods
//     '/users' => ['get' => ['/list' => $c]],   // path-prefix nesting a method group
//     '/ping'  => $c,                           // no method info -> implicitly get,post
// ]]

// after — public/index.php
$app = new Pop\Application($autoloader, include __DIR__ . '/../app/config/app.http.php');
// config: ['routes' => [
//     '/anything'  => $c,                       // was '*' — a method-less route already
//                                               // matches every verb, so drop the key
//     'get'        => ['/users/list' => $c],    // was the '/users' prefix shape
//     'get,post'   => ['/ping' => $c],          // was implicit get,post — now must be
//                                               // explicit, or it opens up to all verbs
// ]]
```
**Migration:** Swap the bootstrap class, then audit every routes config for the shape/default differences above. Explicitly re-add `method`/method-group keys to every route that relied on Popcorn's implicit `get,post` default, or it silently opens up to all verbs.

### The autoloader must now be a real `Composer\Autoload\ClassLoader`
**Severity:** High — **Affects:** apps passing a wrapped/custom autoloader to the `Application` constructor or `registerAutoloader()`
v6 sniffed the argument by class-name substring and duck-typed for `add`/`addPsr4`. v7 uses `$arg instanceof \Composer\Autoload\ClassLoader`, and `registerAutoloader()` is hard-typed. The constructor path fails **silently**: a non-`ClassLoader` argument matches no branch and is dropped, `autoloader()` returns `null`, and the `config['prefix']`/`config['src']` PSR-4 registration plus every module's autoload-prefix registration are skipped — leaving "class not found" errors far from the cause.

```php
// before — any object whose class name contained "autoload"/"classload" was accepted
$app = new Pop\Application(new MyApp\AutoloaderWrapper($composer), $config);

// after — pass the ClassLoader itself
$autoloader = include __DIR__ . '/../vendor/autoload.php';
$app = new Pop\Application($autoloader, $config);
```
**Migration:** Pass the raw object returned by `vendor/autoload.php`. If you wrap it, unwrap before handing it to `Application`.

### Maintenance mode moved out of `AbstractController::dispatch()` into `Application::run()`
**Severity:** High — **Affects:** apps in maintenance mode, apps with middleware, and any code calling `$controller->dispatch()` directly
In v6 the maintenance check was the first branch of `AbstractController::dispatch()`. In v7 `dispatch()` has **no maintenance branch at all**; the check runs in `Application::run()` before the middleware pipeline. Three observable changes:

1. **Middleware no longer runs during maintenance** — v7 short-circuits before `$this->middleware->process()`.
2. **Direct `dispatch()` calls no longer honour maintenance mode** — job workers, custom front controllers and tests now run the normal action while the app is down.
3. **Closure and callable routes now get a generic 503 page**; v6 never applied maintenance mode to non-controller routes at all.

```php
// before — maintenance was enforced inside the controller
$controller->dispatch('index', $params);   // -> maintenance() when MAINTENANCE_MODE is on

// after — enforced by run(); a direct dispatch() runs index() regardless
$app->run();                               // -> maintenance() / 503
```
**Migration:** Route all dispatching through `Application::run()`. If middleware must run during maintenance, move that logic into an `app.route.pre` listener or the controller's `maintenance()` action.

### The `method` route-config key is now enforced instead of being an ignored passthrough
**Severity:** Medium — **Affects:** v6 configs that carried a `method` key for their own manual verb checks
v6 stored unknown route-config keys verbatim and never looked at `method`. In v7 `Http::addRoute()` reads it, normalizes it, and `Http::match()` rejects requests whose verb isn't in the list — producing a 405 rather than dispatching.

```php
// before — 'method' was inert; POST /users dispatched UsersController::index()
'/users' => ['controller' => UsersController::class, 'action' => 'index', 'method' => 'get'],

// after — POST /users now returns 405 Method Not Allowed with "Allow: GET"
```
**Migration:** Audit route configs for a pre-existing `method` key. Remove it, or make sure it lists every verb the route must accept.

### Route matching order is now specificity-based, not declaration order
**Severity:** Medium — **Affects:** any app with overlapping routes whose config ordering was load-bearing
Both `Match\Http::prepare()` and `Match\Cli::prepare()` now `uksort()` prepared routes by a computed specificity score. Literal > required-param > optional-param > array-param; declaration order is only a tiebreaker within a tier.

```php
// before — the first declared route won, so this dispatched CatchAllController for /user/add
'routes' => [
    '/user/:id' => ['controller' => CatchAllController::class],
    '/user/add' => ['controller' => AddController::class],
]

// after — /user/add is fully literal, so AddController wins regardless of order
```
**Migration:** Usually this fixes ordering bugs, but any route deliberately declared early to shadow a more specific one will now lose. There is no flag to restore declaration-order matching.

### `Event\Manager::STOP` and `Event\Manager::KILL` constants removed
**Severity:** High — **Affects:** any listener that returns `Manager::STOP` or `Manager::KILL`
Both class constants are gone, so a listener returning either now fails with an "Undefined constant" `Error`. Propagation control moved onto the event object, which arrives as an extra parameter after `$result`, and aborting the run moved to a thrown exception.

```php
// before
$app->on('app.route.pre', function($application) {
    return \Pop\Event\Manager::STOP;      // stop later listeners for this name
});

// after
$app->on('app.route.pre', function($application, $result, $event) {
    $event->stopPropagation();            // same effect
});
```
**Migration:** Replace `return Manager::STOP` with `$event->stopPropagation()`, declaring the trailing `$event` parameter on the listener. Replace `return Manager::KILL` with `throw new Pop\Event\AbortException()`.

### `Event\Manager::alive()` removed; aborting a run is now an exception
**Severity:** Medium — **Affects:** code calling `$app->events()->alive()`, or relying on `Manager::KILL`
`alive()` and the `$alive` flag are gone. In v6 nothing in `src/` ever consulted `alive()`, so returning `Manager::KILL` set a flag that no code read — it never actually stopped anything. v7 replaces it with `Pop\Event\AbortException`, which `Application::run()` catches to end the run cleanly.

```php
// after
use Pop\Event\AbortException;

$app->on('app.route.pre', function($application) {
    if ($application->isDown()) {
        throw new AbortException('Application is in maintenance mode.');
    }
});
```
**Migration:** Drop `alive()` calls. If you relied on `KILL`, note it was inert in v6 — throwing `AbortException` is new behavior that will now actually halt the run.

### Listeners fire on every `trigger()`, not only the first
**Severity:** High · **Bug fix** — **Affects:** any event name triggered more than once in a single request or process
v6 iterated the listener queue directly, and iterating an `SplPriorityQueue` destructively dequeues it — so the first `trigger()` for a name drained the queue and every later `trigger()` for that name ran **no listeners at all**. v7 iterates a clone, so listeners persist.

```php
$m->on('x', fn() => 'A');
$m->on('x', fn() => 'B');

// before — 2 listener calls total, no matter how often you trigger
$m->trigger('x');  // A, B
$m->trigger('x');  // (nothing runs)

// after — 2 calls per trigger
$m->trigger('x');  // A, B
$m->trigger('x');  // A, B
```
**Migration:** None to make it work — but a listener with side effects (logging, counters, writes) now runs on every trigger instead of silently once. Audit listeners on events your app fires repeatedly, especially in long-running CLI or queue processes.

### `Middleware\Manager::handle()` is no longer a public static method
**Severity:** Medium — **Affects:** code invoking the middleware pipeline manually
v6: `public static function handle(mixed $request, \Closure $dispatch, mixed $dispatchParams = null)`. v7: `protected function handle(mixed $request, array $handlers, \Closure $dispatch, mixed $dispatchParams = null)`, and the static `$handlers` property is deleted (the queue is threaded through as a parameter so nested `process()` calls can't clobber it).

```php
// before
Pop\Middleware\Manager::handle($request, $dispatch, $params);

// after — go through the instance
$app->middleware()->process($request, $dispatch, $params);
```
**Migration:** Call `process()` on the manager instance. There is no public replacement for the static entry point.

### `ApplicationInterface` gained three required methods and `mergeConfig()` gained a parameter
**Severity:** Medium — **Affects:** classes implementing `ApplicationInterface` or overriding `mergeConfig()`
`setFullName(string): static`, `getFullName(): string` and `hasFullName(): bool` are new required members. `mergeConfig()` changed from `(mixed $config, bool $preserve = false)` to `(mixed $config, bool $preserve = false, array $exclude = [])` — an override with the two-parameter signature is now a fatal error.

**Migration:** Add the three `fullName` methods to any direct implementer, and add `$exclude` to any `mergeConfig()` override.

### `ModuleInterface` gained `setName()` / `getName()`
**Severity:** Medium — **Affects:** custom modules implementing `ModuleInterface` directly
Modules extending `AbstractModule` inherit these and are unaffected; a hand-rolled implementer is now a fatal "must implement" error.

**Migration:** Extend `Pop\Module\AbstractModule` instead of implementing the interface by hand, or add the two methods.

### `MatchInterface` renamed five methods and added three new required ones
**Severity:** Medium — **Affects:** custom route-match implementations
Beyond the `*ControllerParams` → `*DispatchableParams` renames, `MatchInterface` now also requires `name(string)`, `hasName(string)` and `getRouteConfig(?string $key = null)`. `AbstractMatch`'s protected `$controllerParams` is renamed `$dispatchableParams`.

**Migration:** Extend `Pop\Router\Match\AbstractMatch` rather than implementing `MatchInterface` from scratch.

### `Service\Locator::get()` throws `NotFoundException`, and `Locator` implements PSR-11
**Severity:** Medium — **Affects:** code catching `Pop\Service\Exception` on a missing service, and `Locator` subclasses
`get()` on an unregistered name now throws `Pop\Service\NotFoundException` (which extends `Exception`, so broad catches still work). `Locator` implements `Psr\Container\ContainerInterface`, adding a public `has(string $id): bool` — a subclass with an incompatibly-signed `has()` is now fatal.

**Migration:** No change needed if you catch `Pop\Service\Exception`. Rename a conflicting `has()` on any `Locator` subclass.

### Dispatch failures throw `Pop\Dispatch\Exception`, not `Pop\Controller\Exception`
**Severity:** Medium — **Affects:** code catching the "action not defined" errors
`Pop\Controller\Exception` still exists but nothing in the package throws it. The two are unrelated, so an existing catch block will not match.

```php
// before
catch (Pop\Controller\Exception $e) { /* ... */ }

// after
catch (Pop\Dispatch\Exception $e) { /* ... */ }
```
**Migration:** Change the catch type, or catch `\Exception`.

### `popphp` no longer requires `popphp/pop-db`
**Severity:** High — **Affects:** any app that used `Pop\Db` without requiring it directly
`popphp/pop-db` was removed from `popphp`'s `require`, so it is no longer installed transitively. An app that used `Pop\Db` while requiring only `popphp` will fail to autoload it.

**Migration:** Add `popphp/pop-db` to your own `composer.json`.
### `Http::getRoutes()` / `getPreparedRoutes()` shape changed
**Severity:** Low — **Affects:** code introspecting the router's route tables (route listers, debug tooling)
Method-constrained routes are stored under a synthetic key `route . "\0" . implode(',', $methods)`. Every prepared entry also gains `regex` and `method` keys.

```php
// before — preparedRoutes keys were always the regex
foreach ($match->getPreparedRoutes() as $regex => $config) { preg_match($regex, $uri); }

// after — use the entry's own 'regex' field
foreach ($match->getPreparedRoutes() as $key => $config) { preg_match($config['regex'], $uri); }
```
**Migration:** Read `$config['regex']` instead of the array key; strip anything after `"\0"` when displaying route keys.

---

## pop-acl

**Scope:** Rule resolution, wildcard permissions, policy handling and a new removal API in `Acl`/`AclRole`/`PolicyTrait`; no classes, interfaces, traits or methods were removed or renamed.
**Break count:** 4 (2 high, 2 medium, 0 low)

### `'*'` is now a reserved wildcard permission in allow/deny rules
**Severity:** High — **Affects:** any app that registered the literal string `'*'` as a permission name
`isAllowed()` now ORs in `in_array('*', $allowedPermissions, true)` and `resolveDenied()` short-circuits to denied when `'*'` is in the denied list. A rule that in v6 matched only the literal permission `'*'` now matches *every* permission — silently granting or blocking access that previously resolved the other way.

```php
// before — '*' is just another permission string
$acl->setStrict(true);
$acl->allow('editor', 'page', '*');
$acl->isAllowed('editor', 'page', 'edit'); // false — 'edit' is not the literal '*'

// after — identical rules, but '*' now means "any permission"
$acl->setStrict(true);
$acl->allow('editor', 'page', '*');
$acl->isAllowed('editor', 'page', 'edit'); // true — the wildcard matches 'edit'
```

Note this break runs the opposite way to most in this release — the v7 wildcard **grants** access that v6 refused.

**Migration:** Audit every `allow()`/`deny()` call for a literal `'*'` permission and rename it if it was meant as a concrete permission. Note the asymmetry: a wildcard *deny* wins over a specific allow, so a stray `'*'` in a deny rule locks the role out of the whole resource.

### A `null` policy result no longer forces a deny
**Severity:** High — **Affects:** any app that calls `isDenied()` while at least one policy is registered
v6 wrote `$result = (!$this->evaluatePolicies(...))` unconditionally; when no policy matched the triple, `evaluatePolicies()` returned `null` and `!null` made the check **denied**. v7 only overrides the rule-based result when the policy result is non-null.

```php
// before
$acl->addPolicy('update', 'editor', 'page');
$acl->isDenied('reader', 'post', 'edit');  // true  (policy returned null -> denied)
$acl->isAllowed('reader', 'post', 'edit'); // TypeError: Return value must be of type bool, null returned

// after
$acl->isDenied('reader', 'post', 'edit');  // false (falls back to rules)
$acl->isAllowed('reader', 'post', 'edit'); // true  (non-strict default)
```
**Migration:** If you relied on "any registered policy denies everything it does not explicitly cover," add explicit `deny()` rules or a catch-all policy. Note the paired `isAllowed()` change turns a v6 `TypeError` into a passing check, so previously-fataling paths now silently allow.

### `PolicyTrait::can()` throws on a non-callable policy method
**Severity:** Medium — **Affects:** roles whose policy method names don't all exist, and comma-separated `can()` calls with a partially-implemented list
v6 silently skipped any method for which `is_callable()` was false. v7 validates the whole list up front and throws `Pop\Acl\Policy\Exception`, which propagates out of `evaluatePolicy()`, `evaluatePolicies()`, `isAllowed()` and `isDenied()`.

```php
// before
$user->can('bogus');          // null, silently ignored
$user->can('update, bogus');  // true (result of update())

// after
$user->can('bogus');          // throws Pop\Acl\Policy\Exception
$user->can('update, bogus');  // throws — validated before any method runs
```
**Migration:** Ensure every method name passed to `can()` / `addPolicy()` exists and is callable, or catch `Pop\Acl\Policy\Exception`.

### Non-string scalar permissions now fatal in the removal and assertion paths
**Severity:** Medium — **Affects:** apps that use integer (or other non-string) permission identifiers
`src/Acl.php` gained `declare(strict_types=1)`, which affects the library's own internal calls into `createAssertion()`/`deleteAssertion()`, both still declaring `?string $permission`. Plain `allow()` without an assertion still works, so the failure only surfaces on removal or when an assertion is attached.

```php
// before
$acl->allow('editor', 'page', 1, $assertion); // ok
$acl->removeAllowRule('editor', 'page', 1);   // ok

// after — TypeError: must be of type ?string, int given
```
**Migration:** Cast permission identifiers to string before passing them in.

---

## pop-audit

**Scope:** Adapter contract retyping, `Table` read-result decoding, a rewritten `resolveDiff()` algorithm, filename-based timestamps in `File`, and a base-class namespace move for `AuditableModel`; the stored audit-record shape and table schema are unchanged.
**Break count:** 6 (3 high, 1 medium, 2 low)

### `AuditableModel` now extends `Pop\Db\Model\AbstractDataModel` instead of `Pop\Model\AbstractDataModel`
**Severity:** High — **Affects:** any app extending `Pop\Audit\Model\AuditableModel`, or type-hinting the old base type
`Pop\Audit\Model\AuditableModel` stays where it is — what changed is the class it extends. Its former parent, `Pop\Model\AbstractDataModel`, no longer exists: `popphp` v7 has no `src/Model/` directory at all, and that base class now lives in `pop-db` as `Pop\Db\Model\AbstractDataModel`.

```php
// before
use Pop\Model\AbstractDataModel;
use Pop\Model\DataModelInterface;

// after
use Pop\Db\Model\AbstractDataModel;
use Pop\Db\Model\DataModelInterface;
```
**Migration:** Rewrite every `Pop\Model\*` import/type-hint/`instanceof` touching audit models.

### `getStateByTimestamp()` retyped from `string` to `int` across the whole adapter contract
**Severity:** High — **Affects:** custom adapters (fatal at class-declaration time) and callers passing string timestamps
`AdapterInterface`, `AbstractAdapter` and all three adapters changed `string $from, ?string $backTo` to `int $from, ?int $backTo`.

```php
// before
$adapter->getStateByTimestamp((string)time(), (string)strtotime('-1 week'));

// after
$adapter->getStateByTimestamp(time(), strtotime('-1 week'));
```
**Migration:** Update custom adapter signatures to `int`/`?int`, and pass real integer unix timestamps.

### `Table` adapter now returns decoded `old`/`new` arrays instead of JSON strings
**Severity:** High — **Affects:** any code that `json_decode()`s the `old`/`new` fields from the `Table` adapter
A new `decodeState()` is applied in `getStates()`, `getStateByModel()`, `getStateByTimestamp()` and `getStateByDate()` — none of which decoded in v6. In PHP 8, `json_decode()` on an array is a `TypeError`, so v6 post-processing fatals. The stored column values are unchanged.

```php
// before
$old = json_decode($row['old'], true);   // 'old' was a JSON string

// after
$old = $row['old'];                      // already an array
```
**Migration:** Drop the `json_decode()` calls for all `Table` read methods. Note `Http`'s equivalents still do *not* decode, so the two adapters are now inconsistent.

### `resolveDiff()` diff algorithm rewritten — different fields get recorded
**Severity:** Medium — **Affects:** every app using `Auditor::send($old, $new)`
v6 computed `array_keys(array_diff($old, $new))` — a value-wise, string-cast comparison ignoring keys. v7 iterates keys and compares with loose `!=`. Consequences: value swaps between two keys recorded nothing in v6 and are now recorded; keys present in `$old` but absent from `$new` were recorded as `modified[$key] = null` and are now skipped; array-valued fields no longer trigger array-to-string conversion; and `0` vs `false` now compares equal.

**Migration:** Re-verify any test or report asserting the exact set of diffed fields; pass complete state arrays.

### `AdapterInterface::hasStateData()` gained a `?string $name` parameter
**Severity:** Low — **Affects:** classes implementing `AdapterInterface` directly without extending `AbstractAdapter`

**Migration:** Add `?string $name = null` to any direct implementation.

### `Http::getFetchedResult()` now requires a `Pop\Http\Client\Response` instance
**Severity:** Low — **Affects:** code injecting a non-`Client\Response` into the fetch client (test doubles, PSR-7 responses) *(uncertain)*
A response object that exposes the right methods but is not that class now makes every `Http` read method return `[]`.

**Migration:** Ensure fetch-client mocks are real `Pop\Http\Client\Response` instances.

---

## pop-auth

**Scope:** Security-hardening pass on the remaining auth adapters: a new `needsRehash()` contract, adapters now throw `Pop\Auth\Exception` on infrastructure failures instead of silently returning `0`. The `Ldap`, `Table`, and `Http` adapters have also been removed entirely — `Table` moved to `pop-db`, `Http` is superseded by using `pop-http`'s `Client`/`Auth` directly — and a new `Jwt` adapter has been added, verifying a token's signature (via a new `pop-crypt` primitive) and claims. `AuthInterface::authenticate()`'s signature also widened to accommodate `Jwt`'s single-token credential.
**Break count:** 7 (3 high, 2 medium, 2 low)

### `Auth\Ldap` has been removed
**Severity:** High — **Affects:** any app using `Auth\Ldap`
The LDAP adapter (`Ldap::class`, `ldap_bind()`-backed) is no longer part of `pop-auth`. There is no replacement adapter in this component — constructing `new Auth\Ldap(...)` now fatals with a class-not-found error.

```php
// before
$auth = new Auth\Ldap('dir.example.com', 389);
$auth->authenticate('cn=admin,dc=example,dc=com', 'password');

// after
// class Pop\Auth\Ldap does not exist
```
**Migration:** If you authenticate against LDAP, either pin your app to `pop-auth` v6 or call `ldap_bind()`/a maintained LDAP client directly outside `pop-auth`.

### `Auth\Table` has been removed
**Severity:** High — **Affects:** any app using `Auth\Table`
Database-table authentication (`Table::class`, backed by a `Pop\Db\Record` model's `findOne()`) is no longer part of `pop-auth`. `pop-auth` no longer depends on `pop-db` at all as a result.

```php
// before
$auth = new Auth\Table('MyApp\Table\Users');
$auth->authenticate('admin', 'password');

// after
// class Pop\Auth\Table does not exist
```
**Migration:** Database-backed authentication now lives in `pop-db` as `Pop\Db\Record\Auth`, but it is not a
drop-in swap: the replacement is a `Record` subclass your table class extends, not an adapter you construct
around a table class, and `authenticate()` returns `false`, `true`, or the loaded record (when issuing an MFA code) rather than
`0`/`1`. Or pin
your app to `pop-auth` v6.

It also reads more of the table than `Auth\Table` did. Beyond the username and password columns, the
defaults expect `attempts`, `active`, `verified`, `last_attempt` and `mfa`, plus `mfa_code`/`mfa_timestamp`
if you use MFA. A missing column reads as `null`, and for `active`/`verified` that is falsy — so a table carrying
only the columns v6 needed fails **every** login with `USER_NOT_ACTIVE`, correct password included, rather
than erroring in a way that points at the cause:

```php
// A users table with no `active` column
$user = new Users();
$user->authenticate('admin', 'correct-password', false);   // false
$user->getAuthFailure();                                   // 'USER_NOT_ACTIVE'
```
Either add the columns, or switch off the checks you do not want by nulling their properties — each is
independent, and a null field always passes:

```php
class Users extends Auth
{
    protected ?string $activeField      = null;
    protected ?string $verifiedField    = null;
    protected ?string $lastAttemptField = null;   // also disables lockout auto-expiry
}
```
`mfa` is the exception among the new columns: it is consulted only when it is set, so a table without it
leaves the `$mfa` argument to `authenticate()` in charge exactly as before.

### `Auth\Http` has been removed
**Severity:** High — **Affects:** any app using `Auth\Http`
Delegated/remote authentication (`Http::class`, forwarding credentials through a `Pop\Http\Client` and checking for a `200` response) is no longer part of `pop-auth`. There is no replacement adapter in this component — constructing `new Auth\Http(...)` now fatals with a class-not-found error.

```php
// before
$auth = new Auth\Http(new Client('https://www.domain.com/auth', ['method' => 'post']));
$auth->authenticate('admin', 'password');

// after
// class Pop\Auth\Http does not exist
```
**Migration:** Use `pop-http`'s `Client` and `Auth` classes directly — build the request, send it, and check `$response->isSuccess()` yourself. Or pin your app to `pop-auth` v6.

### `pop-db` and `pop-http` are no longer `pop-auth` dependencies
**Severity:** Medium — **Affects:** apps that require only `popphp/pop-auth` and use `Pop\Db` or `Pop\Http` classes that arrived transitively
With the `Table` and `Http` adapters gone, `pop-auth`'s `require` block drops `popphp/pop-db` and `popphp/pop-http` and picks up `popphp/pop-crypt` in their place. Neither package is installed alongside `pop-auth` any more, so an app that never declared them itself loses them on `composer update`.

```json
// before
"require": { "php": ">=8.3.0", "popphp/pop-db": "^6.7.0", "popphp/pop-http": "^5.3.8" }

// after
"require": { "php": ">=8.4.0", "popphp/pop-crypt": "^4.0.0" }
```
**Migration:** Add `popphp/pop-db` and/or `popphp/pop-http` to your own `composer.json` if you use either.

### `AuthInterface::authenticate()` signature widened to accommodate token-based credentials
**Severity:** Medium — **Affects:** any class implementing `AuthInterface` directly (not extending `AbstractAuth`)
The abstract contract changed from `authenticate(string $username, string $password): int` to `authenticate(string $credential, ?string $secondary = null): int`, to let the new `Jwt` adapter share the interface with `File`'s two-part credential. A direct implementer with the old signature no longer satisfies the interface and fatals on class-load.

```php
// before
public function authenticate(string $username, string $password): int { ... }

// after
public function authenticate(string $credential, ?string $secondary = null): int { ... }
```
**Migration:** Widen your implementation's second parameter to `?string $secondary = null`.

### `File::authenticate()` throws when the access file cannot be read
**Severity:** Low — **Affects:** `Auth\File` users whose file exists at construction but is unreadable or removed later
v6 emitted warnings and returned `0`; v7 throws. The constructor's `file_exists()` check existed on both branches, so this only fires for read failures.

**Migration:** Catch `Pop\Auth\Exception` around `File::authenticate()`.

### `AuthInterface` gains a required `needsRehash(): bool` method
**Severity:** Low — **Affects:** only classes implementing `AuthInterface` directly rather than extending `AbstractAuth`

**Migration:** Implement `needsRehash()`, or extend `Pop\Auth\AbstractAuth`.

---

## pop-cache

**Scope:** `Cache` gains PSR-16 (with mandatory key validation), a new `Psr6\CacheItemPool`, `remember()`, atomic counters, and an injectable clock; every adapter changes its on-backend key/storage format, hardens `unserialize()`, and gains two new abstract methods.
**Break count:** 12 (5 high, 4 medium, 3 low)

### Cache keys containing `{ } ( ) / \ @ :` (or empty) now throw
**Severity:** High — **Affects:** any app whose cache ids use colons, slashes, or namespacing punctuation — an extremely common convention
`Cache::getItem()`, `saveItem()`, `hasItem()`, `deleteItem()`, `incrementItem()`, `decrementItem()`, `invalidateTag()` and all the ArrayAccess/magic-property forms now call the new `ValidatesKey::validateKey()` and throw `Pop\Cache\InvalidArgumentException`. In v6 these characters were legal (every adapter sha1-hashed the id before storage, so they never reached the backend). `saveItems()`/`deleteItems()` are deliberately exempt, so the same key can succeed through one method and throw through another.

```php
// before
$cache->saveItem('user:42:profile', $data);   // fine
$cache['app/config'] = $data;                 // fine

// after
$cache->saveItem('user:42:profile', $data);   // Pop\Cache\InvalidArgumentException
$cache['app/config'] = $data;                 // Pop\Cache\InvalidArgumentException
```
**Migration:** Rewrite key schemes to avoid `{ } ( ) / \ @ :` (e.g. `user.42.profile`), or route legacy keys through `saveItems()`/`deleteItems()`, which skip validation.

### Cached objects now return as `__PHP_Incomplete_Class`
**Severity:** High — **Affects:** any app caching entities, DTOs, value objects, or arrays containing objects on File/Database/Redis/Session
`File`, `Database`, `Redis` and `Session` now call `unserialize($data, ['allowed_classes' => false])`. v6 used plain `unserialize()` and returned live instances. This is silent on read — the failure surfaces later as a warning on property access or a fatal on a method call.

```php
// before
$cache->saveItem('user', $userObject);
$cache->getItem('user')->getName();   // works

// after
$cache->saveItem('user', $userObject);
$cache->getItem('user')->getName();   // fatal: method call on __PHP_Incomplete_Class
```
**Migration:** Cache arrays/scalars instead of objects, serialize to JSON yourself, or switch to the `Apc`/`Memcached`/`Memory` adapters (which do not use PHP's `unserialize()`).

### Backend key format changed on Apc, Memcached, Redis and Session
**Severity:** High — **Affects:** every deploy using these four adapters, and any code reading the backend keys directly
v6 stored under the raw caller-supplied `$id`. v7 stores under `"{$namespace}:v{$version}:" . sha1($id)` (`Apc`/`Memcached`/`Redis`, via the new `Adapter\NamespacedVersionedKeys` trait) or `"{$namespace}:" . sha1($id)` (`Session`, in `$_SESSION['_POP_CACHE_']`). Every pre-existing entry becomes unreachable — a silent 100% miss rate on deploy — and any external process reading those keys breaks.

```php
// before — APCu key was literally 'foo'
apcu_fetch('foo');

// after — APCu key is 'pop_cache:v1:' . sha1('foo')
apcu_fetch('pop_cache:v1:' . sha1('foo'));
```
**Migration:** Plan for a cold cache on deploy (thundering-herd risk on a hot origin). Any external reader of these keys must be updated to the new format. Old `$_SESSION['_POP_CACHE_']` entries also linger, since the new `clear()` only sweeps prefix-matching keys.

### File adapter storage layout changed to sharded subdirectories
**Severity:** High — **Affects:** every deploy using the `File` adapter
`File` now writes to `{$dir}/{first 2 hex chars of sha1}/{sha1}` via the new `protected fileId()`, instead of the flat `{$dir}/{sha1}` of v6. Existing cache files are unreachable after upgrade. `clear()` opportunistically removes stray flat files at the top level, but only if called.

```php
// before layout
/cache/da39a3ee5e6b4b0d3255bfef95601890afd80709

// after layout
/cache/da/da39a3ee5e6b4b0d3255bfef95601890afd80709
```
**Migration:** Expect a cold cache, or delete the old cache directory contents manually.

### Database adapter now requires a unique index on `key`
**Severity:** High — **Affects:** any app with an existing `pop_cache` table (or custom `$table`)
`Database::saveItem()` replaced the v6 select-then-insert-or-update with a real upsert (`onDuplicateKeyUpdate()` for mysql, `onConflict(...,'key')` for pgsql/sqlite). `createTable()` now adds `->unique('key', $table . '_key_unique')`, but existing tables are never migrated. On sqlite/pgsql every `saveItem()` throws; on mysql `ON DUPLICATE KEY UPDATE` finds nothing to conflict on and every write **silently appends a duplicate row**.

```php
// before — worked against a table with no unique index on `key`
$cache = new Cache(new Database($db));
$cache->saveItem('foo', 'bar');

// after — same table: throws on sqlite/pgsql, silently duplicates rows on mysql
```
**Migration:** `DROP TABLE pop_cache;` (substituting your custom table name) before deploying the upgraded code; it is auto-recreated with the correct schema.

### `clear()`/`destroy()` no longer flush the whole backend
**Severity:** Medium — **Affects:** code relying on `clear()` wiping a whole APCu/memcached/redis instance or the whole session cache bucket
v6's `Apc::clear()` called `apcu_clear_cache()`, `Memcached::clear()`/`destroy()` called `flush()`, and `Redis::clear()`/`destroy()` called `flushDb()`. v7 instead bumps a per-namespace generational version counter, so only that namespace's items become unreachable. `Session::clear()` no longer resets `$_SESSION['_POP_CACHE_'] = []` — it only unsets keys matching the namespace prefix.

```php
// before — nuked the entire redis database
$cache->clear();

// after — only the 'pop_cache' namespace becomes unreachable; other keys survive
$cache->clear();
```
**Migration:** If a full flush was intended, call `$cache->adapter()->redis()->flushDb()` (or the memcached/APCu equivalent) directly.

### `AdapterInterface` / `AbstractAdapter` gained abstract methods and widened signatures
**Severity:** Medium — **Affects:** any custom adapter implementing `AdapterInterface` or extending `AbstractAdapter`
Two new methods were added to both — `incrementItem(string $id, int $amount = 1, int $initial = 0, ?int $ttl = null): int` and `decrementItem(...)` — and `getItem()`/`getItemTtl()` gained a trailing optional `$default` (`mixed $default = false` and `int $default = 0`). Any custom adapter fails to load with a fatal.

```php
// before
class MyAdapter extends AbstractAdapter {
    public function getItem(string $id): mixed { ... }
    public function getItemTtl(string $id): int { ... }
}

// after — must add $default params and implement incrementItem()/decrementItem()
class MyAdapter extends AbstractAdapter {
    public function getItem(string $id, mixed $default = false): mixed { ... }
    public function getItemTtl(string $id, int $default = 0): int { ... }
    public function incrementItem(string $id, int $amount = 1, int $initial = 0, ?int $ttl = null): int { ... }
    public function decrementItem(string $id, int $amount = 1, int $initial = 0, ?int $ttl = null): int { ... }
}
```
**Migration:** Add the `$default` parameters and implement the two counter methods.

### `Cache` now implements `Psr\SimpleCache\CacheInterface`
**Severity:** Medium — **Affects:** subclasses of `Cache` that already define `get`, `set`, `delete`, `has`, `getMultiple`, `setMultiple` or `deleteMultiple`
`Cache` now declares all seven PSR-16 methods. A subclass with any of those names and an incompatible signature fails to load with a "declaration must be compatible" fatal at class-load time.

```php
// before — legal
class MyCache extends Cache {
    public function get(string $id) { return $this->getItem($id); }
}

// after — fatal: must be compatible with Psr\SimpleCache\CacheInterface::get(string $key, mixed $default = null): mixed
```
**Migration:** Rename the conflicting methods, or align them with the PSR-16 signatures.

### `InvalidArgumentException` is not a `Pop\Cache\Exception`
**Severity:** Medium — **Affects:** code with a blanket `catch (Pop\Cache\Exception $e)` around cache calls
The new `Pop\Cache\InvalidArgumentException` extends `\InvalidArgumentException` and implements the two PSR interfaces — it does **not** extend `Pop\Cache\Exception`. Combined with the new key validation, previously-safe cache calls can now throw an exception that existing component-wide catch blocks do not intercept.

```php
// before/after — this catch does NOT catch the new key-validation exception
try {
    $cache->getItem('user:42');
} catch (\Pop\Cache\Exception $e) { /* never reached in v7 */ }
```
**Migration:** Catch `\Pop\Cache\InvalidArgumentException`, `\InvalidArgumentException`, or `\Psr\SimpleCache\InvalidArgumentException` as well.

### `Cache::clear()` return type changed `void` → `bool`
**Severity:** Low — **Affects:** subclasses overriding `clear(): void`
Required by PSR-16; `clear()` now always returns `true`. Call sites that ignore the return value are unaffected; a subclass override declared `: void` is a fatal.

**Migration:** Change the override's return type to `bool` and return a value.

### Non-string ArrayAccess offsets / property names now TypeError
**Severity:** Low — **Affects:** code using integer array offsets on a `Cache` object
`src/Cache.php` gained `declare(strict_types=1)`, so its internal `offsetGet(mixed $offset) → __get(string $name)` hop no longer coerces.

```php
// before
$cache[42] = $data;   // stored under '42'

// after
$cache[42] = $data;   // TypeError: __set(): Argument #1 must be of type string, int given
```
**Migration:** Cast offsets to string: `$cache[(string)42]`.

### `File::saveItem()` can now throw
**Severity:** Low — **Affects:** File-adapter code on read-only or permission-restricted cache directories
Writing now creates the sha1-prefixed shard directory first, and throws `Pop\Cache\Adapter\Exception('Error: Unable to create the cache shard directory.')` if that fails. v6's `saveItem()` never threw — it just let `file_put_contents()` emit a warning.

**Migration:** Ensure the cache directory is writable, or wrap saves in a try/catch.

---

## pop-code

**Scope:** Broad rewrite of the generator/reflection layer: real-PHP-value argument defaults (replacing raw literal strings), a shared `ValueFormatter`, PHP 8 attribute/enum/readonly/promoted-property support, declared-member-only reflection, and a bumped PHP floor of 8.4.
**Break count:** 10 (4 high, 3 medium, 3 low)

### `addArgument()` / `addParameter()` default values are now real PHP values, not raw source literals
**Severity:** High — **Affects:** any code calling `addArgument()`/`addParameter()`/`addArguments()` on `MethodGenerator` or `FunctionGenerator`
Previously the `$value` argument was emitted verbatim into the signature, so callers passed pre-formatted source strings (`"'default'"`, `'[]'`, `'null'`), and `null` meant "no default". Now `$value` is a real PHP value formatted by `Support\ValueFormatter`, and the "no default" sentinel is `new NoValue()`. Passing `null` now emits `= null` and widens the type to `|null`; passing `'[]'` with type `array` throws a `TypeError` from `count()`; passing `"'default'"` emits a doubly-quoted `'\'default\''`.

```php
// before
$method->addArgument('name', null, 'string');        // => string $name
$method->addArgument('name', "'default'", 'string'); // => string $name = 'default'
$method->addArgument('opts', '[]', 'array');         // => array $opts = []

// after
$method->addArgument('name', new Generator\NoValue(), 'string');  // => string $name
$method->addArgument('name', 'default', 'string');                // => string $name = 'default'
$method->addArgument('opts', [], 'array');                        // => array $opts = []
// after, if left unchanged:
$method->addArgument('name', null, 'string');  // => string|null $name = null   (silent change)
$method->addArgument('opts', '[]', 'array');   // => TypeError: count(): ... string given
```
**Migration:** Replace every `null` "no default" argument with `new Pop\Code\Generator\NoValue()`, and replace pre-quoted/pre-bracketed literal strings with the actual PHP value. Use `new Pop\Code\Generator\Literal('self::FOO')` for expressions that must be emitted verbatim.

### `ClassReflection::parse()` now emits only members declared on the class itself, and rejects enums
**Severity:** High — **Affects:** any code round-tripping an existing class through `Reflection::createClass()` / `ClassReflection::parse()`
The reflection pass previously walked `getConstants()`, `getDefaultProperties()`, `getMethods()`, and `getInterfaces()` unfiltered, so every inherited constant, property, method, and transitively-reachable interface was re-emitted into the generated class. It now filters by `getDeclaringClass()`, skips promoted properties, and reduces interfaces to the directly-declared set via `InterfaceHierarchyResolver`. It also now throws `Pop\Code\Reflection\Exception` when the reflected code is an enum.

```php
// before
$class = Pop\Code\Reflection::createClass(MyChild::class);
// generated class re-declares everything inherited from MyParent

// after
$class = Pop\Code\Reflection::createClass(MyChild::class);
// generated class contains only what MyChild itself declares
Pop\Code\Reflection::createClass(MyEnum::class); // throws Exception
```
**Migration:** If you relied on the flattened output, re-add inherited members yourself. Route enums through the new `Pop\Code\Reflection::createEnum()` / `Reflection\EnumReflection::parse()`.

### `InterfaceGenerator::setParent()` / `getParent()` removed; `hasParent()` signature changed
**Severity:** High — **Affects:** any code building or inspecting interfaces via `InterfaceGenerator`
An interface may extend several interfaces, so the single `?string $parent` property was replaced with `array $parents`. `setParent()` and `getParent()` are gone, and `hasParent()` — which previously took no arguments — now requires a `string $parent`, so existing zero-arg calls fatal with an `ArgumentCountError`.

```php
// before
$interface->setParent('Traversable');
$parent = $interface->getParent();   // 'Traversable'
if ($interface->hasParent()) { ... }

// after
$interface->addParent('Traversable');             // or addParents([...])
$parents = $interface->getParents();              // ['Traversable']
if ($interface->hasParents()) { ... }             // any parent
if ($interface->hasParent('Traversable')) { ... } // specific parent
```
**Migration:** Swap `setParent()`→`addParent()`, `getParent()`→`getParents()` (now an array), and zero-arg `hasParent()`→`hasParents()`. The constructor now accepts `mixed` (string, comma-separated string, or array) instead of `?string`.

### `ConstantGenerator::render()` now emits the visibility keyword
**Severity:** High — **Affects:** any code generating class/interface constants
Constants previously rendered as `const NAME = ...;` with no visibility. The renderer now always prefixes `$this->visibility` (default `'public'`), and for interface constants that emits `public const`, changing every generated file that contains a constant.

```php
// before
new Generator\ConstantGenerator('FOO', 'string', 'bar');
// => const FOO = 'bar';

// after
new Generator\ConstantGenerator('FOO', 'string', 'bar');
// => public const FOO = 'bar';
```
**Migration:** Accept the new output, or regenerate golden/expected files. `setTyped(true)` additionally emits the declared type.

### `addArguments()` throws `Generator\Exception` instead of `InvalidArgumentException`
**Severity:** Medium — **Affects:** code that catches `InvalidArgumentException` around argument-building
`Traits\FunctionTrait::addArguments()` previously threw `\InvalidArgumentException` for a missing `'name'` key; it now throws `Pop\Code\Generator\Exception`, which extends `Pop\Code\Exception` → `\Exception`, not `\LogicException`.

```php
// before
try { $method->addArguments($args); }
catch (\InvalidArgumentException $e) { ... }    // caught

// after
catch (Pop\Code\Generator\Exception $e) { ... } // correct
```
**Migration:** Catch `Pop\Code\Generator\Exception` (or `Pop\Code\Exception`, or plain `\Exception`).

### `'integer'` is no longer silently stripped from type hints and return types
**Severity:** Medium — **Affects:** code passing `'integer'` as a `$type` to `addArgument()`/`addParameter()` or to `addReturnType()`
Both methods previously carried a `$typeHintsNotAllowed = ['integer']` guard that dropped the type. That guard is gone, so `'integer'` now lands in the signature, producing PHP that does not compile.

```php
// before
$method->addArgument('id', null, 'integer');  // => $id   (type dropped)
$method->addReturnType('integer');            // => no return type

// after
$method->addArgument('id', new Generator\NoValue(), 'integer'); // => integer $id  (invalid PHP)
$method->addReturnType('integer');                              // => : integer   (invalid PHP)
```
**Migration:** Pass `'int'`.

### `getOutput()` return type widened from `string` to `?string`
**Severity:** Medium — **Affects:** callers of `GeneratorInterface::getOutput()` / `AbstractGenerator::getOutput()` under static analysis or strict null handling
`$output` was always nullable internally; the return type is now declared `: ?string` and returns `null` before `render()` has run.

**Migration:** Null-check the result, or call `hasOutput()` first. Existing implementers declaring `: string` remain covariant-valid.

### `formatArrayValues()` removed from `PropertyGenerator` and `ConstantGenerator`
**Severity:** Low — **Affects:** subclasses that overrode or called the protected `formatArrayValues()`
Both copies were replaced by the shared `Pop\Code\Generator\Support\ValueFormatter::format()`.

**Migration:** Override `render()`, or call `ValueFormatter::format($value, $type, $indent)` directly.

### Trait-use aliases are dropped when rendering `ClassGenerator` / `TraitGenerator` bodies
**Severity:** Low · **Bug fix** — **Affects:** code calling `addUse($trait, $alias)` on a class or trait generator
Trait use previously rendered as `use SomeTrait as Alias;` inside the class body, which is not valid PHP. It now renders `use SomeTrait;` and ignores `$as`.

**Migration:** None required if the output was already broken; the `$as` value is still stored and readable via `getUses()`.

### `NamespaceGenerator::render()` no longer discards a caller-set docblock
**Severity:** Low · **Bug fix** — **Affects:** code calling `setDocblock()` / `setDesc()` on a `NamespaceGenerator`
The generator previously overwrote `$this->docblock` unconditionally with a fresh one carrying only the `@namespace` tag. It now keeps an existing docblock and only adds the tag if absent.

**Migration:** None; expected namespace-block output changes if you were setting a docblock.

---

## pop-color

**Scope:** Seven new color spaces (Hsb/Hsv/Hwb/Lab/Lch/Oklab/Oklch) were added, `ArrayAccess`/magic accessors were consolidated into `AbstractColor` behind a new abstract `channels()` hook, and several long-standing conversion/validation bugs in `Grayscale`, `Cmyk`, `Rgb::toHsl()` and `Rgb::toHex()` were fixed in ways that silently change output for existing v6 code.
**Break count:** 6 (3 high, 1 medium, 2 low)

### `Grayscale::toRgb()` now rescales 0–100 to 0–255
**Severity:** High — **Affects:** any code converting a `Grayscale` to RGB/Hex/CSS, directly or via `render(CSS)`/`render(COMMA)`
v6 fed the raw 0–100 gray percentage straight into `Rgb` as an 0–255 channel value, so a 50% gray came out as `rgb(50, 50, 50)` (near-black). v7 multiplies by 255/100 first. Every grayscale-to-RGB result changes, and `Grayscale::render(self::CSS)` / `render(self::COMMA)` change with it since they delegate to `toRgb()`.

```php
// before
(string)Color::grayscale(50)->toRgb();   // "rgb(50, 50, 50)"
(string)Color::grayscale(100)->toRgb();  // "rgb(100, 100, 100)"

// after
(string)Color::grayscale(50)->toRgb();   // "rgb(128, 128, 128)"
(string)Color::grayscale(100)->toRgb();  // "rgb(255, 255, 255)"
```
**Migration:** Re-baseline any stored/compared RGB or hex values derived from `Grayscale`. If you relied on the v6 identity mapping, construct `Rgb` directly with your 0–255 value instead of going through `Grayscale`.

### A channel value of exactly `1` is now rescaled to `100` in `Cmyk` and `Grayscale`
**Severity:** High — **Affects:** any code passing whole-number `1` to `Cmyk`/`Grayscale`, and any `Rgb::toCmyk()` / `Rgb::toGray()` result that lands on 1
`Cmyk::setC/setM/setY/setK` and `Grayscale::setGray` changed their "this looks like a 0–1 fraction, scale it up" test from `< 1` to `<= 1`. The value `1` used to mean 1%; it now means 100%. This bites hardest on internal conversions: `Rgb::toCmyk()` and `Rgb::toGray()` legitimately produce `1` for near-white / near-black colors, and that `1` is now silently inflated to full saturation / full white.

```php
// before
(new Cmyk(1, 0, 0, 0))->getC();                 // 1.0
Color::grayscale(1)->getGray();                 // 1.0
(new Rgb(255, 252, 252))->toCmyk()->render();   // "0 1 1 0"     (near-white)
(new Rgb(3, 3, 3))->toGray()->toRgb();          // "rgb(1, 1, 1)" (near-black)

// after
(new Cmyk(1, 0, 0, 0))->getC();                 // 100.0
Color::grayscale(1)->getGray();                 // 100.0
(new Rgb(255, 252, 252))->toCmyk()->render();   // "0 100 100 0" (fully saturated)
(new Rgb(3, 3, 3))->toGray()->toRgb();          // "rgb(255, 255, 255)" (white)
```
**Migration:** Never pass a bare `1`; pass `0.01` for 1% or `100` for 100%. Audit any pipeline that round-trips RGB through CMYK or Grayscale — near-white and near-black colors now invert.

### `Rgb::toHex()` emits an 8-digit hex when alpha is set
**Severity:** High — **Affects:** any code that builds an `Rgb` with alpha and renders it as hex (CSS output, PDF/image color codes, stored hex strings)
v6 dropped alpha entirely and always produced a 6-character hex. v7 appends a two-digit alpha byte, so `getHex()` returns 8 characters and `__toString()`/`render()` return `#rrggbbaa`. This flows onward: the resulting `Hex` now carries alpha, so `Hex::toRgb()` returns an `Rgb` *with* alpha, `Hex::toHsl()` renders `hsla(...)` instead of `hsl(...)`, and `Hex::toArray()` gains an `a` key.

```php
// before
$rgb = Color::rgb(120, 60, 30, 0.5);
(string)$rgb->toHex();          // "#783c1e"
$rgb->toHex()->getHex();        // "783c1e"  (6 chars)
(string)$rgb->toHex()->toHsl(); // "hsl(20, 75%, 47%)"

// after
$rgb = Color::rgb(120, 60, 30, 0.5);
(string)$rgb->toHex();          // "#783c1e80"
$rgb->toHex()->getHex();        // "783c1e80" (8 chars)
(string)$rgb->toHex()->toHsl(); // "hsla(20, 75%, 47%, 0.502)"
```
**Migration:** If you need the v6 6-digit output, strip alpha before converting (`Color::rgb($r, $g, $b)->toHex()`) or truncate to 6 chars. Check any consumer that assumes `strlen(getHex()) === 6`.

### `Color::parse()` throws `Color\Exception` where v6 leaked `OutOfRangeException`
**Severity:** Medium — **Affects:** code with `catch (OutOfRangeException)` around `Color::parse()`
v6 let an out-of-range channel exception escape `parse()` untouched. v7 wraps the CMYK, HWB and Lab-family paths in try/catch and rethrows as `Pop\Color\Color\Exception` with the generic "not in the correct color format" message. The original range message is lost.

```php
// before
try { Color::parse('300 300 300 300'); }
catch (OutOfRangeException $e) { /* "Error: The value must be between 0 and 100" */ }

// after
try { Color::parse('300 300 300 300'); }
catch (Pop\Color\Color\Exception $e) { /* "Error: The string was not in the correct color format." */ }
```
**Migration:** Catch `Pop\Color\Color\Exception` (or `Throwable`) around `Color::parse()`. Direct constructor calls still throw `OutOfRangeException` — only `parse()` changed.

### `Cmyk::setY()`, `Cmyk::setK()` and `Grayscale::setGray()` now reject fractional out-of-range values
**Severity:** Low — **Affects:** callers passing float values slightly above 100 (or below 0) that v6 truncated into range
v6 range-checked these three setters after an `(int)` cast, so `100.4` passed and was stored verbatim. v7 compares the float directly, so the same value now throws.

```php
// before
(new Cmyk(0, 0, 100.4, 0))->getY();  // 100.4

// after
(new Cmyk(0, 0, 100.4, 0));  // throws OutOfRangeException
```
**Migration:** Clamp or round values before passing them in.

### `Rgb::toHsl()` hue wraparound: colors that used to throw now succeed
**Severity:** Low — **Affects:** code that relied on `toHsl()` raising `OutOfRangeException` for magenta/pink hues
v6 computed a negative fractional hue for any color whose max channel is red with blue > green, then passed the negative degree value into `Hsl::setH()`, which threw. v7 adds `if ($h < 0) { $h += 1; }`.

```php
// before
(new Rgb(255, 0, 255))->toHsl();  // throws OutOfRangeException

// after
(string)(new Rgb(255, 0, 255))->toHsl();  // "hsl(300, 100%, 100%)"
```
**Migration:** Remove any workaround that special-cased or caught this.

---

## pop-config

**Scope:** Single class `Pop\Config\Config` (plus `Exception`); breaks come from new parse-failure exceptions, rewritten `merge(preserve: true)` semantics, the `ext-yaml` → `symfony/yaml` swap, and dot-notation magic methods.
**Break count:** 8 (3 high, 2 medium, 3 low)

### `merge($data, true)` no longer combines colliding values — it discards the incoming one
**Severity:** High — **Affects:** any caller of `merge()`/`mergeFromData()` with `$preserve = true`
v6 used `array_merge_recursive()`, which turned a scalar collision into an array of both values and appended colliding lists. v7 uses a private `mergeRecursivePreserve()` that keeps the original value and drops the incoming one on any collision where either side is not an array. The default (`$preserve = false`) path still uses `array_replace_recursive()` and is unchanged.

```php
// before
$config = new Config(['db' => ['host' => 'localhost']], true);
$config->merge(['db' => ['host' => 'remote']], true);
// => ['db' => ['host' => ['localhost', 'remote']]]
// and ['a' => [1,2,3]] + ['a' => [4,5]] => ['a' => [1,2,3,4,5]]

// after
// => ['db' => ['host' => 'localhost']]      // incoming value silently dropped
// and ['a' => [1,2,3]] + ['a' => [4,5]] => ['a' => [1,2,3]]
```
**Migration:** Audit every `merge(..., true)` call. If you relied on value-accumulation or list-appending, do the merge yourself with `array_merge_recursive()` on `toArray()` and rebuild the `Config`.

### `parseData()` / `createFromData()` now throw instead of returning `[]` or fataling
**Severity:** High — **Affects:** any code loading an optional/missing config file, an unrecognized extension, or malformed content
v6 silently returned `[]` for any unrecognized extension and produced an uncatchable `TypeError` for a missing/malformed `.json`/`.ini`/`.yml` file. v7 validates input type, requires `is_file()`, and requires the parsed result to be an array, throwing `ParseException` or `UnsupportedFormatException`.

```php
// before
$config = Config::createFromData('/path/to/config.conf');    // empty Config, no error
$config = Config::createFromData('/path/does-not-exist.json'); // TypeError

// after
// UnsupportedFormatException / ParseException
```
**Migration:** Wrap `createFromData()`/`parseData()`/`mergeFromData()` in `try { } catch (\Pop\Config\Exception $e) { }`, or guard with `is_file()` first. Code that depended on the silent `[]` fallback must supply that fallback itself.

### YAML values parse to different PHP types (symfony/yaml replaces the PECL `yaml` extension)
**Severity:** High — **Affects:** every app loading `.yaml`/`.yml` config
`yaml_parse()` (libyaml, YAML 1.1) is replaced by `Symfony\Component\Yaml\Yaml::parseFile()`. A new `normalizeYamlScalars()` pass restores `yes/no/on/off` → bool and `0755` → int, but it runs on the *parsed* output, so it also coerces values that were **explicitly quoted strings**. Bare dates become ints, and sexagesimal/binary literals are no longer converted. Duplicate keys are now a hard parse error.

```php
// same YAML: a: "yes"   b: "0755"   released: 2001-01-23   port: 12:30   bin: 0b101

// before (yaml_parse)
['a' => 'yes', 'b' => '0755', 'released' => '2001-01-23', 'port' => 750, 'bin' => 5]

// after (symfony/yaml + normalizeYamlScalars)
['a' => true,  'b' => 493,    'released' => 980208000,     'port' => '12:30', 'bin' => '0b101']
```
**Migration:** Re-check YAML configs for values meant to stay strings — quoting no longer protects `yes/no/on/off` or leading-zero octal-looking values. Remove duplicate keys.

### YAML output format changed (`yaml_emit()` → `Yaml::dump()`)
**Severity:** Medium — **Affects:** `toYaml()`, `render('yaml')`, `writeToFile('*.yaml')` output consumers
The emitted document no longer has `---`/`...` document markers, uses 4-space indentation instead of 2, indents sequence items under their key, and serializes multi-line strings as escaped double-quoted scalars instead of block literals.

**Migration:** Regenerate committed/golden YAML files and update byte-for-byte assertions. Re-parsing the new output round-trips correctly.

### `__set()` / `__unset()` interpret dots as nested paths, and auto-vivify overwrites existing scalars
**Severity:** Medium — **Affects:** code that writes keys containing a `.` that don't already exist
`walkDotSegments($segments, true)` replaces a non-array value at an intermediate segment with an empty array — losing the previous value. A literal dotted key that *already* exists still wins.

```php
// before
$config = new Config(['db' => 'sqlite'], true);
$config['db.host'] = 'localhost';
// => ['db' => 'sqlite', 'db.host' => 'localhost']

// after
// => ['db' => ['host' => 'localhost']]   // the 'sqlite' value is gone
```
**Migration:** Stop writing literal dotted keys, or seed them in the constructor data so the literal-key branch takes priority.

### `__get()` / `__isset()` / `offsetGet()` / `offsetExists()` resolve dot paths that previously returned null/false
**Severity:** Low — **Affects:** code relying on a dotted lookup being absent

```php
// before
isset($config['db.host']);  // false

// after
isset($config['db.host']);  // true
```
**Migration:** Only an issue if you used a missing dotted key as a sentinel.

### Thrown exception classes are now subclasses, not `Pop\Config\Exception` itself
**Severity:** Low — **Affects:** exact-class exception checks; code that caught `TypeError` from parse failures
`catch (\Pop\Config\Exception $e)` keeps working; `get_class($e) === Pop\Config\Exception::class` does not.

**Migration:** Replace exact-class comparisons with `instanceof`; remove `catch (\TypeError)` blocks.

### `.php` config files resolved through `include_path` no longer load
**Severity:** Low — **Affects:** callers passing a bare filename relying on `include_path`
v7 gates every format on `is_file()` first, which does not consult `include_path`.

**Migration:** Pass absolute (or CWD-relative) paths.

---

## pop-console

**Scope:** `Command` was rebuilt on top of `popphp` 5's new `Pop\Dispatch` stack (making it dispatchable, with `Application`/`Console` now leading its constructor), command storage was extracted into a new `CommandRegistry`, `Table`/`ProgressBar` were added, strict types were enabled, `isWindows()`'s return value was inverted, and the `X_POP_CONSOLE_INPUT` prompt hook was replaced by an injectable stream.
**Break count:** 9 (3 high, 3 medium, 3 low)

### `Command` constructor signature changed — `$name` is no longer the first argument
**Severity:** High — **Affects:** any code that constructs `Pop\Console\Command` (or a subclass) positionally
`Command` no longer declares its own constructor; it inherits `AbstractCommand::__construct(?Application $application = null, Console $console = new Console(120), ?string $name = null, ?string $params = null, ?string $help = null)`. Passing a string first now throws a `TypeError`, and `$name` is no longer required, so `new Command()` silently produces a nameless command registered under an empty-string key.

```php
// before
$command = new Command('users', '--list [<id>]', 'This is the users help screen');

// after
$command = new Command(name: 'users', params: '--list [<id>]', help: 'This is the users help screen');
// or positionally:
$command = new Command($application, new Console(120), 'users', '--list [<id>]', '...help...');
```
**Migration:** Switch every `new Command(...)` call to named arguments (`name:`, `params:`, `help:`), as the v7 README now shows, or insert `null, new Console(...)` ahead of the old arguments.

### `Console::isWindows()` return value inverted
**Severity:** High · **Bug fix** — **Affects:** any code branching on `$console->isWindows()`
v6 returned `stripos(PHP_OS, 'win') === false`, i.e. `true` on Linux/macOS and `false` on Windows. v7 returns `stripos(PHP_OS, 'win') !== false`. The signature is identical, so this flips every consuming branch with no error.

```php
// before — on Linux this was true (the before behavior was inverted/buggy)
if ($console->isWindows()) { $char = '-'; } else { $char = '─'; }

// after — on Linux this is now false; the branches swap
```
**Migration:** Audit every `isWindows()` call site and invert any logic written against the v6 result.

### `$_SERVER['X_POP_CONSOLE_INPUT']` prompt-input override removed
**Severity:** High — **Affects:** tests/automation that fed `prompt()`/`confirm()` non-interactively
v6's `Console::getPromptInput()` read `$_SERVER['X_POP_CONSOLE_INPUT']` when set, only falling back to `php://stdin`. v7 removed that branch: it now reads `$this->inputStream ?? fopen('php://stdin', 'r')`. Code that set the server var will now block on / consume real stdin — a silent behavior change with no error.

```php
// before
$_SERVER['X_POP_CONSOLE_INPUT'] = 'Nick';
$name = $console->prompt('Name: ');

// after
$stream = fopen('php://memory', 'r+');
fwrite($stream, 'Nick' . PHP_EOL);
rewind($stream);
$console->setInputStream($stream);
$name = $console->prompt('Name: ');
```
**Migration:** Replace `$_SERVER['X_POP_CONSOLE_INPUT']` (and the chained `..._2`/`_3`/`_4` variants) with `setInputStream()` and a memory stream holding one line per expected answer.

### `declare(strict_types=1)` added — numeric-string and null size arguments now throw
**Severity:** Medium — **Affects:** callers passing numeric strings or relying on null-size fallbacks for `line()`/`header()`/`alert()`/`alertBox()`
`header()`/`alert()`/`alertBox()` accept `int|string|null $size` and pass the resolved value straight into `line(?int $size)` and `str_repeat()`, so a numeric-string size that v6 coerced now raises a `TypeError`. Likewise `line()` leaves `$size` as `null` when both wrap and width are empty and calls `str_repeat($char, $size)` — a deprecation in v6, a `TypeError` in v7.

```php
// before — '40' was coerced to int
$console->header('Hello World', '=', '40', 'center');
(new Console(null))->line();

// after
$console->header('Hello World', '=', 40, 'center');
(new Console(80))->line();
```
**Migration:** Cast size/width arguments to `int` at the call site and give the console an explicit wrap when relying on implicit `line()` sizing.

### `Console::$commands` changed from `array` to `CommandRegistry`
**Severity:** Medium — **Affects:** subclasses of `Console` that read or write the protected `$commands` property
The public `getCommands()` still returns the same name-keyed array, so pure public-API consumers are unaffected.

```php
// before (in a Console subclass)
foreach ($this->commands as $name => $command) { ... }
$this->commands['custom'] = $command;

// after
foreach ($this->commands->all() as $name => $command) { ... }
$this->commands->add($command);
```
**Migration:** Route subclass access through the `CommandRegistry` API (`add()`, `addAll()`, `all()`, `get()`, `has()`) or through `Console`'s public methods.

### `getCommandsFromRoutes()` now instantiates command controllers and drops an `isset()` guard
**Severity:** Medium — **Affects:** apps calling `getCommandsFromRoutes()`/`addCommandsFromRoutes()`, especially with nested CLI route configs
The logic moved to `CommandRegistry::fromRoutes()`. v7 adds an `else if` that reads `$commandRoutes[$name]['controller']` with no `isset()` guard and, when it implements `CommandInterface`, does `new $commandClass()` to read its help. Since `Pop\Router\Match\Cli::getCommands()` is keyed by flattened route strings while `getRoutes()` returns the raw (possibly nested) config, nested route definitions now emit "Undefined array key" warnings for every command — fatal under an error handler that promotes warnings. It also introduces a new side effect: command controller classes are constructed during help-screen assembly. *(uncertain — depends on whether the app's route config is nested)*

**Migration:** Flatten CLI route configs (or add explicit `'help'` values) so every command key exists in `getRoutes()`, and make sure any `CommandInterface` controller is safely constructible with zero arguments.

### `Command` now extends `Pop\Dispatch\AbstractDispatcher` and implements two interfaces
**Severity:** Low — **Affects:** subclasses of `Command` that already defined colliding members, and any class implementing `CommandInterface` directly
The new hierarchy injects `dispatch()`, `setDefaultAction()`, `getDefaultAction()`, `$defaultAction`, `application()`, `console()`, `getApplication()`, `getConsole()`, `setApplication()`, `setConsole()`, `hasApplication()`, `hasConsole()` and `hasName()` into every subclass, plus `setScriptName()`, `getScriptName()`, `hasScriptName()` and `$scriptName`. Constructing a `Command` also now builds a default `new Console(120)`, which runs the terminal-size probe.

`CommandInterface` grew alongside it and now requires `hasName()` and the three `*ScriptName()` methods. Extending `Command` or `Command\AbstractCommand` picks them all up, but a class implementing the interface directly fatals until it declares them.

**Migration:** Rename or re-signature any colliding members on `Command` subclasses to match the inherited `Pop\Dispatch` contracts, and add the four new methods to any stand-alone `CommandInterface` implementation.

### `Console::help()` and `displayHelp()` gained a trailing `$subCommand` parameter
**Severity:** Low — **Affects:** `Console` subclasses that override either method
`help(?string $command = null, bool $raw = false)` is now `help(?string $command = null, bool $raw = false, ?string $subCommand = null)`, and `displayHelp(bool $raw = false)` is now `displayHelp(bool $raw = false, ?string $subCommand = null)`. Both back the new subcommand-filtered help screen.

Callers are unaffected, since the parameter is optional. A subclass that **overrides** either method is the exception — PHP requires an override to accept at least as many parameters as its parent, so a v6 override fatals with an incompatible-declaration error. Overriding `displayHelp()` to customize the help screen is the likely case here.

**Migration:** Nothing to do for plain callers. Add the trailing `?string $subCommand = null` to any override and pass it through.

### `Console` rendering moved into collaborator classes — subclass overrides stop being called
**Severity:** Low — **Affects:** `Console` subclasses that overrode `alert()`, `header()`, `calculatePad()` or `getPromptInput()` to customize output
`Console` was split up: prompting, headers, alerts and the help screen now live in `Pop\Console\Prompt`, `Header`, `Alert` and `Help`, and `Console` constructs one of them per call. Every public method kept its name and signature, so callers see no change — but the internal call graph they hung off is gone.

```php
// before — alertDanger() called $this->alert(), so this override changed all eight variants
class MyConsole extends Pop\Console\Console {
    public function alert(string $message, ...): Console|string { … }
}
$console->alertDanger('Nope');   // before: your override ran. after: it does not.
```
The same applies to `headerLeft()`/`headerCenter()`/`headerRight()`, which no longer route through `header()`. Two protected helpers were removed from `Console` outright — `calculatePad()` (now on the `MessageTrait` the renderers use) and `getPromptInput()` (now on `Prompt`) — so an override of either is dead code that PHP will not complain about.

Nothing fatals here; the customization simply stops taking effect, which is why it is worth grepping for rather than waiting to notice.

**Migration:** Override the specific public method you actually want to change (`alertDanger()` rather than `alert()`), or subclass the renderer itself and use it directly — `Prompt`, `Header`, `Alert` and `Help` are all usable stand-alone.

---

## pop-cookie

**Scope:** No classes, methods or properties were removed or renamed — every break is a behavior or exception change under an unchanged public API.
**Break count:** 6 (2 high, 3 medium, 1 low)

### An invalid `samesite` value now throws instead of being silently ignored
**Severity:** High — **Affects:** any call to `getInstance()`, `setOptions()`, `set()`, `delete()`, or `clear()` passing a `samesite` that isn't exactly `'None'`, `'Lax'`, or `'Strict'`
v6 skipped the assignment on a bad value, leaving the `'Lax'` default. v7 throws `Pop\Cookie\Exception`. Case matters — `'lax'`, `'strict'`, `'none'` or `''` all now fatal.

```php
// before
$cookie = Cookie::getInstance(['samesite' => 'lax']); // ignored, samesite stays 'Lax'

// after
$cookie = Cookie::getInstance(['samesite' => 'lax']); // throws Pop\Cookie\Exception
```
**Migration:** Normalize `samesite` values to the exact strings, or wrap configuration in try/catch.

### `samesite => 'None'` without `secure => true` now throws
**Severity:** High — **Affects:** any configuration setting `samesite` to `'None'` without `secure` truthy in the resolved state
v6 accepted `['samesite' => 'None']` alone and emitted a cookie browsers would drop. v7 throws whenever the resulting state is `samesite === 'None'` and `secure === false`. The check runs against the *combined* state, so a later `setOptions(['secure' => false])` on a `None` instance also throws.

```php
// before
$cookie = Cookie::getInstance(['samesite' => 'None']); // accepted silently

// after
$cookie = Cookie::getInstance(['samesite' => 'None', 'secure' => true]); // required form
```
**Migration:** Always pass `'secure' => true` alongside `'samesite' => 'None'` in the same options array.

### `getInstance()` with options now reconfigures the existing singleton
**Severity:** Medium — **Affects:** apps calling `Cookie::getInstance([...])` from more than one place in a request
In v6, every call after the first ignored `$options` entirely. In v7 a non-empty `$options` on a later call is forwarded to `setOptions()` on the shared instance, mutating global cookie configuration for every other holder — and it can now throw from a call site that previously could not throw at all.

```php
// before
Cookie::getInstance(['path' => '/app']);
$c = Cookie::getInstance(['path' => '/other']); // ignored; path stays '/app'

// after
$c = Cookie::getInstance(['path' => '/other']); // path is now '/other' for everyone
```
**Migration:** Configure once at bootstrap and call `Cookie::getInstance()` with no arguments everywhere else.

### A failing `setcookie()` now throws instead of failing silently
**Severity:** Medium — **Affects:** `set()`, `__set()`/`offsetSet()`, `delete()`, `clear()`, and `unset($cookie->foo)` — most commonly when headers were already sent
v6 discarded the return value of `setcookie()`. v7 checks it at all four call sites and throws `Pop\Cookie\Exception` on `false`.

```php
// before
echo 'output';
$cookie->foo = 'bar'; // warning only; execution continues

// after
$cookie->foo = 'bar'; // throws Pop\Cookie\Exception
```
**Migration:** Set cookies before any output, or catch `Pop\Cookie\Exception` around late writes.

### `__get()` / `offsetGet()` JSON decoding widened to `[`, `true`, `false`, `null`
**Severity:** Medium — **Affects:** reading any cookie whose value starts with `[` or is exactly `true`, `false`, or `null`
v6 only JSON-decoded values starting with `{`. Those reads now change type — and a value starting with `[` that isn't valid JSON now decodes to `null` rather than returning the original string.

```php
// before
$_COOKIE['flag'] = 'true';   $cookie->flag; // string 'true'  (truthy)
$_COOKIE['x']    = '[bad';   $cookie->x;    // string '[bad'

// after
$cookie->flag; // bool true
$cookie->x;    // null
```
**Migration:** Stop comparing these reads against string literals; for values that must stay verbatim strings, prefix or encode them.

### `declare(strict_types=1)` makes non-string `path`/`domain`/`samesite` options a TypeError
**Severity:** Low — **Affects:** callers passing non-string scalars for those three options
`expires`, `secure` and `httponly` are still explicitly cast and unaffected.

**Migration:** Cast option values to `string` before passing them.

---

## pop-crypt

**Scope:** Crypto-behavior release: AES-CBC payloads are re-keyed via HKDF (old CBC ciphertext is undecryptable), `Encrypter::load()` flips its raw/base-64 default, `encrypt()`/`decrypt()` narrow `mixed` to `string`, and a 4096-byte hashing input cap now throws.
**Break count:** 9 (3 high, 4 medium, 2 low)

### AES-CBC ciphertext is no longer decryptable — encryption and MAC keys are now HKDF-derived
**Severity:** High — **Affects:** every value ever encrypted with `aes-128-cbc`/`aes-256-cbc`, including `Pop\Db\Record\Encoded` `$encryptedFields` columns and any at-rest payload stored by a v6 app
For non-AEAD ciphers, `encrypt()` now encrypts with `hash_hkdf('sha256', $key, 32, 'pop-crypt|encryption-key')` and MACs with a separate derived key, instead of using the master key directly for both. Verified in both directions: v6 CBC payloads decrypted under v7 (and vice versa) throw `Invalid MAC value`. `aes-128-gcm`/`aes-256-gcm` payloads round-trip across versions unchanged.

```php
// before — stored ciphertext produced with the raw key
$payload = (new Encrypter($key, 'aes-256-cbc'))->encrypt('SECRET');

// after — same key, same cipher, same payload
(new Encrypter($key, 'aes-256-cbc'))->decrypt($payload);
// Pop\Crypt\Encryption\Exception: Error: Invalid MAC value.
```
**Migration:** Before upgrading, decrypt all stored CBC values with v6 and re-encrypt under v7 (or switch those fields to `aes-256-gcm`, which is byte-compatible across both versions). `setPreviousKeys()` does not help — the format, not the key, changed.

### `Encrypter::load()` default `$raw` changed from `true` to `false`
**Severity:** High — **Affects:** any app calling `Encrypter::load()` with no argument, i.e. bootstrapping the key from `APP_KEY`/`APP_PREVIOUS_KEYS`
Env key material is now base-64-decoded by default instead of used verbatim. With a raw 32-byte `APP_KEY`, v6 `load()` succeeded and v7 throws `Invalid key or unsupported cipher.`; with a base-64 `APP_KEY` the results are exactly inverted.

```php
// before — APP_KEY holds 32 raw bytes; keys used as-is
$encrypter = Encrypter::load();

// after — same call now base64_decode()s APP_KEY first
$encrypter = Encrypter::load(true);  // before behavior, explicit
```
**Migration:** Pass `load(true)` to keep v6 semantics, or base-64-encode `APP_KEY` and `APP_PREVIOUS_KEYS`. **Do not "fix" a throw by re-encoding the key without also re-encrypting** — the two paths yield different key bytes.

### `encrypt()` / `decrypt()` narrowed from `mixed` to `string`
**Severity:** High — **Affects:** callers passing `null`, arrays, or objects — notably ORM/record layers handing column values straight to `encrypt()`

```php
// before
$encrypter->encrypt(null);   // encrypts "" and returns a payload

// after
$encrypter->encrypt(null);   // TypeError
```
**Migration:** Cast/guard at the call site (`$value === null ? null : $encrypter->encrypt((string) $value)`).

### `declare(strict_types=1)` makes string-valued option arrays fatal
**Severity:** Medium — **Affects:** options loaded from `.env`, `.ini`, or JSON config, where numbers arrive as strings
The internal `setOptions()` → `setCost()`/`setMemoryCost()`/`setTimeCost()`/`setThreads()` calls are made in strict mode and no longer coerce.

```php
// before — '11' silently coerced to 11
Hasher::create(PASSWORD_BCRYPT, ['cost' => '11']);

// after — TypeError: must be of type int, string given
Hasher::create(PASSWORD_BCRYPT, ['cost' => (int)$cfg['cost']]);
```
**Migration:** Cast config values to `int` before passing them.

### `make()` / `createHash()` / `verify()` throw on values over 4096 bytes
**Severity:** Medium — **Affects:** long passphrases, API tokens, or file contents fed to the hashers; `verify()` callers that treated a bad input as "no match"
New `AbstractHasher::MAX_VALUE_LENGTH = 4096` is enforced in both `createHash()` and `verify()`.

```php
// before
$hasher->verify(str_repeat('a', 5000), $hash);  // false

// after
// Pop\Crypt\Hashing\Exception: The value exceeds the maximum allowed length of 4096 bytes.
```
**Migration:** Wrap login/verify paths in try/catch (treating the exception as a failed match) or length-check input first. Values already hashed at >4096 bytes can no longer be verified through this API at all.

### `setCipher()` and `setKey()` now validate and throw
**Severity:** Medium — **Affects:** key-rotation and cipher-switching code that mutates an existing encrypter
Both were pure assignment in v6; they now reject an unavailable cipher, a key that does not match the cipher's size, and a cipher that does not match an already-set key. The constructor's cipher-only failure message also changed.

**Migration:** Validate with `Encrypter::isAvailable()` / `isValid()` first, or catch the exception. Update any code matching the old message string.

### `ext-openssl` and `ext-sodium` are now hard Composer requirements
**Severity:** Medium — **Affects:** builds on minimal/distro PHP images where sodium is a separate package or was compiled out
`ext-sodium` is required even for apps that only use the OpenSSL `Encrypter` or the hashers.

**Migration:** Install/enable both extensions in every environment, including CI and container images.

### `AbstractEncrypter` now implements `EncrypterInterface`, and the interface signatures changed
**Severity:** Low — **Affects:** only custom encrypters that extend `AbstractEncrypter` or implement `EncrypterInterface`
The interface's `encrypt(mixed): string` / `decrypt(mixed): mixed` became `encrypt(string): string` / `decrypt(string): string`. A v6-shaped implementer is now an uncatchable fatal.

**Migration:** Change custom encrypters to the narrowed signatures.

### `Argon2IdHasher::requiresRehash()` now checks against `PASSWORD_ARGON2ID`
**Severity:** Low · **Bug fix** — **Affects:** rehash-on-login logic using `Argon2IdHasher`
v6 passed `PASSWORD_ARGON2I` (a bug), so it returned `true` for every argon2id hash.

**Migration:** None required; code that was unconditionally rehashing on every verify will now rehash only when parameters actually change.

---

## pop-css

**Scope:** No classes added or removed; breaks come from rendered-CSS ordering/format changes, string-normalization of selector property values, removed `AbstractCss` bucket properties, and parser behavior fixes.
**Break count:** 8 (1 high, 3 medium, 4 low)

### Selectors now render in insertion order instead of element → ID → class order
**Severity:** High — **Affects:** every `render()` / `__toString()` / `writeToFile()` call on a stylesheet that mixes selector types
v6 kept three parallel bucket arrays and rendered all element selectors, then all IDs, then all classes. v7 iterates `$this->selectors` directly, so output follows insertion order. Because CSS cascade order matters for equal-specificity rules, this can change how a page actually renders, not just the byte output.

```php
// before
$css->addSelector(new Selector('.bold'));
$css->addSelector(new Selector('html'));
$css->addSelector(new Selector('#login'));
echo $css; // html {} then #login {} then .bold {}

// after
echo $css; // .bold {} then html {} then #login {}
```
**Migration:** Add selectors in the exact order you want them emitted; update golden-file assertions. The same reordering applies inside `Media` blocks.

### Selector property values are normalized to `string` at set time
**Severity:** Medium — **Affects:** code that reads values back via `getProperties()`, `$selector['x']`, or `$selector->x`
v6 stored whatever was assigned verbatim (int, float, bool, null, array). v7 casts to string (or `ColorInterface::render('CSS')`). Rendered CSS is unchanged for scalars, but `===` comparisons and arithmetic on returned values break.

```php
// before
$s['margin'] = 0;
var_dump($s->getProperties()); // ['margin' => int(0)]

// after
var_dump($s->getProperties()); // ['margin' => string "0"]
```
**Migration:** Use loose comparisons or cast on read.

### Parser now keeps declarations containing `:` or `;` inside their values
**Severity:** Medium — **Affects:** `parseString()` / `parseFile()` / `parseUri()` on real-world stylesheets
v6 split on a naive `explode(';', …)` and required exactly two colon-parts, silently **dropping** any declaration whose value contained a colon or semicolon (`url(http://…)`, `url(data:image/png;base64,…)`). v7 uses a paren-depth-aware splitter, so those declarations survive.

```php
// before
Css::parseString(".icon { background: url(data:image/png;base64,iVBOR=); color: #fff; }")
    ->getSelector('.icon')->getProperties();   // ['color' => '#fff']  <- background lost

// after
// ['background' => 'url(data:image/png;base64,iVBOR=)', 'color' => '#fff']
```
**Migration:** Expect richer (and larger) parsed selectors; update assertions that encoded the old lossy behavior.

### `addSelector()` merges into an existing same-name selector instead of replacing it
**Severity:** Medium — **Affects:** code that adds the same selector name twice to build a stylesheet up
v6 assigned into `$this->selectors[$name]`, so the second `Selector` for a given name discarded the first outright. v7 merges the incoming properties into the selector already registered — last value for a given property wins — which is what the CSS cascade does with repeated rules. Properties set on the first object and absent from the second now survive instead of disappearing.

```php
$a = new Selector('.btn');
$a['color']   = '#fff';
$a['padding'] = '10px';
$b = new Selector('.btn');
$b['color']   = '#000';

$css->addSelector($a);
$css->addSelector($b);

// before
$css->getSelector('.btn')->getProperties(); // ['color' => '#000']  <- padding lost

// after
// ['color' => '#000', 'padding' => '10px']
```
**Migration:** Where the old wholesale replacement was the intent, remove the selector first, or build the final `Selector` before adding it.

### `parseCssUri()` throws when the URI cannot be fetched
**Severity:** Low — **Affects:** code parsing stylesheets from a URL or a path that may not resolve
v6 passed `file_get_contents()` straight into `parseCss()` without checking it. A 404, a timeout or a missing file therefore produced a stylesheet with no selectors and no indication anything had gone wrong. v7 raises `Pop\Css\Exception` naming the URI.

```php
// before
$css = (new Css())->parseCssUri('https://example.com/missing.css');
count($css); // 0 — silently empty

// after
// throws Pop\Css\Exception: Error: Unable to fetch CSS from the URI 'https://example.com/missing.css'.
```
**Migration:** Wrap `parseCssUri()` in try/catch where the source may be unreachable. Code that was relying on the silent empty result was almost certainly not doing what it intended.

### `Selector::__isset()` / `offsetExists()` now report synthesized margin/padding longhands
**Severity:** Low — **Affects:** `isset($sel['margin-top'])`-style guards
v6's `__isset()` only checked the literal key while `__get()` happily synthesized a value from the shorthand.

**Migration:** Use `array_key_exists($name, $sel->getProperties())` if you need "explicitly set" semantics.

### Magic `__set` / `offsetSet` with `null`, arrays or non-Stringable objects now behaves differently
**Severity:** Low — **Affects:** code assigning non-scalars
`null` becomes `""` (and `isset()` flips to true, rendering `x: ;`), an array stores `"Array"` with a warning, and an object without `__toString()` throws at assignment time rather than at render time.

**Migration:** Use `removeProperty()`/`unset()` instead of assigning `null`.

### `Cmyk` / `Grayscale` color objects assigned as property values now render as `rgb(...)`
**Severity:** Low — **Affects:** code that passed a color object where a string was expected
v7 explicitly calls `$value->render('CSS')` for anything implementing `ColorInterface`, which routes through `toRgb()`; v6 relied on `__toString()`, which returns the percent format.

```php
// before
$s->setProperty('color', new Cmyk(50, 40, 30, 20)); // "0.5 0.4 0.3 0.2"

// after
$s->setProperty('color', new Cmyk(50, 40, 30, 20)); // "rgb(102, 122, 143)"
```
**Migration:** This is a fix (percent output was never valid CSS); pass `$color->render('PERCENT')` if you relied on the old string.

---

## pop-csv

**Scope:** Single class `Pop\Csv\Csv` plus `Exception`; changes are `declare(strict_types=1)`, an `escapeFormulas` option, a streaming reader, internal refactors, and a PHP-version bump.
**Break count:** 6 (0 high, 2 medium, 4 low)

### `declare(strict_types=1)` makes loosely-typed option values throw TypeError
**Severity:** Medium — **Affects:** any caller passing `limit`, `newline`, or `length` options as strings/ints (common when options come from JSON/INI/env config)
`processOptions()` passes user option values straight into `serializeRow(... int $limit, bool $newline ...)` and `fgetcsv(..., ?int $length ...)` without coercion.

```php
// before — coerced silently, works
Csv::serializeData($data, ['limit' => '3']);
Csv::serializeData($data, ['newline' => 0]);

// after — TypeError
Csv::serializeData($data, ['limit' => 3]);
Csv::serializeData($data, ['newline' => false]);
```
**Migration:** Cast option values to their declared types at the call site.

### `Csv::isValid()` changed from always-true to a real structural check
**Severity:** Medium — **Affects:** any code gating parsing on `isValid()`
On v6 the method only counted fields in the first line, and `str_getcsv('')` returns `[null]`, so it returned `true` for literally every input including the empty string. v7 trims the input, returns `false` for empty/whitespace-only strings, and returns `false` when any row's column count differs from the first row's.

```php
// before — always taken
if (Csv::isValid($string)) { $data = Csv::loadString($string)->getData(); }

// after — skipped for empty input and for any file with ragged rows
```
**Migration:** Ragged CSV that v6 happily parsed is now rejected by the guard. Drop the `isValid()` gate and parse directly, or normalize rows to a uniform column count first.

### `getRowCountFromFile()` escape-character default changed from `\` to `"`
**Severity:** Low — **Affects:** row counts on files containing backslash-before-quote sequences
The fallback now aligns with every other method, but alters how `fgetcsv()` tracks enclosure state.

**Migration:** Pass `['escape' => "\\"]` explicitly to restore the old count.

### `getFieldHeaders()` header line ending changed from `PHP_EOL` to `"\n"`
**Severity:** Low — **Affects:** CSV files generated on Windows
On Linux/macOS the output is byte-identical; on Windows, files previously had a `\r\n` header row followed by `\n` data rows.

**Migration:** Regenerate any golden CSV fixtures produced on Windows.

### Numeric-detection now runs before `limit` truncation, changing leading-zero quoting
**Severity:** Low — **Affects:** serialization using the `limit` option on values that become numeric only after truncation

```php
// before — output: "0123"   (quoted, leading-zero protection applied)
// after — output: 0123      (unquoted; spreadsheets will strip the leading zero)
Csv::serializeData([['a' => '0123abc']], ['limit' => 4, 'fields' => false]);
```
**Migration:** Truncate values yourself before serializing, or wrap affected columns in the enclosure manually.

### `unserializeString()` now strips a leading UTF-8 BOM, changing the first field key
**Severity:** Low — **Affects:** code parsing BOM-prefixed CSV that keys off the BOM-contaminated first column name

```php
// before — $row["\xEF\xBB\xBFid"]
// after — $row["id"]
```
**Migration:** Generally a fix; remove any BOM-stripping workaround applied to the returned keys.

---

## pop-db

**Scope:** Deep rework of the shorthand-condition parser (new structured syntax + deprecation of the old shapes), relationship eager-loading (composite keys, nested `with()`, empty-relationship values), a new `Pop\Db\Model` namespace absorbed from `popphp`, subquery/JSON predicates in the SQL builder, plus a PHP 8.4 requirement and dependency swaps.
**Break count:** 19 (7 high, 9 medium, 3 low)

### `popphp/pop-debug` removed from `pop-db`'s requirements
**Severity:** Medium — **Affects:** apps using the query profiler's debugger integration
`popphp/pop-debug` was dropped from `require` entirely, so it is no longer installed alongside `pop-db`.

**Migration:** Add `popphp/pop-debug` to your own `composer.json` if you use the profiler's debugger integration.
### Legacy shorthand array syntax now emits `E_USER_DEPRECATED`
**Severity:** High — **Affects:** any app using suffixed-operator keys, array-valued IN, or packed BETWEEN in `findBy()`/`findOne()`/`getTotal()`
`Record::parseColumns()` now routes array `$columns` through `Sql\Parser\Condition::parse()`. Anything that isn't plain equality, a bare `null`, or a structured `[OPERATOR, ...]` tuple falls into `parseLegacy()`, which fires `trigger_error(..., E_USER_DEPRECATED)` before delegating to the old parser. Apps with an error handler that promotes notices to exceptions (very common in dev/CI) will now fatal on ordinary queries.

```php
// before — silent, if used in after each will trigger E_USER_DEPRECATED
Users::findBy(['age>=' => 18]);
Users::findBy(['id' => [1, 2, 3]]);
Users::findBy(['%username' => 'test']);
Users::findBy(['id' => '(1, 5)']);

// after — same result with the new format, no E_USER_DEPRECATED triggered
Users::findBy(['age' => ['>=', 18]]);
Users::findBy(['id'  => ['IN', [1, 2, 3]]]);
Users::findBy(['username' => ['LIKE', '%test']]);
Users::findBy(['id' => ['BETWEEN', 1, 5]]);
```
**Migration:** Convert to the structured tuple form. Note the bulk `$record->delete($columns)` path still calls `Expression::parseShorthand()` directly, so it neither deprecates nor understands the structured form — `$user->delete(['age' => ['>=', 18]])` silently renders `age IN ('>=', 18)`.

### Array shorthand values whose first element looks like an operator are reinterpreted
**Severity:** High — **Affects:** IN-style shorthand where the first value is a string like `in`, `=`, `like`, `between`, `contains`
`Condition::isNewSyntax()` classifies any array value as a structured tuple when `strtoupper($value[0])` matches one of its 15 operator names. The check is case-insensitive and happens before the legacy path, so a legitimate list of values silently changes meaning or throws.

```php
// before — WHERE (status IN ('in', 'out'))
Users::findBy(['status' => ['in', 'out']]);

// after — parsed as the IN operator with 'out' as its value list
//      -> Pop\Db\Sql\Parser\Exception
Users::findBy(['status' => ['IN', ['in', 'out']]]);
```
**Migration:** Audit every array-valued shorthand whose first element is a string, and rewrite it explicitly as `['IN', [...]]`.

### Unmatched eager-loaded relationships resolve to `null` / empty `Collection`, not `[]`
**Severity:** High — **Affects:** code that reads `with()`-loaded relationships and checks them with `is_array()`/`empty()`/`count()`
`processWithRelationships()` used to set `[]` when a record had no matching related rows. It now calls `RelationshipInterface::getEmptyRelationshipValue()`: `null` for `HasOne`/`HasOneOf`/`BelongsTo`, `new Record\Collection()` for `HasMany`.

```php
// before
$user = Users::with(['info', 'orders'])->getOne(['id' => 1]);
is_array($user->info);   // true  ([] when unmatched)
count($user->orders);    // 0     (array)

// after
$user->info === null;                // true when unmatched
$user->orders instanceof Collection; // true, count() === 0
```
**Migration:** Replace `is_array()`/`empty()` checks on 1:1 relationships with `!== null`; treat 1:many as a `Collection`. Note `isset($user->info)` now returns `true` even when the value is `null`, because `__isset()` switched to `array_key_exists()`.

### `Pop\Model\AbstractDataModel` and `DataModelInterface` are now `Pop\Db\Model\AbstractDataModel` and `DataModelInterface`
**Severity:** High — **Affects:** any app whose model classes extend `Pop\Model\AbstractDataModel`
These classes themselves changed namespace — `popphp` v7 deletes `src/Model/`, and `pop-db` v7 adds `Pop\Db\Model\{AbstractDataModel, DataModelInterface, Exception}` in its place. The method surface is otherwise identical, so only the imports and type-hints change. Their own base class went elsewhere again: `Pop\Model\AbstractModel` is now `Pop\Utils\AbstractModel`.

```php
// before
use Pop\Model\AbstractDataModel;
class User extends AbstractDataModel {}

// after
use Pop\Db\Model\AbstractDataModel;
class User extends AbstractDataModel {}
```
**Migration:** Update `use` statements to `Pop\Db\Model\*` (and `Pop\Utils\AbstractModel` for plain models). Any `instanceof Pop\Model\DataModelInterface` or type hints against those FQCNs must be updated too.

### Profiler debugger type changed from `Pop\Debug\Debugger` to `Pop\Utils\DebuggerInterface`
**Severity:** High — **Affects:** apps attaching a debugger to the adapter profiler
`Pop\Debug\Debugger` only implements that interface as of `pop-debug` v4 — a `pop-debug` v3 instance is now a `TypeError`.

**Migration:** Require `popphp/pop-debug: ^4.0` explicitly (it is no longer pulled in transitively) and update `Debugger` type hints on your side to `DebuggerInterface`.

### `OR`, `AND`, `EXISTS`, `NOT EXISTS` are reserved shorthand keys; `->` in a key means JSON path
**Severity:** High — **Affects:** shorthand arrays with a column literally named one of those, or containing `->`
`Condition::parseConditions()` intercepts `'OR'`/`'AND'` as nested condition groups and `'EXISTS'`/`'NOT EXISTS'` as subquery predicates before any column handling. `parseTuple()` splits any column key on `->` and routes it to the JSON predicates.

```php
// before — a column filter
Users::findBy(['OR' => 'yes']);

// after — Exception: "The 'OR' key must contain an array of condition groups."
```
**Migration:** Rename columns that collide with the reserved keys, or build those conditions with a `PredicateSet`.

### `BelongsTo` eager loading now always matches on the target table's primary key
**Severity:** High — **Affects:** `with()` on a `belongsTo()` relationship where the FK name also exists as a column on the parent table
v6 queried `WHERE {$foreignKey} IN (...)` and only substituted the parent's primary key when the FK name was *not* a column on the parent table. v7 unconditionally uses the target table's declared primary key. This is the documented "self-referencing belongsTo" fix, but it changes the SQL and the resulting matches for existing schemas.

```php
// Orders::user() { return $this->belongsTo('Users', 'user_id'); }  // and users also has a user_id column

// before eager query: SELECT ... FROM users WHERE user_id IN (?, ?)
// after eager query: SELECT ... FROM users WHERE id       IN (?, ?)
```
**Migration:** Re-test every `with()` on a `belongsTo()` relationship.

### `$fillable` / `$guarded` are now meaningful properties on every `Record`
**Severity:** Medium — **Affects:** table classes that already declare a property with either name
`Record::__construct()` now routes array-like input through the new `fill()` instead of `setColumns()`. Defaults are empty (unrestricted), so behavior is unchanged for most apps — but an existing table class that already declared `$fillable`/`$guarded` for its own purposes will silently start filtering constructor mass-assignment. *(uncertain — depends on your table classes)*

```php
// before — every key was set
$user = new Users(['username' => 'x', 'role' => 'admin']);

// after — filtered through isFillable(); 'role' silently dropped if $fillable = ['username']
```
**Migration:** Rename any pre-existing `$fillable`/`$guarded` properties, or accept the new semantics. Note `fill()` *replaces* the column set rather than merging.

### `RelationshipInterface` gained a required `getEmptyRelationshipValue()` method
**Severity:** Medium — **Affects:** custom relationship classes implementing the interface directly

**Migration:** Implement `public function getEmptyRelationshipValue(): mixed;` (or extend `AbstractRelationship`, whose default returns `[]`).

### `setChildRelationships()` / `getChildRelationships()` changed from string to array
**Severity:** Medium — **Affects:** code calling these on a relationship object

```php
// before
public function setChildRelationships(string $children): static
public function getChildRelationships(): string|null

// after
public function setChildRelationships(array $children): static
public function getChildRelationships(): array
```
**Migration:** Pass/expect arrays of dotted child paths.

### `PredicateSet` comparison methods widened `string $value` to `mixed $value`
**Severity:** Medium — **Affects:** subclasses of `PredicateSet`/`Where`/`Having` that override these methods
`equalTo()`, `notEqualTo()`, `greaterThan()`, `greaterThanOrEqualTo()`, `lessThan()`, `lessThanOrEqualTo()` now accept `mixed` so a `Sql\Select` can be passed as a subquery. PHP's contravariance rule makes an override that still declares `string $value` a fatal incompatible-signature error.

**Migration:** Widen the parameter to `mixed` in any override.

### `Record::parseColumns()` now returns a `PredicateSet` under `expressions`
**Severity:** Medium — **Affects:** code calling the public `parseColumns()` directly

```php
// before — $expressions === ['id = ?']  (array of expression strings)
// after — $expressions instanceof Pop\Db\Sql\PredicateSet
```
**Migration:** Treat `expressions` as a `PredicateSet`. The gateway methods accept it as `mixed $where`, so the internal flow is unchanged.

### `Migrator::getCurrent()` return type changed from `?string` to `?int`
**Severity:** Medium — **Affects:** custom tooling reading the migrator's current position

**Migration:** Adjust type hints; `===` against a string timestamp will now fail.

### `TRUNCATE TABLE` renders as `DELETE FROM` on SQLite
**Severity:** Medium — **Affects:** schema code calling `truncate()` against SQLite
AUTOINCREMENT sequences are not reset by `DELETE FROM`, and any test asserting the emitted SQL string will change.

### `getLastId()` now returns an int on Pdo and Pgsql
**Severity:** Medium — **Affects:** code doing strict comparisons or string operations on the last insert id

```php
// before
$db->getLastId() === '42'; // true

// after
$db->getLastId() === 42;   // true
```
**Migration:** Update strict comparisons. Large BIGINT ids beyond PHP's int range will now be truncated *(uncertain — only on 32-bit or >2^63 ids)*.

### WHERE/HAVING string parsing is now quote-aware
**Severity:** Low — **Affects:** string conditions containing `AND`/`OR`/`IN`/`LIKE`/`BETWEEN`/`NULL` inside quoted literals
The new `Parser\Keyword::split()` skips keywords inside string literals and requires word boundaries.

```php
// before — split on the AND inside the literal, producing garbage predicates
$sql->select()->where("note = 'salt AND pepper'");

// after — kept as a single predicate
```
**Migration:** Generally a fix; a column literally named e.g. `brand` is no longer mistaken for `AND`.

### `Record::reset()` and `Record\Encoded::needsRehash()` / `rehash()` are now taken method names
**Severity:** Low — **Affects:** table classes that already declare a method under one of those names
`Record` gained `reset(string $column, mixed $value = null): void`, and `Record\Encoded` gained `needsRehash(): bool` and `rehash(string $key, string $value): void` plus a protected `$needsRehash` property. A subclass method of the same name with a different signature is now an incompatible override and fatals at class-load. `reset()` is the likely collision — it is a natural name for a password-reset or state-reset helper on a user table. *(uncertain — depends on your table classes)*

```php
// before — fine, Record had no reset()
class Users extends Record {
    public function reset(): static { ... }
}

// after — Fatal error: Declaration of Users::reset(): static must be compatible with
//      Pop\Db\Record::reset(string $column, mixed $value = null): void
```
**Migration:** Rename your method, or adopt the new signature. Note `Record::reset()` calls `save()`, as `increment()` and `decrement()` already did.

### `PredicateSet::render()` no longer emits a leading conjunction
**Severity:** Low · **Bug fix** — **Affects:** predicate sets containing only nested sets

```sql
-- before:  WHERE  AND ((a = 1) OR (b = 2))
-- after:   WHERE ((a = 1) OR (b = 2))
```
**Migration:** None, but SQL-string assertions in tests will change.

---

## pop-debug

**Scope:** The handler contract moved out to `Pop\Utils\DebuggerHandlerInterface`, the logger type switched from `Pop\Log\Logger` to PSR-3 `LoggerInterface`, and `RequestHandler` now redacts sensitive data by default.
**Break count:** 4 (3 high, 1 medium, 0 low)

### `Pop\Debug\Handler\HandlerInterface` deleted
**Severity:** High — **Affects:** any app that implements, type-hints, or `instanceof`-checks the handler interface
The file is removed outright and is not replaced by an alias or stub in the `Pop\Debug\Handler` namespace. The contract now lives in `pop-utils` as `Pop\Utils\DebuggerHandlerInterface` (new in `pop-utils` 3.0), with the same method set except that `setLogger()`/`getLogger()` use `Psr\Log\LoggerInterface` and self-returning methods declare `: DebuggerHandlerInterface`.

```php
// before
use Pop\Debug\Handler\HandlerInterface;
use Pop\Log\Logger;
class MyHandler implements HandlerInterface {
    public function setLogger(Logger $logger): HandlerInterface { /* ... */ }
}

// after
use Pop\Utils\DebuggerHandlerInterface;
use Psr\Log\LoggerInterface;
class MyHandler implements DebuggerHandlerInterface {
    public function setLogger(LoggerInterface $logger): DebuggerHandlerInterface { /* ... */ }
}
```
**Migration:** Replace every reference with `Pop\Utils\DebuggerHandlerInterface`, or better, extend `Pop\Debug\Handler\AbstractHandler` (which the v7 README now recommends) — it satisfies the whole contract and leaves only `prepare()`, `prepareMessage()` and `log()` abstract. Note `StorageInterface::save()` still type-hints the concrete `AbstractHandler`, so a hand-rolled interface implementation that is not an `AbstractHandler` will pass `addHandler()` but fatal on `$debugger->save()`.

### `Debugger` handler type-hints changed to `DebuggerHandlerInterface`
**Severity:** High — **Affects:** custom handlers, subclasses of `Debugger`, and `ArrayAccess` writes
`addHandler()`, `getHandler()`, the constructor dispatch, and `offsetSet()`'s guard all key off the new interface. A handler that only implemented the v6 interface is rejected with a `TypeError` (or a `Pop\Debug\Exception` whose message text also changed).

**Migration:** Make custom handlers implement `Pop\Utils\DebuggerHandlerInterface`. Any `Debugger` subclass overriding those methods with the old types must be retyped or it fatals on load.

### `RequestHandler::prepare()` now redacts sensitive data by default
**Severity:** High — **Affects:** anything reading request-handler output — stored CSV/TSV/DB rows, log context, or direct `prepare()` calls
With an unchanged signature, `prepare()` now returns `[REDACTED]` for values whose key matches a built-in list (`pass`, `pwd`, `secret`, `token`, `apikey`, `authorization`, `auth`, `cookie`, `csrf`, `sessionid`, `ssn`, `pin`, …, matched case- and separator-insensitively as a **substring**, recursively), and blanket-redacts **every** value in `cookie` and `session`.

```php
// before
$data['headers']['Authorization']; // 'Bearer abc123'
$data['session']['user_id'];       // 42

// after — default
$data['headers']['Authorization']; // '[REDACTED]'
$data['session']['user_id'];       // '[REDACTED]'

// after — opt back out / customize
$handler->setRedactSensitiveData(false);
$handler->setRedactedKeys(['password', 'pin']);
```
**Migration:** Call `setRedactSensitiveData(false)` to restore v6 output, or narrow the list. Be aware substring matching is broad — a key like `authors` matches `auth`. `QueryHandler` is deliberately **not** redacted, so bound query params (including passwords) are still captured in the clear.

### Logger type widened from `Pop\Log\Logger` to `Psr\Log\LoggerInterface`
**Severity:** Medium — **Affects:** subclasses that override `setLogger()`/`getLogger()`, and typed code holding the return of `getLogger()`
Callers passing a `pop-log` `Logger` are fine (`pop-log` 5's `Logger` implements `LoggerInterface`; `pop-log` 4's did **not** — another reason the `pop-log` major bump is mandatory). Handler subclasses that redeclared `setLogger(Logger $logger)` now fatal on the contravariance violation.

**Migration:** Retype overrides to `LoggerInterface`; add an `instanceof Pop\Log\Logger` narrowing check where you need pop-log-specific API.

---

## pop-dir

**Scope:** The public API surface is fully preserved and `getFiles()` output is byte-identical, but `getTree()`, `copyTo()`, option coercion and exception types all changed observable behavior.
**Break count:** 7 (1 high, 3 medium, 3 low)

### `getTree()` no longer returns a nested tree unless `recursive` is enabled
**Severity:** High — **Affects:** any code calling `getTree()` on a `Dir` built without `['recursive' => true]` (the default)
In v6 `buildTree()` always recursed regardless of the flag. In v7 the tree is built by the same walk as the file list, so subdirectory keys are present but map to an empty array. No error is raised — consumers silently see an empty subtree.

```php
// before
$dir = new Dir('my-dir');
$dir->getTree();
// ['/abs/my-dir' => ['file1.txt', '/sub' => ['file3.txt', '/deep' => ['file4.txt']]]]

// after
// ['/abs/my-dir' => ['file1.txt', '/sub' => []]]     <-- subtree no longer expanded
```
**Migration:** Pass `['recursive' => true]` wherever a nested `getTree()` result is expected. Note this also changes `getFiles()`/iteration to include descendants, so callers wanting the old tree *and* the old flat list need two `Dir` instances.

### `copyTo()` with a separator-less path now nests the source folder under the destination
**Severity:** Medium — **Affects:** `copyTo($dest)` (default `$full = true`) on a `Dir` constructed from a bare directory name
Old code only assigned `$folder` when the path contained a separator, so a bare name left it undefined (emitting a warning) and coerced to `''` — the copy landed directly in the destination. New code uses `basename()` unconditionally.

```php
// before — cwd contains "src/"
(new Dir('src'))->copyTo('/dest');
// /dest/a.txt, /dest/sub/b.txt      (flat, plus PHP warnings)

// after
// /dest/src/a.txt, /dest/src/sub/b.txt
```
**Migration:** Pass `false` as the second argument to keep the old flat-copy result, or update downstream paths.

### Non-`bool` values in the constructor `$options` array now throw `TypeError`
**Severity:** Medium — **Affects:** callers passing `1`/`0`/`'1'` for `absolute`, `relative`, `recursive` or `filesOnly`
`declare(strict_types=1)` means the constructor's internal calls to the typed setters are strict even when the consumer's file is not.

```php
// before
$dir = new Dir('my-dir', ['recursive' => 1]);   // coerced to true

// after — TypeError: must be of type bool, int given
$dir = new Dir('my-dir', ['recursive' => true]);
```
**Migration:** Pass real booleans in the options array. Direct setter calls from a non-strict consumer file are unaffected — only the constructor path is strict.

### `emptyDir()` gained a third `$followSymlinks` parameter
**Severity:** Low — **Affects:** subclasses that override `emptyDir()` with the two-parameter signature
The signature is now `emptyDir(bool $remove = false, ?string $path = null, bool $followSymlinks = false)`. An override declaring only the first two parameters is incompatible with the parent and fatals at class-declaration time.

Default behavior is unchanged from v6: a symlinked subdirectory is unlinked, leaving its target intact. Passing `true` opts into the destructive form, which recurses through the link and empties the *target* directory — so use it only when you mean it.

```php
// work/link is a symlink to /elsewhere/target

(new Dir('/path/work'))->emptyDir();                    // link removed, target untouched (v6 behavior)
(new Dir('/path/work'))->emptyDir(false, null, true);   // /elsewhere/target/* DELETED
```
**Migration:** Add the third parameter to any `emptyDir()` override. Callers need no change.

### Unreadable-directory failures now throw `Pop\Dir\Exception` instead of `\UnexpectedValueException`
**Severity:** Medium — **Affects:** callers catching `\UnexpectedValueException` around `new Dir(...)`
The original SPL exception is preserved as `$e->getPrevious()`. The exception can now also surface from the option setters, not just the constructor.

**Migration:** Catch `Pop\Dir\Exception` (or `\Exception`).

### Option setters now re-scan the directory immediately
**Severity:** Low — **Affects:** code calling `setAbsolute()`/`setRelative()`/`setRecursive()`/`setFilesOnly()` after construction
In v6 traversal ran once at the end of the constructor, so post-construction setter calls left `getFiles()`/`getTree()` stale. Each setter now calls `rebuild()`, can throw, and re-walks the filesystem.

```php
// before
$dir->setRecursive(true);
$dir->getFiles();   // ['file1.txt', 'sub']            <-- flag ignored

// after
$dir->getFiles();   // ['file1.txt', 'sub', 'file3.txt', 'deep', 'file4.txt']
```
**Migration:** Generally the intended fix, but pass options to the constructor instead if you chain several setters in a hot loop.

### `offsetGet()` / `__get()` by filename now returns the entry instead of `null`
**Severity:** Low · **Bug fix** — **Affects:** code reading `$dir['some-name']` by name rather than numeric index
v6's `offsetGet()` did no name-to-index resolution (only `offsetExists()` and `offsetUnset()` did), so a name lookup always returned `null` even when `isset()` reported `true`.

**Migration:** Any code branching on a `null` return from a name-keyed read now inverts; switch to `isset($dir[$name])` / `fileExists($name)`.

---

## pop-dom

**Scope:** `declare(strict_types=1)` added throughout; `Child::render()` now HTML-escapes attribute values and no longer mutates node state; parse/return types widened.
**Break count:** 6 (3 high, 0 medium, 3 low)

### Attribute values are now HTML-escaped on render
**Severity:** High — **Affects:** every rendered element with an attribute containing `& " ' < >` — ripples through `pop-form` and `pop-nav` output
`Child::render()` changed to `htmlspecialchars((string)$value, ENT_QUOTES)`. This is a genuine XSS fix, but it changes markup for existing callers and **double-escapes values that were already escaped** — the normal v6 pattern, since v6 emitted attributes raw. `pop-nav` sets `href` verbatim from user config and `pop-form` sets `value` verbatim from user data, so any `&amp;`-authored href or pre-escaped field value now renders with a visible `&amp;amp;`.

```php
// before
$a = new Child('a', 'link');
$a->setAttribute('href', '/page?a=1&amp;b=2');
echo $a;  // <a href="/page?a=1&amp;b=2">link</a>

// after
echo $a;  // <a href="/page?a=1&amp;amp;b=2">link</a>
```
**Migration:** Stop pre-escaping attribute values — pass raw strings and let `Child` escape them. Update output-comparison fixtures containing `&`, quotes or angle brackets in attributes.

### `setNodeValue()` now throws TypeError for non-string values
**Severity:** High — **Affects:** any caller passing an int, float, bool, or Stringable
The method body is byte-identical, but `strict_types` makes the assignment to the `?string $nodeValue` typed property strict. The `mixed` signature still advertises that any value is accepted.

```php
// before
$td->setNodeValue($row['count']);  // int 42 -> '42', renders <td>42</td>

// after — TypeError: Cannot assign int to property Pop\Dom\Child::$nodeValue of type ?string
$td->setNodeValue((string)$row['count']);
```
**Migration:** Cast at the call site.

### `getAttribute()` now throws TypeError when the attribute was stored as a non-string
**Severity:** High — **Affects:** code that sets numeric/bool attributes then reads them back — hit by `pop-form`'s own element classes
`setAttribute(string $name, mixed $value)` still stores the raw value, but `getAttribute(): ?string` returning it is now strict. Rendering is unaffected (render casts), so the failure appears only on read-back. Real occurrences in v6 consumers: `pop-form/src/Fieldset.php` (`setAttribute('colspan', 2)`) and `RadioSet`/`CheckboxSet` (`'value' => $k`, an int for integer-keyed value arrays).

```php
// before
$input->setAttribute('size', 10);
echo $input->getAttribute('size');  // '10'

// after — TypeError: Return value must be of type ?string, int returned
```
**Migration:** Always store attribute values as strings.

### `setAsCData()` no longer mutates the node value
**Severity:** Low — **Affects:** code reading `getNodeValue()` after rendering a CDATA node, or rendering one twice
v6 assigned the wrapper back onto `$this->nodeValue` during render, so a second render nested the wrapper.

### Invalid-UTF-8 attribute values now render as an empty string
**Severity:** Low — **Affects:** attributes fed from non-UTF-8 or binary-ish data
The escaping call passes `ENT_QUOTES` explicitly, dropping PHP 8.1+'s default `ENT_SUBSTITUTE`, so `htmlspecialchars()` returns `''` on malformed UTF-8 — silent data loss rather than a mangled byte.

**Migration:** Normalize attribute values to valid UTF-8.

### `parseFile()` / `parseString()` return types widened to `Child|array|null`
**Severity:** Low — **Affects:** callers typed against the old `Child` return, and subclasses overriding these statics
Element-less input that previously fataled now returns `null`, and `parseFile()` on a fragment returns the array instead of throwing — so failures move from inside `pop-dom` to the call site.

**Migration:** Use `addChildren()` (accepts `Child` or array) and null-check the result.

---

## pop-filter

**Scope:** No classes added or removed, but the `FilterInterface` contract moved and nested-array filtering behavior changed.
**Break count:** 2 (0 high, 2 medium, 0 low)

### `FilterInterface` gained a required method `removeCallable()`
**Severity:** Medium — **Affects:** any class implementing `Pop\Filter\FilterInterface` directly rather than extending `AbstractFilter`
Existing direct implementers become abstract-incomplete and fatal at class-declaration time. No direct implementers exist in-tree, so this bites third-party code only — but adding a required method to a published interface is a break regardless.

```php
// before — valid
class MyFilter implements \Pop\Filter\FilterInterface { /* 13 methods */ }

// after — Fatal error: contains 1 abstract method (removeCallable)
```
**Migration:** Implement `removeCallable()`, or extend `Pop\Filter\AbstractFilter`.

### `AbstractFilter::filter()` now recurses into nested arrays instead of passing them to the callable
**Severity:** Medium — **Affects:** filters applied to arrays nested 2+ levels deep, including inbound request data via `pop-http` `Server\Data`/`Server\Request`
Same signature, changed behavior. In v6 each element was passed straight to the callable; if that element was itself an array, a string callable such as `strip_tags` received an array and threw `TypeError`. v7 recurses, so leaves are filtered instead. Security-wise this is an improvement — deeply nested `$_POST`/`$_GET` structures that previously fataled (or, with an array-tolerant callable, passed through unsanitized) are now sanitized. The break is for custom callables that deliberately accept arrays.

```php
// before — callable receives the nested array
$filter = new Filter(fn($v) => is_array($v) ? implode(',', $v) : $v);
$filter->filter(['a' => ['x', 'y']]);   // => ['a' => 'x,y']

// after — callable receives each leaf scalar; recursion happens first
$filter->filter(['a' => ['x', 'y']]);   // => ['a' => ['x', 'y']]
```
**Migration:** Rewrite array-aware filter callables as scalar-leaf callables, or move the array handling into the consuming class's own `filter()` loop.

---

## pop-form

**Scope:** CAPTCHA element removed entirely, CSRF element rewritten (per-field session tokens, CSPRNG, timing-safe compare), `declare(strict_types=1)` added to every file (which makes `Fields::create()` reject loosely-typed config values), checkbox/radio sets refactored onto a new `AbstractInputSet`, and ARIA attributes injected into rendered markup.
**Break count:** 11 (5 high, 1 medium, 5 low)

### `Element\Input\Captcha` and the `captcha` field type were removed
**Severity:** High — **Affects:** any form using `'type' => 'captcha'` or `new Captcha(...)`
The class is deleted, the `case 'captcha':` branch is gone from `Fields::create()`, and the `captcha`/`answer` config keys are no longer read. A config using that type falls through to the default branch and throws `Pop\Form\Exception`. The README states there is no replacement (it suggests a honeypot field instead).

```php
// before
$fields = ['cap' => ['type' => 'captcha', 'captcha' => '4 + 4', 'answer' => '8']];

// after — throws Pop\Form\Exception: That class for that form element (captcha) does not exist.
// Pop\Form\Element\Input\Captcha no longer exists
```
**Migration:** Remove all `captcha` fields. Implement a honeypot or third-party CAPTCHA; port the old class into your app if you need it verbatim (note it used `eval()`).

### `declare(strict_types=1)` in `Fields.php` turns coerced config scalars into `TypeError`
**Severity:** High — **Affects:** any `Form::createFromConfig()` / `Fields::create()` config with non-exact scalar types
`strict_types` applies at the *call site* file, and `Fields.php` now has it, so every internal call it makes into element constructors and setters is strict. The `range` type is the worst case: `Fields::create()` still defaults `$min`/`$max` to `false` and passes them into `Range::__construct(string $name, int $min, int $max, ...)`.

```php
// before — worked (false coerced to int 0)
$fields = ['vol' => ['type' => 'range']];
$fields = ['q'   => ['type' => 'text', 'required' => 1]];
$fields = ['tok' => ['type' => 'csrf', 'expire' => '600']];

// after — TypeError on each
$fields = ['vol' => ['type' => 'range', 'min' => 0, 'max' => 100]];
$fields = ['q'   => ['type' => 'text', 'required' => true]];
$fields = ['tok' => ['type' => 'csrf', 'expire' => 600]];
```
**Migration:** Audit form config arrays and give every key its exact declared type — real booleans for `required`/`disabled`/`readonly`, real ints for `min`/`max`/`expire`/`max-size`, strings for `label`/`value`.

### CSRF: a supplied `value` now becomes the literal token instead of a random seed
**Severity:** High — **Affects:** any code constructing `Csrf` with a value, or a `csrf` config entry with a `'value'` key
In v6 `createNewToken()` always produced a random token and the passed `$value` was only extra entropy. In v7 it is `'value' => $value ?? bin2hex(random_bytes(32))`, so a supplied value *is* the token — a config that sets a value now installs a fixed, attacker-guessable CSRF token. Token length also changes from a 40-char sha1 to a 64-char hex string.

```php
// before
new Csrf('token', 'my-seed');   // renders value="<random sha1>"

// after
new Csrf('token', 'my-seed');   // renders value="my-seed" — that IS the token now
```
**Migration:** Never pass a `value` to `Csrf` or set `'value'` on a `csrf` field config. Widen any DB column storing the token to at least 64 chars.

### CSRF session storage layout changed from a serialized string to a per-field-name array
**Severity:** High — **Affects:** live sessions across the upgrade, and any code reading `$_SESSION['pop_csrf']`
v6 stored `$_SESSION['pop_csrf'] = serialize($token)` — one global token. v7 stores `$_SESSION['pop_csrf'][$fieldName] = $token` as a plain array. On deploy, every existing session's serialized string is discarded, so in-flight submissions fail with "The security token does not match."

```php
// before
$token = unserialize($_SESSION['pop_csrf']);

// after
$token = $_SESSION['pop_csrf']['my_csrf_field'];
```
**Migration:** Update direct `$_SESSION['pop_csrf']` access to the name-keyed, non-serialized shape. Expect one round of token mismatches at deploy; consider forcing a session regeneration.

### `FormValidator::validate($fields)` now actually enforces required fields
**Severity:** High · **Bug fix** — **Affects:** any `FormValidator` usage that passes a field subset to `validate()`
The v6 subset branch iterated values instead of keys, so the required-field check essentially never fired. v7 iterates `$this->required` properly, so validations that silently passed in v6 will now fail.

```php
// before
$v->setRequiredFields(['email']);
$v->validate(['email']);   // returned true even with 'email' absent

// after
$v->validate(['email']);   // returns false; getErrors('email') => 'This field is required.'
```
**Migration:** Ensure all required fields are present in the submitted values, or drop them from `setRequiredFields()`.

### Rendered form HTML now carries ARIA attributes and element ids
**Severity:** Medium — **Affects:** HTML snapshot tests, CSS/JS that selects error/hint markup
The new `Fieldset::prepareFieldAccessibility()` runs on every field, mutating the field itself (`aria-describedby`, `aria-invalid="true"`, removed when clean) and stamping ids on generated containers.

```php
// before output
<input type="text" name="username" id="username" required="required" />
<div class="error">This field is required.</div>

// after output
<input type="text" name="username" id="username" required="required" aria-invalid="true" aria-describedby="username-error" />
<div class="error" id="username-error" role="alert"><span>This field is required.</span></div>
```
**Migration:** Update HTML fixtures and any selectors that assumed `div.error` had no `id`/`role`. Note fields are now mutated during `prepareForView()`, so a re-render after a clean validation strips previously-set ARIA attributes you may have added yourself.

### `Fields::create()` no longer resolves arbitrary `Element\Input\*` classes by type string
**Severity:** Low — **Affects:** configs using an input type not on the new allowlist
v6 built `'Pop\Form\Element\Input\' . ucfirst(strtolower($type))` and instantiated it if it existed. v7 uses a private allowlist of 15 types. Because PHP class names are case-insensitive, v6 accepted e.g. `'datetimelocal'` and even `'exception'`; those now throw.

**Migration:** Use canonical type strings (`datetime-local`, `number`, `range`, `datalist`, `csrf`, …) or build the element object directly.

### The CSRF validator is now a `Closure` instead of a `Pop\Validator\Equal` instance
**Severity:** Low — **Affects:** code inspecting `$csrfElement->getValidators()`

**Migration:** Stop introspecting the CSRF element's validators; rely on `validate()`/`getErrors()`.

### `Form::setAction()` no longer strips `?captcha=1` / `&captcha=1`
**Severity:** Low — **Affects:** apps that relied on the CAPTCHA-refresh query cleanup

### `Form::clearTokens()` no longer clears `$_SESSION['pop_captcha']`
**Severity:** Low — **Affects:** apps upgrading with stale CAPTCHA session data

### `CheckboxSet::$checkboxes` / `RadioSet::$radios` replaced by `AbstractInputSet::$inputs`
**Severity:** Low — **Affects:** subclasses of `CheckboxSet` / `RadioSet`
`setLegend`/`getLegend`/`setContainer`/`getContainer`/`getFieldsetChildNodes`/`validate`/`render` also moved up to the abstract parent.

**Migration:** Rename `$this->checkboxes` / `$this->radios` to `$this->inputs` in subclasses.

---

## pop-http

**Scope:** Full PSR-7/17/18 conformance retrofit (headers, bodies, URIs, immutable `with*()`), a new native `Pop\Http\Body` stream class replacing `Pop\Mime\Part\Body`, a middleware pipeline + mock handler, a rewritten `Client` that drops its options-array shadow store, and a rewritten `Server\Data` input pipeline.
**Break count:** 19 (5 high, 9 medium, 5 low)

### `getHeader()` / `getHeaders()` changed meaning; the old object-returning methods were renamed
**Severity:** High — **Affects:** any code reading headers off `Client\Request`, `Client\Response`, `Server\Request` or `Server\Response`
These names are now the PSR-7 ones, which changes what they return. **`getHeader()` always returns an array of strings — even for a single-valued header**, because PSR-7 makes every header an array (HTTP allows repeats, as with `Set-Cookie`). So `getHeader('Content-Type')` gives `['application/json']`, not `'application/json'`. Use **`getHeaderLine()`** when you want the string. `getHeaders()` likewise returns `[name => string[]]`, and both do a case-insensitive lookup, returning an empty array when the header is absent.

The old `Pop\Mime\Part\Header`-returning methods keep their behavior under new names: `getHeaderObject()` / `getHeaderObjects()`.

```php
// before
$header = $response->getHeader('Content-Type');   // Pop\Mime\Part\Header
echo $header->getValue(0);
foreach ($response->getHeaders() as $h) { echo $h->getName(); }

// after
$header = $response->getHeaderObject('Content-Type');  // Pop\Mime\Part\Header
echo $header->getValue(0);
foreach ($response->getHeaderObjects() as $h) { echo $h->getName(); }
// or, PSR-7 style:
$values = $response->getHeader('Content-Type');        // ['application/json']  <- array, always
$line   = $response->getHeaderLine('Content-Type');    // 'application/json'    <- string
```
**Migration:** Rename every `getHeader()`/`getHeaders()` call that expected `Header` objects to `getHeaderObject()`/`getHeaderObjects()`. Anywhere you want a header as a plain string, use `getHeaderLine()` — assigning `getHeader()` straight into a string context now yields `Array`. `Parser::parseHeaders()` still returns `Header` objects under its `'headers'` key, unchanged.

### Body objects moved from `Pop\Mime\Part\Body` to `Pop\Http\Body`
**Severity:** High — **Affects:** any code type-hinting, `instanceof`-checking or constructing request/response bodies
`AbstractRequestResponse`, `AbstractResponse`, `RequestResponseInterface`, `Server\Request` and `Client` all now use `Pop\Http\Body`, a new class implementing `Psr\Http\Message\StreamInterface` and backed by a stream rather than a string. It is not related by inheritance to `Pop\Mime\Part\Body`, so passing the old type into `setBody()` is a `TypeError`.

```php
// before
use Pop\Mime\Part\Body;

// after
use Pop\Http\Body;
```
**Migration:** Change the import. Note `Pop\Mime\Part\Body` itself also changed in `pop-mime` v3 (its `$encoding` is now an enum) — that is why the class could not simply be reused.

### A JSON/XML POST body no longer populates `$_POST` / `getPost()`
**Severity:** High — **Affects:** server-side code reading a JSON POST body via `getPost()`
`processData()` previously ended by copying parsed data into `$this->post`, so a JSON POST body silently showed up in `getPost()`. It now trusts PHP's native parse for POST and only writes the decoded body to `$parsedData` (and to `$put`/`$patch`/`$delete` for those methods).

```php
// before — POST with Content-Type: application/json, body {"name":"x"}
$request->getPost('name');       // 'x'

// after
$request->getPost('name');       // null  ($_POST is empty for a JSON body)
$request->getParsedData('name'); // 'x'
```
**Migration:** Switch to `getParsedData()` (or `getParsedBody()`, new per PSR-7) for any non-form request body.

### `getQueryData()` / `hasQueryData()` are permanently neutered
**Severity:** High — **Affects:** server code using the query-data accessors
The `QUERY_STRING` re-parse that populated `Server\Data::$queryData` was removed. Both methods remain (now `@deprecated`) but always return `null` / `false` — a silent failure, not a fatal.

```php
// before
$id = $request->getQueryData('id');   // value from QUERY_STRING

// after
$id = $request->getQuery('id');       // reads $_GET
```
**Migration:** Replace all `getQueryData()`/`hasQueryData()` calls with `getQuery()` / `!empty(getQuery())`.

### Pre-encoded JSON string data is now re-encoded (double-encoded)
**Severity:** High — **Affects:** clients that hand an already-serialized JSON string to `setData()` on a JSON request
`Client\Data::prepareJson()` dropped its "only encode if not already encoded" check. Pass-through now requires the request's raw-data flag.

```php
// before
$client = new Client('http://x/', ['type' => Request::JSON]);
$client->setData('{"a":1}');
// body on the wire: {"a":1}

// after — same code sends: "{\"a\":1}"
$client = new Client('http://x/', ['type' => Request::JSON, 'raw_data' => true]);
$client->setData('{"a":1}');
```
**Migration:** Either pass the data as an array and let the client encode it, or set the `raw_data` option / `$request->setRawData(true)`.

### `Client`'s options array is no longer a parallel data store; data setters materialize a `Request`
**Severity:** Medium — **Affects:** clients configured via the `$options` array, or inspected before `send()`
The client now adds `syncRequestFromOptions()`, materializes a `Client\Request` on demand, and every getter reads the request only. `removeAllHeaders()`/`removeAllData()`/`removeType()`/`removeFile()` no longer also clear the corresponding option key.

```php
// before
$client = new Client(['headers' => ['Accept' => 'text/json']]);
$client->getHeaders();   // ['Accept' => 'text/json']  (raw array)
$client->hasRequest();   // false

// after
$client->getHeaders();   // ['Accept' => Pop\Mime\Part\Header]
$client->hasRequest();   // true  (constructor materialized one)
```
**Migration:** Expect `Header` objects and request-shaped data from the getters; don't rely on `hasRequest()` being false after calling a setter.

### `Client::getRequest()` / `getResponse()` now throw instead of returning a null-ish value
**Severity:** Medium — **Affects:** code calling these before a request/response exists, and `Client` subclasses
Both are overridden with narrowed return types and throw `Pop\Http\Exception` when the object isn't there.

```php
// after
if ($client->hasResponse()) { $r = $client->getResponse(); }
```
**Migration:** Guard with `hasRequest()`/`hasResponse()`, and update any `Client` subclass overriding these to the narrowed return types.

### `Parser::parseDataByContentType()` rewritten: stricter type matching, new exceptions, charset conversion
**Severity:** Medium — **Affects:** anything calling the parser directly, and `Client\Response::getParsedResponse()` / `Server\Request` auto-negotiation
Matching moved from `str_contains($contentType, 'xml'|'json')` to a structured `parseMediaType()` split. Consequences: `+xml` suffix types (`application/xhtml+xml`, `image/svg+xml`) are **no longer** XML-parsed; malformed XML now throws `Pop\Http\Exception`; `multipart/form-data` without a `boundary=` throws; a non-UTF-8 `charset=` triggers `mb_convert_encoding()`.

```php
// before
Parser::parseDataByContentType($svg, 'image/svg+xml');   // array (simplexml)
Parser::parseDataByContentType('<broken', 'text/xml');   // false / nonsense

// after
Parser::parseDataByContentType($svg, 'image/svg+xml');   // raw string
Parser::parseDataByContentType('<broken', 'text/xml');   // throws Pop\Http\Exception
```
**Migration:** Wrap parser calls in `try/catch` and stop expecting `+xml` structured-suffix types to be auto-parsed.

### Multipart request bodies are no longer buffered into `getDataContent()`
**Severity:** Medium — **Affects:** code inspecting the prepared body of a multipart request
`prepareMultipart()` is now lazy/zero-copy: it only mints a boundary and sets `Content-Type`, explicitly setting `$dataContent = null`. Curl receives a native array with `CURLFile`s; Stream renders the string itself.

```php
// before
echo $client->getRequest()->getDataContent();   // full rendered multipart body

// after
echo $client->getRequest()->getDataContent();   // null
echo $client->render();                          // rendered request incl. multipart body
```
**Migration:** Use `Client::render()` for inspection, or `Pop\Http\Body\Multipart::build($data, $boundary)`.

### Client file uploads now live in the request's data, and `setFiles()` replaces instead of accumulating
**Severity:** Medium — **Affects:** code using `setFiles()`/`addFile()`/`getFiles()`/`getData()`
`addFile()` writes `['filename' => ..., 'contentType' => ...]` straight into the request data. `getFiles()` derives the old map back out.

```php
// before
$client->addFile('/tmp/a.txt');
$client->getData();   // null  (files not in data until prepare())

// after
$client->getData();   // ['file1' => ['filename' => '/tmp/a.txt', 'contentType' => 'text/plain']]
```
**Migration:** Use `getFiles()` for the filename map; don't assume `getData()` contains only your own non-file fields.

### `Client\Data` dropped the JSON/XML "load content from a file entry" heuristics
**Severity:** Medium — **Affects:** clients that attached a `.json`/`.xml` file and expected its contents to become the body

```php
// after — the file entry is encoded as a data field, not read
$client->setType(Request::JSON)->setData(json_decode(file_get_contents('/tmp/payload.json'), true));
```
**Migration:** Read the file yourself and pass the decoded array (or the raw string with `raw_data`).

### `Uri::getPort()` returns `?int` and now hides scheme-default ports
**Severity:** Medium — **Affects:** code reading the port off a `Uri`
`Pop\Http\Uri` now implements `Psr\Http\Message\UriInterface`, which requires a non-standard port to come back as an `int` and a scheme-default port (80 on `http`, 443 on `https`) to come back as `null`. The rule exists so `getPort()` answers "does a port need to appear in the URI string?" — which is what keeps `getAuthority()` rendering `x` rather than `x:443`.

Note `hasPort()` and `getPort()` can now disagree: `hasPort()` reports whether a port was set on the object at all, while `getPort()` applies the hiding rule. For `https://x:443/`, `hasPort()` is `true` and `getPort()` is `null`.

```php
// before
(new Uri('https://x:443/'))->getPort();   // '443'
(new Uri('http://x:8080/'))->getPort();   // '8080' (string)

// after
(new Uri('https://x:443/'))->getPort();   // null
(new Uri('http://x:8080/'))->getPort();   // 8080 (int)
```
**Migration:** Don't rely on `hasPort()` implying a non-null `getPort()`.

### `getMethod()` is no longer nullable on either request class
**Severity:** Medium — **Affects:** code null-checking the method, and subclasses overriding it
Both now default to `'GET'`.

**Migration:** Replace null checks with `hasMethod()` (client side) or an explicit `$_SERVER` check (server side).

### `HandlerInterface` gained three required methods
**Severity:** Medium — **Affects:** anyone implementing `Pop\Http\Client\Handler\HandlerInterface` directly
`getHttpVersion(): string`, `getUriObject(): Uri` and `prepare(Request $request, ?Auth $auth = null): HandlerInterface` were added.

**Migration:** Add the three methods, or extend `AbstractHandler` (which also gained a protected `?Request $request` property — rename any conflicting property).

### `Client\Handler\Exception` reparented and its constructor changed
**Severity:** Low — **Affects:** catch blocks and code constructing the exception directly
It now extends `Pop\Http\Client\Exception` (which implements `Psr\Http\Client\ClientExceptionInterface`), implements `NetworkExceptionInterface`, and gained `int $curlErrno = 0, ?RequestInterface $request = null`.

```php
// after — the broader catch now swallows handler errors too; reorder
catch (Pop\Http\Client\Handler\Exception $e)  { echo $e->getCurlErrno(); }
catch (Pop\Http\Client\Exception $e)          { }
```
**Migration:** Put the more specific `Handler\Exception` catch first.

### Multipart parsing switched to `Body\Multipart::parse()`
**Severity:** Low — **Affects:** code consuming a parsed multipart result
File parts now carry an extra `'contentType'` key, and indexed `name[N]` fields are supported.

### `Server\Request::isGet()` and friends now default to GET with no `REQUEST_METHOD`
**Severity:** Low — **Affects:** CLI/test contexts where `$_SERVER['REQUEST_METHOD']` is unset

**Migration:** Check `isset($request->getServer()['REQUEST_METHOD'])` if you needed the "no method at all" distinction.

### `Uri::hasUsername()` / `hasPassword()` were checking the wrong properties
**Severity:** Low · **Bug fix** — **Affects:** code that depended on the old buggy results
They previously returned `$this->query !== null` and `$this->fragment !== null` respectively.

### SSL options are only applied to `Curl`, `Stream` and `Mock` handlers
**Severity:** Low — **Affects:** custom handlers that implemented `setVerifyPeer()` / `allowSelfSigned()`
The `verify_peer` / `allow_self_signed` options are now silently ignored for any other handler.

**Migration:** Configure SSL directly on a custom handler, or extend `AbstractCurl`/`Stream`.

---

## pop-i18n

**Scope:** Internal refactor of `I18n` (hash-keyed catalog, extracted per-format loaders) plus XML/JSON output escaping; the public API surface is unchanged and the XML/JSON language-file format itself is byte-for-byte identical.
**Break count:** 9 (0 high, 3 medium, 6 low)

### `Format\Xml` now escapes values, double-escaping pre-escaped content
**Severity:** Medium — **Affects:** apps calling `Format\Xml::createFile()`/`createFragment()` that pre-escaped entities to work around v6's missing escaping
A new `escape()` runs `htmlspecialchars($v, ENT_XML1|ENT_QUOTES, 'UTF-8')` over every attribute and element value. v6 wrote values raw, so any app with `&`, `<` or `>` had to pre-escape; those entities now get escaped a second time and survive the round-trip as literal text.

```php
// before — hand-escaped input
// file: <output>Tom &amp; Jerry</output>   -> loads as "Tom & Jerry"

// after — same call
// file: <output>Tom &amp;amp; Jerry</output>
$lang->__('Tom & Jerry');   // "Tom &amp; Jerry"
```
**Migration:** Remove manual escaping from data passed to `Format\Xml` and pass raw strings; regenerate previously hand-escaped language files.

### `Format\Xml` escaping silently blanks non-UTF-8 values
**Severity:** Medium — **Affects:** apps whose translation source data is ISO-8859-1 or any non-UTF-8 encoding
`escape()` passes `ENT_XML1 | ENT_QUOTES` with no `ENT_SUBSTITUTE`/`ENT_IGNORE`, so `htmlspecialchars()` returns an empty string for invalid UTF-8. The generated file is still well-formed XML, so nothing throws — the translations are just gone.

```php
// before — latin-1 bytes passed through
// <language ... native="Fran<0xE7>ais">  ... <output>Bonjour <0xE7>a va</output>

// after
// <language ... native="">              ... <output></output>
```
**Migration:** Convert all `$lang`/`$locales` data to valid UTF-8 (`mb_convert_encoding`) before calling `createFile()`, and diff regenerated files for empty values.

### `Format\Xml::createFile()` throws `TypeError` on non-string values
**Severity:** Medium — **Affects:** callers passing ints, floats, or `Stringable` objects anywhere in `$lang` or `$locales`
`escape(string $value)` is called from within the same strict-types file, so the check applies regardless of the caller's own setting. This hits `src`, `output`, `name`, `native`, `region`, `source`, `alt`, and every output value. `Format\Json::createFile()` is unaffected.

**Migration:** Cast every value to `string` when building the arrays.

### Duplicate `<locale region>` blocks: last match no longer wins
**Severity:** Low · **Bug fix** — **Affects:** language files containing two locale entries with the same `region`
v6's search loop had no `break`, so the **last** matching locale won; v7 breaks on the **first**.

**Migration:** De-duplicate `region` values, or reorder so the intended block comes first.

### Duplicate `<source>` entries: first match no longer wins
**Severity:** Low · **Bug fix** — **Affects:** language files (or multiple files loaded into one instance) with a repeated `source` string
v7 keys `$content` by source string, so a later entry overwrites an earlier one and the **last** definition wins. This also changes multi-file loading.

### `Format\Json::createFile()` now throws on `text` entries missing `source`/`output`
**Severity:** Low — **Affects:** callers building `$locales` with incomplete rows
`Format\Xml::createFile()` already threw for the identical case, so this is a consistency fix.

### Numeric `$variation` against a plain-string output no longer returns one character
**Severity:** Low — **Affects:** `__()`/`_e()` calls passing an integer `$variation` for a source with no alternates
v6's `isset()` on a plain string was a string-offset check, so an integer variation returned a single character.

### Malformed JSON with a non-array `locale` now fatals instead of degrading
**Severity:** Low — **Affects:** JSON language files where `language.locale` is a single object rather than an array

**Migration:** Wrap the `locale` value in an array, per the documented format.

### `getLanguages()` now scans files whose name begins with `.xml`/`.json`
**Severity:** Low — **Affects:** directories containing files like `.xml.bak`
v6 used the truthy result of `stripos()`, so a match at offset 0 was skipped; v7 parses the file, and an unparseable one throws out of `getLanguages()` uncaught.

**Migration:** Keep non-language files out of the language directory.

---

## pop-image

**Scope:** Both GD and Imagick adapters survive intact; the only class removed is `Pop\Image\Captcha`, plus a PHP 8.4 floor, a `pop-color` major bump, and a silent Imagick draw/type opacity default change.
**Break count:** 2 (1 high, 0 medium, 1 low)

### `Pop\Image\Captcha` removed entirely
**Severity:** High — **Affects:** any app that generated CAPTCHA images or validated `$_SESSION['pop_captcha']`
The 473-line class is deleted along with its whole public API (`setUrl`, `setExpire`, `setAnswer`, `getImage`, `getImageHtml`, `getToken`, `createNewToken`, `createImage`, `random`, `__toString`, …), the `pop_captcha` session key and its serialized payload, and the config keys `adapter`, `width`, `height`, `lineSpacing`, `lineColor`, `textColor`, `font`, `size`, `rotate`. Nothing in the framework replaces it.

```php
// before
$captcha = new Pop\Image\Captcha('/captcha.php');
header('Content-Type: image/gif');
echo $captcha;
$token = unserialize($_SESSION['pop_captcha']);

// after — fatal: Class "Pop\Image\Captcha" not found
```
**Migration:** Vendor the v4.1.3 `Captcha.php` into your own application namespace (it only depends on `Pop\Image\Image` and `Pop\Color\Color`, both still present), or move to a third-party/hosted CAPTCHA. Any HTML template using `getImageHtml()` output or the `pop_captcha` session key must be reworked. **Note `pop-form`'s `captcha` field type was removed in the same release.**

### New `Exception` thrown for `ColorInterface` implementations without `toRgb()`
**Severity:** Low — **Affects:** apps passing a custom `ColorInterface` implementation into `pop-image`
`createColor()`, `getBlend()`, the Imagick gradient methods and `Filter\Imagick::skew()` now guard with `method_exists($color, 'toRgb')` and throw the namespace's own `Exception` instead of letting a fatal `Error` escape.

**Migration:** Give custom color classes a `toRgb(): Rgb` method, or catch the per-namespace `Exception` instead of `\Error`.

---

## pop-kettle

**Scope:** Kettle is re-architected from a `Pop\Module\Module` plugged into a generic `Pop\Application` into its own `Pop\Kettle\Application` class with a new `prepare()/load()/run()` bootstrap, a rewritten `kettle` script that no longer has an include hook, restructured scaffolding templates, a regrouped `web:*` command namespace, `app:*` renamed to `pop:*`, and a new `queue:*`/`create:command` command surface.
**Break count:** 18 (4 high, 10 medium, 4 low)

### `Pop\Kettle\Module` removed and replaced by `Pop\Kettle\Application`
**Severity:** High — **Affects:** any `kettle` script, custom console script, or code that registers Kettle as a module or reads `Module::VERSION`
`src/Module.php` is deleted; `Pop\Kettle\Application` now extends `\Pop\Application` directly, exposing typed constants `NAME`, `FULL_NAME`, `VERSION` plus new `prepare()`, `load()`, `getConsole()`.

```php
// before
$app = new Pop\Application($autoloader, include __DIR__ . '/config/app.console.php');
$app->register(new Pop\Kettle\Module());
$app->run();
echo Pop\Kettle\Module::VERSION;

// after
$app = new Pop\Kettle\Application($autoloader, $config);
$app->prepare()->load()->run();
echo Pop\Kettle\Application::VERSION;
```
**Migration:** Replace every `Pop\Kettle\Module` reference; Kettle can no longer be registered as a module into someone else's application object.

### `kettle` bootstrap script rewritten — old copies break
**Severity:** High — **Affects:** every project that copied `kettle` into its project root (the documented install step)
The script no longer constructs `Pop\Application` + `Module`, resolves paths with `__DIR__`, and now merges custom command routes through `Pop\Console\CommandRegistry::loadRoutes()` before instantiating the app. A v6 copy in a project root fatals on the missing `Module` class.

**Migration:** Re-copy `kettle` from `vendor/popphp/pop-kettle/kettle`, `chmod 755`, and re-apply the config-path edit documented in the README.

### `kettle.inc.php` removed — the include hook is gone entirely
**Severity:** High — **Affects:** every v6 project, since `app:init` wrote a `kettle.inc.php` into all of them
The v7 `kettle` script no longer looks for `kettle.inc.php`, and the packaged template is deleted. The file itself is left sitting in your project root, so nothing errors — it simply **stops being executed**. Everything it did is silently lost: the `addPsr4()` line that told Kettle about your app namespace, plus any routes, services or bootstrapping you added to it.

Losing the autoloader line is what you notice first — custom controllers and table-backed migration classes stop resolving, and `db:migrate` fails on a class it can no longer find.

```php
// before — kettle.inc.php, included by the kettle script
$app->router()->addRoute('my:cmd', [...]);
$autoloader->addPsr4('MyApp\\', __DIR__ . '/app/src');
```

```json
// after — composer.json
"autoload": { "psr-4": { "MyApp\\": "app/src/" } }
```
Autoloading is now Composer's job for every entry point at once — `kettle`, `public/index.php` and a stand-alone `script/<app>` all read the same generated autoloader, and the `addPsr4()` calls are gone from the scaffolded scripts too. Custom commands replace the routes half: classes under `app/src/Console/Command/Kettle` are discovered by `CommandRegistry::loadRoutes()` on every run.

**Migration:** Add your namespace to `composer.json` under `autoload.psr-4` (`"MyApp\\": "app/src/"`), run `composer dump-autoload`, move any custom routes to `create:command` classes, and delete `kettle.inc.php`. Re-running `pop:init` does the composer.json edit and the dump for you.

### Controller constructor params are no longer injected by Kettle
**Severity:** High — **Affects:** custom Kettle controllers reached through routes you added yourself
v6's `Module::register()` called `addControllerParams('*', ['application' => …, 'console' => new Console(120, '    ')])`. `Pop\Kettle\Application` does none of that; the v7 `popphp` router instantiates a dispatchable as `new $class($application)` only when the class uses `Pop\Dispatch\ConsoleTrait`, otherwise `new $class()`.

```php
// before — any controller got both params
class MyCtrl extends \Pop\Controller\AbstractController {
    public function __construct(Application $app, Console $console) { … }
}

// after — extend Kettle's AbstractController (which uses ConsoleTrait)
class MyCtrl extends \Pop\Kettle\Controller\AbstractController { … }
```
**Migration:** Extend `Pop\Kettle\Controller\AbstractController`, or add `Pop\Dispatch\ConsoleTrait` and give `$console` a default.

### `serve` renamed to `web:serve`
**Severity:** Medium — **Affects:** anyone running `./kettle serve`, and any script, Makefile, Procfile, container `CMD` or README that calls it
The built-in web server command moved under a new `web:` namespace, which also holds the new `web:watch`/`web:build` asset commands. There is no alias — the bare `serve` route is gone, so v6 invocations now print "Invalid Command" and exit without starting anything.

```bash
# before
./kettle serve --host=0.0.0.0 --port=8080

# after
./kettle web:serve --host=0.0.0.0 --port=8080
```
Flags are unchanged: `--host` still defaults to `localhost`, `--port` to `8000`, `--folder` to `public`.

**Migration:** Change `serve` to `web:serve` everywhere it's invoked. Grep for it outside your PHP source too — this one usually lives in tooling and docs rather than code.

### The `app:*` command namespace is now `pop:*`
**Severity:** Medium — **Affects:** every invocation of `app:init`, `app:env`, `app:status`, `app:down` or `app:up` — in scripts, Makefiles, deploy jobs, cron entries and docs
All five application-level commands moved namespace wholesale. There are no aliases, so a v6 invocation misses the route and prints "Invalid Command" without doing anything.

```bash
# before                # after
./kettle app:init       ./kettle pop:init
./kettle app:env        ./kettle pop:env
./kettle app:status     ./kettle pop:status
./kettle app:down       ./kettle pop:down
./kettle app:up         ./kettle pop:up
```
The one to check your deployment scripts for is `app:down` / `app:up` — a maintenance-mode wrapper that silently stops working leaves the site live through a deployment that assumed it was down. `--secret` on `pop:down` is otherwise unchanged; `pop:env` additionally gained a `--set` flag it did not have in v6.

`app:` was vacated deliberately: the scaffolded application's default namespace is now `App`, so the commands *you* write with `create:command` get to be `app:*` without colliding with Kettle's own.

**Migration:** `s/app:/pop:/` across your tooling for those five commands only — `create:*`, `db:*`, `migrate:*`, `queue:*` and `web:*` are untouched.

### `pop:init` takes no flags and no namespace argument
**Severity:** Medium — **Affects:** every scripted, documented or CI invocation of `app:init` — the first command in the v6 quick start
On top of the namespace rename above, the route dropped from `app:init [--web] [--api] [--cli] <namespace>` to a bare `pop:init`. Everything it used to take on the command line is now asked interactively, and the router matches the command exactly — so any leftover argument makes it miss the route entirely. `<namespace>` was **required** in v6, which means every invocation written against the v6 README breaks twice over.

```bash
# before
./kettle app:init --web --api MyApp

# after — no arguments; everything is prompted for
./kettle pop:init
```
The failure is loud rather than silent: you get Kettle's "Invalid Command" box and the `Try ./kettle help for help` hint, and nothing is scaffolded. The three application-type flags collapsed to a single yes/no question — `Is this a CLI-only application? [Y/N]` — because there is no longer a web-versus-API choice to make: a non-CLI install scaffolds one `Http\Controller` that answers both, picking HTML or JSON off the request's `Accept` header. `--web`, `--api` and `--web --api` are all just "N".

`Controller\ApplicationController::init()` lost both parameters to match (`init(?string $namespace, array $options = [])` → `init()`). A subclass still overriding it with the v6 signature is a fatal `Declaration must be compatible` error, since an override cannot introduce a required parameter the parent doesn't have.

**Migration:** Drop the flags and the namespace from every call and answer the prompts instead. There is no non-interactive equivalent — automation that needs one should call `Model\Application::init()` directly, which still takes all of it as arguments.

### `Event\Console::header()` and `::footer()` removed
**Severity:** Medium — **Affects:** anything that registered these as `app.route.pre` / `app.dispatch.post` listeners
Replaced by `maintenanceDisplay()` and `productionDisplay()`; banner printing moved into `Application::load()`.

```php
// before
$app->on('app.route.pre', 'Pop\Kettle\Event\Console::header')
    ->on('app.dispatch.post', 'Pop\Kettle\Event\Console::footer');

// after
$app->on('app.route.pre', fn() => Pop\Kettle\Event\Console::maintenanceDisplay($console));
```

### `Model\Application` / `Model\Database` now extend `Pop\Utils\AbstractModel` instead of `Pop\Model\AbstractModel`
**Severity:** Medium — **Affects:** anything extending or type-hinting `Model\Application` / `Model\Database`
`Pop\Kettle\Model\*` stays where it is — what changed is the class it extends, since `Pop\Model\AbstractModel` no longer exists and now lives in `pop-utils`.

**Migration:** Update any `Pop\Model\AbstractModel` type-hint or `instanceof` covering these models to `Pop\Utils\AbstractModel`.

### `Model\Application::init()` / `install()` re-signatured — `$env` is gone and the three type flags collapsed to one bool
**Severity:** Medium — **Affects:** programmatic scaffolding callers, and any subclass overriding either method
Two changes land on the same signature. `pop:init` no longer sets the environment, so `string $env` is gone — and it was **not** the last parameter, so everything after it shifts left. Separately, `?bool $web, ?bool $api, ?bool $cli` collapsed into a single `bool $cliOnly`, since a non-CLI install now scaffolds one `Http\Controller` that serves both HTML and JSON.

```php
// before
public function init(string $location, string $namespace, ?bool $web = null, ?bool $api = null, ?bool $cli = null,
    string $name = 'Pop', string $env = 'local', string $url = 'http://localhost'): void

// after
public function init(string $location, string $namespace, bool $cliOnly = false,
    string $name = 'App', string $url = '', bool $cliApp = false, bool $createDb = false,
    ?string $frontend = null): void
```
The dangerous case is positional. In a v6 call, argument 3 was `$web` and it now lands in `bool $cliOnly` — so `init($loc, $ns, true, ...)`, which meant "web app", now means **"CLI-only app"**: exactly inverted, both types check, and nothing warns. What happens to argument 4 (`$api`, a bool, now hitting `string $name`) depends on the calling file: `declare(strict_types=1)` makes it a `TypeError`, and a v6 file without it coerces the bool to `''` or `'1'` and names your app that.

`install()` changed the same way and is worse, because its first parameter changed type: `string $install` (the flavor name, e.g. `'web-api'`) became `bool $cliOnly`, so a v6 call fatals on argument 1 rather than misbehaving quietly. `Model\Application::resolveInstallType()`, which mapped the three flags onto a flavor string, is gone with it.

Alongside that: the three trailing parameters are new (`$frontend` being `'alpine'`, `'vue'`, `'react'` or `null`), defaults changed — `$name` `'Pop'` → `'App'`, `$url` `'http://localhost'` → `''` — and the template root is now `config/templates/codebase/cli` or `.../full`. A subclass that **overrides** either method also fatals until it matches the new signature.

**Migration:** Switch to named arguments — this signature has now moved twice, and positional calls are what make the failure silent. Pass `cliOnly: true` where you previously passed `cli: true` with no `web`/`api`; everything else is `cliOnly: false`. To set the environment, use `pop:env --set` or write `APP_ENV` yourself.

### `create:ctrl` lost its `--web` and `--api` flags, and `--cli` no longer also creates an HTTP controller
**Severity:** Medium — **Affects:** `./kettle create:ctrl` with any flag
The route went from `create:ctrl [--web] [--api] [--cli] <ctrl>` to `create:ctrl [--cli] <ctrl>`. `--web` and `--api` are no longer recognized, so passing either misses the route and prints "Invalid Command" — there is no separate `Http\Web\Controller` / `Http\Api\Controller` namespace left to target. A bare `create:ctrl <ctrl>` writes the one HTTP controller under `app/src/Http/Controller/`.

That matters even though your v6 app's existing `Http\Web` and `Http\Api` classes keep working untouched: you can no longer **generate** new controllers into those folders. Kettle only knows about the consolidated layout.

`--cli` changed too. v6 passed `false` for unset flags and gated on `empty()`, so a `--cli`-only invocation created **both** controllers; v7 gates on `null` and additionally throws if no `script/` folder exists.

`Model\Application::createController()` follows the route: `(string $ctrl, string $location, ?bool $web, ?bool $api, ?bool $cli): array` is now `(string $ctrl, string $location, ?bool $cli = null): string`. It returns the one class name it created rather than a list, so a v6 caller doing `foreach ($model->createController(...) as $class)` iterates the characters of a string.

**Migration:** Drop `--web`/`--api`; run `create:ctrl <ctrl>` for the HTTP controller and `create:ctrl --cli <ctrl>` for the console one, as two separate calls. Programmatic callers should stop treating the return value as an array.

### `create:model` generates different parent classes
**Severity:** Medium — **Affects:** `./kettle create:model` and `--data`
Plain models now use `Pop\Utils\AbstractModel`; `--data` models use `Pop\Db\Model\AbstractDataModel`.

### `db:config <name>` behaves differently for non-`default` databases
**Severity:** Medium — **Affects:** multi-database projects
v6 always rewrote the single `DB_*` keys regardless of `$database`. v7 **appends** `DB_<NAME>_*` keys to `.env` and rewrites `app/config/database.php` by string-replacing the closing `];`. It also now calls `Dotenv::createMutable(...)->safeLoad()`, mutating `$_ENV` in-process.

**Migration:** Expect new `DB_<NAME>_*` env keys; verify `app/config/database.php` after running, since the block is appended by naive string replacement.

### `Application::initDb()` now registers every database as a lazy service
**Severity:** Medium — **Affects:** code relying on Kettle eagerly connecting the default database
v6 only handled `$database['default']` and connected immediately. v7 loops every top-level entry, registers each as a service (`database` / `database_<key>`), and only calls `Record::setDb()` for `default`. Connections are now lazy.

**Migration:** Pull connections from `$app->services()['database']` rather than assuming an eager `Record` binding.

### Scaffolding template directory layout restructured — six install flavors became two
**Severity:** Low — **Affects:** only code referencing `config/templates/…` paths directly
v6 shipped one codebase template per flag combination: `web`, `api`, `web-api`, `web-cli`, `api-cli` and `web-api-cli`. v7 ships `cli` and `full`. `config/templates/script/myapp` is likewise now `config/templates/script/app`.

Nothing in your application reads these paths — they are Kettle's own scaffolding source — so this only bites code that reached into `vendor/popphp/pop-kettle/config/templates/` to copy or patch a template.


### `X_POP_CONSOLE_INPUT_2/3/4` prompt-override hooks removed
**Severity:** Low — **Affects:** external harnesses that drove Kettle prompts via `$_SERVER`
The init prompt sequence also changed, so any script feeding it canned answers is off by more than one. v6 asked four questions — app name, environment, URL, configure-a-database — with the namespace and install flavor supplied as command-line arguments. v7 asks up to seven, in this order: namespace, app name, CLI-only yes/no, URL (skipped for CLI-only), stand-alone CLI app, configure-a-database, install-a-front-end plus the framework choice (both skipped for CLI-only). The environment question is gone entirely.

**Migration:** Feed prompts through `Console::setInputStream()`, and re-record the answer sequence against a v7 `pop:init` run.

### `.env` template: `APP_NAME` default changed, and `APP_ENV` is no longer written
**Severity:** Low — **Affects:** re-running init over a pre-existing `.env`
`APP_NAME` defaults to the display name derived from your namespace — `App` if you accept every default — instead of `Pop`, and since `.env` is only copied when absent, an existing v6 `.env` will no longer have its app name substituted. `APP_ENV` is not substituted at all any more — a new app always starts at `local`, and `pop:env --set` changes it afterward. The template also gained `QUEUE_ADAPTER`, `QUEUE_PRIORITY`, `QUEUE_LEASE`, and the packaged file was renamed `orig.env` → `.env.example` (in the `popphp/framework` skeleton too, where it sits in your project root).

### `db:reset` uses `DELETE FROM` instead of `TRUNCATE` on SQLite
**Severity:** Low — **Affects:** `./kettle db:reset` against SQLite
Behavior differs on autoincrement counters, which `DELETE` does not reset.

---

## pop-log

**Scope:** `Pop\Log` becomes a PSR-3 logger — level values change from ints to strings across the whole API and every writer's output, the fluent logging API returns `void`, and the writer contract is re-typed.
**Break count:** 12 (6 high, 4 medium, 2 low)

### Log level constants changed from `int` to `string`
**Severity:** High — **Affects:** any code referencing `Logger::ERROR`, `Logger::INFO`, etc., or the integers 0–7, as stored/compared values
`Logger::EMERGENCY`…`Logger::DEBUG` were `int` 0–7; they are now the `Psr\Log\LogLevel::*` strings (`Logger::ERROR === 'error'`). Passing them into `log()` still works, but any comparison, array key, DB column, serialization, or arithmetic on them breaks.

```php
// before
if ($level === Logger::ERROR) { ... }   // 3
$severity = Logger::ERROR + 1;

// after
if ($level === Logger::ERROR) { ... }   // 'error'
$severity = Level::toSeverity(Logger::ERROR) + 1;
```
**Migration:** Treat the constants as opaque tokens. Use the new `Pop\Log\Level::toSeverity()` / `fromSeverity()` / `toName()` helpers wherever the numeric value was needed.

### All logging methods return `void` — fluent chaining removed
**Severity:** High — **Affects:** every chained logging call
PSR-3's `LoggerInterface` mandates `void` and PHP forbids narrowing it, so chained calls now fatal with "Call to a member function … on null".

```php
// before
$log->info('started')->debug('details')->alert('boom');

// after
$log->info('started');
$log->debug('details');
$log->alert('boom');
```
**Migration:** Split every chained logging call. `addWriter()`, `setLogLimit()` and `setTimestampFormat()` are still fluent.

### Every writer now emits the level as a string, not a number
**Severity:** High — **Affects:** any downstream parser, log shipper, dashboard, alerting rule, or DB query that reads the level column/field
`Logger::log()` now normalizes to the PSR-3 string before dispatch, so writers receive `'info'` where they used to receive `6`. The signature is unchanged from the caller's side — this is silent.

```php
// before — app.log
2015-07-11 12:32:32	6	INFO	Just a info message

// after — app.log
2015-07-11 12:32:32	info	INFO	Just a info message
```
**Migration:** Update log parsers/regexes, Grafana/Kibana queries, and any `WHERE level = 3` SQL. Historical log files/rows keep the old integers, so parsers must handle both.

### `Writer\Database` schema: `level` column changed from `INT(1)` to `VARCHAR(20)`
**Severity:** High — **Affects:** existing apps whose `pop_log` table already exists
`createTable()` only runs when the table is absent, so an upgraded app keeps its old `INT` column while the writer inserts `'info'`. Under MySQL strict mode this raises an insert error; otherwise the value is silently coerced to `0`.

**Migration:** Run `ALTER TABLE pop_log MODIFY level VARCHAR(20)` (and translate old integer rows) before deploying.

### `WriterInterface` / `AbstractWriter` re-typed — custom writers fatal
**Severity:** High — **Affects:** any application-defined writer
`writeLog()`'s `$level` narrowed from `mixed` to `string`, `setLogLimit()`/`isWithinLogLimit()` widened `int` → `string|int`, and `getLogLimit()` changed `int|null` → `string|null`. Any existing implementation is a fatal "Declaration must be compatible" error at class-load time. `AbstractWriter::$limit` also changed type from `?int` to `?string`.

**Migration:** Update every custom writer's signatures, and change internal numeric comparisons on `$level` to `Level::toSeverity($level)`.

### `getLogLimit()` returns a level string
**Severity:** High — **Affects:** code that reads back a configured limit
Setting still accepts ints, so `setLogLimit(3)` then `getLogLimit()` returns `'error'`, not `3`.

```php
// before
if ($writer->getLogLimit() > 4) { ... }

// after
if (Level::toSeverity($writer->getLogLimit()) > 4) { ... }
```

### `$message` narrowed from `mixed` to `string|Stringable`
**Severity:** Medium — **Affects:** callers passing non-strings (`null`, arrays, non-`Stringable` objects)
`null` and `array` arguments now raise `TypeError` even from non-`strict_types` caller files (ints/floats/bools still coerce).

```php
// before
$log->info($maybeNull);          // logged as ''
$log->error(['a' => 1]);         // logged as 'Array'

// after
$log->info($maybeNull ?? '');
$log->error(json_encode(['a' => 1]));
```

### `{placeholder}` interpolation now rewrites messages and consumes context keys
**Severity:** Medium — **Affects:** anyone logging messages containing literal `{...}` text, or reading the full context downstream
Every scalar/`Stringable` context key whose `{key}` appears in the message is substituted inline **and removed from `$context`**. Reserved keys `timestamp`, `name`, `format` are exempt. v6 did neither.

```php
// before
$log->info('User {user} failed', ['user' => 'bob']);
// message: "User {user} failed"   context: "user=bob;"

// after
// message: "User bob failed"      context: ""
```
**Migration:** Escape or rephrase messages that legitimately contain `{word}` matching a context key.

### Invalid levels now throw instead of degrading
**Severity:** Medium — **Affects:** code passing out-of-range or unknown level values
`getLevel()`/`getLogLevel()` returned `''` for an unknown int; they now throw `Psr\Log\InvalidArgumentException`. `log()` also validates the level up front (v6 emitted an undefined-index warning and carried on).

### `psr/log` is now a hard runtime dependency
**Severity:** Medium — **Affects:** every consuming app
If the app already pins `psr/log` `^1` or `^2` (common with older Monolog/Guzzle), resolve the conflict before upgrading.

### Writer exceptions: all writers now run, then the first failure is rethrown
**Severity:** Low — **Affects:** multi-writer setups and code relying on writes being best-effort
In v4 the first exception aborted the loop, skipping remaining writers. `Writer\File` can now also throw on `.xml`/`.json` files when it cannot obtain an exclusive `flock`, where v4 failed silently.

**Migration:** Expect duplicate-delivery-on-failure semantics; wrap `log()` calls in try/catch if a failing writer must not surface to the app.

### Exception class for invalid levels changed
**Severity:** Low — **Affects:** narrow catch blocks
Now `Psr\Log\InvalidArgumentException` (which extends `\InvalidArgumentException`, so broad catches still work) with different message text.

---

## pop-mail

**Scope:** `Pop\Mail\Message` and all message parts were re-based onto `Pop\Mime\Message`/`Pop\Mime\Part` (`pop-mime` 3.x), deleting the entire `Message\AbstractMessage` / `AbstractPart` / `MessageInterface` / `PartInterface` / `Simple` hierarchy and replacing part constructors, encoding constants, header accessors and render signatures.
**Break count:** 22 (11 high, 7 medium, 4 low)

### `Message` no longer extends `AbstractMessage` / implements `MessageInterface`
**Severity:** High — **Affects:** any code type-hinting, `instanceof`-checking, or extending the old message hierarchy
`Pop\Mail\Message` now extends `Pop\Mime\Message`. `Message\AbstractMessage`, `MessageInterface`, `AbstractPart`, `PartInterface` and `Simple` are all deleted.

```php
// before
function handle(\Pop\Mail\Message\MessageInterface $m) { ... }
$part = new \Pop\Mail\Message\Simple('raw content');

// after
function handle(\Pop\Mail\Message $m) { ... }          // or \Pop\Mime\Part for parts
$part = \Pop\Mail\Message\Text::create('raw content');
```
**Migration:** Replace all `MessageInterface` / `PartInterface` / `AbstractMessage` / `AbstractPart` / `Simple` references with `Pop\Mail\Message` or `Pop\Mime\Part`.

### `Message::getBody()` no longer returns the rendered body string
**Severity:** High — **Affects:** any code that read the assembled message body
In v6 `getBody(): ?string` returned the fully assembled (multipart) body. In v7 the method resolves to `Pop\Mime\Part::getBody(): Part\Body` — an object. Worse, `Part::$body` is `?Part\Body` while the return type is non-nullable, so on a message built from parts (the normal case) `getBody()` throws a `TypeError`.

```php
// before
$body = $message->getBody();          // string

// after
$body = $message->getBodyContent();   // ?string
```
**Migration:** Use the new `Message::getBodyContent(): ?string`.

### `Message::getHeader()` now returns a header object, not a string
**Severity:** High — **Affects:** any code reading a header value off a message
`Pop\Mail\Message` deliberately does not override `getHeader()`, so it inherits `Pop\Mime\Part::getHeader(string): ?Part\Header`. Casting the result to string yields `"Name: value"`, not `"value"` — a silent corruption rather than a fatal.

```php
// before
$to = $message->getHeader('To');            // "you@domain.com"

// after
$to = $message->getHeaderValue('To');       // "you@domain.com"
```
**Migration:** Switch to the new `getHeaderValue(string): ?string`.

### `render()`, `renderAsLines()`, `toByteStream()` and `save()` signatures changed — `$omitHeaders` removed
**Severity:** High — **Affects:** any caller passing an omit-headers array
`render(array $omitHeaders = [])` became `render(bool $preamble = true, ?string $body = null)`; the others changed similarly. With strict types these throw `TypeError`. `save(string $to, array $omitHeaders = [])` became `save(string $to)` — the extra argument is silently ignored, so headers you asked to omit are now written to disk.

```php
// before
$message->render(['Bcc']);
$message->save($file, ['Bcc']);

// after
$message->render();
$message->getHeadersAsString(['Bcc']);  // omit only available here
```
**Migration:** Drop the omit arrays; build output from `getHeadersAsString($omit)` + `getBodyContent()` if you need omission. Audit any `save()` call that relied on omitting Bcc.

### Part constructors removed — `new Text()/new Html()/new Attachment()` now silently produce empty parts
**Severity:** High — **Affects:** any code constructing message parts directly
Message part creation moved to the `pop-mime` component. `Text`, `Html` and `Attachment` now extend
`Pop\Mime\Part` and no longer support the constructor — it accepts only `Part`, `Part\Header` and `Part\Body`
objects, so a string is silently discarded. Nothing throws: the part is built with no body and no
`Content-Type`, and the message renders with an empty part.

```php
// after — the before idiom, verified
$part = new \Pop\Mail\Message\Text('Hello World!');
$part->hasBody();     // false
$part->getContent();  // null

// ...and the message renders with nothing between the boundaries:
// --f96ad039f4d8045f53a9eed27afd3bba025d6d42
//
// --f96ad039f4d8045f53a9eed27afd3bba025d6d42--
```

```php
// before
$message->addPart(new \Pop\Mail\Message\Text('Hello'));

// after
$message->addPart(\Pop\Mail\Message\Text::create('Hello'));
// Content-Type: text/plain
//
// Hello
```
**Migration:** Replace every `new Text(...)` / `new Html(...)` with the static `::create()` factories. Grep for `new Pop\Mail\Message\` — none of these will error.

### Encoding constants removed; `attachFile()` takes an enum
**Severity:** High — **Affects:** any caller passing an encoding to `attachFile()` / `attachFileFromStream()` / `Attachment`
`AbstractPart::BASE64`, `QUOTED_PRINTABLE`, `BINARY`, `_8BIT`, `_7BIT` are gone with the deleted class.

```php
// before
$message->attachFile($file, \Pop\Mail\Message\Attachment::BASE64);

// after
$message->attachFile($file, \Pop\Mime\Part\Body\Encoding::BASE64);
```
**Migration:** Use the `Pop\Mime\Part\Body\Encoding` enum.

### `Attachment` constructor and factory methods replaced; `getStream()` removed
**Severity:** High — **Affects:** any code building attachments directly
`createFromFile()` / `createFromStream()` and the `['contentType'|'basename'|'encoding'|'chunk']` options contract are gone.

```php
// before
$a = Attachment::createFromFile($file, ['encoding' => 'BASE64', 'chunk' => true]);
$raw = $a->getStream();

// after
$a = Attachment::create($file);                    // ($file, ?$contentType, $disposition, Encoding, $split)
$a = Attachment::createFromContent($bytes, 'x.pdf');
$raw = $a->getContent();
```
**Migration:** Move to `create()` / `createFromContent()`; replace `getStream()` with `getContent()`.

### `Message::parseAddresses()` and `parseNameAndEmail()` removed
**Severity:** High — **Affects:** code reusing `pop-mail`'s address parsing

```php
// after
$list = \Pop\Mime\Part\Header\AddressList::parse('"Me" <me@x.com>');
foreach ($list->getAddresses() as $a) { $a->getAddress(); $a->getName(); }
```
**Migration:** Use `Pop\Mime\Part\Header\AddressList` / `Address` directly.

### `addPart()` / `getPart()` type contract changed
**Severity:** High — **Affects:** custom part classes and code type-hinting `PartInterface`
Custom parts that implemented `PartInterface` without extending `Pop\Mime\Part` are rejected with a `TypeError`.

```php
// after
class MyPart extends \Pop\Mime\Part { use \Pop\Mail\Message\PartContentTrait; }
```
**Migration:** Re-base custom parts on `Pop\Mime\Part`; the new `PartContentTrait` / `CharsetAwareTrait` restore `getContent()`/`setContent()`/`setCharSet()`.

### Message/part ID API removed: `setId()`, `getId()`, `setIdHeader()`, `getIdHeader()`
**Severity:** High — **Affects:** code setting or reading a Message-ID / Content-ID
`generateId()` survives but changed semantics: it now writes a real `Message-ID` header and returns the rendered value.

```php
// before
$message->setIdHeader('Message-ID')->setId('<abc@host>');

// after
$message->setMessageId('<abc@host>');
$id = $message->getHeaderValue('Message-ID');
// parts: $part->setContentId('<abc@host>');
```
**Migration:** Use `setMessageId()` for messages and `setContentId()` for parts.

### `declare(strict_types=1)` added to every class
**Severity:** High — **Affects:** callers that relied on loose scalar coercion
A concrete in-repo example: `Api\AbstractHttpClient::setTokenExpires(string $tokenExpires)` — `pop-mail` itself had to add `(string)` casts around `time() + $response['expires_in']`.

```php
// before
$client->setTokenExpires(time() + 3600);

// after
$client->setTokenExpires((string)(time() + 3600));
```
**Migration:** Audit call sites passing ints where a `string` is declared.

### Rendered MIME output changed shape
**Severity:** Medium — **Affects:** anything asserting on or post-processing rendered message text
v6 crammed the boundary *and* the preamble text into the `Content-Type` header value. v7 synthesizes a proper `Content-Type` header with the preamble as a separate body line. The boundary value also changed from `sha1(time())` to `sha1(uniqid())`.

**Migration:** Update any golden-file or regex assertions.

### Attachment `Content-Type` no longer carries `name=`, and content-type detection changed
**Severity:** Medium — **Affects:** recipients relying on the legacy `name=` parameter, and unusual file extensions

```php
// before
Content-Type: application/pdf; name="doc.pdf"

// after
Content-Type: application/pdf
Content-Disposition: attachment; filename="doc.pdf"
```
**Migration:** Pass an explicit `$contentType` to `Attachment::create()` if you depend on a specific value.

### `addPart()` now deletes an explicitly set `Content-Type` header
**Severity:** Medium — **Affects:** code that calls `setContentType()` on the message and then adds parts
Any message-level `Content-Type` is discarded as soon as a part is added, and re-synthesized as `multipart/…` at render time. Silent.

**Migration:** Set content types on the individual parts, not the message.

### `Message::getHeaders()` returns rendered values and drops multi-value headers
**Severity:** Medium — **Affects:** code iterating `getHeaders()`
v7 builds `[$name => $header->getValue(0)->render($name)]`, applying `pop-mime`'s encoded-word encoding, and **skips any header whose value count is not exactly 1**.

**Migration:** Use `Pop\Mime\Part::getHeaders()` semantics (returns `Header` objects) if you need every header.

### `Smtp\HandlerInterface::onCommand()` return type changed `void` → `?string`
**Severity:** Medium — **Affects:** anyone implementing `HandlerInterface` or extending `AuthHandler`
An existing implementation declaring `: void` is now fatal at class-load time.

### `Message::decodeText()` no longer uses `imap_mime_header_decode()`
**Severity:** Medium — **Affects:** code decoding MIME encoded-word header text
Switched to `Pop\Mime\Part\Header\EncodedWord::decode()`. This removes an undeclared ext-imap dependency but output is not guaranteed byte-identical for non-UTF-8 or multi-segment headers.

### `Client\Office365::getMessages()` `unread` filter semantics fixed
**Severity:** Medium · **Bug fix** — **Affects:** callers passing an `unread` filter to the Office365 client
v6 had an operator-precedence bug that emitted the bare literal `false` as the filter; v7 emits a real `isRead eq false`. The result set changes.

**Migration:** Re-test any Office365 listing that used the `unread` filter.

### `Attachment::getBasename()` now derives the name from headers
**Severity:** Low — **Affects:** code reading an attachment's display name
Values normally match, but header-encoded or manually-mutated names can differ.

### `Imap::hasMessageAttachments()` ignores its `$encoding` argument
**Severity:** Low — **Affects:** callers passing a non-default `$encoding`

**Migration:** Call `getMessageAttachments($id, $encoding)` directly if the encoding mattered.

### `Client\Google::getMessage()` raw base64url decoding corrected
**Severity:** Low · **Bug fix** — **Affects:** code consuming `getMessage($id, true)` raw output
The `strtr()` map was wrong in v6 (`'._-'` instead of `'-_.'`); raw Gmail bodies that previously decoded to garbage now decode correctly.

**Migration:** Remove any workaround that re-fixed the decoding downstream.

### `Queue::prepare()` applies subject placeholders unconditionally
**Severity:** Low — **Affects:** queued messages with no text/HTML parts
Subject substitution was hoisted out of the per-part loop, so attachment-only messages now get their subject placeholders replaced.

---

## pop-mime

**Scope:** Header parsing/rendering was rewritten on a new RFC 5322 lexer with RFC 2047 encoded-word and address-list support, and body encoding moved from string constants to a `Part\Body\Encoding` backed enum.
**Break count:** 16 (10 high, 4 medium, 2 low)

### `Body` encoding constants removed in favor of an enum
**Severity:** High — **Affects:** any code that names an encoding when building a body or attachment
`Body::BASE64`, `Body::QUOTED`, `Body::URL` and `Body::RAW_URL` no longer exist. They are replaced by `Pop\Mime\Part\Body\Encoding` (`BASE64`, `QUOTED_PRINTABLE`, `URL`, `RAW_URL`, plus new `BINARY`, `_7BIT`, `_8BIT`).

```php
// before
$body = new Body($content, Body::BASE64);

// after
use Pop\Mime\Part\Body\Encoding;
$body = new Body($content, Encoding::BASE64);
```
**Migration:** Replace every `Body::X` constant; note `QUOTED` is now `QUOTED_PRINTABLE`.

### Encoding parameters are now typed `Body\Encoding`, not `string`
**Severity:** High — **Affects:** any caller passing an encoding string to `Body` or `Part::addFile()`
Passing a string is now a `TypeError` (v6 silently ignored unrecognized strings).

```php
// before
$part->addFile('test.pdf', 'attachment', Body::BASE64, true);

// after
$part->addFile('test.pdf', 'attachment', Encoding::BASE64, true);
```
**Migration:** Pass enum cases. Use `Encoding::fromHeaderValue($string)` to map a wire value back to a case.

### `Body::getEncoding()` returns an enum instead of a string
**Severity:** High — **Affects:** code that inspects or compares a body's encoding

```php
// before
if ($body->getEncoding() == 'BASE64') { ... }

// after
if ($body->getEncoding() === Encoding::BASE64) { ... }
// or: $body->getEncoding()?->value / ->toHeaderValue()
```

### `Body::isQuotedEncoding()` renamed
**Severity:** High — **Affects:** any caller checking for quoted-printable bodies
Now `isQuotedPrintableEncoding()`. Note `pop-http`'s own `Body` still exposes `isQuotedEncoding()`, so mixed call sites are easy to miss.

### Header values are now RFC 2047 encoded-word encoded on render
**Severity:** High — **Affects:** every consumer that renders headers containing non-ASCII text
Applications that pre-encoded their own subjects/display names now double-encode.

```php
// before
echo new Header('Subject', 'José García');  // Subject: José García

// after
echo new Header('Subject', 'José García');  // Subject: =?UTF-8?B?Sm9zw6kgR2FyY8OtYQ==?=
```
**Migration:** Stop pre-encoding header values; pass raw UTF-8. Note `(string)$value` / `getValuesAsStrings()` still return the *un*encoded form, so the string cast and the rendered header now differ.

### Address headers are re-parsed and re-rendered through `AddressList`
**Severity:** High — **Affects:** `To`/`From`/`Cc`/`Bcc`/`Reply-To`/`Sender`/`Resent-*` headers
The output is normalized (`, ` separators, display-name quoting/encoding per address) rather than emitted verbatim, and a malformed value degrades to a bare address instead of erroring.

```php
// before — value rendered verbatim
$m->addHeader('To', 'Doe, John <john@doe.com>');   // To: Doe, John <john@doe.com>

// after — reparsed as two addresses and re-rendered
// To: Doe, "John" <john@doe.com>  (normalized)
```
**Migration:** Quote display names containing commas, or build the value with `AddressList`/`Address`. **This is the change most likely to alter `pop-mail` wire output.**

### `Value::parse()` now splits `Basic`/`Bearer` schemes out of the value
**Severity:** High — **Affects:** code reading `Authorization` header values (notably `pop-http`)

```php
// before
Value::parse('Bearer abc123')->getValue();   // 'Bearer abc123'

// after
Value::parse('Bearer abc123')->getValue();   // 'abc123'
Value::parse('Bearer abc123')->getScheme();  // 'Bearer '
```
**Migration:** Read the token from `getValue()` directly and stop stripping the scheme prefix yourself.

### `Body::render()` now persists the encoded content
**Severity:** High — **Affects:** anything that renders a body and then reads its content, or renders twice
v6 set `isEncoded = true` on render but left `$content` raw, so a second `render()` returned the *raw* string.

```php
// before
$b->render();        // 'SGVsbG8gV29ybGQ='
$b->render();        // 'Hello World'   <- raw

// after — both calls return 'SGVsbG8gV29ybGQ='; getContent() returns the base64 string
```
**Migration:** Use `Part::getContents()` (which decodes based on the encoding) instead of `Body::getContent()` after a render.

### `Body::setContentFromFile()` is now lazy — the missing-file exception is deferred
**Severity:** High — **Affects:** code relying on immediate validation of an attachment path
v6 threw right there for a nonexistent file; v7 only records the path and throws later, from `render()`/`getContent()`/`__toString()`. `hasContent()` also now returns `true` for a pending-but-unread file.

**Migration:** Move the try/catch to the render/read site, or validate the path yourself.

### Header parsing requires strict CRLF line endings
**Severity:** High — **Affects:** any code feeding LF-only header blocks to `Message::parseHeaders()` / `parseMessage()`
v6 located header names with a regex anywhere in the string; v7 unfolds only `\r\n` and explodes on it, so an LF-only block collapses into a single header.

```php
// before
count(Message::parseHeaders("A: 1\nB: 2\n"));   // 2

// after
count(Message::parseHeaders("A: 1\nB: 2\n"));   // 1
```
**Migration:** Normalize input to CRLF before parsing. (The flip side is a fix: v6 mis-split values containing `http:` into bogus extra headers.)

### `Header::getValueIndex()` return type changed from `bool` to `int|bool`
**Severity:** Medium — **Affects:** code using the returned index
v6 coerced a found index (`0 → false`, `1 → true`) and threw a `TypeError` on a miss.

**Migration:** Test with `!== false` rather than truthiness — index `0` is now falsy-but-valid.

### `Header::getValueAsString()` returns null for a missing index
**Severity:** Medium — **Affects:** code reading a value at an index that may not exist
v6 emitted an undefined-key warning and produced `''`.

### `Value::getParametersAsString()` quoting and escaping rules changed
**Severity:** Medium — **Affects:** any header with parameter values containing `=`, `,`, `;` or quotes
v7 quotes whenever the value matches `[\s;,="\\]` and escapes embedded quotes/backslashes.

```php
// before: 'attachment; filename=a=b,c.txt'
// after: 'attachment; filename="a=b,c.txt"'
```
**Migration:** Update byte-exact assertions; the v7 form is spec-correct.

### Parsed parts with 7bit/8bit/binary transfer encodings now carry an `Encoding`
**Severity:** Medium — **Affects:** code inspecting `hasEncoding()`/`isEncoded()` on parsed parts
Decoded content is unchanged (these are identity encodings), but the flags are not.

### `Part::getSubType()` and `getContentType()` are now nullable
**Severity:** Low — **Affects:** strictly-typed consumers
In v6 they declared `string` while able to hold `null`, producing a `TypeError`; v7 returns `null` cleanly, so the `TypeError` moves to the caller's own boundary.

### `Part::getFilename()` no longer uses `imap_mime_header_decode()`
**Severity:** Low — **Affects:** code depending on ext-imap decoding for attachment filenames
Decoding now goes through `Header\EncodedWord::decode()`, which handles both `B` and `Q` and never requires ext-imap.

---

## pop-nav

**Scope:** No public signature was removed or altered — the breaks come from `declare(strict_types=1)` turning two latent deprecations into `TypeError`s, new HTML-escaping of node labels, a corrected `on`/`off` URL comparison, and render-cache invalidation.
**Break count:** 7 (2 high, 2 medium, 3 low)

### Top-level node with a relative `href` now throws `TypeError`
**Severity:** High — **Affects:** any tree whose root-level nodes use a relative href (anything not starting with `/`, `#`, `http`, or `mailto:`)
`NavBuilder::build()` is called with `$parentHref = null` at depth 1; the relative-href branch calls `str_ends_with($parentHref, '/')`. Under v6 that was a deprecation notice and `null` coerced to `''`, so the href silently resolved. With strict types it is now a fatal.

```php
// before — worked (with a deprecation), rendered <a href="/home">
$nav = new Nav([['name' => 'Home', 'href' => 'home']]);

// after — TypeError: str_ends_with(): Argument #1 ($haystack) must be of type string, null given
$nav = new Nav([['name' => 'Home', 'href' => '/home']]); // must be absolute
```
**Migration:** Give every root-level node an absolute href or an external/anchor form. Only nested nodes may use relative hrefs — the new README states this as a rule.

### Node `name` is now HTML-escaped
**Severity:** High — **Affects:** any nav label containing markup (icon `<i>`/`<span>` tags, `<br>`) or pre-encoded HTML entities
`NavBuilder::build()` changed to `new Child('a', htmlspecialchars($node['name'], ENT_QUOTES))`. This is a deliberate XSS fix, but it is unconditional with no opt-out, and pre-encoded entities double-encode.

```php
// before
['name' => '<i class="icon-home"></i> Home', 'href' => '/home']
// -> <a href="/home"><i class="icon-home"></i> Home</a>

// after
// -> <a href="/home">&lt;i class=&quot;icon-home&quot;&gt;&lt;/i&gt; Home</a>
```
**Migration:** Move markup out of `name` — put icons on the `<a>` via CSS (`attributes: ['class' => 'icon-home']`) or a pseudo-element. Replace pre-encoded entities with their literal characters.

### `on`/`off` link class now resolves correctly when the request URI has a query string
**Severity:** Medium · **Bug fix** — **Affects:** any app using `config['on']`/`config['off']` on URLs carrying query strings
v6 had an inverted `substr()` that kept the query string *only*, so no href ever matched and every link got the `off` class. v7 yields the path, so the active link correctly receives `on`.

```php
// before — REQUEST_URI '/pages?sort=asc'
// <a href="/pages" class="link-off">Pages</a>   (never highlighted)

// after
// <a href="/pages" class="link-on">Pages</a>
```
**Migration:** A correctness fix, but CSS/tests written against the always-`off` behavior will change. Use the new `setCurrentUrl()` / `config['currentUrl']` for explicit control.

### Rendered attribute values are HTML-escaped by pop-dom (double-encoding included)
**Severity:** Medium — **Affects:** any `href` or per-node `attributes` value containing `&`, `"`, `'`, `<`, or `>`
No `pop-nav` code change — `Pop\Dom\Child::render()` now escapes every attribute value with `double_encode` at its default `true`. This corrects genuinely malformed output (a JSON `data-` attribute used to break out of its quotes), but any href you pre-escaped is now double-encoded.

```php
// before -> <a href="/x?a=1&amp;b=2">      pre-escaped, correct
// after  -> <a href="/x?a=1&amp;amp;b=2">  DOUBLE-ENCODED
```
**Migration:** Stop pre-escaping href/attribute values in your tree; pass raw `&`, `"` etc.

### A `'*'` permission in your pop-acl rules now changes which nav items render
**Severity:** Low — **Affects:** apps that literally used the string `'*'` as a permission name
`pop-acl` v5 reserves `'*'` as a wildcard. Only fires when the checked permission is non-`null`, so resource-only nav nodes are unaffected.

```php
// before: deny(...,'*') denied only the literal permission "*"  -> node RENDERED
// after: '*' is a wildcard deny                                 -> node HIDDEN
```
**Migration:** Rename any literal `'*'` permission. Note the mirror case: with `setAclStrict(true)`, `allow($role,$res,'*')` now *reveals* nodes that v6 hid.

### Non-string node `name` now throws `TypeError`
**Severity:** Low — **Affects:** trees where `name` is an int, float, or `Stringable`

**Migration:** Cast `name` to `string` when building the tree.

### An uncallable policy method now throws instead of being ignored
**Severity:** Low — **Affects:** users of `config['policy']` or a node's `acl.policy`
`pop-acl` v5's `can()` pre-validates the method list and throws; this propagates out of `Nav::render()`. Conversely, v5 removes a v4 `TypeError` that fired whenever policies were registered but none matched.

**Migration:** Ensure every node's `acl.permission` matches a public method on the policy role class.

---

## pop-paginator

**Scope:** Public API (classes, interface, method signatures, return types) is byte-for-byte unchanged; the breaks are new constructor validation and two behavior changes in rendering.
**Break count:** 3 (0 high, 1 medium, 2 low)

### Constructor now validates arguments and throws `Pop\Paginator\Exception`
**Severity:** Medium — **Affects:** any code building a paginator from user- or config-supplied `total`/`perPage`/`range` values that can be zero or negative
`AbstractPaginator::__construct()` now rejects `total < 0`, `perPage < 1` and `range < 1`, and calls `calculateRange()` eagerly. In v6 these constructed fine and only blew up later at render time with a different, non-Pop error class — or, for a negative total, never threw at all.

```php
// before — constructs; fails later (or not at all)
$paginator = Paginator::createRange($total, $perPage); // $perPage = 0
echo $paginator; // DivisionByZeroError at render
$p = Paginator::createRange(-5); // never throws, renders nothing

// after — throws immediately from the constructor
// Pop\Paginator\Exception
```
**Migration:** Clamp `perPage`/`range` to at least 1 and `total` to at least 0 before constructing, or catch `Pop\Paginator\Exception`. Catch blocks targeting `DivisionByZeroError`/`\Error` will no longer match.

### Out-of-bounds page numbers now render the last range block instead of nothing
**Severity:** Low — **Affects:** requests where `?page=` exceeds the real page count — i.e. user-supplied page values
`calculateRange()` now computes a single `lastBlockStart`, so any page past the end clamps into the final block instead of returning nothing. In-bounds pages are unaffected — the only behavior difference is for page numbers beyond the real page count.

```php
// before
$paginator = Paginator::createRange(100, 10, 10); // 10 pages
$paginator->getLinkRange(11); // [] — renders nothing

// after
$paginator->getLinkRange(11); // the full 1..10 block
```
**Migration:** None required — the new behavior is more forgiving than v6's. Clamp the incoming page to `1..getNumberOfPages()` if you would rather treat an out-of-range request as an error.

### Rendered markup now HTML-escapes the request URI and preserved `$_GET` keys/values
**Severity:** Low — **Affects:** apps whose URL path or carried-over query parameters contain `& < > " '`; also any test that string-matches the rendered HTML
The markup *structure* is unchanged — no new/removed tags, attributes or classes, so CSS selectors are unaffected. Only the character-level content of `href`/`action`/`value` changes.

```php
// before — REQUEST_URI '/search/a&b"c.php', $_GET['q'] = 'a"b&c<script>'
<a href="/search/a&b"c.php?page=1&…">1</a>
<input type="hidden" name="q" value="a"b&c<script>" />

// after
<a href="/search/a&amp;b&quot;c.php?page=1&…">1</a>
<input type="hidden" name="q" value="a&quot;b&amp;c&lt;script&gt;" />
```
**Migration:** No action for normal URLs. Update assertions that compare rendered pagination HTML byte-for-byte.

---

## pop-pdf

**Scope:** Text rendering moves to encoding-aware output (WinAnsi transcoding for standard fonts, `/Type0` Identity-H CID output for embedded TrueType/OpenType, with hard failures on missing glyphs); PDF import/text-extraction is rewritten onto a new native `Pop\Pdf\Extract` engine (dropping `smalot/pdfparser`); the HTML parser gains a real table/box layout engine.
**Break count:** 21 (7 high, 8 medium, 6 low)

### `ext-mbstring` is now effectively required but still only *suggested*
**Severity:** High — **Affects:** any host without `ext-mbstring`; text rendering fatals at runtime
New `mb_str_split()` / `mb_chr()` calls mean text rendering fatals without the extension, yet `composer.json` still lists it under `suggest`, so Composer installs happily on a host that lacks it.

**Migration:** Ensure `ext-mbstring` is installed, and add it to your own `composer.json` `require` to make the dependency explicit.

### Characters the font has no glyph for now throw instead of rendering garbage
**Severity:** High — **Affects:** any document whose text contains non-ASCII/non-Windows-1252 characters with a standard (base-14) font
`Text::getPartialStream()` and `Text\Stream::getStream()` now call `Font::requireGlyphCoverage()` for standard fonts and `Font::stringToGidHex()` for embedded CID fonts. Both throw `Pop\Pdf\Build\Font\Exception` at compile time. Previously those bytes were written out raw and rendered as mojibake.

```php
// before — compiles; renders as garbage in the viewer
$document->addFont(Font::ARIAL);
$page->addText(new Page\Text('ПРИВІТ → 世界', 12), Font::ARIAL, 50, 600);
Pdf::writeToFile($document, 'out.pdf');

// after — throws Pop\Pdf\Build\Font\Exception:
// "Error: The font 'Arial' does not contain a glyph for character 'П' (U+041F)."
```
**Migration:** Embed a Unicode-capable TrueType/OpenType font (`$document->embedFont(new Font('/path/DejaVuSans.ttf'))`) for any non-Latin text, or sanitize/transliterate strings first. Wrap `Pdf::writeToFile()` in try/catch if user-supplied text can reach it.

### Embedded TrueType/OpenType fonts are now compiled as composite CID fonts
**Severity:** High — **Affects:** any document using `Document::embedFont()` with a `.ttf`/`.otf` file
`Build\Font\Parser::parse()` now emits `/Type0` + `/Encoding /Identity-H` + a `/CIDFontType2` descendant + a `/ToUnicode` CMap instead of a single-byte `/TrueType` font with `/Widths`. Text is written as glyph-ID hex strings rather than literals, so the same input produces completely different PDF bytes.

**Migration:** Regenerate any byte-comparison fixtures or output hashes taken over documents that embed a font.

### Text extraction rewritten natively; `smalot/pdfparser` removed
**Severity:** High — **Affects:** every caller of `Pdf::extractTextFromFile()` / `extractTextFromData()`, and anything relying on the transitive dependency
Both methods now use `Pop\Pdf\Extract\Document` + `Extract\Content\Interpreter`. The returned string differs: pages are individually trimmed, empty pages dropped, and pages joined with `"\n\n"` (previously concatenated with no separator). Exceptions changed to `Pop\Pdf\Extract\Exception`. A 64 MB decode budget and 64-level page-tree depth limit are now enforced.

**Migration:** Re-baseline any string comparisons, hashes, or regexes over extracted text. Catch `Pop\Pdf\Extract\Exception`. If your app used `\Smalot\PdfParser\*` directly, add the package to your own `composer.json`.

### `Build\Parser` rewritten — object map, object streams and imported fonts are now always empty
**Severity:** High — **Affects:** code that inspects `Build\Parser` internals, or adds text to an imported page using the source PDF's own font name
`getObjectStreams()`, `getObjectMap()` and `getFonts()` are retained only for signature compatibility and now always return `[]`. The parser no longer calls `Document::importFonts()`, so `Compiler::prepareText()` throws for a font name that came from the imported PDF. The protected `mapObjects()`, `mapFonts()`, `filterPages()`, `getObjects()` and `AbstractParser::getStreamType()` were removed.

```php
// before
$doc = Pdf::importFromFile('source.pdf');
$doc->getPage(1)->addText(new Page\Text('Stamp', 12), 'ArialMT', 50, 50); // font came from source

// after — throws: "The font 'ArialMT' has not been added to the document."
$doc->addFont(Font::ARIAL);   // register explicitly first
```
**Migration:** Always register the font you draw with before `addText()` on an imported page. Replace `getObjectMap()`/`getObjectStreams()` use with the `Pop\Pdf\Extract` API.

### `<form>` markup in parsed HTML now compiles into real, interactive form fields
**Severity:** High — **Affects:** every user of `Build\Html\Parser` / `Pdf::importFromHtml*()` whose markup contains a `<form>`, an `<input>`, a `<select>`, a `<textarea>` or a `<button>`
Form markup used to be inert: the parser walked past every control and produced nothing on the page. It is now converted into `Document\Page\Field\*` widgets by `Build\Html\Form\Layout`, with no opt-in flag, so the same markup that rendered as an empty gap now renders a visible, fillable widget, adds a `Document\Form` to the document, and lengthens the page — pushing following content down and potentially onto another page. A control with no `<form>` ancestor still renders, into an implicit form named `__default__`.

```php
// before — the input produced nothing; the document had no forms
$document = Pdf::importFromHtml('<p>Name</p><input type="text" name="who">');
$document->hasForms();   // false

// after — a Field\Text widget on the page, inside an implicit form
$document = Pdf::importFromHtml('<p>Name</p><input type="text" name="who">');
$document->hasForms();   // true
$document->getForm('__default__');
```
**Migration:** Strip form markup from any template you render to PDF and do not want fields for, or accept the fields and re-check the template's pagination. Re-baseline byte comparisons and page counts over HTML-derived output.

### A checkbox or radio's `/V` now follows `setChecked()`, not `setValue()`
**Severity:** High — **Affects:** anyone building `Field\Button` checkboxes or radios and setting a slash-prefixed on-state through `setValue()`
`Button::setValue()` now means the export name a reader reports for that widget when it *is* checked, and nothing more; whether the widget starts out checked is a separate flag, `setChecked()`. The compiler sanitizes the value into a bare PDF name (anything outside `A-Za-z0-9_` becomes `_`) and writes it as `/V` and `/AS` only when the field is checked, `/Off` otherwise — so the old `setValue('/Yes')` form now yields an on-state named `_Yes` on a box that starts out clear.

```php
// before — checked, because /V carried the state
$agree->setValue('/Yes');            // /V /Yes

// after — the two are independent
$agree->setValue('Yes')->setChecked();   // on-state /Yes, /V /Yes, /AS /Yes
$agree->isChecked();                     // true
```
**Migration:** Drop the leading slash from the value and add `setChecked()` wherever the box should start out checked.

### Standard-font text is transcoded to WinAnsi and always re-escaped, ignoring `escape: false`
**Severity:** Medium — **Affects:** documents with accented/punctuation characters, and any caller constructing `Text` with `$escape = false`
The compiler now emits `Text::encodeWinAnsi($this->rawString)`, which transcodes UTF-8 → Windows-1252 and unconditionally applies `Text::escape()`. The constructor's `$escape = false` flag is silently ignored on this path, and a pre-escaped string gets escaped a second time.

**Migration:** Stop pre-escaping; pass unescaped UTF-8 and let the library escape. Regenerate golden-file PDF fixtures.

### `AbstractDocument` narrowed to `Document` in the compiler API
**Severity:** Medium — **Affects:** custom `CompilerInterface` implementations and `AbstractCompiler` subclasses
`setDocument()`, `finalize()`, `Pdf::writeToFile()` and `Pdf::outputToHttp()` all changed their parameter type from `Document\AbstractDocument` to the concrete `Pop\Pdf\Document`.

**Migration:** Update type hints; pass a `Pop\Pdf\Document`.

### `Build\Html\Parser` no longer accepts a null document
**Severity:** Medium — **Affects:** callers passing `null` explicitly
The constructor and static factories changed from `?Document $document = null` to `Document $document = new Document()`. `getDocument()`, `document()` and `process()` narrowed from `?Document` to `Document`.

**Migration:** Drop the explicit `null` argument.

### `Build\Font\Parser` requires CID indices for TrueType and lost `getGlyphWidthsFromCmap()`
**Severity:** Medium — **Affects:** code driving `Build\Font\Parser` directly instead of through `Compiler`
`parse()` now throws unless both `setCidFontObjectIndex()` and `setToUnicodeIndex()` were called for a TrueType/OpenType font.

**Migration:** Allocate five consecutive object indices (font, CID font, descriptor, font file, ToUnicode) and set all of them before `parse()`.

### `Text\Stream::getStream()` now escapes text before writing it
**Severity:** Medium — **Affects:** anyone building `Page\Text\Stream` with text containing `(`, `)`, `\`, or newlines
It was previously written verbatim, corrupting the PDF for unbalanced parens — but pre-escaped input now gets escaped twice. The internal wrap test is also now shared across `getStream()`/`hasOrphans()`/`measureHeight()`, so line breaks land in different places.

**Migration:** Pass unescaped text to `Stream::addText()`; re-check layouts that depended on the old wrap condition.

### HTML rendering and table layout rewritten
**Severity:** Medium — **Affects:** every user of `Build\Html\Parser` / `Pdf::importFromHtml*()`
Tables now go through a new layout engine (colspan/rowspan, repeating headers, content-derived column widths) instead of fixed 25pt rows. Vertical advance after a text node changed from a flat `marginBottom ?: 25` to measured height + margin. The default `a` selector color changed from a `Color\Rgb` object to a plain `[0, 0, 255]` array, and `prepareNodeStyles()` gained `borderWidth`/`borderColor`/`backgroundColor` keys.

**Migration:** Visually re-verify every HTML-to-PDF template. Handle an array if you read `getCss()['a']['color']`.

### `Field\Choice::setValue()` and `setDefaultValue()` now write escaped string literals
**Severity:** Medium — **Affects:** callers preselecting a choice field's row
Per PDF 32000-1 12.7.4.4 a choice field's `/V` is a text string, not a bare name, and it is now emitted parenthesized, escaped and — under an encrypted document — encrypted. A value that was pre-wrapped for the old form is wrapped a second time.

```php
// before
$country->setValue('(Canada)');   // /V (Canada)

// after — the same call now emits /V (\(Canada\))
$country->setValue('Canada');     // /V (Canada)
```
**Migration:** Pass the bare value with no parentheses of your own.

### Same-named radio buttons now compile into one grouped field instead of several
**Severity:** Medium — **Affects:** code reading `Form::getFieldIndices()` / `getNumberOfFields()`, or building radio groups by hand
Two or more `Field\Button` fields on a page that share a form, share a field name and are marked `setRadio()` now compile to one shared, non-visual parent field plus one child widget each. Only the parent is indexed into the form, so a three-option group counts as **one** field rather than three, and the children carry no `/T` or `/Ff` of their own — those live on the parent. A solitary radio with no same-named sibling still compiles as a single top-level field.

**Migration:** Expect one index per group rather than one per option when reading `getFieldIndices()`, and re-baseline any object-number assertions.

### Encrypted and malformed PDFs now throw on import and extraction
**Severity:** Low — **Affects:** apps that fed arbitrary/untrusted PDFs to `importFromFile()` or the extract methods
The new reader raises `Extract\Exception` for missing/malformed `startxref`, unresolvable catalogs, circular references, and a 64 MB decode budget. The previous regex-based parser silently produced a garbage document instead.

An encrypted PDF also throws, but now only when no password is given, or the wrong one is — every method that reads an existing PDF takes a password, and AES-128 and AES-256 documents open normally with it. RC4 and revision-5 encryption are refused outright rather than opened.

**Migration:** Wrap import/extraction of untrusted PDFs in try/catch, and pass the password where you have one — see the encryption entries in [`NEW-FEATURES.md`](NEW-FEATURES.md).

### Character wrapping now measures width in characters rather than bytes
**Severity:** Low · **Bug fix** — **Affects:** `Page\Text::setCharWrap()` on text containing non-ASCII characters
`setCharWrap()` ran on PHP's byte-based `wordwrap()`, so a width of 24 meant 24 *bytes* — every accented or non-Latin character counted as two or more, and lines came out shorter than asked for. It now runs on a multibyte-aware wrap that counts characters, so the same call breaks the same string in different places, often into fewer lines.

```php
$text = new Page\Text('Café naïve résumé première année déjà vu', 11);
$text->setCharWrap(24, 14);

// before — 3 lines: 'Café naïve résumé' / 'première année déjà' / 'vu'
// after  — 2 lines: 'Café naïve résumé' / 'première année déjà vu'
```
Pure-ASCII text wraps identically, so only content carrying multibyte characters moves. The wrap works with an embedded CID font as well as a standard one.

**Migration:** Re-check the vertical space budgeted for any char-wrapped non-ASCII text, and regenerate golden-file fixtures over it.

### `Document\Page\Text::escape()` is now static
**Severity:** Low — **Affects:** subclasses of `Page\Text` that override `escape()`
Instance-style calls still work, but a subclass that redeclares it non-statically is a fatal error.

### Page and image dimensions are now integer-cast
**Severity:** Low — **Affects:** code setting fractional page sizes or image resize dimensions
`setWidth()`/`setHeight()` changed from `(float)` to `(int)` casts, and `getResizeDimensions()` now returns ints.

### Imported documents no longer inherit the source PDF's version
**Severity:** Low — **Affects:** code reading `Document::getVersion()` after an import
An imported document always reports the default `1.7`.

**Migration:** Call `$doc->setVersion(...)` explicitly if the output version matters.

### Checkboxes, radios and captioned push buttons now carry `/AP` appearance streams
**Severity:** Low — **Affects:** byte-for-byte fixtures over documents containing form fields
Each checkbox and radio now compiles two extra Form XObjects — an on state (a filled square, or a filled circle for a radio) and an off state — and a captioned push button compiles one. Object numbering shifts accordingly, and because a reader stops synthesizing its own appearance once `/AP` exists, the border and background are drawn into both states rather than left to `/MK` alone.

**Migration:** Regenerate golden-file PDF fixtures for any document carrying form fields.

---

## pop-queue

**Scope:** The storage-adapter contract was rewritten from a one-step `pop()` model to a lease-based reserve/delete/release/bury model with a dead-letter store; scheduled-task "buffer" became "grace period" with an inverted default; CLI `exec` jobs moved to `symfony/process`; `aws/aws-sdk-php` was dropped from `require`.
**Break count:** 12 (7 high, 5 medium, 0 low)

### `AdapterInterface` / `AbstractAdapter` contract rewritten
**Severity:** High — **Affects:** any custom adapter, and any code calling adapter methods directly
`pop()`, `getStart()`, `getEnd()`, `getStatus()`, `hasFailedJob()`, `getFailedJob()`, `hasFailedJobs()`, `getFailedJobs()` and `clearFailed()` were removed. In their place: `reserve()`, `release()`, `delete()`, `bury()`, `count()`, `hasDeadJobs()`, `countDead()`, `getDeadJobs()`, `getDeadJob()`, `retryDeadJob()`, `deleteDeadJob()`, `clearDead()` — all abstract, so any 2.x adapter is now a fatal error.

```php
// before
$job = $queue->adapter()->pop();
$failed = $queue->adapter()->getFailedJobs();
$n = $queue->adapter()->getEnd();

// after
$job = $queue->adapter()->reserve();
$queue->adapter()->delete($job);          // or release($job) / bury($job, $reason)
$failed = $queue->adapter()->getDeadJobs();
$n = $queue->adapter()->count();
```
**Migration:** Rewrite custom adapters against the new interface, including lease bookkeeping (`Pop\Queue\Adapter\Memory` is the reference implementation). Application code calling `Queue::clearFailed()` is unaffected — it now delegates to `clearDead()`.

### `TaskAdapterInterface` gained `claimTaskRun()` and `getAllTasks()`
**Severity:** High — **Affects:** classes implementing `TaskAdapterInterface` directly
`AbstractTaskAdapter` provides a concrete `getAllTasks()` fallback but declares `claimTaskRun()` abstract, so even adapters that extend it are fatal until they implement it.

**Migration:** Implement `claimTaskRun()` with a storage-level atomic same-window claim honoring `AbstractTaskAdapter::TASK_CLAIM_TTL` (90s).

### Task "buffer" renamed to "grace period", default flipped from `0` to `-1`
**Severity:** High — **Affects:** any application scheduling tasks
`Task::setBuffer()`/`buffer()`/`getBuffer()` and `Cron::setBuffer()`/`getBuffer()` were removed and replaced by `setGracePeriod()`/`gracePeriod()`/`getGracePeriod()`/`hasGracePeriod()`. `Cron::__construct()`'s second parameter default changed from `0` to `-1`. Semantically, a minute-granularity task went from "due only on the `00` second" to "due for the whole matching minute" — tasks that previously silently never fired now fire.

```php
// before
$task->every30Minutes()->setBuffer(10);
$cron = new Cron('* * * * *');       // strict: second 00 only

// after
$task->every30Minutes()->setGracePeriod(10);
$cron = new Cron('* * * * *');       // due for the whole minute
$cron = new Cron('* * * * *', 0);    // restore v6 strictness
```
**Migration:** Rename all call sites. If v6 strict-to-the-second behavior was intentional, pass `0` explicitly.

### CLI `exec` jobs now run via `symfony/process` and fail on non-zero exit
**Severity:** High — **Affects:** any application using `Job::exec()` / `setExec()`
`runExec()` replaced `exec()` with `Process::fromShellCommandline(...)->mustRun()`. In v6 the exit code was never inspected, so a failing command completed the job successfully. In v7 a non-zero exit throws `ProcessFailedException`, which `Queue::work()` treats as a job failure. It also requires `proc_open()` rather than `exec()`.

```php
// before — job "succeeds" even though grep found nothing (exit 1)
$job = Job::exec('grep foo /var/log/app.log');

// after — throws ProcessFailedException; job is retried, then buried
```
**Migration:** Wrap tolerable non-zero exits (`'cmd || true'`), or move the command into a callable job. Verify `proc_open()` is not disabled.

### `aws/aws-sdk-php` moved from `require` to `suggest`
**Severity:** High — **Affects:** anyone using the `Sqs` adapter
Instantiating `Pop\Queue\Adapter\Sqs` after upgrade fails with "Class Aws\Sqs\SqsClient not found".

**Migration:** `composer require aws/aws-sdk-php` explicitly.

### `File` adapter on-disk layout changed — existing queued jobs are orphaned
**Severity:** High — **Affects:** File-adapter queues with jobs already persisted
v6 wrote each job to `<folder>/<index>/payload` + `<index>/status`. v7 writes to `<folder>/pending/<index>/payload` and moves reserved jobs to `<folder>/reserved/<index>/`; dead jobs become `<folder>/dead-<jobId>` files. Numeric directories left at the folder root by v6 are never scanned — those jobs silently vanish from `count()`, `hasJobs()` and `reserve()`.

**Migration:** Drain the queue on v6 before upgrading, or manually move existing numeric job directories into `pending/`.

### Persisted failed jobs from v6 become invisible on `Database` and `Redis`
**Severity:** High — **Affects:** queues holding v6 failed-job rows/entries
v6 marked failed jobs with `status = 2` (Database) or a parallel `:status` list (Redis). v7's eligibility filter matches only `status = 1 OR (status = 0 AND reserved_until <= now)`, and dead jobs are `type = 'dead'` rows — so old `status = 2` rows are unreachable via both `reserve()` and `getDeadJobs()`. On Redis, old failed entries are now indistinguishable from healthy pending jobs and will simply be run. (Live/in-flight jobs *are* handled: the schema is auto-migrated and `status = 0` rows backfilled.)

**Migration:** Export or clear v6 failed jobs before upgrading; delete the orphaned Redis `<prefix>:status` key.

### `JobInterface` command/exec signatures widened
**Severity:** Medium — **Affects:** classes implementing `JobInterface` directly
`setCommand(string)` → `setCommand(string|array)`, `setExec(string)` → `setExec(string|array)`, and the getters widened to `string|array|null`.

**Migration:** Widen the signatures; guard `getExec()`/`getCommand()` consumers against arrays.

### `Queue::work()` failure handling changed
**Severity:** Medium — **Affects:** every application working a queue
v6 caught `\Exception`, called `failed()` and unconditionally re-`push()`ed the job. v7 catches `\Throwable`, then either `release()`s (honoring `getBackoffDelay()`) or `bury()`s it to the dead-letter store. A job that fails its final attempt no longer stays in the main queue.

**Migration:** Read failed jobs via `getDeadJobs()`; retry via `retryDeadJob($jobId)`.

### `push()` no longer skips invalid jobs or repositions failed ones
**Severity:** Medium — **Affects:** code calling `addJob()` with expired or exhausted jobs
An invalid job is now persisted and will be reserved and immediately buried, where v6 silently discarded it at push time.

**Migration:** Check `$job->isValid()` before `addJob()` if the v6 silent-drop behavior was relied upon.

### `Sqs` dead-letter methods are stubs, two of them throwing
**Severity:** Medium — **Affects:** SQS-backed queues that inspected failed jobs
`hasDeadJobs()` returns `false`, `countDead()` returns `0`, `getDeadJobs()` returns `[]`, `clearDead()` is a no-op, and `retryDeadJob()`/`deleteDeadJob()` **throw**. `Sqs::release()` also ignores `$delay`/`setBackoff()`.

**Migration:** Configure a native SQS redrive policy against a separate dead-letter queue.

### Scheduled tasks are now claimed — at most one run per due window
**Severity:** Medium — **Affects:** applications invoking `run()`/`runAll()` more than once per window
`claimTaskRun($taskId, $window)` is checked before running a due task (TTL 90s). Combined with the new `-1` grace-period default, a worker invoked twice inside the same minute runs a due task once.

---

## pop-session

**Scope:** The public API is nearly intact (no classes/methods removed), but the session-cookie and `session_start()` bootstrap in `Session::__construct()` changed defaults and `SessionInterface` gained a method; the on-disk `_POP_SESSION_` bookkeeping key and its shape are unchanged, so stored session payloads stay readable.
**Break count:** 5 (0 high, 3 medium, 2 low)

### Session cookie params are now always set, with new HttpOnly/SameSite defaults
**Severity:** Medium — **Affects:** any app that relied on `php.ini` cookie settings, reads the session cookie from JavaScript, or needs the cookie in a cross-site/iframe context
In v6, `session_set_cookie_params()` was called **only if `$options` was non-empty**, and `httponly`/`samesite` fell back to `php.ini`. In v7 it is called on every cold start, `httponly` hard-defaults to `true`, and `samesite` defaults to `'Lax'` whenever `session.cookie_samesite` is empty — which is PHP's stock default.

```php
// before — no options: php.ini values used verbatim
$sess = Session::getInstance();          // HttpOnly off, no SameSite attribute

// after — cookie params always written
$sess = Session::getInstance();          // HttpOnly=true, SameSite=Lax
// to restore v6-like behavior:
$sess = Session::getInstance(['httponly' => false, 'samesite' => '']);
```
**Migration:** If the session cookie must be script-readable or sent cross-site (embedded iframe, third-party POST, OAuth-style redirect flows), pass `httponly`/`samesite` explicitly on the first `getInstance()` call.

### `session.use_strict_mode` is now forced on by default
**Severity:** Medium — **Affects:** apps that propagate or accept externally supplied session IDs (SSO handoffs, shared session stores, test harnesses that pin an ID)
v6 called bare `session_start()`, inheriting the ini default (`0`). v7 passes `use_strict_mode` from `$options['strict_mode'] ?? true`. Under strict mode PHP rejects an uninitialized session ID and issues a new one, so a client presenting an unknown ID silently gets an empty new session.

```php
// after
Session::getInstance(['strict_mode' => false]);  // before behavior
```

### Cookie `path` default changed (v6 wrote the lifetime value into `path`)
**Severity:** Medium · **Bug fix** — **Affects:** apps that pass an options array without a `'path'` key; **live sessions are invalidated on deploy**
v6 had `$path = $options['path'] ?? $sessionParams['lifetime'];` — a copy/paste bug that set the cookie path to the lifetime integer (usually `0`). v7 uses the real path (normally `/`). The scope of the issued session cookie therefore changes, so browsers holding a v6-issued cookie will not match it and users get a fresh session.

**Migration:** Expect a one-time logout of active sessions on deploy; pin `'path' => '/'` explicitly if you want the scope stable across the upgrade.

### `SessionInterface::sweep()` added and left unimplemented in `AbstractSession`
**Severity:** Low — **Affects:** anyone implementing `SessionInterface` or extending `AbstractSession` with their own session class
`AbstractSession` does **not** provide an implementation, so any concrete subclass that doesn't declare `sweep()` is a fatal error at class-declaration time.

**Migration:** Add a covariant `sweep()` to every custom implementation.

### `Session::__construct()` now initializes and sweeps even when a session was started elsewhere
**Severity:** Low · **Bug fix** — **Affects:** apps where another library/bootstrap calls `session_start()` before `Session::getInstance()`
In v6 the entire body was inside `if (session_id() == '')`, so with a pre-started session `getId()`/`getName()` threw a `TypeError` and `_POP_SESSION_` was never created — meaning timed/request values were never swept. v7 always assigns id/name and runs `init()`, so hop counters that previously never advanced now do.

---

## pop-storage

**Scope:** The storage abstraction and all three adapters (Local, S3, Azure).
**Break count:** 17 (6 high, 7 medium, 4 low)

### Every failing operation now throws instead of returning `false` or silently doing nothing
**Severity:** High — **Affects:** all write/read/delete/copy/move calls on all three adapters
This is the central v3.0 change. In v6, `putFile()`, `copyFile()`, `renameFile()`, `deleteFile()`, `replaceFileContents()` and the `*External()` methods were wrapped in `if (file_exists(...))` and simply returned when the source was missing; `fetchFile()` returned `false` (Local/S3) or `null` (Azure). All now throw a typed `Pop\Storage\Exception\*`.

```php
// before — silently no-ops / returns false
$storage->deleteFile('missing.pdf');           // did nothing
$contents = $storage->fetchFile('missing.pdf'); // false or null

// after
try {
    $storage->deleteFile('missing.pdf');        // throws FileNotFoundException
} catch (Pop\Storage\Exception\FileNotFoundException $e) { /* ... */ }
```
**Migration:** Wrap storage calls in try/catch. `catch (Pop\Storage\Exception $e)` catches everything (all new types extend it). Guard first with `fileExists()`/`isFile()`, which still return plain `bool`.

### `getFileSize()` / `getFileType()` / `getFileMTime()` / `md5File()` no longer return `false`
**Severity:** High — **Affects:** any code testing these return values for `false`
Return types narrowed across the interface, abstracts and all three adapters. A missing file throws `FileNotFoundException`; unreadable metadata throws `UnableToReadFileException`.

```php
// before
$size = $storage->getFileSize('test.pdf');
if ($size === false) { /* not found */ }

// after
try { $size = $storage->getFileSize('test.pdf'); }
catch (Pop\Storage\Exception\FileNotFoundException $e) { /* not found */ }
```

### `Pop\Storage\Adapter\Exception` and `Adapter\Azure\Exception` deleted
**Severity:** High — **Affects:** every `catch` block and `use` statement referencing them
Both classes are gone — renamed into `Exception\PathTraversalException` and `Exception\FileNotFoundException` — so the old FQCNs no longer resolve at all. Both were direct `\Exception` subclasses in v6, unrelated to `Pop\Storage\Exception`, so a v6 blanket catch would *not* have caught them; now everything does.

```php
// before
catch (Pop\Storage\Adapter\Exception $e) { /* invalid upload array */ }

// after
catch (Pop\Storage\Exception\UnableToWriteFileException $e) { /* ... */ }
```

### Path traversal now throws — `..` in any path segment is rejected
**Severity:** High — **Affects:** any path containing `..`, including `$_FILES['...']['name']`
`AbstractAdapter::scrub()` gained a segment loop that throws `PathTraversalException`. Additionally, `Local::uploadFile()` and `S3::uploadFile()` now route `$file['name']` through `scrub()` — v6 concatenated it raw, so attacker-controlled `$_FILES` names could escape the storage root.

```php
// before
$storage->fetchFile('../outside.pdf');   // resolved and read

// after — throws PathTraversalException
```
**Migration:** Remove `..` from any deliberate relative path; use `chdir()` or `setBaseDir()` to reposition instead.

### Azure blob URIs were built in the wrong order under `chdir()` — files now land somewhere else
**Severity:** High · **Bug fix** — **Affects:** every Azure app that calls `chdir()`
v6 built `'/' . $baseDirectory . '/' . $filename` and then *prepended* the sub-directory: with base `mycontainer` and `chdir('foo')`, it produced `/foo/mycontainer/test.pdf` — treating `foo` as the container. v7 produces `/mycontainer/foo/test.pdf`.

```php
// before — PUT /foo/mycontainer/test.pdf
// after — PUT /mycontainer/foo/test.pdf
$storage->chdir('foo');
$storage->putFileContents('test.pdf', $data);
```
**Migration:** Data written by a v6 Azure app while `chdir`'d lives under the old (wrong) URI and will no longer be found. **Audit and relocate those blobs before upgrading.**

### Azure `listDirs()` / `listFiles()` return relative names and only one level
**Severity:** High — **Affects:** Azure listing code, especially after `chdir()`
v7 sends `delimiter=/`, strips the prefix off each name, and filters out anything still containing `/` unless `$recursive` is true. It also follows `NextMarker` pagination (v6 stopped at Azure's first page).

```php
// before — chdir('foo')
$storage->listFiles();  // ['foo/test.pdf', 'foo/bar/test2.pdf']

// after
$storage->listFiles();          // ['test.pdf']
$storage->listFiles(null, true); // ['test.pdf', 'bar/test2.pdf']
```

### S3 `listDirs()` / `listFiles()` semantics changed
**Severity:** Medium — **Affects:** S3 listing code
`listDirs()` was rewritten onto S3's `CommonPrefixes`, so directories that exist only implicitly now appear. `listFiles()` now skips zero-byte directory markers, which v6 included. Both walk full pagination, so buckets over 1000 keys return more results.

### Azure absolute paths no longer bypass the base directory
**Severity:** Medium · **Bug fix** — **Affects:** Azure code passing `/other-container/file.pdf` to non-`*External()` methods

**Migration:** Use `copyFileFromExternal()` / `moveFileFromExternal()` for cross-container access.

### `Azure::replaceFileContents()` now requires the file to already exist
**Severity:** Medium — **Affects:** Azure code using it as an upsert
In v6 it was a bare alias for `putFileContents()`. Local and S3 already had the guard but silently no-op'd — they now throw instead.

**Migration:** Switch to `putFileContents()` wherever the file may not exist.

### `S3::fetchFileInfo()` throws instead of returning an empty array
**Severity:** Medium — **Affects:** S3 code checking `empty($info)`
An `AwsException` from `headObject()` is now wrapped in `UnableToReadFileException`. (Azure's `fetchFileInfo()` is the one deliberate exception — it still returns its array on a 404.)

### `S3::putFile($file, false)` now actually deletes the local source
**Severity:** Medium — **Affects:** S3 code passing `$copy = false`
v6 called `rename()` across stream-wrapper protocols, which always failed and left the source in place. v3 does `copy()` then `unlink()`.

**Migration:** Pass `true` (the default) if you relied on the source surviving.

### Three new methods on the contracts, plus a new `$recursive` parameter
**Severity:** Medium — **Affects:** custom adapters and any class implementing `StorageInterface` or extending the abstracts
`putFileStream()`, `fetchFileStream()` and `getTemporaryUrl()` are now required across all five contract files. `listAll()`, `listDirs()` and `listFiles()` all gained `bool $recursive = false`.

**Migration:** Implement the three new methods (throw `UnsupportedOperationException` where the backend has no equivalent, as `Local::getTemporaryUrl()` does) and add `$recursive` to the listing signatures.

### `AuthInterface` / `AbstractAuth` gained an abstract `generateSasToken()`
**Severity:** Medium — **Affects:** custom Azure auth implementations

**Migration:** Implement it, or extend the concrete `Adapter\Azure\Auth`.

### `S3::setBaseDir()` normalizes the bucket to an `s3://` prefix
**Severity:** Low — **Affects:** S3 code reading `getBaseDir()`/`getCurrentDir()` back out

### `Local::mkdir()` throws when the directory already exists
**Severity:** Low — **Affects:** Local code calling `mkdir()` idempotently
v7 wraps it in `@mkdir()` and throws `UnableToCreateDirectoryException` on any failure — which includes "already exists" and "parent doesn't exist" (no recursive flag is passed).

**Migration:** Guard with `isDir()`, or catch the exception.

### `rmdir()` exception types changed on Local and S3
**Severity:** Low — **Affects:** code catching around `rmdir()`
`\Pop\Dir\Exception` no longer escapes directly; it is re-thrown as `UnableToDeleteDirectoryException` with the original as `$previous`. `S3::rmdir()` was also rewritten to a paginated batch `deleteObjects()`.

### Narrowed return types under strict types turn unreadable-file failures into `TypeError`
**Severity:** Low — **Affects:** files that exist but cannot be stat'd/read *(uncertain — inferred from the return-type narrowing; no test covers it)*
A raw `TypeError` escapes instead of the documented `Pop\Storage\Exception\*`, and `catch (Pop\Storage\Exception $e)` will not catch it.

**Migration:** Add a `\TypeError` (or `\Throwable`) catch alongside `Pop\Storage\Exception` if you handle unreadable files.

---

## pop-utils

**Scope:** No classes, interfaces, traits, constants, properties or methods were removed or renamed; the breaks come from `Pop\Model\AbstractModel` relocating into this package as `Pop\Utils\AbstractModel`, `declare(strict_types=1)` in `functions.php`, the `popphp/popphp` dependency being dropped, and a PHP 8.4 floor.
**Break count:** 8 (2 high, 1 medium, 5 low)

### `Pop\Model\AbstractModel` is now `Pop\Utils\AbstractModel`
**Severity:** High — **Affects:** every application model class, since Pop apps conventionally extend it
The class itself changed namespace. `Pop\Utils\AbstractModel` is declared identically — `abstract class Pop\Utils\AbstractModel extends ArrayObject {}` — so this is a pure relocation with no behavior change. But v7 `popphp` has **no** `src/Model/` directory at all, so the old FQCN is gone entirely and any `use Pop\Model\AbstractModel;` is a fatal "Class not found".

```php
// before
use Pop\Model\AbstractModel;
class User extends AbstractModel { }

// after
use Pop\Utils\AbstractModel;
class User extends AbstractModel { }
```
**Migration:** Change every import. Note the sibling `Pop\Model\AbstractDataModel` / `DataModelInterface` / `Exception` did **not** come here — they moved to `Pop\Db\Model\*` in `pop-db`.

### `app_date()` now throws `TypeError` for numeric-string and float timestamps
**Severity:** High — **Affects:** any `app_date()`/`app_time()` call passing a Unix timestamp that is not a native `int` (DB columns, `$_GET`/`$_POST` values, `microtime(true)`)
`src/functions.php` gained `declare(strict_types=1)`, which makes the internal `date()`/`gmdate()` calls *inside that file* strict. The `$timestamp` parameter is still `mixed`, and the `is_numeric()` guard deliberately leaves numeric strings untouched — so they now reach `date()` as a `string` and blow up.

```php
// before
app_date('Y-m-d', '1755302400');   // => "2025-08-15"
app_date('Y-m-d', 1755302400.75);  // => "2025-08-15"

// after — TypeError: date(): Argument #2 ($timestamp) must be of type ?int
app_date('Y-m-d', (int)$timestamp);
```
**Migration:** Cast at the call site. Native `int` timestamps and non-numeric date strings (which go through `strtotime()`) are unaffected.

### `app_time()` return type changed from `string|null` to `int|null`
**Severity:** Medium — **Affects:** code that strict-compares, type-checks, or JSON-encodes the result
In v6 the function was declared `: string|null` in a file **without** strict types, so the `strtotime()` int return was weak-mode coerced to a numeric string.

```php
// before
var_dump(app_time('2026-01-15 10:00:00')); // string(10) "1768492800"

// after
var_dump(app_time('2026-01-15 10:00:00')); // int(1768492800)
```
**Migration:** Remove `(string)` round-trips, switch `===` string comparisons to int, and re-check any `json_encode()` payload whose consumer expected a quoted value.

### `popphp/popphp` is no longer a dependency of pop-utils
**Severity:** Low — **Affects:** apps that required `popphp/pop-utils` directly and relied on `popphp` arriving transitively
`popphp/popphp` was removed from `require` (and `psr/log` added). `pop-utils` v7 is now standalone — `functions.php` dropped its `use Pop\App;`.

**Migration:** If your app uses `Pop\App`, `Pop\Application`, etc. but only lists `popphp/pop-utils`, add an explicit `popphp/popphp` requirement.

### `app_date()` reads `$_ENV` directly instead of `Pop\App::env()`
**Severity:** Low — **Affects:** apps whose `APP_TIMEZONE` env value uses `popphp`'s magic literals
`App::env()` mapped `true`/`false`/`null`/`empty` (and their parenthesized forms) to real PHP values; the new `$_ENV[$env] ?? $envDefault` returns them verbatim.

**Migration:** Set `APP_TIMEZONE` to a real timezone identifier or numeric offset.

### `Collection` silently yields an empty array for non-array-like data
**Severity:** Low — **Affects:** `new Collection($x)` / `Collection::merge($x)` where `$x` is a scalar or `null`
`getDataAsArray()` now delegates to the new `Arr::toArray()`, which returns `[]` for unrecognised input. Previously this raised a `TypeError` from the `: array` return declaration.

```php
// before
new Collection('foo');   // TypeError

// after
new Collection('foo');   // silently => []
```
**Migration:** A loud failure became a silent empty collection. Validate input before constructing, or assert on `count()`.

### `Str::__callStatic()` throws `TypeError` for unrecognised method names
**Severity:** Low — **Affects:** typo'd or non-existent `Str::` static calls
The body is byte-identical, but `Str.php` gained strict types, so the internal calls with a `null` separator now throw instead of emitting a deprecation and returning garbage. All 100 legitimate conversion combinations were exercised on both branches and produce byte-identical output.

**Migration:** Use the real API (`Str::createRandom()`, `Str::createSlug()`) — this only surfaces calls that were already wrong.

### `CallableObject` with a `'new Class'` target no longer discards its parameters
**Severity:** Low · **Bug fix** — **Affects:** `new CallableObject('new MyClass', $params)`, and any route or service wired that way
`prepare()` appends `_PARAMS` to the resolved call type whenever parameters are present, but `call()`'s switch had no `NEW_OBJECT_PARAMS` case — so the synthesized type matched nothing and `call()` returned `null` without constructing anything. The same applied to an already-instantiated object passed with parameters (`OBJECT_PARAMS`).

```php
// before
(new CallableObject('new MyClass', 'Hello World'))->call();   // null — nothing constructed

// after
(new CallableObject('new MyClass', 'Hello World'))->call();   // MyClass instance, ctor got 'Hello World'
```
`'new MyClass'` now behaves identically to the bare `'MyClass'` form, which always honored its parameters. `OBJECT_PARAMS` returns the object instead of `null`. Both types now have real constants on `AbstractCallable`.

**Migration:** None needed if you avoided the broken form. If you branched on the `null` return, or switched to bare `'MyClass'` to work around it, that code now sees a constructed object.

---

## pop-validator

**Scope:** No class, interface, constant or method was removed or renamed and no constructor changed, but `declare(strict_types=1)` was added library-wide and several validators (`Email`, `Ipv4`, `CreditCard`, `DateTimeBetween*`) had their matching logic or message text rewritten.
**Break count:** 15 (5 high, 5 medium, 5 low)

### `Email` re-implemented on `filter_var()` — previously-valid values now fail
**Severity:** High — **Affects:** any form/API field validated with `Pop\Validator\Email`
v6 used an **unanchored** regex, so any string *containing* something email-shaped passed. v7 uses `filter_var($v, FILTER_VALIDATE_EMAIL)`, which requires the whole string to be an address. **Untrimmed input from a form field is the common casualty.**

```php
// before
(new Email())->evaluate('  test@test.com  ');   // true
(new Email())->evaluate('Bob <bob@x.com>');     // true
(new Email())->evaluate('test@test.com.');      // true
(new Email())->evaluate('x@y.z');               // false

// after
(new Email())->evaluate('  test@test.com  ');   // false
(new Email())->evaluate('Bob <bob@x.com>');     // false
(new Email())->evaluate('x@y.z');               // true  (single-char TLD now accepted)
(new Email())->evaluate('user@[192.168.0.1]');  // true  (IP-literal domain now accepted)
```
**Migration:** Trim/normalize input before validating. If you relied on substring matching, extract the address first.

### `DateTime*` validators fatal on integer Unix timestamps
**Severity:** High — **Affects:** any code passing `time()` / DB timestamps to a DateTime validator
`DateTimeTrait.php` now declares strict types, so its internal `detectFormat(string)` and `strtotime()` calls reject `int`/`float`/`bool`. This hits both the constructor and `evaluate()`.

```php
// before
new DateTimeGreaterThan(time());                       // ok

// after — TypeError: detectFormat(): Argument #1 must be of type string, int given
new DateTimeGreaterThan(date('Y-m-d H:i:s', time()));
```
**Migration:** Cast timestamps to date strings before handing them to any `DateTime*` / `Has*DateTime*` validator.

### `IsJson` fatals on non-string input
**Severity:** High — **Affects:** JSON-column / mixed-type fields validated with `IsJson`

```php
// before
(new IsJson())->evaluate(5);      // true  (json_decode("5"))

// after — TypeError: json_decode(): Argument #1 must be of type string, int given
```
**Migration:** Cast to string, or guard with `is_string()` first.

### `Ipv4` anchored — embedded/padded addresses now fail, and `IsSubnetOf` throws
**Severity:** High — **Affects:** IP validation and every `IsSubnetOf` call
The pattern changed from `/\b…\b/` to `/^…$/`. `IsSubnetOf::evaluate()` calls `Ipv4` first and **throws** when it fails, so an input that merely contains an IP now converts a `false` return into a thrown exception.

```php
// before
(new Ipv4())->evaluate(' 10.0.0.1 ');                // true
(new Ipv4())->evaluate('10.0.0.1:8080');             // true
(new IsSubnetOf('10.0.0'))->evaluate(' 10.0.0.1 ');  // false

// after
(new Ipv4())->evaluate(' 10.0.0.1 ');                // false
(new IsSubnetOf('10.0.0'))->evaluate(' 10.0.0.1 ');  // Pop\Validator\Exception
```
**Migration:** Trim and strip ports before validating; wrap `IsSubnetOf` in try/catch or pre-validate with `Ipv4`.

### `getField()` throws `TypeError` when no field is set
**Severity:** High — **Affects:** any code calling the `ValidatorInterface::getField()` accessor
`str_contains($this->field, '[')` with `?string $field` no longer coerces `null`.

```php
// before
(new Alpha('x'))->getField();   // null

// after — TypeError: str_contains(): Argument #1 must be of type string, null given
```
**Migration:** Guard with `hasField()` first. Internal library calls are already guarded, so this only bites direct callers.

### `CreditCard`: empty input now fails, and `getInput()` is no longer normalized
**Severity:** Medium — **Affects:** optional card fields and anything reading back `getInput()`

```php
// before
$v->evaluate('');                          // true  (vacuously passed!)
$v->evaluate('abcd');                      // TypeError
$v->evaluate('4111 1111 1111 1111');
$v->getInput();                            // '4111111111111111'  (mutated)

// after
$v->evaluate('');                          // false
$v->evaluate('abcd');                      // false
$v->getInput();                            // '4111 1111 1111 1111'  (untouched)
```
**Migration:** If a card field is optional, gate the validator on non-empty yourself. Strip separators yourself if you depended on the normalized `getInput()`.

### `Contains` / `NotContains` / `InArray` / `NotInArray` fatal on arrays holding non-strings
**Severity:** Medium — **Affects:** validators built with a mixed-type array of allowed values

```php
// before
(new Contains(['a', 1]))->evaluate('a1b');   // true

// after — TypeError: str_contains(): Argument #2 must be of type string, int given
new Contains(array_map('strval', $values));
```

### `RegEx` fatals on a null/non-string pattern
**Severity:** Medium — **Affects:** `new RegEx()` with no value, or a pattern from loosely-typed config

### `Rule::parse()` return shape changed for 22 more `Has*` validators
**Severity:** Medium — **Affects:** `Rule::parse()` callers, `Condition::createFromRule()`, `ValidatorSet::createFromRules()`
`Rule::$hasClasses` grew from 10 to 30 entries. Those now get `value` wrapped as `[$field => $value]`, and `ValidatorSet::evaluate()` hands them the whole input array.

```php
// before
Rule::parse('ages:has_one_greater_than:18');
// ['value' => '18', ...]  -> validator then threw "must be an array of node name and value"

// after
// ['value' => ['ages' => '18'], ...]
```
**Migration:** Mostly a fix (those rules were unusable in v6), but drop any workaround that pre-wrapped the value.

### `ValidatorSet::getConditionStatus()` returns `null` instead of `PASSED_ALL`
**Severity:** Medium — **Affects:** code reading condition status on sets with no conditions

**Migration:** Treat `null` as "no conditions"; check `hasConditions()` first, or use `?? ValidatorSet::PASSED_ALL`.

### `ValidatorSet::evaluate()` passes the full input array for post-load validators
**Severity:** Low — **Affects:** sets where validators are added after `loadValidators()`
Validators for missing fields now receive the array, not `null`.

### Array input no longer fatals in the string validators — `NotStartsWith`/`NotEndsWith` now *pass*
**Severity:** Low — **Affects:** code that fed array input and caught the resulting `TypeError`

```php
// before — TypeError
// after
(new NotStartsWith('ab'))->evaluate(['abc']);   // true  (silently passes)
```
**Migration:** Add an `IsArray`/`is_array()` guard if array input must not silently pass validation.

### `Has*` and `Required` no longer throw when input comes from `setInput()`
**Severity:** Low — **Affects:** code relying on the exception for a null `evaluate()` argument
The string validators changed `if (!is_array($input))` to `if (!is_array($this->input))`.

### `HasCount*` return `false` instead of fataling on non-countable nodes
**Severity:** Low — **Affects:** `HasCountEqual`/`GreaterThan`/`LessThan`/`NotEqual` over scalar nodes

### `AlphaNumeric` now honors bracket key-field notation
**Severity:** Low — **Affects:** `AlphaNumeric` used with `setField('user[name]')`
v6 computed the field value and then discarded it, testing the whole array instead — a `TypeError`.

---

## pop-view

**Scope:** No classes/methods removed or renamed; breaks come from a new path-traversal guard on `{{@include}}`/`{{@extends}}` and two silent output-behavior changes in includes and blocks.
**Break count:** 5 (1 high, 2 medium, 2 low)

### `{{@include}}` / `{{@extends}}` paths with `..` or absolute paths now throw
**Severity:** High — **Affects:** any template whose include/extends target reaches out of its own directory (`../layouts/main.html`), or uses an absolute filesystem path
New `Stream::assertSafeTemplatePath()` rejects targets starting with `/` or `\`, matching a Windows drive letter, or containing a `..` segment. It runs in the `Stream` constructor, so it fires at `new View(...)` time, not at render time.

```html
<!-- before: works — resolved relative to the including file's directory -->
{{@extends ../layouts/main.html}}

<!-- after: throws Pop\View\Template\Stream\Exception at construction -->
{{@extends layouts/main.html}}
```
**Migration:** Restructure the template tree so every target is at or below the including template's directory; rewrite all `../` and absolute-path targets.

### `{{@include}}` now defers data substitution — conditionals inside included files change output
**Severity:** Medium — **Affects:** templates whose `{{@include}}`d partials contain `[{if(...)}]` blocks
`parseIncludes()` changed from splicing the included template's *rendered* output (where construction-time data is always `[]`) to splicing its *unrendered* resolved text. In v6 every conditional in an included partial was evaluated against empty data at construction and collapsed to its `[{else}]` branch (or was deleted outright). In v7 those conditionals survive to render time and are evaluated against the real data. This is a fix, but it silently changes rendered output.

```html
<!-- inc.html --> [{if(foo)}]YES-[{foo}][{else}]NO[{/if}]
<!-- main.html --> MAIN:{{@include inc.html}}:END
```
```php
// data: ['foo' => 'FOOVAL']
// before output: MAIN:NO:END
// after output: MAIN:YES-FOOVAL:END
```
**Migration:** Re-check the rendered output of every template that includes a partial containing a conditional; markup that was effectively dead in v4 will now appear.

### `{{parent}}` in a block not named `header` now resolves instead of leaking
**Severity:** Medium · **Bug fix** — **Affects:** templates using `{{@extends}}` inheritance with a block named anything other than `header`
`parseBlocks()` had a hardcoded block name, so under v4 a non-`header` block kept its literal `{{parent}}` marker in the output and never picked up the parent block's content.

```html
<!-- parent.html --> <html>{{content}}DEFAULT{{/content}}</html>
<!-- child.html  --> {{@extends parent.html}}{{content}}{{parent}}-CHILD{{/content}}
```
```php
// before output: <html>{{parent}}-CHILD</html>
// after output: <html>DEFAULT-CHILD</html>
```
**Migration:** Review inheritance-based templates. Anywhere a stray literal `{{parent}}` was tolerated (or stripped downstream), the parent block's real content now appears.

### `Template\File` exception handling: `\Throwable` and `ob_end_clean()`
**Severity:** Low · **Bug fix** — **Affects:** apps that catch a template exception from a `.phtml` view and keep executing
v6 caught only `\Exception` (so an `Error` from a typo'd function call bypassed the handler) and used `ob_clean()`, leaving the output buffer on the stack and silently swallowing subsequent unrelated output. Partial template output that leaked out at shutdown in v4 is now discarded.

**Migration:** Remove any compensating `ob_end_clean()` calls in app-level catch blocks.

### `View::filter()` passes the data key to filters as a string
**Severity:** Low — **Affects:** custom filters that inspect the `$name` argument for numerically-keyed view data
Integer array keys now arrive as `"0"` rather than `0`.

**Migration:** Use loose or string comparison on `$name`.
