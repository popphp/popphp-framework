# Pop PHP Framework v7.0.0

## What's New

Everything **Pop PHP Framework v7.0.0** gives you that **v6.0.0** did not, component by component.

**296 new features** across the bundled components, plus one entirely new package.

This is the companion to [`BC-BREAKS.md`](BC-BREAKS.md). That document tells you what will break; this one
tells you what you get for it.

---

## How to read an entry

Each entry states what the feature is, shows the shortest realistic usage, and says what you had to do in v6
instead — or that it simply was not possible.

Some entries describe functionality that shipped in v6 but was unreachable or broken. Those are called out as
newly *working* rather than brand new, so you can tell whether you are gaining a capability or gaining one
that finally does what it always claimed.

Section lengths vary widely. `pop-audit` is listed with **no new features** because its v7 work went entirely
into correctness rather than API surface, and `pop-image`, `pop-filter`, `pop-dom`, `pop-paginator` and
`pop-dir` are similarly short. A short section means that component was already doing its job.

---

## Framework-level changes

### `popphp/framework` — a new one-line installer

The `popphp/framework` repo becomes the official installer, giving your project **one** `require` entry
instead of the 30+ that came from requiring the raw metapackage.

```bash
composer create-project popphp/framework project-folder
```
Your project's `composer.json` then carries a single `popphp/framework` requirement, and the `kettle` CLI
script lands in the project root ready to use.

### `popphp/pop-parser` — a new bundled component

Native name and address parsing with zero third-party dependencies. New in v7 and bundled with the
framework — see its section for the full API.

### PHP 8.4 across the board

Every component now requires `php >= 8.4.0` and declares `strict_types=1` throughout `src/`, so the whole
framework is uniformly typed.

---

## The ten most significant additions

1. **`pop-http` becomes fully PSR-7/17/18.** Immutable messages, six factories, a PSR-18 client — plus a
   composable middleware pipeline with retry-and-backoff and PSR-3 logging middleware, and a `Mock` handler
   that makes HTTP-calling code testable with no network.
2. **`pop-queue` becomes crash-safe and observable.** A lease-based reserve/release/bury model with a
   dead-letter store means a worker that dies mid-job no longer loses it; a worker registry adds heartbeats
   and stuck-worker detection; daemon loops with signal handling replace cron-per-minute.
3. **`popphp` absorbs Popcorn's routing.** HTTP-verb routes, a fluent verb API, custom verbs and real
   404-vs-405 handling are now native — plus PSR-11/14/15 layers and a `Pop\Dispatch` namespace that makes
   any class a route target.
4. **`pop-db` has its shorthand syntax become a query language.** Structured `[OPERATOR, ...]` tuples, OR/AND groups,
   subqueries (`IN`/`EXISTS`), JSON column querying, composite foreign keys, multi-path eager loading —
   plus Record lifecycle hooks and mass-assignment protection.
5. **`pop-pdf` gains a native PDF reader.** A 39-class `Pop\Pdf\Extract` engine (xref streams, object streams,
   all standard filters, CMap/CID font decoding, a repair pass) replaces `smalot/pdfparser` — and on top of it,
   PDF merging, image-only page detection for OCR routing, and real HTML table layout with colspan/rowspan.
6. **`pop-cache` gains PSR-6 and PSR-16** alongside `remember()` with stampede protection, tag-based
   invalidation, atomic counters, an injectable clock for deterministic TTL tests, and Memory/Null adapters.
7. **`pop-code` catches up to modern PHP.** Attributes, enums, readonly, constructor promotion, variadic and
   by-reference parameters, typed constants, and intersection/DNF types — in both the generator and the
   reflection direction.
8. **`pop-log` becomes a real PSR-3 logger** with placeholder interpolation, context processors, pluggable
   formatters, JSON Lines output, and two new writers — a genuine RFC-3164 UDP syslog writer and a
   stream/stdout writer for containerized deployments.
9. **`pop-color` adds seven color spaces** (HSV/HSB/HWB, Lab/Lch, Oklab/Oklch) with colorimetrically correct
   conversions, CSS Color 4 parsing and rendering, and hex-with-alpha.
10. **`pop-view` can compile templates to PHP.** An opt-in cache directory turns every render after the first
    into a plain `include` — and multi-block layout inheritance actually works now, where v6 only ever
    resolved a block named `header`.

---

## Themes running through the release

**PSR interoperability.** Pop v7 can be dropped into a wider PHP ecosystem: PSR-3 (`pop-log`, `pop-debug`),
PSR-6 and PSR-16 (`pop-cache`), PSR-7/17/18 (`pop-http`), PSR-11 (the service locator), PSR-14 (events) and
PSR-15 (middleware). In most cases the native API is untouched and the PSR surface sits alongside it.

**Testability seams.** A recurring pattern: every component that talks to the outside world grew a way to
substitute that world. `Client\Handler\Mock` (`pop-http`), `Adapter\Memory` + `Queue::fake()` (`pop-queue`),
`Clock\MutableClock` (`pop-cache`), `setInputStream()` (`pop-console`), `setHandler()` (`pop-storage` Azure and
`pop-mail`'s API clients), `setCurrentUrl()` (`pop-nav`), and superglobal-free request construction (`pop-http`).

**Security hardening.** XChaCha20-Poly1305 and HKDF key separation (`pop-crypt`), per-field CSPRNG CSRF tokens
with timing-safe comparison (`pop-form`), `needsRehash()` and `hash_equals()` (`pop-auth`), automatic redaction
of sensitive request data (`pop-debug`), path-traversal rejection (`pop-storage`), HMAC-signed job payloads
(`pop-queue`), formula-injection escaping (`pop-csv`), hardened `unserialize()` (`pop-cache`), secure-by-default
cookie flags and strict mode (`pop-session`), and output escaping in `pop-dom`, `pop-nav`, `pop-paginator` and `pop-i18n`.

**Streaming and memory.** Large payloads stop being buffered: stream bodies and zero-copy multipart
(`pop-http`), `putFileStream()`/`fetchFileStream()` (`pop-storage`), a generator row reader (`pop-csv`), lazy
attachments through `php://filter` (`pop-mime`), one rendered body reused across a BCC loop (`pop-mail`),
compiled templates (`pop-view`), and a single filesystem walk (`pop-dir`).

**Operational visibility.** The framework got easier to run in production: worker registries and dead-letter
queues (`pop-queue`), syslog/stdout/NDJSON logging (`pop-log`), NDJSON debug storage (`pop-debug`), and a
`queue:*` command family in the CLI (`pop-kettle`).

---

## Feature count by component

| Component | v6 → v7 | New features |
|---|---|---|
| pop-http | 5.3.8 → 6.0.0 | 18 |
| popphp (core) | 4.4.4 → 5.0.0 | 18 |
| pop-kettle | 2.3.4 → 3.0.0 | 16 |
| pop-code | 5.0.8 → 6.0.0 | 14 |
| pop-pdf | 5.2.12 → 6.2.0 | 20 |
| pop-queue | 2.1.3 → 3.0.0 | 11 |
| pop-console | 4.2.6 → 5.0.0 | 11 |
| pop-db | 6.8.15 → 7.0.0 | 13 |
| pop-log | 4.0.4 → 5.0.0 | 10 |
| pop-mail | 4.0.7 → 5.0.0 | 10 |
| pop-mime | 2.0.3 → 3.0.0 | 10 |
| pop-cache | 4.0.3 → 5.0.0 | 9 |
| pop-color | 1.0.3 → 2.0.0 | 8 |
| pop-storage | 2.1.3 → 3.0.0 | 8 |
| pop-crypt | 3.0.1 → 4.0.0 | 8 |
| pop-acl | 4.1.4 → 5.0.0 | 7 |
| pop-view | 4.0.4 → 5.0.0 | 7 |
| pop-config | 4.0.4 → 5.0.0 | 7 |
| pop-dir | 4.0.3 → 5.0.0 | 7 |
| pop-css | 2.0.3 → 3.0.2 | 8 |
| pop-i18n | 4.0.3 → 5.0.0 | 6 |
| **pop-parser** | **NEW (1.0.4)** | **10** |
| pop-session | 4.0.4 → 5.0.0 | 6 |
| pop-cookie | 4.0.4 → 5.0.0 | 5 |
| pop-csv | 4.2.5 → 5.0.0 | 5 |
| pop-dom | 4.0.7 → 5.0.0 | 5 |
| pop-form | 4.2.7 → 5.0.0 | 5 |
| pop-paginator | 4.0.3 → 5.0.0 | 5 |
| pop-validator | 4.8.1 → 5.0.0 | 5 |
| pop-debug | 3.0.0 → 4.0.0 | 4 |
| pop-filter | 4.0.4 → 5.0.0 | 4 |
| pop-image | 4.1.3 → 5.0.0 | 4 |
| pop-nav | 4.1.5 → 5.0.0 | 4 |
| pop-utils | 2.4.2 → 3.0.0 | 4 |
| pop-auth | 4.0.3 → 5.0.0 | 4 |
| pop-audit | 2.0.3 → 3.0.0 | 0 |

---

# Component-by-component breakdown

## popphp (framework core) — 4.4.4 → 5.0.0

**Summary:** The core absorbed Popcorn's HTTP-verb routing natively, grew a reusable `Pop\Dispatch` namespace, added PSR-11/14/15 interoperability, and gained cross-application state merging.
**Feature count:** 18

### Popcorn-style method-grouped route configs
**If your v6 app groups its routes by verb, that config still works — the core router understands the shape
natively, with no `popphp/popcorn` install and no rewrite.** A top-level routes key that is a bare verb, or a
comma-separated list of verbs, is recognized as a **method group**, and every route underneath it is
registered with that verb list as its `method`.

```php
'routes' => [
    'post,options' => [
        '/users/create' => ['controller' => UsersController::class, 'action' => 'create'],
    ],
    'get' => [
        '/users' => ['controller' => UsersController::class, 'action' => 'index'],
    ],
],
```

`POST /users/create` dispatches; `GET /users/create` returns a real **405** with an `Allow: OPTIONS, POST`
header rather than a 404.

**A key is treated as a method group only when it cannot be a path.** It must not be empty, must not be
`'*'`, must not begin with `/`, must not end with `/*`, and must not contain `:controller`; every
comma-separated segment must be a known verb (the nine standard ones, or a verb you registered with
`addCustomMethod()`). Anything else is a route path, so a literal path is never mistaken for a verb list.

**Groups may nest paths to any depth.** The verb list is applied to every leaf config in the sub-tree, not
just the group's immediate children:

```php
'routes' => [
    'get,options' => [
        '/users' => [
            '[/]'    => ['controller' => UsersController::class, 'action' => 'index'],
            '/count' => ['controller' => UsersController::class, 'action' => 'count'],
        ],
    ],
    'post,options' => [
        '/users' => [
            '/create' => ['controller' => UsersController::class, 'action' => 'create'],
        ],
    ],
],
```

Three further behaviors worth knowing:

- **The same path can live under several groups** with a different controller in each. `GET /users` and
  `POST /users` above resolve to different actions, and a verb no group covers 405s.
- **The group wins over a nested `method` key.** A route config inside a `'post'` group that also carries
  `'method' => 'get'` is registered as `post` — the group is authoritative, matching Popcorn's semantics.
- **Groups mix freely with ordinary path routes** in the same config. A route registered at the top level
  with no method information matches *any* verb.

Verb lists are normalized and sorted, so `'post,options'` and `'options,post'` are equivalent and
`getRouteConfig('method')` returns `['options', 'post']` for both.

> **Note:** `'*'` is *not* a method group in the core router — it is the wildcard/default-route key. A Popcorn
> `'*'` block does not register the routes inside it. See the BC-breaks document.

**Previously:** the core router had no concept of a method group, so this config silently registered an unmatchable
route literally named `'options,get/users'`. Getting verb-scoped routing meant installing `popphp/popcorn` and
bootstrapping through `Popcorn\Pop` instead of `Pop\Application`.

### Native HTTP-method routing (`method` route key)
The other way to constrain verbs is per route, rather than by group: any HTTP route config can carry a `method` key — a verb string, a comma-separated string, or an array of verbs — normalized internally to a sorted lowercase list. Use it when a single route needs its own verb list, or when you would rather keep each route's constraint next to the route itself. The same path can be registered multiple times under different verbs with different controllers — the classic REST pattern — because method-constrained routes are stored under a composite key rather than colliding on the shared path regex.

```php
// New
'routes' => [
    '/users' => ['controller' => UsersController::class, 'action' => 'index',  'method' => 'get'],
    '/users' => ['controller' => UsersController::class, 'action' => 'create', 'method' => 'post,put'],
],
```
**Previously:** Not possible. A route pattern matched any verb, so you branched on `$_SERVER['REQUEST_METHOD']` inside the action; registering the same path twice silently overwrote the first entry.

### Fluent verb API on `Application`, `Router` and `Match\Http`
Nine declared verb methods — `get()`, `head()`, `post()`, `put()`, `delete()`, `trace()`, `options()`, `connect()`, `patch()` — each `(string $route, mixed $controller): static`, exist at all three layers as thin proxies. Guards (`Router::httpMatch()`, `Application::httpRouter()`) throw when the active router isn't HTTP-mode, so `Application` stays HTTP/CLI-agnostic everywhere else.

```php
// New
$app->get('/users', 'MyApp\Controller\UsersController')
    ->post('/users', 'MyApp\Controller\UsersController');
```
**Previously:** Not possible in the core — only the separate `popphp/popcorn` package offered a verb API, and its per-verb route tree could not generate named-route URLs for any verb other than the one being handled.

### Custom HTTP verbs
`addCustomMethod()`, `addCustomMethods()` and `hasCustomMethod()` maintain a whitelist, proxied up through `Router` and `Application`. A whitelisted verb is then callable as a fluent method via `__call()`, which returns `static` so custom verbs chain like the standard nine.

```php
// New
$app->addCustomMethod('propfind');
$app->propfind('/dav', 'MyApp\Controller\DavController');
```
**Previously:** Not possible in the core (Popcorn-only, and its `__call()` returned `void`, breaking the chain).

### 404 vs. 405 Method Not Allowed
Matching no longer stops at the first path match whose method is rejected — it keeps scanning and records every verb a path-matching-but-rejected route would have accepted. `Match\Http` exposes `hasMethodMismatch()`, `getAllowedMethods()` and `methodNotAllowed()` (sending `405` plus an `Allow:` header), which `Application::run()` consults. A wildcard `'*'` fallback still wins over a 405.

```php
// New — inside Application::run()'s error branch
if ($this->router->isHttp() && $this->router->hasMethodMismatch()) {
    $this->router->methodNotAllowed($this->router->getAllowedMethods(), $exit);
} else {
    $this->router->noRouteFound($exit);
}
```
**Previously:** Not possible — every unmatched request collapsed into `noRouteFound()`'s 404.

### Content-negotiated 404 and 405 bodies
Both built-in error responses now check the request's `Accept` header and emit JSON instead of the HTML stub
when the client asks for `application/json` and not `text/html`. The 405 payload carries the allowed verbs
alongside the `Allow:` header, so an API consumer gets a machine-readable body without the app having to
override the handler.

```php
// GET /users with  Accept: application/json  — and only POST registered
// HTTP/1.1 405 Method Not Allowed
// Allow: POST
// Content-Type: application/json
{
    "error": "Method Not Allowed",
    "allowed": ["POST"]
}
```
**Previously:** both handlers emitted a fixed HTML document regardless of `Accept`, so an API client had to
override `noRouteFound()` to get anything parseable.

### `Pop\Dispatch` — dispatchability decoupled from controllers
A new namespace holding what used to be controller-only machinery: `DispatchableInterface`, `AbstractDispatcher`, `MaintenanceInterface` + `MaintenanceTrait`, `HttpTrait`, `ConsoleTrait` and `Dispatch\Exception`. Any class — a controller, `pop-console`'s `AbstractCommand`, or a hand-written class — can now become a route target by extending `AbstractDispatcher`.

```php
// New
namespace MyApp;

use Pop\Dispatch\{AbstractDispatcher, HttpTrait};

class ApiHandler extends AbstractDispatcher
{
    use HttpTrait;

    public function index(): void { $this->response()->setBody('ok'); }
}
```
**Previously:** Dispatch logic lived inline in `AbstractController::dispatch()` and `Router::route()` threw unless the target implemented `Pop\Controller\ControllerInterface`, so nothing outside the controller hierarchy could be routed to.

### `Event\Manager` is itself a PSR-14 dispatcher
`Pop\Event\Manager` implements `Psr\EventDispatcher\EventDispatcherInterface` and
`ListenerProviderInterface` directly — it is one event system with two ways in, not a PSR layer bolted
alongside the native one. String-keyed `on()`/`trigger()` and class-keyed `listen()`/`dispatch()` run through
the same `dispatch()` call, so a listener registered either way sees the same firing.

There is one typed event class per built-in hook point, all under `Pop\Event\`: `InitEvent`,
`RoutePreEvent`, `DispatchPreEvent`, `DispatchPostEvent` and `ErrorEvent` (which also exposes `exception()`).

```php
// New — class-keyed, PSR-14 style
use Pop\Event\RoutePreEvent;

$app->events()->listen(RoutePreEvent::class, function(RoutePreEvent $event) {
    $event->application();
});

// New — string-keyed, unchanged from before; both reach the same event
$app->on('app.route.pre', function($application) { /* ... */ });
```

Two behaviors worth knowing:

- **`listen()` resolves by exact event class**, with no inheritance walk — a listener on a parent class does
  not receive subclasses.
- **Only `Application` constructs the typed classes.** A direct `Manager::trigger($name, $params)` call always
  builds a generic `Pop\Event\Event`, so a same-named `trigger('app.dispatch.pre')` from your own code will
  not reach a `listen(DispatchPreEvent::class, ...)` listener.

**Previously:** only string-keyed `$app->on('app.route.pre', ...)` existed — no PSR-14 interfaces, no
`psr/event-dispatcher` dependency, and no way to hand Pop's dispatcher to a library expecting the standard.
### Propagation control and a clean abort for listeners
A listener can stop later listeners for the same event by calling `stopPropagation()` on the event object,
which arrives as an extra trailing parameter after `$result`. To end the whole run, it throws
`Pop\Event\AbortException`, which `Application::run()` catches and handles cleanly.

```php
// New
$app->on('app.route.pre', function($application, $result, $event) {
    $event->stopPropagation();          // later app.route.pre listeners are skipped
});

$app->on('app.route.pre', function($application) {
    if ($application->isDown()) {
        throw new Pop\Event\AbortException('Application is in maintenance mode.');
    }
});
```
**Previously:** a listener returned the `Manager::STOP` constant to halt propagation, and `Manager::KILL` to end the
run — except `KILL` only set an internal `$alive` flag that nothing in the framework ever read, so it never
stopped anything.

### PSR-15 middleware bridge
`Pop\Middleware\Psr15\MiddlewareAdapter` wraps a real `Psr\Http\Server\MiddlewareInterface` into Pop's queue, backed by `Psr15\RequestHandler`. Deliberately a bridge rather than end-to-end support: Pop's own request/response are still not PSR-7, so the wrapped middleware only sees a genuine `ServerRequestInterface` if the app supplies one.

```php
// New
use Pop\Middleware\Psr15\MiddlewareAdapter;

$app->middleware->addHandler(new MiddlewareAdapter(new SomeThirdPartyPsr15Middleware()));
```
**Previously:** Not possible — no way to run a PSR-15 handler in Pop's queue.

### PSR-11 service container
`Pop\Service\Locator` now implements `Psr\Container\ContainerInterface`, gaining `has(string $id): bool`. A new `Pop\Service\NotFoundException` (implementing `NotFoundExceptionInterface`) is thrown by `get()` for an unregistered name, and `Service\Exception` implements `ContainerExceptionInterface` — both still catchable as their original types.

```php
// New
function boot(\Psr\Container\ContainerInterface $c) { /* ... */ }

boot($app->services());
$app->services()->has('foo');
```
**Previously:** Not possible — `Locator` implemented no standard interface.

### Cross-application state merging
`mergeServices()`, `mergeMiddleware()`, `mergeEvents()` and the one-call `mergeApplication()`. Services/middleware merge overwrite-by-name; `mergeEvents()` clones each source priority queue and re-registers every listener at its original priority so both apps' listeners survive. `mergeConfig()` gained a third `array $exclude = []` that strips named top-level keys from the *incoming* config.

```php
// New
$kettleApp->mergeApplication($customApp, false, ['routes']);
```
**Previously:** Not possible — no merge methods, and no way to protect a key, so an incoming config's `routes` always clobbered the host app's.

### Specificity-based route ordering
Each prepared route gets a specificity score — `1000 - (requiredParams * 5) - (optionalParams * 10) - (hasArrayParam ? 500 : 0)` — and both matchers sort most-specific-first using PHP 8's stable sort, so declaration order remains the tiebreaker.

```php
// New — resolves to the literal route regardless of declaration order
'routes' => [
    '/users/:id' => [...],   // specificity 995
    '/users/new' => [...],   // specificity 1000 — wins for /users/new
],
```
**Previously:** First match in declaration order won, so config ordering decided whether `/users/new` hit the literal route or was swallowed by `/users/:id`.

### Callable-object route targets
A route's `controller` can now be any plain string/array callable that isn't an `AbstractDispatcher` subclass; `Application` invokes it through `Pop\Utils\CallableObject` with the route params, in both the direct and middleware paths.

```php
// New
'routes' => [
    '/user/:id' => ['controller' => 'MyApp\Handler::showUser'],
],
```
**Previously:** Not possible — only a `Closure` or a `ControllerInterface` implementation; anything else threw.

### Route target shorthand strings, on every registration path
A route target no longer has to be split into `controller`/`action` keys. The route value itself can be a single pop-utils pseudo-callable string, and every registration path normalizes it identically — array configs, wildcard/default routes, the fluent verb methods, and method-group nested routes — through one shared `Match\AbstractMatch::normalizeController()`.

```php
// New — all four register the same target
'routes' => ['/users' => 'MyApp\Handler->listUsers'],

$app->get('/users', 'MyApp\Handler->listUsers');
$router->addRoute('/users/*', 'MyApp\Handler->listUsers');
$app->addRoutes(['get,post' => ['/users' => 'MyApp\Handler->listUsers']]);
```

`'Class->method'` instantiates then calls the method, `'Class::method'` calls it statically, and a bare `'Class'` — or its alternate spelling `'new Class'` — constructs, with route params going to the *constructor*. Arrays are passed through untouched, so an existing `['controller' => ..., 'action' => ...]` config and a nested-route sub-array are unaffected.

Shorthand is not a shorter spelling of a controller route. It dispatches through `CallableObject`, so the target is built with a plain `new Class()` and gets no application injected — a controller routed that way has no `$this->application()`, `$this->request` or `$this->response`, and falls back to the generic maintenance response instead of its own `dispatchMaintenance()`. A class extending `Dispatch\AbstractDispatcher` only takes the dispatcher path when it is named by the `controller` key with an explicit `action`. Use shorthand for plain handlers; keep `controller`/`action` for anything that wants the framework wiring.

Detection now runs through `Pop\Utils\CallableObject::isCallable()` rather than PHP's `is_callable()`, which also means a malformed target fails loudly at *registration* time — `'MyApp\NoSuchHandler->listUsers'` throws `Pop\Utils\Exception` naming the missing class or method, instead of quietly registering a route that can never dispatch.

**Previously:** `is_callable()` was the gate, and it is false for every one of these forms — `'Class->method'`, `'new Class'`, a bare class name, and even `'Class::method'` when the method isn't static. Only real PHP callables (closures, function names, genuinely-static method strings) were accepted; everything else was stored raw and silently failed to match a dispatchable.

### Uniform maintenance mode for every route target shape
Maintenance handling moved into `Application::run()`, checked once right after `getDispatchable()`. A `MaintenanceInterface` target gets `dispatchMaintenance()`; anything that can't implement an interface (a closure, a callable-object route) gets the new `renderMaintenanceResponse()` — an HTTP 503 page or a CLI `Service Unavailable.` line.

```php
// New — a closure route is now covered too, with no extra setup
$app->get('/status', function() { echo 'up'; });  // returns 503 while MAINTENANCE_MODE is on
```
**Previously:** Only controller routes honored maintenance mode; closure routes dispatched normally while the app was "down".

### Forced routes with real params, and repeatable `run()`
`run()` and `Router::route()` now accept an array of pre-split segments as well as a string, and both matchers re-`seed()` their segments from the forced route, so a forced route's `:params`/`<params>`/`[--options]` parse through the same path a real request uses. Both also clear per-match state at the top of every call, making repeated dispatch against one booted `Application` (a queue worker) safe.

```php
// New
$app->run(false, 'send:email --quiet 1001');
$app->run(false, ['send:email', '-q', 'John Smith']);  // array form: values with spaces
```
**Previously:** `$forceRoute` was `?string` only, and it was matched against the regex while params were still parsed from the real request — forced routes silently lost their params.

### Application full name
`setFullName()`, `getFullName()` and `hasFullName()` — a human-readable display name alongside the existing slug-like `name` and `version`.

```php
// New
$app->setName('my-app')->setFullName('My Application')->setVersion('1.2.0');
```
**Previously:** Only `name`/`version` existed.

### Smaller additions
- `Dispatch\HttpTrait` gained `getApplication()`, `setApplication()`, `setRequest()`, `setResponse()`, `hasApplication()`, `hasRequest()`, `hasResponse()`; `ConsoleTrait` gained the console equivalents. Both now take a *nullable* `?Application` first argument.
- `Router::route()` walks a dispatchable's **entire parent class chain** collecting `class_uses()` (statically cached) when deciding whether to inject the application, so a shared base class can declare the trait once for all subclasses.
- `MatchInterface` now declares `name()`, `hasName()` and `getRouteConfig()`, so custom matchers must support named routes and route-config access.
- `Middleware\Manager::process()` is now re-entrant — the remaining-handler queue is threaded through the continuation closure instead of a static property, so a middleware can safely trigger a nested `process()`.
- `Application::autoloader()`, `App::autoloader()`, `registerAutoloader()` and `bootstrap()` are typed against `\Composer\Autoload\ClassLoader` instead of `mixed`/duck-typing.

---

## pop-acl — 4.1.4 → 5.0.0

**Summary:** Wildcard permissions, role/resource removal with cascade cleanup, effective-permission introspection, working array-permission checks, and role-hierarchy teardown.
**Feature count:** 7

### Wildcard `'*'` permissions
`'*'` is now a reserved permission meaning "any permission." `allow()` grants everything on a resource in one rule and `deny()` blocks everything; deny still wins, so a wildcard allow can be narrowed by a specific deny.

```php
// New
$acl->setStrict();
$acl->allow($admin, $page, '*')       // can do anything to a page...
    ->deny($admin, $page, 'delete');  // ...except delete it

$acl->isAllowed($admin, $page, 'edit');   // true
$acl->isAllowed($admin, $page, 'delete'); // false
```
**Previously:** not possible — `'*'` was a literal permission name with no special meaning. You enumerated every permission in an `allow()` array, or left the list empty (blanket allow) and lost the ability to carve out exceptions.

### `removeRole()` and `removeResource()`
`removeRole()` reparents the role's children onto its own parent (or makes them roots), detaches it from its parent, and purges every allow/deny rule, assertion and policy referencing it. `removeResource()` purges that resource's rules and assertions across *every* role, plus any policies naming it, dropping now-empty role entries so no accidental blanket rule is left behind.

```php
// New
$acl->removeRole($admin);
$acl->removeResource($page);
var_dump($acl->hasRole('admin'), $acl->hasResource('page')); // false, false
```
**Previously:** not possible — the only removal API was `removeAllowRule()`/`removeDenyRule()`, which strip rules but leave the role/resource registered and leave orphaned children, assertions and policies behind.

### `getAllowedPermissions()` / `getDeniedPermissions()`
Introspection into the effective rule set for a role on a resource, merged up the parent chain. Returns `['*']` when access is unrestricted at any level, or `[]` when no rule exists — useful for rendering UI, debugging, or exporting an ACL without probing `isAllowed()` permission-by-permission.

```php
// New
$acl->allow($editor, $page, 'read');
$acl->allow($reader, $page, 'comment');   // reader is a child of editor
print_r($acl->getAllowedPermissions($reader, $page)); // ['comment', 'read']
```
**Previously:** not possible — the rule arrays were protected with no accessor, so the only way to learn a role's permissions was to guess names and call `isAllowed()` for each, reimplementing inheritance merging by hand.

### Array permissions now work in `isAllowed()` / `isDenied()`
Passing an array of permissions to a check now works end to end; the assertion-key methods widened `$permission` from `?string` to `mixed` and join arrays internally.

```php
// New
$acl->isAllowed($admin, $page, ['edit', 'delete']); // true — all must be allowed
$acl->isDenied($admin, $page, ['edit', 'delete']);  // true if any is denied
```
**Previously:** always threw. `isDenied()` called an assertion-key method typed `?string`, so an array raised a `TypeError` before any evaluation; `isAllowed()` calls `isDenied()` first, so it threw too. The array-handling code in `allow()`/`deny()` existed but was unreachable from a check.

### `AclRole::removeChild()` and `clearParent()`
The role hierarchy can now be torn down, not just built up. `removeChild()` detaches a child and clears its back-reference; `clearParent()` detaches a role from its parent, making it a root.

```php
// New
$editor->removeChild($reader);  // $reader->getParent() is now null
$reader->clearParent();
```
**Previously:** not possible — `AclRole` had only `addChild()`/`setParent()`, with the backing properties protected and no way to unset them, so a hierarchy was write-once for the life of the objects.

### Rules survive when a policy doesn't match
`isAllowed()`/`isDenied()` only let a policy override the rule-based result when a policy actually matched. Registering a policy for one role no longer poisons checks for unrelated roles and resources, so rule-based and policy-based authorization can coexist in a single `Acl`.

```php
// New
$acl->addPolicy('update', $editor, $page); // policy for editor only
$acl->allow($admin, $page, 'edit');
$acl->isAllowed($admin, $page, 'edit');    // true — falls back to the rule
```
**Previously:** the policy result overwrote the rule result unconditionally; a non-matching evaluation returned `null`, which the `bool` return type coerced to `false`. **Adding a single policy effectively denied every check that policy didn't cover.**

### New protected extension points
The duplicated allow/deny paths were factored into overridable protected methods, giving subclasses single hook points for custom rule storage or evaluation.

```php
// New
protected function setRule(string $type, mixed $role, mixed $resource = null, mixed $permission = null, ?AssertionInterface $assertion = null): Acl
protected function removeRule(string $type, mixed $role, mixed $resource = null, mixed $permission = null): Acl
protected function resolveDenied(mixed $role, mixed $resource, mixed $permission, bool|null $policyResult): bool
protected function getEffectivePermissions(string $type, mixed $role, mixed $resource): array
protected function deleteRuleAssertions(string $type, string $roleName, string $resourceName, array $permissions): void
```
**Previously:** not possible — `allow()`/`deny()` and the two removal methods each carried a full copy of the logic inline, so overriding behavior meant overriding all four public methods.

### Smaller additions
- `PolicyTrait::can()` validates that every method in a comma-separated list is callable *before* invoking any of them, throwing with the offending method and class name; v6 silently skipped unknown methods and could return a stale result from a prior method.
- `hasAssertionKey()` is implemented as `getAssertionKey(...) !== null`, so the two can no longer disagree.
- `isAllowed()` evaluates policies once and passes the result into the internal deny check — policy methods with side effects now fire once per check instead of twice.
- `PolicyTrait` carries `@phpstan-require-implements PolicyInterface`, so static analysis enforces the pairing.

---

## pop-audit — 2.0.3 → 3.0.0

**Summary:** A correctness pass on diff resolution and state retrieval — two auditing paths that silently recorded nothing in v6 now work.
**Feature count:** 0

The public API is unchanged, so there is nothing new to adopt. What changed is that auditing now captures
changes it used to miss.

### Previously-broken functionality that now works

**`resolveDiff()` records key-wise differences.** v6 used `array_keys(array_diff($old, $new))`, which compares *values* as strings across the whole array. Two real classes of change were silently dropped: a swap of values between keys (`['a'=>1,'b'=>2]` → `['a'=>2,'b'=>1]` produced an empty diff, so `Auditor::send()` returned `false` and **nothing was recorded**), and any array-valued field (`array_diff` string-casts it and compares every array as `"Array"`). v7 walks `$old` key by key with a loose comparison, which handles nested arrays correctly, and guards with `array_key_exists()` so a key present in `$old` but absent from `$new` no longer emits a warning.

`resolveDiff()` also resets `$original`/`$modified` at entry, so reusing one adapter instance across several records no longer leaks the prior record's diff into the next one.

**`Table` multi-row reads return decoded `old`/`new`.** In v6 only `getStateById()` JSON-decoded those columns; `getStates()`, `getStateByModel()`, `getStateByTimestamp()` and `getStateByDate()` returned raw JSON strings, so callers had to know which method they had used and decode by hand. All five now return arrays uniformly.

**`File` ordering and timestamp filtering survive file copies.** `send()` has always embedded `time()` in the filename, but v6's read methods all used `filemtime()` instead. Copying, syncing or restoring the audit folder rewrote every mtime and destroyed the audit ordering. v7 parses the timestamp back out of the filename.

### Smaller additions
- `Http::getFetchedResult()` `instanceof`-checks the response before calling `hasBody()`, instead of assuming a `Client\Response` came back.
- `Http::decodeState()` fixes a dead JSON guard: v6's check was true for almost any malformed input, so bad JSON was written into the result as `null`; v7 checks `json_last_error()` and leaves the raw string in place on failure.
- `Table` timestamp/date queries use `pop-db` 7's structured array predicates rather than the key-suffix form.
- `AuditableModel` extends `Pop\Db\Model\AbstractDataModel` — the base class moved from `popphp` to `pop-db` upstream, so this is dependency tracking rather than added capability.

---

## pop-auth — 4.0.3 → 5.0.0

**Summary:** Transparent password-hash upgrade detection, timing-safe plaintext credential comparison, typed exceptions that separate "the check couldn't run" from "the credentials were wrong," and a new `Jwt` adapter that verifies a token's signature and claims with no network or database dependency.
**Feature count:** 4

### Typed `Pop\Auth\Exception` for infrastructure failures
Every adapter's `authenticate()` now throws when it cannot perform the credential check at all — an unreadable access file, or malformed/unusable key material for the `Jwt` adapter. **A `0` return now unambiguously means "credentials checked and rejected,"** so an app can log and alert on outages instead of silently reporting a bad password. Wrapped causes are preserved as `$previous`.

```php
// New
$auth = new Auth\File('/path/to/.htmyauth');

try {
    if ($auth->authenticate('admin', 'password')) {
        // authenticated
    } else {
        // credentials genuinely didn't match
    }
} catch (Auth\Exception $e) {
    // adapter couldn't run the check (unreadable file, malformed JWT key material)
}
```
**Previously:** not possible to distinguish. `File::authenticate()` called `file()` unguarded and fell into `foreach (false)` on an unreadable file. The only `throw` anywhere in v6's `src/` was for a nonexistent access file at construction.

### `needsRehash()` on `AbstractAuth` / `AuthInterface`
After a successful `verify()`, the adapter records whether the stored hash should be upgraded — either because it was stored unhashed, or because `password_needs_rehash()` reports it as out of date against `PASSWORD_DEFAULT`. This lets an app migrate legacy plaintext or weak-cost hashes on the next successful login, **while it still holds the cleartext password**. It is part of the interface contract, so it is available on every adapter — though only `File` ever has a hash to compare against; `Jwt` verifies a signature instead, so `needsRehash()` is always `false` there.

```php
// New
$auth->authenticate('admin', 'password');

if ($auth->isAuthenticated() && $auth->needsRehash()) {
    $newHash = password_hash('password', PASSWORD_DEFAULT);
    // persist $newHash yourself — pop-auth never writes to storage
}
```
**Previously:** not possible. `verify()` returned only a bool and discarded the `password_get_info()` result; an app wanting this had to re-fetch the stored hash itself and call `password_needs_rehash()` outside the adapter.

### Timing-safe comparison of unhashed stored credentials
When the stored credential is not a recognized password hash (plaintext access-file entries), `verify()` compares it with `hash_equals()` instead of `===`, removing the early-exit byte leak that made the comparison time-dependent on how many leading characters matched.

```php
// New — src/AbstractAuth.php
$isUnhashed = (($info['algo'] == 0) && ($info['algoName'] == 'unknown'));
$result     = $isUnhashed ? hash_equals($hash, $password) : password_verify($password, $hash);
```
**Previously:** a plain `($password === $hash)` on the unhashed path; nothing in the component called `hash_equals()`.

### `Jwt` adapter — signature and claims verification
A new adapter that authenticates a bearer token by verifying its signature (`HS256`/`RS256`/`ES256`, via a new `Pop\Crypt\Signature\Verifier` primitive) and claims (`exp`/`nbf` always checked with an optional leeway, `aud`/`iss` opt-in), with no network call and no stored credential lookup. The algorithm is fixed at construction and never read from the token itself, avoiding the JWT "alg confusion" vulnerability class. Successfully verified claims are available via `getUser()`.

```php
// New
$auth = new Auth\Jwt('HS256', $secret);
$auth->setAudience('my-api')->setLeeway(30);

if ($auth->authenticate($token)) {
    $claims = $auth->getUser();
}
```
**Previously:** not possible — no token-based adapter existed; JWT verification had to be implemented outside `pop-auth` entirely.

### Smaller additions
- `AuthInterface::authenticate()` and each implementation carry `@throws Exception` docblocks, making the new failure mode part of the published contract.
- `AuthInterface::authenticate()`'s signature widened to `authenticate(string $credential, ?string $secondary = null): int`, so `File`'s two-part credential and `Jwt`'s single token share one contract.
- README gains "Handling Exceptions," "Using a JWT," and "Rehashing Passwords" sections, plus previously undocumented behavior: `File`'s realm-scoped `username:realm:hash` entries and custom delimiter.

---

## pop-cache — 4.0.3 → 5.0.0

**Summary:** Full PSR-16 and PSR-6 interoperability plus a modern caching toolkit — `remember()` with stampede protection, tags, atomic counters, an injectable clock, and two dependency-free adapters.
**Feature count:** 9

### PSR-16 (SimpleCache) compliance
`Pop\Cache\Cache` now implements `\Psr\SimpleCache\CacheInterface` directly, so any existing `Cache` instance can be handed to any library or framework that accepts a PSR-16 cache. The eight PSR methods live alongside the pre-existing `getItem`/`saveItem`/`hasItem`/`deleteItem` API and read the same underlying values.

```php
// New
function useAnyPsr16Cache(\Psr\SimpleCache\CacheInterface $cache): void {
    $cache->set('foo', 'bar', 300);
    $cache->get('foo', null);         // 'bar'
}
useAnyPsr16Cache(new Cache(new Redis(300)));
```
**Previously:** Not possible — `Cache` implemented only `\ArrayAccess`, had no `get`/`set`/`has`/`delete`/`*Multiple` methods and no PSR dependency; you had to write your own bridge class.

### PSR-6 CacheItemPool with deferred saves
New `Pop\Cache\Psr6\CacheItemPool` and `Psr6\CacheItem` wrap any `pop-cache` adapter. (It could not be folded into `Cache` because PSR-6 declares `getItem`/`hasItem`/`deleteItem`/`clear` with signatures incompatible with `Cache`'s own.) Deferred items are visible to `getItem()`/`hasItem()` per PSR-6 §1.4, and `__destruct()` commits so pending writes are never silently dropped.

```php
// New
$pool = new Pop\Cache\Psr6\CacheItemPool(new Redis(300));
$item = $pool->getItem('foo');
if (!$item->isHit()) {
    $item->set('bar')->expiresAfter(300); // int | \DateInterval | null
    $pool->save($item);
}
```
**Previously:** Not possible — no `Psr6` namespace, no item objects, no deferred-write buffer.

### `remember()` with probabilistic early recomputation
`Cache::remember(string $id, callable $callback, ?int $ttl = null, float $beta = 0.0): mixed` returns the cached value or computes, caches, and returns it. A unique sentinel compared by `===` means a legitimately falsy result (`false`/`null`/`0`/`''`) is cached correctly rather than recomputed forever. `$beta > 0.0` enables XFetch-style stampede protection: as an item nears its TTL, reads have a rising chance of refreshing it early while concurrent readers keep getting the still-valid copy.

```php
// New
$value = $cache->remember('expensive-report', fn() => generateExpensiveReport(), 300, 1.0);
```
**Previously:** Not possible as a built-in — you hand-wrote `hasItem()`/`getItem()`/`saveItem()` around every callback, and a cached `false` was indistinguishable from a miss. No stampede mitigation existed.

### Tag-based bulk invalidation
`saveTaggedItem()` associates tags with an item; `invalidateTag()`/`invalidateTags()` delete every item under a tag without knowing its ids. Implemented as pure composition over the adapter's own primitives, so it works identically on all eight adapters with zero adapter changes. Bookkeeping lives under `@`-prefixed keys that user keys can never collide with.

```php
// New
$cache->saveTaggedItem('product-1', $data, ['products', 'category-electronics'], 300);
$cache->invalidateTag('category-electronics');       // deletes product-1
$cache->invalidateTags(['products', 'category-books']);
```
**Previously:** Not possible — you tracked the id list yourself in a separate cache entry and looped `deleteItem()`.

### Atomic increment/decrement counters
`incrementItem(string $id, int $amount = 1, int $initial = 0, ?int $ttl = null): int` and `decrementItem(...)` were added to `Cache`, `AdapterInterface` and every adapter — a counter primitive for rate limiting, view counts and concurrency-safe tallies. `Apc`/`Memcached`/`Redis` get genuine backend-native atomicity (`apcu_add()`+`apcu_inc()`; `Memcached::add()`+`increment()`; a Lua `EVAL` on Redis so a custom initial value is seeded atomically). `File`/`Database`/`Session`/`Memory`/`NullAdapter` do a non-atomic read-modify-write.

```php
// New
$views    = $cache->incrementItem('page-views-123');                  // creates at 0, +1 -> 1
$attempts = $cache->decrementItem('login-attempts-user42', 1, 5, 60); // creates at 5, -1 -> 4
$current  = $cache->incrementItem('page-views-123', 0);               // peek without mutating
```
**Previously:** Not possible atomically — no increment/decrement in the contract, so a counter meant a racy read/+1/write in user code even on Redis/APCu.

### Injectable clock with `MutableClock`
New `Clock\ClockInterface` (`now(): int`), `Clock\SystemClock` and the shipped-public `Clock\MutableClock` (`setTime()`/`advance()`). Every adapter constructor takes a trailing optional `$clock`, as does `Cache` itself, replacing hardcoded `time()` calls in the expiration envelope — TTL behavior becomes testable deterministically without `sleep()`.

```php
// New
$clock = new Pop\Cache\Clock\MutableClock();
$cache = new Cache(new File('/path/to/cache', 0, $clock));
$cache->saveItem('foo', 'bar', 10);
$clock->advance(11);
$cache->getItem('foo'); // false — expired, no sleep() needed
```
**Previously:** Not possible — every adapter called `time()` directly; testing expiry required real `sleep()`.

### `Memory` and `NullAdapter` adapters
Two new dependency-free adapters, always reported as available. `Memory` is an in-memory array store for tests and single-request caching — it never serializes, so a cached object comes back as the exact original instance. `NullAdapter` is a true no-op, disabling caching wholesale without touching call sites.

```php
// New
$cache = new Cache(new Pop\Cache\Adapter\Memory(300));      // in-memory, no extension needed
$cache = new Cache(new Pop\Cache\Adapter\NullAdapter());     // caching disabled entirely
```
**Previously:** Not possible — all six adapters required a real backing store, extension, or writable directory.

### Default value on miss for `getItem()`/`getItemTtl()`
An optional `$default` was threaded through the contract and all eight adapters, finally letting callers distinguish a genuine miss from a legitimately cached `false` — the ambiguity that also made a correct `remember()` impossible in userland.

```php
// New
$cache->saveItem('feature_enabled', false);
$cache->getItem('feature_enabled', null);     // false — the real cached value
$cache->getItem('never_cached_key', null);    // null  — a genuine miss
```
**Previously:** Not possible — `getItem()` returned bare `false` for both cases.

### Namespace-scoped `clear()`/`destroy()`
`Apc`, `Memcached`, `Redis` and `Session` each take a trailing `string $namespace = 'pop_cache'`. The first three scope `clear()`/`destroy()` via generational versioning (a per-namespace version counter folded into every key — `clear()` bumps the version instead of flushing the backend), shared through the new `Adapter\NamespacedVersionedKeys` trait. Multiple apps can now safely share one APCu/memcached/redis instance.

```php
// New
$cache = new Cache(new Apc(300, 'my-app'));
$cache->clear(); // only 'my-app' keys — other tenants untouched
```
**Previously:** Not possible — `clear()`/`destroy()` flushed the entire shared instance, taking every other consumer's data with it.

### Smaller additions
- `Pop\Cache\InvalidArgumentException` implementing *both* PSR-16 and PSR-6 `InvalidArgumentException`, thrown by the new shared `ValidatesKey` trait.
- `getAvailableAdapters()` now reports `memory` and `null`, and probes `Memcached` rather than the wrong `Memcache` class name.
- `unserialize()` hardened with `['allowed_classes' => false]` in `File`, `Session`, `Database` and `Redis` as a PHP object-injection mitigation.
- `Adapter\NamespacedVersionedKeys` is public API surface for third-party namespaced adapters: implement a one-line `fetchVersion()` and get the rest free.
- Key hashing is now consistent — `Apc`/`Memcached`/`Redis`/`Session` `sha1()` the id, matching what `File`/`Database` always did.
- `File` writes are atomic (temp file + `rename()`).

---

## pop-code — 5.0.8 → 6.0.0

**Summary:** Full modern-PHP coverage — attributes, enums, readonly, promoted/variadic/by-ref parameters, typed constants and DNF types — in both the generator and the reflection direction.
**Feature count:** 14

### PHP 8 attribute generation
New `Generator\AttributeGenerator` renders one `#[Name(args)]` usage, and a new `AttributesTrait` mixes `addAttribute()`/`hasAttribute()`/`getAttributesByName()`/`removeAttribute()` into every attribute-bearing construct: class, interface, trait, enum, enum case, property, constant, method and function. Repeated attributes are kept as an indexed list rather than collapsed by name.

```php
// New
$class = new Generator\ClassGenerator('Product');
$class->addAttribute(new Generator\AttributeGenerator('Entity'));
$class->addAttribute((new Generator\AttributeGenerator('Table'))->addArgument('products', 'name'));
// #[Entity]
// #[Table(name: 'products')]
// class Product
```
**Previously:** not possible — no attribute class existed anywhere in `src/`.

### Parameter-level attributes
`addArgument()`/`addPromotedArgument()` take an `array $attributes` as their final argument; these render inline, before the parameter's type.

```php
// New
$function->addArgument('request', new Generator\NoValue(), 'Request', false, false, [
    new Generator\AttributeGenerator('Autowire'),
]);
// function handle(#[Autowire] Request $request): Request
```
**Previously:** not possible.

### Enum generation
New `Generator\EnumGenerator` and `EnumCaseGenerator`. Supports pure and backed (`int`/`string`) enums via `setBackingType()`, interfaces via `addInterface()`, plus constants, methods, trait `use` and per-case docblocks/attributes. `render()` validates the case/backing-type contract.

```php
// New
$enum = new Generator\EnumGenerator('Status', 'string', 'HasLabel');
$enum->addCase(new Generator\EnumCaseGenerator('Active', 'active'));
// enum Status: string implements HasLabel { case Active = 'active'; }
```
**Previously:** not possible — `ClassGenerator` could only emit `class`.

### Enum reflection
New `Reflection\EnumReflection` plus `Reflection::createEnum()` reconstruct an existing enum: backing type, cases and values, interfaces (minus the implicit `UnitEnum`/`BackedEnum`), constants, docblocks, attributes and user methods, skipping PHP's synthesized `cases()`/`from()`/`tryFrom()`.

```php
// New
$enum = Reflection::createEnum('MyApp\Status');
```
**Previously:** not possible; `ClassReflection::parse()` on an enum produced broken `class` output (it now throws a clear error pointing at `createEnum()`).

### Readonly classes and readonly properties
`ClassGenerator::setAsReadonly()` emits a PHP 8.2 `readonly class`; `PropertyGenerator::setAsReadonly()` emits a readonly property. The generator enforces the language rules — readonly is mutually exclusive with `static`, a readonly property must have a type, and it renders with no default. `suppressReadonlyKeyword()` hides the redundant per-property keyword inside a readonly class. Reflection picks both up.

```php
// New
$class->setAsReadonly();
(new Generator\PropertyGenerator('id', 'int', null, 'public'))->setAsReadonly();
// readonly class ...   /   public readonly int $id;
```
**Previously:** not possible — no readonly flag on either generator.

### Constructor property promotion
`MethodGenerator::addPromotedArgument()` emits a promoted constructor parameter, validating that the method is `__construct`, that visibility is legal, and that a readonly promotion has a type. `MethodReflection` detects `isPromoted()` and round-trips it.

```php
// New
$ctor = new Generator\MethodGenerator('__construct');
$ctor->addPromotedArgument('x', 'public', new Generator\NoValue(), 'int')
     ->addPromotedArgument('label', 'private', new Generator\NoValue(), 'string', true)
     ->setBody('');
// public function __construct(public int $x, private readonly string $label)
```
**Previously:** not possible — promoted parameters had to be hand-written into a method body string.

### Variadic and by-reference parameters
`addArgument()`/`addParameter()` gained `bool $variadic` and `bool $byRef` flags. A variadic argument with a default value throws.

```php
// New
$fn->addArgument('numbers', new Generator\NoValue(), 'int', true);        // int ...$numbers
$fn->addArgument('counter', new Generator\NoValue(), 'int', false, true); // int &$counter
```
**Previously:** not possible — the signature had no such flags.

### `NoValue` and `Literal` value objects
`NoValue` is a sentinel meaning "this parameter has no default at all", which plain `null` could not express (`null` previously always rendered as an actual `= null`). `Literal` wraps a raw PHP source expression emitted verbatim — constants, enum cases, `new Foo()`. Reflection now emits a `Literal` when a default is a constant, preserving `= self::DEFAULT_MODE` instead of inlining the resolved value.

```php
// New
$fn->addArgument('mode', new Generator\Literal('self::DEFAULT_MODE'), 'string');
// function withDefault(string $mode = self::DEFAULT_MODE): string
```
**Previously:** not possible; every non-null default was rendered by type-guessing and string defaults were unconditionally quoted.

### Typed class constants and constant visibility
`ConstantGenerator::setTyped()` emits a PHP 8.3 typed constant, and `render()` now emits the visibility modifier (the inherited `setVisibility()` was previously ignored).

```php
// New
(new Generator\ConstantGenerator('STATUS', 'string', 'active'))
    ->setTyped(true)->setVisibility('protected');
// protected const string STATUS = 'active';
```
**Previously:** `render()` produced only `const STATUS = 'active';` — no type, no visibility.

### Constant reflection
New `Reflection\ConstantReflection` and `Reflection::createConstant()` turn a `ReflectionClassConstant` into a `ConstantGenerator`, carrying visibility, declared type, value and attributes. The class/interface/enum reflections all route constants through it, and `ClassReflection` now filters out constants inherited from a parent.

```php
// New
$constant = Reflection::createConstant($reflectionClassConstant);
```
**Previously:** constants were reconstructed via `gettype()` from `getConstants()` — no visibility, no declared type, no attributes, and inherited constants were re-emitted.

### Union, intersection and DNF type support
New `Reflection\Support\TypeNormalizer` provides `normalize()` (mapping `gettype()`'s `integer`/`boolean`/`double` to valid keywords) and `resolveReflectionType()`, handling named, union, intersection and DNF (`(A&B)|C`) types in one place. The generators complement it by wrapping an intersection in parens when widening to nullable.

```php
// New
new Generator\PropertyGenerator('collection', '\Countable&\Traversable', null, 'public');
// public (\Countable&\Traversable)|null $collection = null;
```
**Previously:** intersection types were unsupported everywhere; parameter reflection called `getName()` directly, which crashes or drops the type on a union, and `gettype()` names like `integer` leaked into type-hint positions as invalid PHP.

### Shared `ValueFormatter` value rendering
`Generator\Support\ValueFormatter::format()` is the single value-to-PHP-source path used by properties, constants, enum cases, attributes and argument defaults. Beyond deduplicating three divergent copies, it adds `Literal` passthrough, `\UnitEnum` cases rendered as `Status::Active`, proper quote/backslash escaping, correct member selection for union-typed values, a compact single-line array mode, and an exception on non-`Stringable` objects.

```php
// New
ValueFormatter::format(Status::Active);   // Status::Active
ValueFormatter::format("it's", 'string'); // 'it\'s'
```
**Previously:** each generator had its own inline chain that emitted values unescaped and could not represent enum cases or literals at all.

### Multiple parent interfaces
`InterfaceGenerator` models `extends` as a list — `addParent()`, `addParents()`, `getParents()`, `hasParents()`, `hasParent()`, `removeParent()` — and its constructor accepts an array or comma-separated string. `InterfaceReflection` resolves the *direct* parents out of the transitive closure via the new `InterfaceHierarchyResolver`.

```php
// New
new Generator\InterfaceGenerator('Countable2', ['Arrayable', 'Traversable']);
// interface Countable2 extends Arrayable, Traversable
```
**Previously:** a single `?string $parent`, even though PHP allows many.

### Docblock param lookup and removal
`DocblockGenerator::findParam(string $var)` and `removeParam(string $var)` address `@param` tags by variable name rather than index, so a re-added argument replaces its stale tag instead of appending a duplicate.

```php
// New
$existing = $docblock->findParam('$foo');
$docblock->removeParam('$foo');
```
**Previously:** `@param` tags were append-only; there was no way to find or remove one by name.

### Smaller additions
- `FunctionGenerator` can render a truly anonymous closure (no `$name`), emitting a bare `function(...) {}` instead of throwing.
- Closure detection in `FunctionReflection` matches PHP 8.4's `{closure:file:line}` naming.
- New `Reflection\Support\NamespaceImportResolver` auto-generates collision-safe `use` imports, falling back to an FQCN when two classes share a short name.
- New `UseStatementParser`, `SourceBodyExtractor` (token-aware, per-file cached) and `InterfaceHierarchyResolver` are reusable public support classes.
- `ClassReflection` filters inherited members by declaring class and skips promoted properties.
- `MethodReflection` no longer emits the illegal `abstract` keyword on interface methods, and gives concrete methods an explicit body so they render with braces.
- `Generator::render()` is now side-effect free — rendering twice no longer compounds indentation.
- `NamespaceGenerator::render()` preserves a caller-set docblock instead of overwriting it.

---

## pop-color — 1.0.3 → 2.0.0

**Summary:** Seven new color spaces (HSV/HSB/HWB and the CIE Lab/Lch + Oklab/Oklch families), CSS Color 4 string parsing and rendering, and full hex-with-alpha support.
**Feature count:** 8

### Seven new color space classes with matching `Color::` factories
`Hsv`, `Hsb`, `Hwb`, `Lab`, `Lch`, `Oklab` and `Oklch` are brand-new classes, each with validating per-channel setters/getters, `toRgb()`, `toArray()`, `render()` and `ArrayAccess`. The Lab family uses `getAlpha()`/`setAlpha()` (since `a`/`b` are already axis channels), and the Lab↔Lch / Oklab↔Oklch pairs convert directly to each other.

```php
// New
$oklch = Color::oklch(0.628, 0.2577, 29);   // also hsv/hsb/hwb/lab/lch/oklab
echo $oklch;                                 // oklch(0.628 0.2577 29)
echo $oklch->toRgb();                        // rgb(255, 0, 0)
```
**Previously:** not possible — only `Rgb`, `Hsl`, `Hex`, `Cmyk` and `Grayscale` existed.

### `Rgb` conversions into every new space
`Rgb` gained `toHsv()`, `toHsb()`, `toHwb()`, `toLab()`, `toLch()`, `toOklab()` and `toOklch()`, keeping `Rgb` the hub format that reaches every other class in one step. The Lab/Oklab paths implement the real sRGB → linear-light → XYZ (D65) → Lab / LMS → Oklab matrices, so the values are colorimetrically correct rather than approximations.

```php
// New
$lab   = Color::rgb(255, 0, 0)->toLab();     // lab(53.24% 80.09 67.2)
$oklab = Color::rgb(255, 0, 0)->toOklab();   // oklab(0.628 0.2249 0.1258)
```
**Previously:** not possible — `Rgb` only had `toCmyk()`, `toGray()`, `toHsl()`, `toHex()`.

### CSS Color 4 parsing — space-separated syntax with `/ alpha`
`Color::parse()` now recognizes `hsv()`, `hsb()`, `hwb()`, `lab()`, `lch()`, `oklab()` and `oklch()`, and for the CSS-native formats accepts both legacy comma-separated values and modern space-separated syntax with a `/`-alpha suffix (percent signs tolerated). Malformed or out-of-range input is normalized into `Color\Exception` instead of leaking `OutOfRangeException`/`TypeError`.

```php
// New
$hwb = Color::parse('hwb(210 20% 10% / 0.5)');
echo $hwb->getW() . ' ' . $hwb->getA();   // 20 0.5
Color::parse('lab(53.24% 80.09 67.2 / 0.5)');
```
**Previously:** not possible — `parse()` only sniffed `rgb`, `hsl`, `#hex`, space-separated CMYK and bare numeric grayscale, comma-separated only.

### 4- and 8-digit hex with alpha
`Hex::setHex()` accepts 4-digit `#RGBA` and 8-digit `#RRGGBBAA` alongside 3- and 6-digit, exposing alpha as the same 0–1 float used elsewhere via `getA()`, `setA()`, `hasA()` and `hasAlpha()`. `Hex::toRgb()` carries alpha through, `Rgb::toHex()` emits the 8-digit form when alpha is set, and `'a'` is a valid channel on `Hex`.

```php
// New
$hex = Color::hex('#ff880080');
echo $hex->getA();                          // 0.502
echo Color::rgb(120, 60, 30, 0.5)->toHex(); // #783c1e80
```
**Previously:** not possible — anything but 3 or 6 digits threw, `Hex` had no alpha at all, and `toHex()` silently dropped it.

### `toCss()` on every color object
`AbstractColor::toCss()` returns `render(self::CSS)` regardless of what a class's `__toString()` defaults to. This matters because `Cmyk`, `Grayscale` and `Hex` default to non-CSS output, so a `(string)` cast on an arbitrary `ColorInterface` was not safe to drop into a stylesheet.

```php
// New
echo Color::cmyk(0, 50, 75, 53)->toCss();  // rgb(120, 60, 30)
```
**Previously:** you had to know the concrete class and call `render(AbstractColor::CSS)` yourself.

### Native CSS Color 4 render output
`Hwb`, `Lab`, `Lch`, `Oklab` and `Oklch` render true CSS function syntax with the space-separated channel form and a `/ alpha` suffix, so their output is directly usable in modern CSS.

```php
// New
echo Color::hwb(210, 20, 10, 0.5);        // hwb(210 20% 10% / 0.5)
echo Color::lab(53.24, 80.09, 67.2, 0.5); // lab(53.24% 80.09 67.2 / 0.5)
```
**Previously:** not possible — `rgb()`/`rgba()`/`hsl()`/`hsla()` were the only CSS forms, none supporting the `/ alpha` syntax.

### Public sRGB gamma helpers
`Rgb::linearize(float $value): float` and `Rgb::delinearize(float $value): float` convert a 0–1 channel between gamma-encoded sRGB and linear light. They back the Lab/Oklab math internally but are usable directly for blending, contrast ratios or custom color math.

```php
// New
$linear = Pop\Color\Color\Rgb::linearize(0.5);       // 0.2140...
$srgb   = Pop\Color\Color\Rgb::delinearize($linear); // 0.5
```
**Previously:** not possible — you implemented the sRGB transfer function yourself.

### Unified channel access via `AbstractColor`
`AbstractColor` implements `\ArrayAccess` and the magic accessors once, dispatching through a new `abstract protected function channels(): array` that each class declares. Every color class — including all seven new ones — gets bracket and property access for free, error messages list the actual valid channels, and a custom subclass needs only `channels()` plus matching `get*`/`set*` methods.

```php
// New
$oklch = Color::oklch(0.628, 0.2577, 29);
echo $oklch['c'];        // 0.2577
$oklch->h = 200;         // works on every color class
```
**Previously:** each of the five classes duplicated its own offset/magic-method block with a hard-coded `switch`; new channels meant hand-editing every branch.

### Smaller additions
- `Hwb`, `Lab`, `Lch`, `Oklab`, `Oklch` support `render(self::PERCENT)` by delegating to `toRgb()`; `Hsv`/`Hsb` delegate both CSS and PERCENT.
- `Lab::toRgb()` (and thus Lch/Oklab/Oklch → RGB) clamps out-of-gamut results into 0–255 instead of throwing.
- `Color::parse()` no longer uses reflection, so it no longer declares `ReflectionException`.
- `Hex::setA()` normalizes a 3-digit base to 6 digits before appending the alpha byte, so `Color::hex('#f80')->setA(0.5)` yields `#ff880080`.

---

## pop-config — 4.0.4 → 5.0.0

**Summary:** Dot-notation access across all four magic accessors, three typed exception classes with real input validation, portable YAML via `symfony/yaml`, and array input to the parse/merge entry points.
**Feature count:** 7

### Dot-notation reads (`->`, `[]`, `isset()`)
`__get()` and `__isset()` resolve a dotted key by walking nested arrays, so a nested value can be fetched in one hop. A literal key always wins first — a key that genuinely contains a `.` (e.g. `'example.com'`) is matched exactly before any traversal.

```php
// New
$config = new Config(['database' => ['host' => 'localhost', 'port' => 5432]]);

$host = $config['database.host'];      // 'localhost'
$host = $config->{'database.host'};    // 'localhost'
isset($config['database.port']);       // true
```
**Previously:** not possible — `Config` inherited the accessors verbatim from `Pop\Utils\ArrayObject`, which only did `array_key_exists()`. `$config['database.host']` returned `null`; you wrote `$config['database']['host']`, and since nested arrays are never wrapped, `$config->database->host` did not work either.

### Dot-notation writes and unsets
`__set()` and `__unset()` accept the same dotted paths, backed by a reference-walker. Setting auto-vivifies missing intermediate levels; unsetting removes only the leaf and is a no-op when the path is missing. Because `offsetSet()`/`offsetUnset()` delegate to the magic methods, `$config['a.b'] = …` and `unset($config['a.b'])` work too.

```php
// New
$config = new Config(['database' => ['host' => 'localhost']], true);

$config['database.port'] = 5432;   // ['database' => ['host' => 'localhost', 'port' => 5432]]
unset($config['database.host']);   // ['database' => ['port' => 5432]]
```
**Previously:** not possible — writes created a flat literal key `'database.port'` at the top level. Nested writes meant pulling `toArray()`, mutating, and rebuilding the whole config.

### Typed exception classes
Three subclasses of the existing `Pop\Config\Exception` let callers distinguish failure modes without string-matching messages: `ChangesNotAllowedException` (the immutability gate), `ParseException` (missing file, unparseable content, bad input type) and `UnsupportedFormatException` (unrecognized extension or render format). All still extend the base, so existing catches keep working.

```php
// New
try {
    $config = Config::createFromData('/path/to/config.yml');
} catch (Pop\Config\ParseException $e) {
    // missing file or malformed content, specifically
}
```
**Previously:** not possible — `src/Exception.php` was the only exception class in the component.

### Validated parsing (real errors instead of silent `[]` or TypeErrors)
`parseData()` switches on the file extension, checks `is_file()` up front, and verifies the parse actually produced an array — each failure raising a typed exception at the point of failure.

```php
// New
Config::createFromData('/no/such/file.json');
// ParseException: Error: The config file '/no/such/file.json' does not exist.

Config::createFromData('/path/to/composer.lock');
// UnsupportedFormatException: ... Supported extensions are .php, .phtml, .json, .yaml, .yml, .ini and .xml.
```
**Previously:** an unknown extension silently returned `[]` with no signal at all; a missing `.json` file produced a warning and then a `TypeError` from the `: array` return type.

### Array input to `parseData()` / `createFromData()` / `mergeFromData()`
`parseData()` short-circuits on `is_array($data)` and returns it as-is, so already-loaded arrays flow through the same entry points as file paths — useful for tests, cached config and merging in-memory data.

```php
// New
$config = Config::createFromData(['db' => ['host' => 'localhost']], true);
$config->mergeFromData(['db' => ['port' => 5432]]);
```
**Previously:** not possible — `parseData()` opened with `substr($data, -6)`, so any array argument was an immediate `TypeError`. This even made `Config::createFromData()` throw with its own default `$data = []`.

### YAML via `symfony/yaml` (no `ext-yaml`)
YAML read and write go through `Yaml::parseFile()`/`Yaml::dump()`, with `symfony/yaml` as a hard requirement. YAML config files work on any host without the PECL extension installed, and Symfony's own `ParseException` is caught and rethrown as `Pop\Config\ParseException` with the original as `$previous`.

```php
// New — no ext-yaml needed
$config = Config::createFromData('/path/to/config.yml');
echo $config->render('yaml');
```
**Previously:** `yaml_parse()`/`yaml_emit()` were called directly; without the PECL extension both were fatal undefined-function errors.

### `normalizeYamlScalars()` extension hook
A new `protected static` method recurses the parsed YAML tree and restores YAML 1.1 / libyaml scalar semantics that `symfony/yaml` drops — boolean words (`y`/`n`/`yes`/`no`/`on`/`off`) become real `bool`, and leading-zero octal-looking integers become `int`. Being protected, a subclass can override it to add its own coercion rules on the YAML read path.

```php
// New
class MyConfig extends Config {
    protected static function normalizeYamlScalars(mixed $value): mixed
    {
        $value = parent::normalizeYamlScalars($value);
        return (is_string($value) && str_starts_with($value, 'env:'))
            ? getenv(substr($value, 4)) : $value;
    }
}
```
**Previously:** not possible — there was no post-parse hook of any kind.

### Smaller additions
- `Config::createFromData()` and `parseData([])` now work with their own default empty-array argument (a `TypeError` in v6).
- Dotted-key resolution is literal-first in every accessor, so a config loaded from a file with a real `'example.com'` key still reads and unsets by that exact key.
- `ChangesNotAllowedException` makes the immutability gate catchable on its own, separately from format/parse errors.
- `ext-yaml` was dropped from `suggest` entirely.

---

## pop-console — 4.2.6 → 5.0.0

**Summary:** From an output/help-screen helper into a small CLI toolkit: table and progress-bar renderers, a standalone command registry with directory-based discovery, commands that are themselves dispatchable, namespace-filtered help screens, and testable prompts via an injectable input stream.
**Feature count:** 11

### Table renderer
New `Pop\Console\Table` plus a `Console::table()` facade renders headers and rows into a bordered grid with configurable border characters, optional header colorization, and column widths computed from content. Passing `null` for the vertical character drops the column dividers.

```php
// New
$console->table(
    ['Name', 'Status'],
    [['kettle', 'active'], ['brew', 'idle']],
    '-', '|', Color::BOLD_GREEN
);
// Console::table(array $headers, array $rows, string $h = '-', ?string $v = '|',
//     ?int $headerFg = null, ?int $headerBg = null, bool $newline = true, bool $return = false)
```
**Previously:** not possible — no tabular output of any kind; you `str_pad()`/`sprintf()`'d rows yourself.

### Progress bar
New `Pop\Console\ProgressBar`, obtained from `Console::progressBar()` (which pre-applies the console's indent). It redraws a single terminal line in place, skipping the redraw when the visible line hasn't changed, and supports custom bar/progress/empty characters, width, a leading message, and color.

```php
// New
$bar = $console->progressBar(100, 'Processing');
foreach ($items as $item) {
    // do work
    $bar->advance();          // or $bar->setProgress(50)
}
$bar->finish();               // clamps to 100% and prints a trailing newline
```
**Previously:** not possible — no progress rendering; you would hand-roll `"\r"` output.

### Dispatchable commands (`Command\AbstractCommand` / `CommandInterface`)
`Command` now extends the new `Command\AbstractCommand`, which extends `Pop\Dispatch\AbstractDispatcher`, implements `DispatchableInterface` and uses `ConsoleTrait`. A command is therefore a valid route target in its own right — no separate controller/action pair — and carries the `Application` and `Console` it needs.

```php
// New
class UsersCommand extends \Pop\Console\Command
{
    public function handle(string $id): void
    {
        $this->console()->write('Showing user ' . $id);
    }
}

$command = new UsersCommand(name: 'users', params: '--list [<id>]');
$command->dispatch(null, ['123']);   // -> handle('123')
```
**Previously:** not possible — `Command` was a plain value object holding only name/params/help; routes had to point at a controller class, and the command object had no application or console reference.

### `CommandRegistry`
Command bookkeeping moved out of `Console` into a standalone registry (`add()`, `addAll()`, `all()`, `get()`, `has()`, `fromRoutes()`, `addFromRoutes()`). `Console`'s methods are now thin facades over it, so the registry can be built and unit-tested without a console.

```php
// New
$registry = new CommandRegistry();
$registry->add(new Command(name: 'users', params: '--list [<id>]', help: 'Users help'));
$registry->has('users');   // true
$registry->all();          // ['users' => $command]
```
**Previously:** not possible as a separate object — commands lived in a protected array inside `Console`.

### `CommandRegistry::loadRoutes()` directory scanning
A static helper that scans a directory of one-command-per-file classes and builds a CLI routes config from them: it parses the namespace out of the first file, resolves each class from its filename, instantiates it, keys the route by the command's own string cast, and pulls `help` from `getHelp()`.

```php
// New
$routes = CommandRegistry::loadRoutes($routes, __DIR__ . '/src/Command');
// loadRoutes(array $routes, string $location, bool $prepend = true): array
```
**Previously:** not possible — every command route had to be written out by hand in the config.

### Help text sourced from the command class
When building commands from CLI routes, if a route config has no `'help'` key and the controller implements `CommandInterface`, the class is instantiated and its own `getHelp()` supplies the help string.

```php
// New — route needs no 'help' key; UsersCommand::getHelp() is used
$console->addCommandsFromRoutes($cliRouteMatch, './myapp');
```
**Previously:** help came only from the route config; a command with built-in help text had to duplicate it there or show none.

### Injectable input stream (testable prompts)
`setInputStream()`/`getInputStream()`/`hasInputStream()` let you supply any readable resource that `prompt()` and `confirm()` read instead of `php://stdin` (the injected stream is not closed after reading).

```php
// New
$stream = fopen('php://memory', 'r+');
fwrite($stream, 'Nick' . PHP_EOL);
rewind($stream);

$console->setInputStream($stream);
$name = $console->prompt('Please provide your name: ');   // 'Nick', no TTY needed
```
**Previously:** the only override was the undocumented, test-only `$_SERVER['X_POP_CONSOLE_INPUT']` string — one canned answer per process. That hook is gone in v7.

### Subcommand-filtered help screens
`help()` and `displayHelp()` take an optional `$subCommand` that narrows the help screen to the commands under one namespace, so a CLI app with dozens of commands can answer `./myapp help db` instead of dumping everything.

```php
// New
$console->help(null, false, 'db');      // help(?string $command, bool $raw, ?string $subCommand)
$console->displayHelp(false, 'db');
```

```text
    db:migrate    Migrate the database
    db:seed       Seed the database
```
Matching is a plain prefix check against each command's own bare name, so it is not tied to `:` as a delimiter and works just as well for space-separated names like `user list`/`user edit`. To make that reliable, `CommandRegistry` now records the script name a command was registered under (`setScriptName()`/`getScriptName()`/`hasScriptName()`) and strips it by exact value before matching — so a script named `dbapp` can't make `'db'` match every command it owns. A `$subCommand` that matches nothing renders an empty list instead of throwing.
**Previously:** not possible — `displayHelp()` rendered the entire registry, and there was no way to scope it.

### `promptMulti()` — one prompt, several answers
`promptMulti()` is a `prompt()` that accepts a comma-separated list and hands back an array. It validates the whole list against the allowed options before accepting it, so a single bad token re-prompts the entire answer rather than letting a partial selection through.

```php
$types = $console->promptMulti('Select one or more, comma-separated: ', ['1', '2', '3']);
// '1, 3' => ['1', '3']
```
Whitespace around tokens is trimmed and duplicates are collapsed, so `1, 3, 3` and `1,3` give the same result. Matching is case-insensitive unless you pass `true` for `$caseSensitive`. Empty input returns an empty array instead of re-prompting — which is what makes it usable as an "accept the default" answer, and also what stops a closed input stream from spinning the retry loop forever.
**Previously:** `prompt()` accepted exactly one value from the option list; offering a multi-select meant asking repeatedly in your own loop and deduplicating the answers yourself.

### Non-exiting `confirm()`
`confirm()` gained a trailing `bool $exit = true`. Passing `false` returns the response so the caller can branch on it, instead of the method calling `exit(127)` on a "no" answer.

```php
// New
$response = $console->confirm('Are you sure?', ['Y', 'N'], false, 500, true, false);
if (strtolower($response) === 'n') {
    $console->write('Cancelled.');
}
```
**Previously:** a "no" answer always terminated the process with exit code 127.

### `Prompt`, `Header`, `Alert` and `Help` are stand-alone classes
The rendering that made `Console` a god object is now four collaborators in `Pop\Console`, each usable on its own. `Console` keeps every method it had, with the same signatures, and delegates — so this costs existing code nothing and buys you the pieces separately.

```php
use Pop\Console\{Prompt, Alert, Header, Help};

$name = (new Prompt('    '))->prompt('Name? ');
echo (new Alert('    '))->alertDanger('Deploy failed', 60);
echo (new Header('    '))->headerCenter('Report', '=', 60);
```
Each takes its own indent/wrap/width/margin, so you can render a boxed alert or a prompt from a class that has no `Console` at all — a value object, a formatter, a test. `Help::render()` takes the command list directly, which is what makes a custom help screen possible without subclassing `Console`. The shared padding and line-wrapping math lives in a `MessageTrait` the renderers use, so `Header` and `Alert` agree on alignment by construction rather than by two copies of the same code.
**Previously:** all of it was `Console`'s private business — prompting, header drawing, alert boxes and the help screen were methods on one class, reachable only by instantiating a full `Console` (and customizable only by subclassing it). See the BC guide: the old internal call chain between those methods is what changed.

### Smaller additions
- New `Pop\Console\Command\Exception` for the command namespace.
- `ProgressBar` throws when constructed with a total of zero or less.
- `Table` is usable directly (`setHeaders()`, `addRow()`, `setRows()`, `setHeaderColor()`, `render()`) without going through `Console`.
- `Command` inherits `application()`, `console()`, `setApplication()`, `setConsole()`, `hasApplication()`, `hasConsole()` from `ConsoleTrait`, with `Console` defaulting to `new Console(120)`.
- `CommandInterface` adds a `hasName()` check alongside `hasParams()`/`hasHelp()`.
- Terminal size probing (`stty`/`tput`) is detected once per process and cached statically, so building many `Console` instances no longer re-spawns subprocesses.

---

## pop-cookie — 4.0.4 → 5.0.0

**Summary:** A one-class component that gained real error handling — `Pop\Cookie\Exception` went from a dead marker class to an actually-thrown type — plus atomic option updates, reconfigurable singleton access, and wider JSON round-tripping.
**Feature count:** 5

### `samesite` validation and `SameSite=None` safety check
`setOptions()` throws if `samesite` is anything other than `'None'`, `'Lax'` or `'Strict'`, and separately if the resolved configuration is `samesite => 'None'` without `secure => true`. **Browsers silently drop `SameSite=None` cookies that aren't `Secure`**, so this converts a class of invisible client-side failures into a loud, catchable error at configuration time. Because `getInstance()`, `set()`, `delete()` and `clear()` all forward options to `setOptions()`, the guard applies at every entry point.

```php
// New
try {
    $cookie->setOptions(['samesite' => 'None', 'secure' => false]);
} catch (Pop\Cookie\Exception $e) {
    // "A 'samesite' value of 'None' requires the 'secure' option to be set to true."
}
```
**Previously:** not possible — a bad `samesite` value was silently ignored, and `None` without `secure` was happily emitted, producing a cookie the browser would reject with no signal to the application.

### `setcookie()` failure is detected and thrown
Every call site of PHP's native `setcookie()` — `set()`, `delete()`, `clear()` and `__unset()`/`offsetUnset()` — checks the return value and throws on `false`. That catches the common real-world case where output has already been sent and headers can no longer be modified.

```php
// New
try {
    $cookie->set('foo', 'bar');
} catch (Pop\Cookie\Exception $e) {
    // e.g. headers already sent — the cookie was never written
}
```
**Previously:** not possible — the return value was discarded in all four methods, so a failed write looked identical to a successful one.

### Atomic `setOptions()`
`setOptions()` resolves all incoming values into locals and runs every validation check before writing anything onto the object; the six properties are assigned only once the whole array has passed. A thrown exception therefore leaves the cookie configuration exactly as it was.

```php
// New
$cookie->setOptions(['expires' => time() + 3600, 'path' => '/admin']);
try {
    $cookie->setOptions(['path' => '/api', 'samesite' => 'Bogus']);
} catch (Pop\Cookie\Exception $e) {
    $cookie->getPath();   // still '/admin' — nothing was committed
}
```
**Previously:** each option was assigned the moment it was read, in sequence, leaving the object in a mixed old/new state with no way to roll back.

### `getInstance()` can reconfigure the shared instance
Passing a non-empty options array to `getInstance()` after the singleton exists now applies those options to it. This makes `getInstance()` usable as the single access point throughout a request, instead of only mattering on the very first call.

```php
// New
$cookie = Cookie::getInstance(['expires' => time() + 3600, 'path' => '/']);
// ...later, elsewhere in the same request:
$cookie = Cookie::getInstance(['expires' => time() + 7200]);   // reconfigures the same object
```
**Previously:** the options argument was ignored entirely on every call after the first; you had to keep a reference and call `setOptions()` on it directly.

### JSON decoding on read widened to arrays and scalar literals
`__get()` (and `offsetGet()`) now JSON-decodes stored values that start with `[` as well as `{`, plus the exact literals `'true'`, `'false'` and `'null'`. This matches the set of forms `json_encode()` produces on the write side, so lists, booleans and `null` round-trip symmetrically.

```php
// New
$cookie->tags       = ['php', 'pop'];
$cookie->rememberMe = true;

// on a later request:
$cookie->tags;         // ['php', 'pop']
$cookie->rememberMe;   // true (bool)
```
**Previously:** only values beginning with `{` were decoded. A JSON-encoded array came back as the literal string and a boolean as `'true'`; you called `json_decode()` yourself and had to know which cookies needed it.

### Smaller additions
- Values are cast to `string` before being handed to `setcookie()` in all four write paths.
- `Pop\Cookie\Exception` is now a documented, catchable part of the public contract, with `@throws` annotations on `setOptions()`, `set()`, `delete()`, `clear()` and `__unset()`.

---

## pop-crypt — 3.0.1 → 4.0.0

**Summary:** A libsodium XChaCha20-Poly1305 encrypter alongside the OpenSSL one, AES-CBC hardened with HKDF-separated encryption/MAC keys and verify-before-decrypt, a hasher input cap, `EncrypterInterface` conformance across both encrypters, and a new `Signature\Verifier` for HMAC/RSA/EC signature verification.
**Feature count:** 8

### `SodiumEncrypter` — XChaCha20-Poly1305 AEAD
A second concrete encrypter built on the bundled `sodium` extension instead of OpenSSL. It is authenticated encryption by construction (no separate HMAC step to get wrong), and its 24-byte nonce is large enough that a fresh random nonce per message is safe at any realistic volume — unlike AES-GCM's 96-bit nonce, which has a birthday-bound collision risk under a single key at high volume, where reuse is catastrophic. It shares the same key/previous-key/`$raw` conventions as `Encrypter`, minus any cipher argument.

```php
// New
$encrypter = Encryption\SodiumEncrypter::create();          // or: new SodiumEncrypter($key, $raw = true)
$payload   = $encrypter->encrypt('SENSITIVE_DATA');
$plain     = $encrypter->decrypt($payload);

$key       = Encryption\SodiumEncrypter::generateKey(false); // base64
$encrypter = Encryption\SodiumEncrypter::load();             // APP_KEY / APP_PREVIOUS_KEYS
$encrypter->setPreviousKeys([$oldKey1, $oldKey2]);
```
**Previously:** not possible — `Encrypter` (OpenSSL AES-CBC/GCM) was the only implementation; libsodium was not used anywhere.

### HKDF-derived encryption and MAC keys for AES-CBC
For the non-AEAD ciphers, `Encrypter` no longer uses your master key directly. It derives two independent 32-byte subkeys with `hash_hkdf('sha256', ...)` under distinct info strings, one for the cipher and one for the HMAC. **This enforces key separation** — the same secret is never used for two different cryptographic primitives, removing the class of cross-protocol/related-key interactions that arise when one key drives both AES and HMAC-SHA256. GCM is unaffected.

```php
// New — internal to Encrypter::encrypt(), no API change
private const HKDF_ENCRYPTION_INFO = 'pop-crypt|encryption-key';
private const HKDF_MAC_INFO        = 'pop-crypt|mac-key';

$encKey = ($aead) ? $this->key : hash_hkdf('sha256', $this->key, 32, self::HKDF_ENCRYPTION_INFO);
```
**Previously:** the raw master key was passed to both `openssl_encrypt()` and `hash_hmac()`. (Note this is also the documented BC break: v6-produced CBC ciphertext will not decrypt under v7.)

### Verify-the-MAC-before-decrypting (CBC)
`decrypt()` now checks the HMAC for each candidate key *first* and skips to the next key when it fails, only calling `openssl_decrypt()` on data that has already been authenticated. This is correct encrypt-then-MAC ordering and closes the padding-oracle exposure of feeding attacker-supplied, unauthenticated CBC ciphertext into the cipher.

```php
// New — Encrypter::decrypt() key loop
foreach ($this->getAllKeys() as $key) {
    if (!$aead) {
        $macKey   = hash_hkdf('sha256', $key, 32, self::HKDF_MAC_INFO);
        $validMac = hash_equals(hash_hmac('sha256', $payload['iv'] . $payload['value'], $macKey), $payload['mac']);
        if (!$validMac) { continue; }
        $encKey = hash_hkdf('sha256', $key, 32, self::HKDF_ENCRYPTION_INFO);
    }
    $decrypted = openssl_decrypt($payload['value'], $this->cipher, $encKey, 0, $iv, $tag);
    if ($decrypted !== false) { break; }
}
```
**Previously:** `openssl_decrypt()` ran first on unauthenticated ciphertext and the MAC was computed afterward; `$validMac` was overwritten on every iteration, so only the last key's result was ever examined.

### `MAX_VALUE_LENGTH` input cap on hashers
`AbstractHasher` gained a `MAX_VALUE_LENGTH` constant (4096 bytes, matching Symfony's password hasher) enforced in both `createHash()` and `verify()`. Argon2's cost scales with input size, so an unbounded attacker-controlled value on a login or registration endpoint is an algorithmic-complexity DoS vector; oversized input now throws before any hashing work is done.

```php
// New
const MAX_VALUE_LENGTH = 4096;

if (strlen($value) > static::MAX_VALUE_LENGTH) {
    throw new Exception('Error: The value exceeds the maximum allowed length of ' . static::MAX_VALUE_LENGTH . ' bytes.');
}
```
**Previously:** no limit — any length was passed straight to `password_hash()`/`password_verify()`.

### Payload type-validation on decrypt
Both encrypters type-check the decoded JSON envelope before using it: `iv` and `value` must be strings; for non-AEAD CBC a string `mac` is required (so a payload can't simply omit the MAC to skip authentication); an optional `tag` must be a string. Everything invalid raises the same generic exception, so a malformed or forged payload yields a clean, uniform error rather than a `TypeError` with a revealing stack trace.

```php
// New
if (!$aead && (!isset($payload['mac']) || !is_string($payload['mac']))) {
    throw new Exception('Error: The payload is not valid data.');
}
```
**Previously:** only `is_array()` plus `isset()` on `iv`/`value`; a payload with `"iv": []` or a missing `mac` reached `base64_decode()`/`hash_equals()` and produced a raw PHP `TypeError`.

### `EncrypterInterface` conformance across both encrypters
`AbstractEncrypter` now actually declares `implements EncrypterInterface`, and the interface's `encrypt()`/`decrypt()` were tightened to `string` in / `string` out to match. Consumers can type-hint the interface and accept either encrypter interchangeably.

```php
// New
public function myThing(EncrypterInterface $encrypter): void { /* Encrypter or SodiumEncrypter */ }
```
**Previously:** `EncrypterInterface` existed but was orphaned — `AbstractEncrypter` did not implement it, so type-hinting the interface matched nothing the library shipped.

### Fail-fast key/cipher validation on the setters
`setCipher()` rejects an unavailable cipher and one incompatible with an already-set key; `setKey()` rejects a key whose length doesn't match the already-set cipher. An invalid key can no longer be installed on a live encrypter and silently produce weak ciphertext at encrypt time.

```php
// New
$encrypter->setCipher('aes-128-cbc'); // throws if key is 32 bytes
$encrypter->setKey($shortKey);        // throws
```
**Previously:** validation happened only in the constructor; both setters assigned unconditionally, so post-construction mutation bypassed every check.

### `Signature\Verifier` — HMAC/RSA/EC signature verification
A new `Pop\Crypt\Signature\Verifier` class providing bare signature-verification primitives: `hmac()` (via `hash_hmac()` + `hash_equals()`), and `rsa()`/`ec()` (via `openssl_verify()`). A malformed or unusable key throws `Pop\Crypt\Exception`; a signature that simply doesn't match returns `false` — a bad signature and a bad key are distinguishable failure modes. This is the primitive `pop-auth`'s new `Jwt` adapter builds its token-signature verification on.

```php
// New
use Pop\Crypt\Signature\Verifier;

Verifier::hmac($data, $signature, $secret);              // bool
Verifier::rsa($data, $signature, $publicKeyPem);          // bool, throws on bad key
Verifier::ec($data, $signature, $publicKeyPem);           // bool, throws on bad key
```
**Previously:** not possible — no signature-verification primitive existed in this component; a consumer would call `openssl_verify()`/`hash_hmac()` directly with no exception-typed error handling.

### Smaller additions
- `SodiumEncrypter::decrypt()` catches `\SodiumException` inside the key-rotation loop and treats it as a decryption failure, so a malformed payload throws the library's own exception.
- `SodiumEncrypter::isAvailable()` reports whether the `sodium` extension is loaded, giving a runtime capability check before use.
- `SodiumEncrypter::load()` reads `APP_KEY`/`APP_PREVIOUS_KEYS` with base64 as the default encoding (no `APP_CIPHER_METHOD` needed).
- `composer.json` explicitly requires `ext-openssl` and `ext-sodium`, so a missing extension fails at install time rather than at first encrypt.

---

## pop-css — 2.0.3 → 3.0.2

**Summary:** First-class `Pop\Color` integration on selector properties, comment removal, non-string property values, and a parser that no longer breaks on values containing `:` or `;`, loses a repeated rule, or swallows an unreachable stylesheet.
**Feature count:** 8

### Color objects as property values, and `getColorProperty()` to read them back
`Selector::setProperty()` (and `__set`/`ArrayAccess`) accepts any `Pop\Color\Color\ColorInterface` and normalizes it to valid CSS at set time via `render('CSS')` — including spaces like CMYK/Grayscale/HSV that have no native CSS syntax and get converted through RGB. The new `getColorProperty()` runs the stored string back through `Color::parse()` and returns a `ColorInterface` (or `null`), so colors from a parsed stylesheet can be manipulated as objects.

```php
// New
$box = new Selector('.box');
$box->setProperty('color', Color::rgb(255, 0, 0));
$box->setProperty('background-color', Color::cmyk(30, 20, 10, 5)); // -> rgb(170, 194, 218)

echo $box->getColorProperty('color')->toHex(); // #ff0000
```
**Previously:** not possible. `setProperty(string $property, string $value)` accepted strings only, and although `pop-color` was a composer dependency, **nothing under `src/` referenced it** — you rendered the color to a string yourself on the way in, and re-parsed it yourself on the way out.

### Comment removal — `removeComment()` / `removeAllComments()`
`CommentTrait` gains `removeComment(int $i)` (re-indexing the remainder) and `removeAllComments()`. Because the trait is used by `AbstractCss` (so `Css` and `Media`) and `Selector`, comments are now fully manageable on every object that can hold them.

```php
// New
$css->addComment('First')->addComment('Second');
$css->removeComment(0);      // drops 'First', re-indexes
$css->removeAllComments();
```
**Previously:** not possible — the trait exposed only `addComment()`, `getComments()` and `hasComments()`; once attached there was no API to take a comment off.

### Parser survives values containing `;` or `:` (data URIs, `background: url(...)`)
The new protected `Css::splitDeclarations()` walks the rule block character by character tracking parenthesis depth, so a `;` inside `url(data:image/png;base64,...)` no longer splits one declaration into two. Paired with `explode(':', $value, 2)`, the property/value split stops at the first colon instead of discarding any declaration containing more than one.

```php
// New
$css = Css::parseString('.logo { background: url(data:image/png;base64,iVBORw0KGgo=) no-repeat; }');
echo $css['.logo']['background']; // url(data:image/png;base64,iVBORw0KGgo=) no-repeat
```
**Previously:** not possible. `explode(';', ...)` cut the declaration at the embedded semicolon, and the unlimited colon split produced 3 parts, so the `count($v) == 2` guard **silently dropped the whole declaration**.

### `isset()` on synthesized margin/padding longhands
`Selector::__isset()` reports `true` for `margin-top`/`-right`/`-bottom`/`-left` (and the padding set) whenever the corresponding shorthand is set, matching what `__get()` will actually hand back.

```php
// New
$box->setProperty('margin', '10px 20px');
isset($box['margin-top']);  // true
echo $box['margin-top'];    // 10px
```
**Previously:** `__isset()` was a bare key check, so `isset($box['margin-top'])` returned `false` even though reading it correctly returned `10px`.

### Numeric property values (`int` / `float`)
`setProperty()`'s union type includes `int|float` and casts to string internally, so `0`, `1.5` and `700` can be passed directly without manual stringification.

```php
// New
$box->setProperties(['margin' => 0, 'line-height' => 1.5, 'font-weight' => 700]);
```
**Previously:** the `string $value` type hint meant a bare `0` or `1.5` relied on weak-mode juggling and would be a `TypeError` under `strict_types`.

### Protected extension points for parsing and shorthand resolution
Seven pieces of inline parser logic are now named protected methods, overridable in a subclass: `splitDeclarations()`, `detectMediaType()`, `detectMediaCondition()`, `parseMediaFeatures()`, `normalizeComment()`, `extractSelectorNameAfterComment()` on `Css`, and `resolveShorthandValue()` on `Selector`.

```php
// New
class MyCss extends Css {
    protected function detectMediaType(string $mediaQuery): string|null {
        return str_contains($mediaQuery, 'tv') ? 'tv' : parent::detectMediaType($mediaQuery);
    }
}
```
**Previously:** not possible — all of this lived inline inside `parseMediaQueries()`, `parseComments()`, `parseSelectors()` and `Selector::__get()`.

### Repeated rules for one selector cascade instead of clobbering
`addSelector()` merges an incoming `Selector`'s properties into the one already registered under that name — last value for a given property wins — rather than replacing the object outright. Building a stylesheet up from several sources, a base rule plus an override, now behaves the way a browser does with two rules for the same selector.

```php
// New
$base = new Selector('.btn');
$base['color']   = '#fff';
$base['padding'] = '10px';

$theme = new Selector('.btn');
$theme['color']  = '#000';

$css->addSelector($base);
$css->addSelector($theme);

$css->getSelector('.btn')->getProperties(); // ['color' => '#000', 'padding' => '10px']
```
**Previously:** the second `addSelector()` discarded the first selector entirely, taking `padding` with it — merging meant reading the existing selector out and copying properties across by hand.

### `parseCssUri()` fails loudly on an unreachable stylesheet
A URI that 404s, times out or does not exist now raises `Pop\Css\Exception` naming it, instead of handing `file_get_contents()`'s failure value to the parser and producing an empty stylesheet.

```php
// New
try {
    $css = (new Css())->parseCssUri('https://example.com/missing.css');
} catch (\Pop\Css\Exception $e) {
    echo $e->getMessage();
    // Error: Unable to fetch CSS from the URI 'https://example.com/missing.css'.
}
```
**Previously:** the call returned a `Css` object with no selectors and no warning, so a broken stylesheet URL looked exactly like an empty one.

### Smaller additions
- `Css::removeMedia()` re-indexes the media array after unsetting, so `getMedia(0)` stays valid after a removal.
- `Media::__construct()` types `$tabSize` as `int`.

---

## pop-csv — 4.2.5 → 5.0.0

**Summary:** A memory-flat streaming reader, opt-in formula-injection escaping, and a validator that actually validates — plus BOM handling and safe temp files on the parse path.
**Feature count:** 5

### Streaming row reader (`readRowsFromFile()`)
The only new public method in the component. It returns a `\Generator` that yields one row at a time straight off the file handle, so **memory stays flat regardless of file size**. It honors the same options as the other read methods, maps rows to associative arrays from the header row when `fields` is on, closes the handle in a `finally`, and throws if the file is missing.

```php
// New
foreach (Pop\Csv\Csv::readRowsFromFile('huge.csv') as $row) {
    processRow($row); // $row === ['id' => '1', 'email' => '...']
}
```
**Previously:** not possible. Every read path built the complete array in memory first — plus, on the string path, wrote a full copy of the input to a temp file. The only memory-conscious API was `appendDataToFile()`, for writing.

### `escapeFormulas` option — CSV/Excel formula injection guard
A new serialization option (default `false`). When enabled, any non-numeric cell whose first character is `=`, `+`, `-` or `@` is prefixed with a single quote, so spreadsheet apps treat it as literal text rather than executing it. Threaded through `serialize()`, `serializeData()`, `serializeRow()` and both append paths.

```php
// New
$csv = new Pop\Csv\Csv([['note' => '=SUM(A1:A10)']], ['escapeFormulas' => true]);
echo $csv->serialize(); // note\n'=SUM(A1:A10)\n
```
**Previously:** not possible via the library — you sanitized every user-supplied value yourself before handing the array to `Csv`, since `serializeRow()` only ever applied quoting/enclosure rules.

### `isValid()` is a real structural check
It now trims the input, rejects empty/blank strings, parses the first line to establish an expected column count, and confirms every non-empty line parses to that same count.

```php
// New
Pop\Csv\Csv::isValid('');                    // false
Pop\Csv\Csv::isValid("a,b\n1,2,3");          // false — ragged row
Pop\Csv\Csv::isValid("a,b\n1,2");            // true
```
**Previously:** the method was effectively a no-op that returned `true` for literally every input, including `''` — `str_getcsv('')` yields `[null]`, so the count check was always satisfied, and only the first line was ever looked at.

### UTF-8 BOM stripping on read
Both parse entry points handle a leading `\xEF\xBB\xBF`: `unserializeString()` strips it up front, and `readRowsFromFile()` peeks the first three bytes and rewinds if they aren't a BOM, so the stream is left undisturbed for BOM-less files.

```php
// New
$data = Pop\Csv\Csv::unserializeString($csvExportedFromExcel);
$data[0]['id']; // works — key is 'id', not "\xEF\xBB\xBFid"
```
**Previously:** the BOM was silently absorbed into the first header name, so the first column's array key carried three invisible bytes and every `$row['id']` lookup missed.

### Concurrency-safe temp files
`unserializeString()` allocates its scratch file with `tempnam()` and deletes it in a `finally` block.

```php
// New
$data = Pop\Csv\Csv::unserializeString($string); // unique temp file per call
```
**Previously:** the temp path was `sys_get_temp_dir() . '/pop-csv-tmp-' . time() . '.csv'` — a second-resolution name shared by every concurrent caller, so two parses in the same second clobbered each other's data and one could `unlink()` the file the other was still reading. Cleanup also only ran on the success branch, leaving orphaned files behind.

### Smaller additions
- `serializeData()` no longer errors on an empty data array — it returns `''` instead of indexing an empty array.
- The append-with-validation path closes the file handle it opens to read the header row (v6 leaked a handle per call) and tolerates a failed open.
- The `escape` default on the row-count read path was corrected to match every other read/write path.
- Generated header rows terminate with `"\n"` instead of `PHP_EOL`, so header and data lines use the same terminator on Windows.

---

## pop-db — 6.8.15 → 7.0.0

**Summary:** The shorthand condition array becomes a real structured query language (operators, OR/AND groups, subqueries, JSON paths), plus record safety and extensibility — mass-assignment guards, lifecycle hooks, composite-key and multi-path eager loading — a `Pop\Db\Model` data-model layer, and an `Auth` record that absorbs `pop-auth`'s table adapter and builds account gating, expiring attempt lockout and MFA on top of it.
**Feature count:** 13

### Structured shorthand condition syntax
Every `Record` finder now parses its `$columns` array through the new `Sql\Parser\Condition`, which accepts an explicit `[OPERATOR, ...values]` tuple per column plus reserved `'OR'`/`'AND'` keys for nested boolean groups. Operators are arity-validated, so a wrong number of values (or an empty `IN` array) throws instead of silently rendering something unintended. Supported: `=`, `!=`, `>`, `>=`, `<`, `<=`, `LIKE`, `NOT LIKE`, `IN`, `NOT IN`, `BETWEEN`, `NOT BETWEEN`, `IS NULL`, `IS NOT NULL`, `CONTAINS`.

```php
// New
$users = Users::findBy([
    'age'        => ['>=', 18],
    'name'       => ['LIKE', '%smith%'],
    'created_at' => ['BETWEEN', '2024-01-01', '2024-12-31'],
    'deleted_at' => ['IS NULL'],
    'OR' => [
        ['role' => 'admin'],
        ['age'  => ['>=', 65]],
    ],
]);
// WHERE age >= ? AND name LIKE ? AND created_at BETWEEN ? AND ? AND deleted_at IS NULL
//   AND (role = ? OR age >= ?)
```
**Previously:** only the ambiguous suffixed-key forms (`'age>=' => 18`, `'%username' => 'test'`, `'id' => '(1, 5)'`); those still work but now emit `E_USER_DEPRECATED`. **OR/AND grouping inside a shorthand array was not possible at all** — you had to drop down to a hand-built `PredicateSet`.

### Subquery support (EXISTS / NOT EXISTS and Select-as-value)
`IN`/`NOT IN` and all six scalar comparison predicates now accept a `Sql\Select` as their value, and `PredicateSet` gained `exists()`/`notExists()` for column-less `EXISTS (SELECT ...)` predicates. The shorthand array supports the same via `['col' => ['IN', $select]]` and reserved top-level `'EXISTS'`/`'NOT EXISTS'` keys.

```php
// New
$subquery = $db->createSql()->select('user_id')->from('orders');
$subquery->where->greaterThanOrEqualTo('total', 100);

$sql->select()->from('users')->where->in('id', $subquery);
$sql->select()->from('users')->where->exists($subquery);

Users::findBy(['id' => ['IN', $subquery]]);
Users::findBy(['EXISTS' => $subquery]);
```
**Previously:** not possible — `Predicate\In::render()` threw for anything but an array, the comparison predicates were typed `string $value`, and there was no EXISTS predicate.

### JSON column querying
New `Sql\JsonExtract` expression object plus `JsonEqualTo`, `JsonNotEqualTo` and `JsonContains` predicates. `AbstractSql::jsonExtract($column, $path)` renders the dialect-correct extraction (`JSON_UNQUOTE(JSON_EXTRACT())` on MySQL, `->>`/`#>>` on PostgreSQL, `json_extract()` on SQLite, `JSON_VALUE()` on SQL Server) and is accepted as a SELECT column and by `orderBy()`/`groupBy()`. Callers always write MySQL-style `'$.path'`; PostgreSQL translation is internal.

```php
// New
$sql->select(['id', 'extracted_name' => $sql->jsonExtract('data', '$.name')])->from('users');
$sql->select()->from('users')->where->jsonEqualTo('data', '$.role', 'admin');

Users::findBy(['data->$.role'  => 'admin']);
Users::findBy(['data->$.roles' => ['CONTAINS', 'admin']]);  // MySQL/PgSQL only
```
**Previously:** not possible — no JSON API at all; you wrote the raw dialect-specific function into a string predicate yourself.

### Mass-assignment protection (`$fillable` / `$guarded` / `fill()`)
`AbstractRecord` gained `$fillable` (allowlist, takes precedence) and `$guarded` (denylist) properties plus public `fill()` and `isFillable()`. `Record::__construct()` now routes array-like input through `fill()`. With neither declared, behavior is unrestricted as before.

```php
// New
class Users extends Record
{
    protected ?string $table    = 'users';
    protected array   $fillable = ['username', 'email'];  // or: protected array $guarded = ['is_admin'];
}

$user = new Users($request->all());   // filtered
$user->fill($request->all())->save(); // filtered
```
**Previously:** not possible — the constructor set every key it was given, so an extra request-body key (`is_admin`) was written straight to the row. Internal flows (`replicate()`, the create path of `findOneOrCreate()`) deliberately bypass the filter via `newUnfilteredRecord()`.

### Record lifecycle hooks
Eight protected no-op hooks a table class can override: `beforeSave()`, `afterSave()`, `beforeInsert()`, `afterInsert()`, `beforeUpdate()`, `afterUpdate()`, `beforeDelete()`, `afterDelete()`. Order on `save()` is `beforeSave` → `beforeInsert`|`beforeUpdate` → statement → `afterInsert`|`afterUpdate` → `afterSave`; `afterUpdate()` runs after the row is re-fetched, and `afterDelete()` can still read the deleted record's values. Throwing from a hook aborts and propagates. Single-record paths only, not bulk.

```php
// New
class Users extends Pop\Db\Record
{
    protected function beforeSave(): void
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }
}
```
**Previously:** not possible — you had to override `save()`/`delete()` entirely and call `parent::`.

### Composite (multi-column) foreign keys in relationships
`hasOne()`, `hasOneOf()`, `hasMany()` and `belongsTo()` all widened `$foreignKey` from `string` to `string|array`. An array is matched positionally against the target table's primary keys (`hasOneOf`/`belongsTo`) or the declaring record's own (`hasOne`/`hasMany`), on both the lazy and eager paths.

```php
// New
public function user()
{
    return $this->belongsTo('Users', ['user_id', 'org_id']);
}
```
**Previously:** not possible — every relationship constructor was typed `string $foreignKey`.

### Multiple and nested eager-loading paths under one relationship
`with()` can now request more than one nested path hanging off the same parent relationship; `addWith()` merges child paths onto an existing entry. The parent is still resolved only once, and nesting is unlimited in depth.

```php
// New
$user = Users::with(['posts.comments', 'posts.tags'])->getById(1);
// with('posts.comments.author') also works
```
**Previously:** `with(['posts.comments', 'posts.tags'])` silently dropped all but the last path. Unmatched relationships now also resolve to a shape-correct value (`null` for 1:1, empty `Collection` for 1:many).

### `Pop\Db\Model` namespace (data model layer)
New namespace containing `AbstractDataModel`, `DataModelInterface` and `Exception`, giving a table-class-backed model API: static `fetchAll()`, `fetch()`, `createNew()`, `filterBy()`, and instance `getAll()`, `getById()`, `create()`, `update()`, `delete()`, `count()`, `describe()`, `validate()`, `filter()`. A `$requirements` property makes `create()`/`replace()` validate input and return `['errors' => [...]]` on a miss. Model `MyApp\Model\User` auto-links to table `MyApp\Table\Users` by convention.

```php
// New
use Pop\Db\Model\AbstractDataModel;

class User extends AbstractDataModel {}

$users = User::filterBy('username LIKE myuser%')->getAll('-id', '10', 2);
$user  = User::createNew($userData);
```
**Previously:** not available from `pop-db` — the class lived in `popphp` as `Pop\Model\AbstractDataModel` (removed there in v7). What is new for `pop-db` users is that it ships here and extends `Pop\Utils\AbstractModel`; `filter()` also returns `static`, so it chains into subclass methods.

### Auth records — authentication, attempt lockout and MFA on a table class
`Pop\Db\Record\Auth` extends `Record\Encoded` and turns a user table into a complete username/password authentication flow: credential verification against the hashed password column, failed-attempt lockout, and optional multi-factor code issuance and verification. The constructor adds `$passwordField` to `$hashFields` itself, so a table class cannot forget to hash its password column.

```php
use Pop\Db\Record\Auth;

class Users extends Auth {}

$user = new Users();

// $mfa = false — authenticate outright
if ($user->authenticate($username, $attemptedPassword, false)) {
    // logged in
} else {
    echo $user->getAuthFailureMessage();   // "Invalid credentials", "The user does not exist", ...
}

// $mfa = true (the default) — on success a fresh code and expiration are saved to the
// record and the record is returned, so the app can deliver the code however it likes
$result = $user->authenticate($username, $attemptedPassword);
if ($result !== false) {
    $mailer->send($result->email, $result->mfa_code);
}

// later, with the user record loaded
$user->authenticateMfa($attemptedCode);    // clears the stored code on success, so it cannot be replayed
```

Every failure path sets a reason constant, readable via `getAuthFailure()` and `getAuthFailureMessage()`: `USER_DOES_NOT_EXIST`, `USER_NOT_ACTIVE`, `USER_NOT_VERIFIED`, `ATTEMPTS_EXCEEDED`, `INVALID_CREDENTIALS`, `INVALID_MFA_CODE`, `MFA_CODE_EXPIRED`. A bad password, a wrong MFA code and an expired MFA code all increment the same `$attemptsField`, so a locked-out account is locked out of MFA guessing too. Codes are compared with `hash_equals()`.

Field names, the attempt limit, the lockout window, and the MFA code length, expiry and alphabet are all property overrides: `$usernameField`, `$passwordField`, `$attemptsField`, `$activeField`, `$verifiedField`, `$lastAttemptField`, `$attemptsLimit`, `$lockoutExpiration`, `$mfaConfig` — and the last three are readable and settable at runtime too.

**Previously:** `Pop\Auth\Table` checked the password and nothing else — it took a table class name, compared one column, and returned `0`/`1`. Attempt counting, lockout and MFA were entirely the application's job. That adapter is gone; see [`BC-BREAKS.md`](BC-BREAKS.md).

### Active and verified account gating on `Record\Auth`
An auth class can refuse a login on account *state* as well as credentials. `$activeField` and `$verifiedField` (default `active` and `verified`) name two columns that `authenticate()`, `authenticateMfa()` and `generateMfaCode()` all check before they look at anything else.

```php
// New
$user = new Users();
$user->authenticate($username, $attemptedPassword, false);

$user->getAuthFailure();          // 'USER_NOT_ACTIVE'
$user->getAuthFailureMessage();   // 'The user is not active'
```

Both are hard blocks, not guess failures: they are checked ahead of `ATTEMPTS_EXCEEDED` and `INVALID_CREDENTIALS` and never touch `$attemptsField`, so hammering a deactivated account cannot walk it into a lockout — and a lockout cannot be used to tell a deactivated account apart from a live one. `userActive()` and `userVerified()` expose the checks directly. Either check is opt-out per field: set its property to `null` and it always passes.

```php
class Users extends Auth
{
    protected ?string $verifiedField = null;   // this app doesn't do email verification
}
```

**Previously:** not available. `authenticate()` knew only "found and password matched" — enforcing a disabled or unconfirmed account meant re-reading the row and re-checking it in the calling code after a successful login, on every path that logged someone in.

### Lockouts that expire on their own
A lockout now clears itself once `$lockoutExpiration` seconds (default `900`) have passed since the last failed guess, so the common case needs no unlock path at all.

```php
// New
$user = Users::findOne(['username' => $username]);

$user->lockoutExpired();      // true, once the window has passed
$user->attemptsExceeded();    // false — and the attempts column is reset to 0 as a side effect
```

The clock is anchored to `$lastAttemptField` (default `last_attempt`), which is stamped only when an actual guess fails — not on a request already blocked by `attemptsExceeded()`. That distinction is what keeps a locked-out account from being held locked indefinitely by an attacker who simply keeps trying. `lockoutExpired()` reads the clock without the auto-clear side effect that `attemptsExceeded()` has.

Setting `$lockoutExpiration` to `0`, or `$lastAttemptField` to `null`, restores permanent lockout — `hasLockoutExpiration()` reports `false` and only an explicit `resetAttempts()` clears it.

**Previously:** lockout was permanent by design. Reaching `$attemptsLimit` locked the account until something called `resetAttempts()`, so every application needed its own unlock path — an admin screen, an emailed link, or a scheduled task — before a lockout could be anything but permanent.

### Reissuing an MFA code without re-checking the password
`generateMfaCode()` is public and fluent, so a "resend code" affordance is one call on the loaded record — the code `authenticate()` issues internally and the code a resend issues come from the same method.

```php
// New
$user = Users::findOne(['username' => $username]);
$user->generateMfaCode();

if ($user->wasMfaCodeGenerated()) {
    $mailer->send($user->email, $user->mfa_code);
} else {
    echo $user->getAuthFailureMessage();
}
```

It refuses in four cases, in order: the record is not a loaded user, the user is not active, the user is not verified, or attempts have already been exceeded. A refusal leaves any existing code and timestamp untouched, sets the matching failure constant, and reports `false` from `wasMfaCodeGenerated()`. The locked-out case is the deliberate one — MFA verification checks `attemptsExceeded()` before it ever compares the code, so a resend that worked on a locked-out account would be an unlimited-guessing loophole.

**Previously:** the code was generated inline inside `authenticate()`. Resending one meant either asking the user for their password again, or reimplementing the code generation, expiry stamp and save by hand against `$mfaConfig`'s column names.

### Transparent password rehashing on `Record\Encoded`
`verify()` now records whether the hash it just checked was made with an outdated algorithm or cost, so an app can upgrade stored hashes on the next successful login — while it still holds the plaintext.

```php
if ($user->verify('password', $attemptedPassword)) {
    if ($user->needsRehash()) {
        // re-hashes under the current $hashOptions and saves
        $user->rehash('password', $attemptedPassword);
    }
}
```

`rehash()`'s value parameter is marked `#[\SensitiveParameter]`, so the plaintext stays out of stack traces. `Record\Auth::authenticate()` runs this automatically on every successful login.

**Previously:** `verify()` returned a bare `bool`. Raising a bcrypt cost left every existing hash at the old cost indefinitely unless you re-implemented the check by hand.

### Smaller additions
- New `Sql\Parser\Keyword` with public `indexOf()`/`split()` — quote-aware AND/OR splitting, so `where('name = "a AND b"')` no longer splits inside a quoted literal.
- New `Sql\Parser\Condition` helpers `isNewSyntax()` and `isPlainEquality()` for code that needs to classify a shorthand entry.
- `findWhereBetween()`/`findWhereNotBetween()` accept an unambiguous 2-element array (`[1, 5]`); all `findWhere*()` magic methods build structured shorthand internally, so none emits a deprecation.
- `RelationshipInterface` gained `getForeignKey()` and `getEmptyRelationshipValue()`; `AbstractRelationship` gained `setChildRelationships(array)`/`getChildRelationships(): array` plus static `buildCompositeKey()`/`tupleFor()`.
- `Record\Auth` settings that were property-only are now readable and settable at runtime: `getAttemptsLimit()`/`setAttemptsLimit()`/`hasAttemptsLimit()`, `getLockoutExpiration()`/`setLockoutExpiration()`/`hasLockoutExpiration()`, and `getMfaConfig()`/`setMfaConfig()`. `setMfaConfig()` merges, so `setMfaConfig(['length' => 8])` leaves the other four keys alone, and any key it does not recognize is dropped rather than stored.
- `Record\Auth::authenticate()` takes an optional fourth `$attemptsLimit`. It calls `setAttemptsLimit()` internally, so the value sticks on the instance rather than applying to that one call.
- An `$attemptsLimit` of `0` turns attempts enforcement off entirely — `attemptsExceeded()` stays `false` however high the column climbs.
- `Record\Auth::resetAttempts()` returns `static` rather than `void`, so it chains.
- New protected extension points on `Predicate\AbstractPredicate`: `renderValue()`, `renderJsonValue()`, `assertNoSubqueryAlias()`.
- `Select::render()` split into overridable `buildColumnsClause()`, `buildFromClause()`, `buildJoinsClause()`, `buildLimitOffsetClause()`, `quoteByColumn()`; `Record` gained overridable `parseFindWhereArguments()`, `buildWhereConditionColumns()`, `newUnfilteredRecord()`.
- `PredicateSet::getParameters()`/`hasParameters()` now recurse into nested sets, so parameters bound inside an OR/AND group reach the prepared statement.
- `Adapter\Profiler\Profiler` types its debugger as `Pop\Utils\DebuggerInterface`, so any debugger implementation plugs in — and the `pop-debug` dependency was dropped.
- `Record::reset(string $column, mixed $value = null)` sets a column and saves in one call — the counterpart to the existing `increment()`/`decrement()`.

---

## pop-debug — 3.0.0 → 4.0.0

**Summary:** Automatic redaction of sensitive request data, an NDJSON storage format with real nested context, PSR-3 logger interoperability, and conformance to the shared `Pop\Utils` debugger interfaces.
**Feature count:** 4

### Automatic redaction of sensitive request data
`RequestHandler` scrubs secrets out of everything it captures before that data reaches a logger or storage adapter — **enabled by default**. Keys are matched case- and separator-insensitively as substrings against `DEFAULT_REDACTED_KEYS` (`pass`, `pwd`, `secret`, `token`, `apikey`, `authorization`, `cookie`, `csrf`, `creditcard`, `cvv`, `ssn`, `pin`, …) across headers, `$_SERVER`, `$_ENV`, query/post/put/patch/delete and parsed data (recursively into nested arrays), while `$_COOKIE` and `$_SESSION` are redacted wholesale regardless of key.

```php
// New
$requestHandler = new RequestHandler();          // redaction on by default
$requestHandler->setRedactSensitiveData(false);  // or capture raw values
$requestHandler->setRedactedKeys(['password', 'pin']); // replace the default list
$requestHandler->addRedactedKey('x-internal-id');      // append to the current list

$requestHandler->isRedactingSensitiveData(); // bool
$requestHandler->getRedactedKeys();          // array
```
**Previously:** not possible. `prepare()` returned headers, server vars, post data, `$_COOKIE` and `$_SESSION` verbatim, so passwords, tokens, session IDs and auth headers were written to CSV/database storage and log context in plaintext. Suppressing them meant not using the request handler at all, or subclassing and overriding `prepare()`.

### NDJSON storage format with real nested context
`Storage\File` accepts a third format, `ndjson` (one self-contained JSON object per line), alongside `csv` and `tsv`. Unlike the flat CSV/TSV cell where `context` stays a JSON-encoded *string*, the NDJSON writer decodes `context` back into a real nested value before encoding the event — so output is proper nested JSON suited to `jq` and log-aggregator ingestion. Encoding uses `JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR` so binary payloads don't drop a whole line.

```php
// New
$debugger->setStorage(new File(__DIR__ . '/log', 'ndjson'));
// public function __construct(string $dir, string $format = 'csv')
```
**Previously:** not possible — `setFormat()` threw for anything but `csv`/`tsv`. Getting structured JSON out meant post-processing the CSV and double-decoding the `context` column, or writing a custom `StorageInterface`.

### PSR-3 logger support (any logger, not just pop-log)
Every logger type hint moved from the concrete `Pop\Log\Logger` to `Psr\Log\LoggerInterface`, across `Debugger::addLogger()`, `AbstractHandler::__construct()`/`setLogger()`/`getLogger()`, and every handler constructor. Any PSR-3 logger — Monolog, Laminas, a framework's app logger, a test spy — can now be wired into the debugger.

```php
// New
$debugger->addHandler(new TimeHandler());
$debugger->addLogger($monolog, ['level' => 'warning', 'limit' => 2]);
// public function addLogger(LoggerInterface $logger, array $loggingParams): Debugger
```
**Previously:** not possible without a wrapper — the signatures required an actual `Pop\Log\Logger`, so a Monolog logger had to be adapted behind a fake `Logger` subclass.

### Conformance to the `Pop\Utils` debugger interfaces
`Debugger` implements `Pop\Utils\DebuggerInterface` and handlers implement `Pop\Utils\DebuggerHandlerInterface` (both new in `pop-utils` 3.0), replacing the package-local `Handler\HandlerInterface`. Because the contracts live in `pop-utils`, **other components can type-hint a debugger or handler generically and accept a `pop-debug` instance without depending on `pop-debug` itself**.

```php
// New
use Pop\Utils\DebuggerInterface;

class SomeService
{
    public function __construct(protected ?DebuggerInterface $debugger = null) {}
}

$service = new SomeService(new Pop\Debug\Debugger()); // accepted
```
**Previously:** not possible — the only contract was defined inside this package, with no debugger-level interface at all, so a consuming component had to require `pop-debug` and type-hint the concrete class.

### Smaller additions
- `AbstractHandler::resolveLogLevel()` — a protected helper guarding the shared `log()` preconditions, so custom handlers no longer duplicate that block; all seven built-in handlers use it.
- `Storage\File::FORMATS` — a supported-format constant replacing the inline literal, with a matching error message.
- `RequestHandler::isRedactingSensitiveData()` and `getRedactedKeys()` inspection accessors.
- README now documents previously undocumented behavior: constructor injection of handlers/storage, the `$name` prefix for registering multiple handlers of one type, request-ID correlation via `getRequestId()`/`setRequestId()`, and the caveats that `File::clear()` deletes every file in the directory, `Database::clear()` is an unscoped `DELETE`, and **`QueryHandler` performs no redaction of bound parameters**.

---

## pop-dir — 4.0.3 → 5.0.0

**Summary:** The option setters, name-keyed array access and symlink handling that were documented-or-implied in v6 now actually work, and the walk is unified behind four overridable hooks.
**Feature count:** 7

### Option setters now re-scan the directory
`setAbsolute()`, `setRelative()`, `setRecursive()` and `setFilesOnly()` call the new `rebuild()` once the object is past construction, so both `getFiles()` and `getTree()` reflect the new flag immediately.

```php
// New
$dir = new Dir('my-dir');
$dir->setRecursive(true)->setAbsolute(true);
print_r($dir->getFiles()); // recursive, absolute paths
```
**Previously:** the setters only flipped a property — the file list built in the constructor was never regenerated, so the change **silently did nothing**. You had to discard the object and re-construct it with an options array.

### Name-keyed reads return the entry
`offsetGet()` (and therefore `$dir['file.txt']`) runs the offset through the new `resolveOffset()` helper, matching a file/directory name to its index the same way `offsetExists()` and `offsetUnset()` already did. Reads by name and `isset()` by name now agree.

```php
// New
if (isset($dir['file1.txt'])) {
    echo $dir['file1.txt']; // 'file1.txt'
}
```
**Previously:** `isset($dir['file1.txt'])` was `true` but `$dir['file1.txt']` returned `null` — you had to `array_search()` the name in `getFiles()` yourself.

### Recursive walks no longer descend into symlinked subdirectories
`walkDirectory()` only recurses when `recursive` is set *and* the entry is not a link; a symlinked subdirectory is recorded as an empty-array leaf in the tree. **This makes a recursive scan safe against symlink cycles** that would previously recurse until exhaustion.

```php
// New — terminates even if my-dir/link points back at my-dir
$dir = new Dir('my-dir', ['recursive' => true]);
$dir->getTree();
```
**Previously:** not possible to guard against — both the tree builder and the SPL iterator followed symlinks.

### `emptyDir()` is symlink-safe by default, with opt-in traversal
Emptying a directory no longer reaches outside it. A symlinked subdirectory is unlinked, leaving whatever it
points at untouched — so a stray link in a cache or upload folder can no longer take the target's contents
with it. Passing `true` as the third argument opts into the destructive form, which recurses through the link
and empties the *target* as well.

```php
// New
$dir = new Dir('my-dir');
$dir->emptyDir();                    // symlinks removed, their targets left alone
$dir->emptyDir(false, null, true);   // follows every symlink and empties the target too
```
**Previously:** the same outcome, but by accident rather than by policy — `emptyDir()` tried `unlink()` first,
which happens to remove a symlink rather than follow it. Nothing declared the intent, there was no way to opt
into traversal, and the behavior sat one refactor away from silently becoming destructive.

### Unreadable directories throw `Pop\Dir\Exception`
`rebuild()` catches the native `\UnexpectedValueException` the SPL iterators raise on an unreadable directory and rethrows it as `Pop\Dir\Exception`, preserving the code and passing the original as `$previous`. This makes the `@throws` contract on the constructor accurate.

```php
// New
try {
    $dir = new Dir('/unreadable-dir', ['recursive' => true]);
} catch (Pop\Dir\Exception $e) {
    echo $e->getPrevious()->getMessage(); // original SPL message
}
```
**Previously:** a raw `\UnexpectedValueException` escaped, so callers catching `Pop\Dir\Exception` missed it entirely.

### One filesystem walk instead of two or three
The tree and the flat file list are produced together by a single `DirectoryIterator` pass per level, with the root's `realpath()` resolved once and threaded through.

```php
// New — identical call, roughly half the stat/readdir work
$dir = new Dir('my-dir', ['recursive' => true]);
```
**Previously:** the tree was built with one traversal and then the whole directory was re-traversed for the file list — with the recursive case re-walking a third time via `RecursiveIteratorIterator`.

### Protected extension points for subclasses
Four new protected methods give subclasses a seam: `rebuild()` (re-scan trigger), `walkDirectory()` (per-level traversal and recursion policy), `resolveEntry()` (how a walked entry is formatted, honoring absolute/relative/filesOnly) and `resolveOffset()` (name-to-index resolution).

```php
// New
class SkipHidden extends Dir {
    protected function resolveEntry(\SplFileInfo $f, string|false $abs, string|false $root): ?string {
        return str_starts_with($f->getFilename(), '.') ? null : parent::resolveEntry($f, $abs, $root);
    }
}
```
**Previously:** not possible — the path-formatting logic was inlined and duplicated across the two traversal methods, with no single hook.

### Smaller additions
- `emptyDir()` distinguishes a directory from an undeletable file and throws naming the file; v6 fell through to a recursive call on the file path and surfaced a misleading "Unable to open the directory path" error.
- `copyTo()` uses `basename()` for the destination folder name, so it works for a path with no separator in it (v6 used an undefined variable in that case).
- README gained sections on reading contents, the tree structure, `copyTo()`, deleting files and the full list of thrown exception cases.

---

## pop-dom — 4.0.7 → 5.0.0

**Summary:** A small hardening release: attribute values are auto-escaped, `render()` became side-effect free, and the parse/removal APIs return sane values instead of fataling.
**Feature count:** 5

### Automatic HTML-escaping of attribute values
Attribute values are run through `htmlspecialchars(..., ENT_QUOTES)` at render time, so you can pass raw user data straight into `setAttribute()`/`setAttributes()` without breaking out of the quoted attribute or injecting markup.

```php
// New
$input = new Child('input', null, ['attributes' => ['value' => 'he said "hi" & <b>']]);
echo $input; // <input value="he said &quot;hi&quot; &amp;lt;b&gt;" />
```
**Previously:** the value was interpolated verbatim, so you had to call `htmlspecialchars()` yourself on every attribute or accept an XSS/malformed-markup hole. **Note this ripples into `pop-form` and `pop-nav` output, which render through `pop-dom`.**

### `render()` is side-effect free
`render()` no longer mutates `$indent` or `$nodeValue`. Indentation is recomputed from the passed depth on every call, and CDATA wrapping is applied to a local copy. A node can therefore be rendered standalone, rendered again, rendered at a different depth, or rendered and *then* nested into a parent, and always come out correct.

```php
// New
$span = new Child('span', 'x');
echo $span;                 // <span>x</span>
$div = new Child('div');
$div->addChild($span);
echo $div;                  // <div>\n    <span>x</span>\n</div>
```
**Previously:** the first render froze the indent to the depth-0 value, so the nested render came out flush-left. A CDATA node rendered twice produced a doubly-wrapped `<![CDATA[<![CDATA[hello]]>]]>`, and `getNodeValue()` after a render returned the wrapped string.

### `parseString()` / `parseFile()` return `null` on element-less input
Input with no element nodes (empty or whitespace-only strings, a blank file) returns `null`, which the widened `Child|array|null` return type expresses.

```php
// New
$result = Child::parseString('   ');   // null
if ($result !== null) { $doc->addChildren($result); }
```
**Previously:** a fatal `Error: Call to a member function getParent() on null` inside the parser — an uncatchable-by-type crash rather than a value you could check.

### `parseFile()` can parse multi-root fragments
`parseFile()` shares `parseString()`'s return type, so a file with more than one top-level node yields an array of `Child` instances.

```php
// New
$children = Child::parseFile('fragment.html'); // ['<div>one</div>', '<div>two</div>']
$doc->addChildren($children);
```
**Previously:** declared `: Child`, so parsing any fragment file without a single root threw a `TypeError`. Only `parseString()` could handle fragments.

### `removeChild()` reindexes the remaining children
Removal re-packs the child array, so index-based access stays contiguous.

```php
// New
$ul->removeChild(0);
$ul->getChild(0)->getNodeValue(); // 'Two' — the list shifted down
```
**Previously:** the gap remained, so `getChild(0)` returned `null` after removing index 0 and you had to `array_values()` the result yourself.

### Smaller additions
- `getIndent()` is now typed `?string`, so calling it on a node with no explicit indent returns `null` instead of throwing a `TypeError`.
- Parser internals were extracted into private helpers (`extractAttributes()`, `climbToNamedParent()`, `appendSiblingText()`, `normalizeWhiteSpace()`), and `extractAttributes()` guards on `instanceof \DOMElement`.

---

## pop-filter — 4.0.4 → 5.0.0

**Summary:** Four additions: nested arrays filter recursively, a filter's callable can be removed to make it a no-op, and `FilterableTrait` gains `filterEach()`, `hasFilter()` and `removeFilter()`.
**Feature count:** 4

### Recursive filtering of nested arrays
`AbstractFilter::filter()` recurses when an array element is itself an array, so multi-level structures are sanitized all the way down instead of handing an array to a string callable. **This is what makes filtering real `$_POST`/`$_GET` payloads (checkbox groups, repeated fieldsets, JSON bodies) actually work.**

```php
// New
$filter = new Pop\Filter\Filter('strip_tags');
$filter->filter(['user' => ['name' => '<b>Admin</b>', 'tags' => ['<i>a</i>']]]);
// => ['user' => ['name' => 'Admin', 'tags' => ['a']]]
```
**Previously:** only one level deep was handled — a nested array was passed straight into the callable, so `strip_tags(array)` raised a `TypeError` (or the value silently survived unfiltered with a lenient callable). You flattened or hand-walked the array yourself first.

### `removeCallable()` and null-callable-safe filters
`removeCallable()` unsets the callable, turning the filter into a pass-through that returns values untouched — useful for temporarily disabling a filter already registered in a chain, without removing it or rebuilding it.

```php
// New
$filter = new Pop\Filter\Filter('strip_tags');
$filter->removeCallable();
echo $filter->filter('<b>admin</b>'); // <b>admin</b>
```
**Previously:** not possible — there was no `removeCallable()`, `getCallable()` was typed non-nullable, and every accessor dereferenced the callable unconditionally, so a callable-less filter would fatal. The only option was to drop and re-add the filter.

### `FilterableTrait::filterEach()`
A ready-made loop that runs every registered filter over every value in an array, passing the array key through as the filter's `$name`. Because the key is supplied, **each filter's `excludeByName` rules are honored automatically** — which the existing `filterAll()` cannot do.

```php
// New
class User { use Pop\Filter\FilterableTrait;
    public function filter(array $values): array { return $this->filterEach($values); }
}
$user->addFilter(new Pop\Filter\Filter('strip_tags', null, 'username'))
     ->filter(['username' => '<b>Admin</b>', 'email' => '<i>a@b.com</i>']);
// username stays '<b>Admin</b>', email becomes 'a@b.com'
```
**Previously:** every consuming class hand-wrote the nested loop (exactly what the old README told you to copy) to get key-aware, exclude-respecting filtering. `filterAll()` was the only built-in helper and it ignores excludes entirely.

### `FilterableTrait::hasFilter()` / `removeFilter()`
Identity-based (`===`) membership check and removal of a single registered filter, with the remainder re-indexed.

```php
// New
$stripTags = new Pop\Filter\Filter('strip_tags');
$user->addFilters([$stripTags, new Pop\Filter\Filter('trim')]);
$user->hasFilter($stripTags);    // true
$user->removeFilter($stripTags); // 'trim' remains
```
**Previously:** only `hasFilters()` (any/none) and `clearFilters()` (all-or-nothing) existed — removing one filter meant clearing the chain and re-adding the others.

### Smaller additions
- `getCallable()` return type widened to `?CallableObject` on both the abstract and the interface, so a callable-less filter can be inspected safely.
- `getParams()` returns `[]` and `hasParams()` returns `false` instead of fataling when no callable is set; `setParams()` is a silent no-op in that state.

---

## pop-form — 4.2.7 → 5.0.0

**Summary:** Automatic ARIA wiring on rendered fields, built-in file-upload type/size validation, hardened per-field-name CSRF tokens, and two new reusable extension points.
**Feature count:** 5

### Automatic ARIA wiring on rendered fields
Every render path (`dl`, `div`, `p`, `table`, and the fields-as-array path used by view templates) runs each field through `Fieldset::prepareFieldAccessibility()`, which gives hints and error containers stable ids (`{name}-hint` / `{name}-error`, with `[]` stripped from array field names), points `aria-describedby` at them, sets `aria-invalid="true"` while the field has errors, and puts `role="alert"` on the error `<div>`. Both attributes are *removed* once a field validates, so a corrected field stops announcing itself as invalid on re-render. `aria-invalid` is deliberately skipped on `CheckboxSet`/`RadioSet`, which render as a grouping `<fieldset>`.

```php
// New
$form = Form::createFromConfig([
    'username' => ['type' => 'text', 'label' => 'Username:', 'required' => true, 'hint' => 'Letters and numbers only.']
]);
$form->setFieldValues($_POST);
echo $form;
// <input type="text" name="username" id="username" required="required"
//   aria-invalid="true" aria-describedby="username-hint username-error" />
// <span id="username-hint">Letters and numbers only.</span>
// <div class="error" id="username-error" role="alert"><span>This field is required.</span></div>
```
**Previously:** not possible — the error `<div>` had no id and no `role`, hint `<span>`s had no id, and no `aria-*` attribute was ever emitted. You subclassed `Fieldset` or post-processed the rendered HTML to associate errors with their control.

### File-upload validation: `allowed-types` and `max-size`
`Element\Input\File` gained an extension allowlist and a byte-size cap, enforced inside its own `validate()`. Extensions are normalized (lowercased, leading dot stripped) and matched against the submitted filename; the size error is rendered human-readably via `Pop\Utils\File::formatFileSize()` (e.g. "2 MB") rather than a raw byte count. Both are also reachable as config keys.

```php
// New
$fields = [
    'avatar' => [
        'type'          => 'file',
        'label'         => 'Avatar:',
        'allowed-types' => ['jpg', 'jpeg', 'png', 'gif'],
        'max-size'      => 2000000
    ]
];
$form = Form::createFromConfig($fields);

// or directly on the element:
$file = new Pop\Form\Element\Input\File('avatar');
$file->setAllowedTypes(['jpg', '.png'])->setMaxSize(2000000);
```
**Previously:** not possible out of the box — `Input\File::validate()` only checked `required`. You attached a closure validator that re-parsed the filename and inspected `$_FILES` size yourself, and there were no config keys.

### Per-field-name CSRF tokens with CSPRNG generation and timing-safe comparison
`$_SESSION['pop_csrf']` is now an array keyed by field name instead of a single serialized token, so multiple CSRF-protected forms (or several tokens on one page) coexist without clobbering each other. Token values come from `bin2hex(random_bytes(32))` instead of `sha1(rand(...))`, and submission is checked with `hash_equals()` against the server-side value.

```php
// New — two independently-protected forms in the same session
$loginForm   = Form::createFromConfig(['login_token'   => ['type' => 'csrf'], /* ... */]);
$profileForm = Form::createFromConfig(['profile_token' => ['type' => 'csrf'], /* ... */]);
// $_SESSION['pop_csrf'] = ['login_token' => [...], 'profile_token' => [...]]
```
**Previously:** one global token slot. A second `csrf` field overwrote the first, so two CSRF forms on a page could not both validate. The token was non-CSPRNG (`sha1(rand(...))`) and comparison went through a non-timing-safe `==`.

### `Element\AbstractInputSet` — a base class for custom multi-input fields
New abstract class that `CheckboxSet` and `RadioSet` now extend. It owns the shared `$inputs` array, legend/container handling, group-wide `setDisabled()`/`setReadonly()` propagation, `validate()`, `render()`, plus protected `appendInputWithSpan()`/`setInputAttribute()` helpers — making it a supported extension point for building your own `<fieldset>`-rendered composite field types.

```php
// New
class ToggleSet extends Pop\Form\Element\AbstractInputSet
{
    public function __construct(string $name, array $values, ?string $indent = null)
    {
        parent::__construct('fieldset');
        foreach ($values as $k => $v) {
            $this->appendInputWithSpan(
                new Pop\Form\Element\Input\Checkbox($name . '[]', $k), $v,
                'toggle-span', $indent, 'toggle-fieldset-container'
            );
        }
    }
    public function setValue(mixed $value = null): static { /* ... */ return $this; }
    public function getType(): string { return 'checkbox'; }
}
```
**Previously:** not possible without duplication — all of that logic was copy-pasted separately inside `CheckboxSet` and `RadioSet`. A custom input set meant re-implementing ~200 lines of plumbing by hand.

### `ValidatorEvaluationTrait` — reusable validator evaluation
New public trait exposing `evaluateValidator(mixed $validator, mixed $value, array $formValues = []): array`, which handles all four supported validator shapes (a `ValidatorInterface`, a callable returning a string, a callable returning a validator, a callable returning an array of validators) and returns the resulting messages. It is mixed into both `AbstractElement` and `FormValidator`, and can be pulled into your own classes.

```php
// New
class MyApiValidator
{
    use Pop\Form\ValidatorEvaluationTrait;

    public function check(mixed $validator, mixed $value): array
    {
        return $this->evaluateValidator($validator, $value);
    }
}
```
**Previously:** not possible — the identical logic was inlined three times with no shared entry point.

### Smaller additions
- `AbstractElement::validateCallable()` de-duplicates messages before appending them, matching what `validateValue()` already did.
- `Fields` gained an explicit `type => class-string` allowlist, replacing dynamic `class_exists()` resolution — a config-supplied `type` can no longer instantiate arbitrary classes from that namespace.
- `ElementInterface::setRequired()`/`setDisabled()`/`setReadonly()` declare `ElementInterface` returns instead of `mixed`, and the input-set setters return `static`, so fluent chaining is properly typed for static analysis.
- `Select` forwards `$xmlFile` and `$indent` to `setValues()` from its constructor, so XML-sourced option sets actually build.
- `Input\Datalist` always builds its `<datalist>` child, so a datalist created with an empty values array still renders the referenced element instead of a dangling `list=` attribute.

---

## pop-http — 5.3.8 → 6.0.0

**Summary:** A fully PSR-7/17/18-conformant HTTP library with a composable client middleware pipeline (retry + logging), a mock transport for tests, and a native streaming `Body`.
**Feature count:** 18

### PSR-7 immutable message API
Every request, response, URI and body implements the matching `psr/http-message` interface and gained the immutable `with*()`/`without*()` methods alongside the existing fluent mutators. PSR-7's `getHeader()`/`getHeaders()`/`getHeaderLine()` return plain strings; the old object-returning methods live on as `getHeaderObject()`/`getHeaderObjects()`.

```php
// New
$request = (new Request('http://localhost/'))
    ->withHeader('X-Api-Key', 'abc123')   // new instance
    ->withMethod('POST');

$request->getHeader('X-Api-Key');     // ['abc123']
$request->getHeaderLine('X-Api-Key'); // 'abc123'
```
**Previously:** Not possible — no `with*()` methods anywhere, no class implemented any PSR interface.

### PSR-18 HTTP client (`Client::sendRequest()`)
`Client` implements `Psr\Http\Client\ClientInterface`, so it can be injected anywhere a PSR-18 client is expected; a foreign PSR-7 request is transparently converted first. The exception hierarchy is conformant too: `Client\Exception implements ClientExceptionInterface`, `Handler\Exception implements NetworkExceptionInterface`, and a new `Client\RequestException implements RequestExceptionInterface`.

```php
// New
$response = (new Client())->sendRequest(new Request('http://localhost/api'));
echo $response->getStatusCode();
```
**Previously:** Not possible — only `send()`/`sendAsync()`, no interoperability interface.

### PSR-17 factories
Six new factories under `Pop\Http\Factory` that build native `pop-http` objects: `RequestFactory`, `ResponseFactory`, `ServerRequestFactory`, `StreamFactory`, `UploadedFileFactory`, `UriFactory`.

```php
// New
$request = (new RequestFactory())->createRequest('POST', 'http://localhost/');
$body    = (new StreamFactory())->createStream('{"a":1}');
$request = $request->withBody($body);
```
**Previously:** Not possible — no `Pop\Http\Factory` namespace.

### Client middleware pipeline
`addMiddleware()`/`getMiddleware()`/`hasMiddleware()` plus an onion-style `Middleware\Pipeline` applied uniformly to `send()`, `sendAsync()` and `sendRequest()`. Registration order is wrap order; a middleware can short-circuit by never calling `$handler->handle()`. Accepts a plain closure or a `MiddlewareInterface`.

```php
// New
$client->addMiddleware(function ($request, $handler) {
    return $handler->handle($request->withHeader('X-Trace-Id', uniqid()));
});
$client->send();
```
**Previously:** Not possible — no interception point between `send()` and the handler.

### `RetryMiddleware` — retries with exponential backoff and jitter
Retries transient network exceptions and configurable status codes (default `429/502/503/504`), only for idempotent-safe methods by default. Honors a `Retry-After` response header over the computed delay, and skips retrying entirely if the request body is non-seekable.

```php
// New
$client->addMiddleware(
    (new RetryMiddleware(5))
        ->setBaseDelay(0.2)
        ->setMaxDelay(10.0)
        ->setRetryableStatusCodes([429, 500, 503])
        ->setOnRetry(fn($attempt, $req, $res, $ex, $delay) => error_log("retry $attempt in $delay s"))
);
```
**Previously:** Not possible — retry had to be hand-rolled in a caller-side loop.

### `LoggingMiddleware` — PSR-3 request logging with header redaction
Logs one line per dispatch attempt to any `Psr\Log\LoggerInterface`, at a level derived from the outcome (`info` 2xx/3xx, `warning` 4xx, `error` 5xx or thrown). `Authorization`, `Cookie`, `Set-Cookie`, `X-Api-Key` and `Proxy-Authorization` are redacted by default; bodies are excluded unless opted in, and are read with a bounded, position-preserving read so a large upload is never materialized.

```php
// New
$client->addMiddleware((new RetryMiddleware(3))->setOnRetry(LoggingMiddleware::logRetriesTo($logger)))
       ->addMiddleware((new LoggingMiddleware($logger))->setIncludeBody(true)->setMaxBodyLength(500));
```
**Previously:** Not possible — `psr/log` was not even a dependency.

### `Mock` handler — network-free testing
A drop-in replacement for `Curl`/`Stream` returning canned results with no real I/O. Results come from a FIFO `queue()` or a callback matcher `when()`; a queued `\Throwable` is thrown verbatim to simulate a transport failure. Every dispatched request is recorded for assertions, and an exhausted/unmatched dispatch throws rather than silently returning a default.

```php
// New
$mock = new Mock();
$mock->queue(new Response(['code' => 200, 'body' => '{"ok":true}']));
$mock->when(fn($request) => $request->getMethod() === 'POST', new Response(['code' => 201]));

$response = (new Client('http://localhost/', $mock))->send();
$mock->getLastRequest();
$mock->getRequests();
```
**Previously:** Not possible — testing HTTP-calling code meant hitting a real server or hand-writing a `HandlerInterface`.

### `Pop\Http\Body` — native streaming body / PSR-7 stream
A new first-class body backed by a real stream resource (`php://temp` or a file handle) implementing `StreamInterface`. `setContentFromFile()` opens the file rather than slurping it, and `setContentFromStream()`/`getStream()` let a consumer copy bytes to the wire without materializing them as a PHP string.

```php
// New
$body = new Body();
$body->setContentFromFile('/path/to/large.zip'); // opened, not buffered
echo $body->getSize();
$chunk = $body->read(8192);
$body->rewind();
```
**Previously:** `Pop\Mime\Part\Body::setContentFromFile()` ran `file_get_contents()` on the whole file, and the body had no stream API at all.

### `Server\UploadedFile` — PSR-7 uploaded file objects
`Server\UploadedFile implements UploadedFileInterface`, with `getStream()`, `moveTo()`, `getSize()`, `getError()`, `getClientFilename()`, `getClientMediaType()`, plus `createFromFilesArray()` which normalizes both flat and per-field multi-file `$_FILES` shapes.

```php
// New
foreach ($server->request->getUploadedFiles() as $name => $file) {
    if ($file->getError() === UPLOAD_ERR_OK) {
        $file->moveTo('/uploads/' . $file->getClientFilename());
    }
}
```
**Previously:** Not possible — uploads were raw `$_FILES` arrays; no object per file and no `getUploadedFiles()`.

### `Body\Multipart` — native RFC 7578 multipart build/parse
Replaces `Pop\Mime\Message::createForm()`/`parseForm()` (a mail-multipart tool being bent to serve HTTP). File parts stream from disk into a `php://temp` output stream, boundaries are CRLF-sanitized, and header parameters are quote-escaped. `toArray()` produces the curl-native `CURLFile` shape so curl streams uploads with zero buffering.

```php
// New
$body = Multipart::build([
    'title' => 'My upload',
    'photo' => ['filename' => '/path/to/photo.jpg'],
]);
$data = Multipart::parse($rawBody, $boundary);
```
**Previously:** Handled indirectly through `pop-mime`, which buffered whole file contents into memory (and base64-encoded them) before a byte reached the wire.

### Superglobal-free `Server\Request` construction
The constructor gained `bool $populateFromGlobals = true` and `array $serverParams = []`. With `false`, none of the superglobals is touched — `$serverParams` seeds the server params directly. This is what `ServerRequestFactory` uses.

```php
// New
$request = new Request(
    'http://localhost/api/users', null, null,
    false, ['REQUEST_METHOD' => 'POST', 'SERVER_PORT' => 8080]
);
```
**Previously:** Not possible — the constructor unconditionally read every superglobal.

### PSR-7 attribute bag on `Server\Request`
The per-request key/value bag (`getAttribute()`, `withAttribute()`, `withoutAttribute()`) — the standard place for middleware to attach resolved route params or an authenticated user — plus the full PSR-7 server-request accessors (`getServerParams()`, `getCookieParams()`, `getQueryParams()`, `getUploadedFiles()`, `getParsedBody()` and their `with*()` counterparts).

```php
// New
$request = $server->request->withAttribute('user_id', 42);
$request->getAttribute('user_id');       // 42
$request->getAttribute('missing', null); // default
```
**Previously:** Not possible — no attribute concept, and none of the PSR-7 accessors.

### `Uri` PSR-7 accessors and immutable setters
`getAuthority()` (assembling `[userinfo@]host[:port]`, omitting a scheme-default port), `getUserInfo()`, `getPath()`, plus `withScheme()`, `withUserInfo()`, `withHost()`, `withPort()`, `withPath()`, `withQuery()`, `withFragment()`.

```php
// New
$uri = new Uri('https://user:pass@localhost:8080/api/users?a=1');
echo $uri->getAuthority(); // user:pass@localhost:8080
echo $uri->getPath();      // /api/users
$other = $uri->withHost('example.com')->withPort(null);
```
**Previously:** Not possible — `Uri` had only mutable `set*()` methods.

### Structured transport errors
`Handler\Exception` now carries the underlying curl error number and the in-flight request via `getCurlErrno(): int` and `getRequest(): RequestInterface`.

```php
// New
try {
    $client->send();
} catch (\Pop\Http\Client\Handler\Exception $e) {
    if ($e->getCurlErrno() === CURLE_OPERATION_TIMEDOUT) {
        echo 'Timed out calling ' . $e->getRequest()->getUri();
    }
}
```
**Previously:** Not possible — it was `class Exception extends \Exception {}` with nothing attached.

### `Parser::parseMediaType()` and charset-aware parsing
A structured media type replaces substring matching, so `application/vnd.api+json` decodes as JSON and `application/xhtml+xml` is no longer routed as plain XML. A non-UTF-8 `charset` parameter transcodes the payload via `mb_convert_encoding()` before the type-specific parse.

```php
// New
Parser::parseMediaType('application/vnd.api+json; charset=utf-8');
// ['type' => 'application', 'subtype' => 'vnd.api', 'suffix' => 'json', 'params' => ['charset' => 'utf-8']]
```
**Previously:** Not possible — `str_contains($contentType, 'xml')`-style matching, no charset handling.

### `CurlMulti::isBatchSuccess()` / `isBatchError()`
Batch-level status named distinctly from the per-response `isSuccess()`/`isError()` convention, removing the ambiguity of the same name meaning two different things. The old names remain as deprecated aliases.

```php
// New
do { $multiHandler->send($running); } while ($running);
if ($multiHandler->isBatchSuccess()) {
    $responses = $multiHandler->getAllResponses();
}
```
**Previously:** Only `isSuccess()`/`isError()`, with per-batch semantics that silently differed from the identically-named per-response methods.

### `Client::dispatch()` / `dispatchRequest()`
`dispatch()` performs the synchronous request unconditionally, never redirecting into the async branch the way `send()` does when `'async' => true` is set — which is what lets `Promise::wait()` actually fire the request instead of returning another `Promise`. `dispatchRequest()` is the public terminal handler at the center of the middleware pipeline.

```php
// New
$client   = new Client('http://localhost/', ['async' => true]);
$response = $client->dispatch(); // synchronous, despite 'async'
```
**Previously:** Not possible — calling `send()` from inside a promise's `wait()` re-entered the async branch.

### Accept-header content negotiation (`accepts()`, `getPreferredType()`)
`Server\Request` can answer "what does this client actually want back?" without you touching the raw header. `Pop\Http\Server\AcceptHeader` parses the `Accept` header per RFC 7231 — q-values, `type/*` and `*/*` wildcards, whitespace and casing — and `Server\Request` forwards to it, so one branch is all it takes to serve HTML and JSON from the same route.

```php
if ($request->acceptsHtml()) {
    // render a view
} else {
    // send JSON
}

$request->accepts(['application/json', 'application/xml']);        // either acceptable?
$request->getPreferredType(['text/html', 'application/json']);     // best match, or null
```
`acceptsHtml()`, `acceptsJson()` and `acceptsXml()` (which covers both `application/xml` and `text/xml`) are shorthands over `accepts()`. `getPreferredType()` takes what your server can actually produce and returns the client's best pick, breaking ties in *your* list's order — HTTP doesn't rank equal-quality client preferences, so the server's stated preference wins.

The subtlety is wildcards, and `AcceptSpecificity` is the control for it. `curl` sends `Accept: */*`, and browsers send `text/html,...` — under the default `Any`, both "accept HTML", so a bare `acceptsHtml()` sends a curl user an HTML page. Pass `AcceptSpecificity::Loose` and a bare `*/*` no longer counts, so `curl` falls through to JSON while a browser still gets HTML; `Exact` requires a literal `text/html` entry.

```php
use Pop\Http\Server\AcceptSpecificity;

$request->acceptsHtml(AcceptSpecificity::Loose);   // browsers yes, `curl` no
```
The enum is an `int`-backed enum whose value *is* the internal specificity tier (exact = 2, `type/*` = 1, `*/*` = 0), so enforcing a minimum is a plain integer comparison. A missing or empty `Accept` header is treated as `*/*`, per RFC. `AcceptHeader` works stand-alone on any header string, and the underlying `Server\QualityValue::parseList()` is a generic RFC 7231 q-value parser — reusable for `Accept-Language`, `Accept-Encoding` or `Accept-Charset`.
**Previously:** not possible — nothing parsed `Accept`. You read `$request->getHeaderValue('Accept')` and did your own `str_contains()`, which gets q-values and wildcard precedence wrong the moment a real browser sends a real header.

### Smaller additions
- `curl -L` / `--location` (`CURLOPT_FOLLOWLOCATION`) now round-trips through the curl CLI ⇄ `Client` converter; `-F`/`--form` was added to the option table too.
- `HandlerInterface` formally declares `prepare(AbstractRequest $request, ?Auth $auth = null)` — previously duck-typed.
- `AbstractHandler::collectRequestHeaders()` / `resolveRequestBody()` are new shared hooks both `Curl` and `Stream` build on.
- `Curl::getInfo()` widened to `array|string|int|float|bool` so numeric `CURLINFO_*` values aren't lossily coerced.
- `Mock::setVerifyPeer()` / `allowSelfSigned()` — accepted and ignored, so mock is a true drop-in.
- `RetryMiddleware::setSleeper()` and `setShouldRetryException()` — injectable sleep function and custom retryable-exception predicate.
- `Client\Middleware\CallableMiddleware` / `CallableRequestHandler` — public closure adapters for building a pipeline outside `Client`.
- `Promise::wait()` widened to `Response|string|array|null`, so an auto-parsed string response is a legal promise result.

---

## pop-i18n — 4.0.3 → 5.0.0

**Summary:** A small, correctness-driven release: generated XML/JSON language files are now valid and reloadable, placeholder substitution stops breaking past 9 parameters, translation lookup becomes O(1), and four protected hooks open the loaders to subclassing.
**Feature count:** 6

### Generated XML files are escaped and round-trippable
`Format\Xml` runs every attribute and element value through `htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8')`, covering `createFile()` and `createFragment()` alike. A language file containing `&`, `<`, `>` or a quote in a source or output string is now well-formed XML that `I18n::loadFile()` can read back.

```php
// New
Format\Xml::createFile(
    ['src' => 'en', 'output' => 'de'],
    [['region' => 'DE', 'text' => [
        ['source' => 'Tom & Jerry <b>', 'output' => 'Tom & Jerry <b>']
    ]]],
    '/path/to/language/files/de.xml'
);
// -> <source>Tom &amp; Jerry &lt;b&gt;</source>  — parses back fine
```
**Previously:** the raw value was concatenated straight into the markup, producing a file `SimpleXMLElement` refused to parse — **the generator could emit a language file its own loader could not load**.

### JSON fragments are encoded, not concatenated
`Format\Json::createFragment()` emits `json_encode($value)` for both `source` and `output` instead of wrapping the raw line in hand-written quotes.

```php
// New
Format\Json::createFragment('source/en.txt', 'output/de.txt', '/path/to/files/');
// a line containing "quotes", a backslash, or a tab now produces valid JSON
```
**Previously:** any line containing a double quote, backslash or control character produced a syntactically invalid fragment that `json_decode()` rejected.

### Placeholder substitution handles more than 9 parameters
`translate()` builds a `%1 => value` replacement map and applies it in a single `strtr()` call. `strtr()` matches longest-key-first and never rescans its own output, so `%10`–`%99` resolve correctly.

```php
// New
$lang->__('%1 %2 %3 %4 %5 %6 %7 %8 %9 %10', ['a','b','c','d','e','f','g','h','i','j']);
// -> "a b c d e f g h i j"
```
**Previously:** sequential `str_replace('%1', ...)` calls ran first, so `%10` was rewritten to `a0` before the 10th pass ever saw it — effectively a hard 9-parameter ceiling.

### Constant-time translation lookup
`$content` is now a single array keyed by source string (the parallel source/output arrays are gone), so `translate()` resolves via one `isset()` instead of `array_search()` over the whole catalog.

```php
// New
$lang = new I18n('fr_FR', '/path/to/language/files'); // 5,000-entry catalog
echo $lang->__('Hello');  // O(1) hash lookup
```
**Previously:** every `__()`/`_e()` call did a linear search across the source list — cost grew with catalog size and repeated for every string on the page.

### Language files with no matching (or no) locale node no longer fatal
Both loaders scan for the matching locale and leave the key `null` when none is found, guarding the subsequent read. An XML file with zero `<locale>` nodes, or a JSON file with an empty `locale` array, now simply loads nothing.

```php
// New
$lang = new I18n('fr_FR');
$lang->loadFile('/path/to/empty-locales.xml'); // no error; __() passes strings through
```
**Previously:** the code unconditionally indexed the first locale node, which errored on an empty locale set.

### Four protected extension points on `I18n`
`loadFile()` and `getLanguages()` delegate to `loadXmlFile()`, `loadJsonFile()`, `getXmlLanguages()` and `getJsonLanguages()` — all protected, the latter two static. Subclasses can override one format's handling (or add caching, or point at a different schema) without reimplementing the dispatcher.

```php
// New
class MyI18n extends I18n
{
    protected function loadJsonFile(string $langFile): void
    {
        $this->content = $myCache->get($langFile) ?? parent::loadJsonFile($langFile);
    }
}
```
**Previously:** all four bodies were inlined; customizing either meant copying the whole method.

### Smaller additions
- `Format\Json::createFile()` validates that every `text` entry defines both `source` and `output`, throwing rather than writing a broken file — matching the check `Format\Xml` already had.
- `translate()` guards variation selection with `is_array($output)`; in v6 a numeric variation against a plain string output indexed into the string and returned a single character.
- `getLanguages()` compares `stripos(...) !== false` rather than relying on truthiness, so a file whose extension match starts at offset 0 is no longer skipped.
- Locale matching in both loaders breaks on the first match instead of scanning every remaining node.

---

## pop-image — 4.1.3 → 5.0.0

**Summary:** Almost entirely a removal-and-correctness release (the `Captcha` class is gone); the only genuinely new capability is the seven additional color spaces inherited from `pop-color` 2.x, plus three defect fixes that unlock behavior silently broken in v6.
**Feature count:** 4

### Seven new color spaces usable everywhere a color is accepted
The `pop-color` requirement moved from `^1.0.3` to `^2.0.0`, adding `Hsb`, `Hsv`, `Hwb`, `Lab`, `Lch`, `Oklab` and `Oklch` alongside the existing classes. Every `pop-image` entry point typed against `ColorInterface` — `setFillColor()`, `setStrokeColor()`, `rotate()`, `effect->fill()`, `effect->border()`, all four gradient methods, `filter->skew()`, `createColor()` — accepts them, and both adapters normalize them to RGB internally.

```php
// New
use Pop\Color\Color\Oklch;

$img->draw->setFillColor(new Oklch(0.7, 0.15, 145))
    ->circle(200, 200, 75);
```
**Previously:** only Rgb, Hex, Cmyk, Grayscale and Hsl existed; perceptual spaces (Lab/Lch/Oklab/Oklch) and Hsb/Hsv/Hwb had to be converted to RGB by hand first.

### Imagick draws and text are visible without calling `setOpacity()`
`Draw\Imagick::$opacity` and `Type\Imagick::$opacity` defaulted to `1.0`, which the adapter interprets as 1 out of 100 — i.e. ~99% transparent. Both now default to `100`, so an un-configured shape or text string renders fully opaque.

```php
// New — renders a solid red rectangle
$img->draw->setFillColor(new Rgb(255, 0, 0))
    ->rectangle(50, 50, 200, 100);
```
**Previously:** the same code produced a near-invisible shape; you had to add an explicit `->setOpacity(100)` first.

### `Adapter\Gd::destroy()` actually frees the image
The guard was `!is_string($this->resource) && is_resource($this->resource)`. Since PHP 8.0 GD returns a `GdImage` object, not a resource, so `is_resource()` was always false and `imagedestroy()` never ran. The check is now `instanceof \GdImage`, so memory is released — which matters in loops over many images.

```php
// New
$img = Image::loadGd('image.jpg');
$img->writeToFile('out.jpg');
$img->destroy();          // imagedestroy() now actually executes
```
**Previously:** `destroy()` only nulled the property and optionally unlinked the file; the underlying GD image was never explicitly freed.

### Clear exception for color objects that cannot convert to RGB
`ColorInterface` declares only `render()` and `__toString()` — not `toRgb()`. Any custom implementation therefore blew up with a PHP fatal. Both adapters' `createColor()` and the Effect classes' fill/gradient methods now guard with `method_exists($color, 'toRgb')` and throw a catchable namespaced exception instead.

```php
// New
try {
    $img->effect->verticalGradient($myCustomColor, new Rgb(0, 0, 255));
} catch (\Pop\Image\Effect\Exception $e) {
    // 'Error: The color object does not support conversion to RGB.'
}
```
**Previously:** an uncatchable fatal `Error` at the point of the `toRgb()` call.

### Smaller additions
- `Adapter\Imagick::resizeImage()`'s `$blur` parameter widened from `?int` to `?float`, so fractional blur values can be passed — matching the `float` property and Imagick's own signature.
- `Layer\AbstractLayer::setOpacity()` returns `static`, so chaining keeps the concrete `Layer\Gd`/`Layer\Imagick` type.
- `Adjust\Imagick::contrast()` passes real booleans to `contrastImage()` instead of `1`/`0`.
- The README was substantially expanded with per-editing-object GD-vs-Imagick method tables, a "Working with Color" section, an "Image Information" section and an "Error Handling" section documenting the per-namespace exceptions.

> **Note:** `Pop\Image\Captcha` was removed in v7 with no replacement, as was `pop-form`'s matching `captcha` field type. See the BC-breaks document.

---

## pop-kettle — 2.3.4 → 3.0.0

**Summary:** `pop:init` is now a guided interview that scaffolds one content-negotiating HTTP application instead of six web/API permutations, with a front-end build system (AlpineJS, Vue or React, each with Tailwind CSS) installed straight into it. Plus a full `queue:*` command family, project-defined `create:command` commands that can be auto-discovered and dispatched through Kettle or through your own stand-alone CLI application, multi-connection database config, Composer-registered app autoloading, and opt-in stand-alone CLI app scaffolding.
**Feature count:** 16

### Front-end scaffolding at `pop:init` — AlpineJS, Vue or React, each with Tailwind
`pop:init` now offers to install a complete front-end build system alongside your PHP app. The prompt appears on every install except a CLI-only one.

```text
Would you like to install a front-end? [Y/N] y

1: AlpineJS
2: Vue
3: React

Please select a front-end framework from above:
```

Whichever you pick, **Tailwind CSS v4** is scaffolded alongside it and **Vite** is the build tool. Tailwind is wired CSS-first through the `@tailwindcss/vite` plugin, so there is no `tailwind.config.js` to maintain — `app/assets/css/app.css` is a single `@import "tailwindcss";` line. Vue and React each get a working counter component to prove the reactive layer is live.

```text
package.json           # project root, next to the kettle script
vite.config.js         # project root
app/assets/css/app.css
app/assets/js/app.js   # app.jsx for React
app/assets/js/components/App.vue|App.jsx
```

Source assets live under `app/`, next to `app/src` and `app/view`, and build output goes to `public/assets`. Vite is configured (`rollupOptions.output.entryFileNames`/`assetFileNames`) to write **fixed, non-hashed paths** — `public/assets/js/app.js` and `public/assets/css/app.css` — for both a watch build and a production build, so the `<link>`/`<script>` tags in the generated `app/view/index.phtml` are correct from the first build and never change between the two. `node_modules/` is added to `.gitignore` for you.

Init then runs `npm install` and `npm run build` itself, so the landing page is already built and styled the first time you load it. If Node/npm is not on your `PATH`, `pop:init` still completes — you get a warning telling you to install Node and run the two npm commands yourself.
**Previously:** not possible — `app:init` scaffolded PHP only. Front-end tooling was entirely your problem: your own `package.json`, your own bundler config, your own output paths, and your own `<link>`/`<script>` tags to match them.

### The `web:` command group — `web:serve`, `web:watch`, `web:build`
Everything to do with running and building the web side of an app now sits in one namespace, so `./kettle help web` lists the whole workflow in one place. `web:watch` and `web:build` wrap the front-end build, so you do not have to leave `kettle` to rebuild assets, and the v6 `serve` command moved here as `web:serve`.

```bash
./kettle web:serve [--host=] [--port=] [--folder=]   # PHP's built-in server
./kettle web:watch                                   # npm run watch -> vite build --watch
./kettle web:build                                   # npm run build -> one-shot production build
```
`web:watch` rebuilds `public/assets/js/app.js` and `public/assets/css/app.css` to disk on every save. There is no dev server and no hot-module reload — you refresh the browser yourself. Both asset commands print a message and exit cleanly if the project has no front-end installed or if npm is not on your `PATH`, so neither one blows up on a PHP-only project.
**Previously:** `serve` existed but stood alone, and there were no asset commands at all. Note that `serve` has no alias in v7 — see the BC guide.

### `help <command>` narrows the help screen to one namespace
`./kettle help` now takes an optional command namespace and lists only the commands under it, instead of the full screen. The trailing `:` is optional, and `--raw` combines freely with it.

```bash
./kettle help db          # only db:* commands
./kettle help web         # web:serve, web:watch, web:build
./kettle help --raw db:   # same, without ANSI color
```
A namespace that matches nothing prints an empty list rather than erroring. Your own `create:command` commands are matched the same way, so `./kettle help myapp` narrows to the ones you wrote.
**Previously:** not possible — `help` took only `--raw`, and printed every registered command every time.

### `pop:env --set` changes the application environment
`pop:env` gained a `--set` flag that rewrites `APP_ENV` in `.env` for you, picked from the same numbered list `app:init` used to ask at scaffold time. Because it is a menu, only the five valid values can be written — a typo just re-prompts instead of leaving an unusable environment in `.env`.

```bash
./kettle pop:env          # show the current environment
./kettle pop:env --set    # 1: local  2: dev  3: testing  4: staging  5: production
```
Either way you get the same color-coded alert box back, so setting the environment confirms itself. If there is no `.env` in the current folder, `--set` says so and exits rather than creating one.
**Previously:** the environment was asked once during `app:init` and never again — changing it afterward meant hand-editing `APP_ENV` in `.env`, with nothing validating what you typed. Init no longer asks at all; every new app starts at `local`.

### Queue command family (`queue:*`)
A complete `pop-queue` front end: configure a queue's adapter (File, Database or Redis) interactively, run the worker or scheduler as a daemon or a single cron-friendly pass, inspect pending/dead-letter jobs and scheduled tasks, and clear them.

```bash
./kettle queue:config [<queue>]                              # writes .env QUEUE_* + app/config/queue.php
./kettle queue:work [-o|--once] [-s|--sleep=] [<queue>]      # daemon, or one pass with --once
./kettle queue:scheduler [-o|--once] [-s|--sleep=] [<queue>]
./kettle queue:clear [-f|--failed] [-t|--tasks] [<queue>]
./kettle queue:jobs [<queue>]                                # pending/dead counts + failure reasons
./kettle queue:tasks [<queue>]                               # cron expression + grace period
```
Pass `all` as `<queue>` to service every configured queue in one worker, weighted by each queue's configured `weight`; any other name (`queue:config reports`) configures an additional queue under `QUEUE_REPORTS_*`.
**Previously:** not possible — `pop-queue` was not even a dependency, and there were no queue commands or config scaffolding.

### `create:command` — project-owned Kettle commands
Scaffolds a single-action `Pop\Console\Command\AbstractCommand` subclass. Without flags it lands in `app/src/Console/Command/Kettle/`, whose classes Kettle merges into its own route table on every run, so the command is immediately runnable and appears in `./kettle help`.

```bash
./kettle create:command send-email     # -> app/src/Console/Command/Kettle/SendEmail.php
./kettle create:command email:send     # -> Send.php, registered as 'email:send'
./kettle send-email                    # run it
```
The generated class exposes `public ?string $name/$params/$help` (add router syntax like `'<to> [--cc=]'` to `$params`) and a `handle()` method with `$this->application` and `$this->console` available. `-a|--app` puts it in `app/src/Console/Command/` for the stand-alone script instead.
**Previously:** not possible — no command scaffolding and no per-class command concept; extra Kettle routes had to be hand-written into `kettle.inc.php`.

### Command auto-discovery via `CommandRegistry::loadRoutes()`
The `kettle` script and the scaffolded stand-alone script both merge built-in routes with whatever command classes are found on disk, so new commands need zero wiring.

```php
// kettle
$config['routes'] = Pop\Console\CommandRegistry::loadRoutes(
    $config['routes'], __DIR__ . '/app/src/Console/Command/Kettle'
);
```
**Previously:** not possible — route tables were static arrays in the config file.

### `Pop\Kettle\Application` with `prepare()` / `load()` hand-off
Kettle is now an application, not a module. `prepare()` matches the route and, when the dispatchable is *not* a `Pop\Kettle\*` class, resolves and returns the consuming project's own `Application` instance to run instead — so a custom command boots through `kettle` but executes inside your app, with your services, config and DB connections.

```php
// kettle
$app = new Pop\Kettle\Application($autoloader, $config);
$app->prepare()   // may return YOUR MyApp\Application
    ->load()      // db init, header, maintenance/production events
    ->run();
```
Also, new: `Application::NAME`/`FULL_NAME`/`VERSION` constants, `getConsole()` for the shared 120-column console, and controllers using `Pop\Dispatch\ConsoleTrait`.
**Previously:** `Pop\Kettle\Module` registered against a generic `Pop\Application`; there was no way to hand a command off to the project's own application — Kettle was explicitly "unaware of your application."

### Multiple named database connections
`db:config <name>` for a non-`default` name now appends `DB_<NAME>_*` variables to `.env` and a matching named block to `app/config/database.php`, instead of overwriting the single default connection. `Application::initDb()` then connects every configured entry and registers each as a service (`database`, `database_<name>`).

```bash
./kettle db:config default
./kettle db:config reports    # adds DB_REPORTS_* + 'reports' => [...] block
```
**Previously:** a second `db:config <name>` clobbered the single `DB_*` block; only `database['default']` was ever connected.

### Opt-in stand-alone CLI application, offered on every install
A second console application is never forced on you, and it is no longer gated behind picking a "CLI" install type — every app is asked, CLI-only or not.

```text
Initialize a stand-alone CLI application? [Y/N]
```
Accept and you get `script/<slug>` (chmod 755) plus `app/src/Console/Controller`; decline and neither is created. Either way you can still register one-off commands with `create:command`, since `app/src/Console/Command/` is scaffolded for every install — the stand-alone script is for when you want a *separate* CLI application with its own namespaced command groups. If you declined, `create:ctrl --cli` fails explicitly rather than scaffolding into a directory that doesn't exist.
**Previously:** every `--cli` install unconditionally copied `script/myapp` and the console controller folder; no prompt, no choice — and a web-only install could not have a stand-alone CLI app at all.

### `pop:init` registers your namespace in `composer.json` — the include file is gone
Autoloading is Composer's job now. `pop:init` adds your namespace to `composer.json`'s `autoload.psr-4` map and runs `composer dump-autoload`, so `kettle`, `public/index.php` and a stand-alone `script/<app>` all resolve your classes from the one generated autoloader. The `addPsr4()` calls are gone from the scaffolded scripts, and `kettle.inc.php` is deleted outright — there is no second place to keep in sync.

```json
"autoload": { "psr-4": { "App\\": "app/src/" } }
```
The entry is only added when it isn't already there, so re-running `pop:init` won't duplicate it. If Composer isn't on your `PATH`, init still completes and warns you to run `composer dump-autoload` yourself. The other half of what `kettle.inc.php` was used for — extra routes — is covered by `create:command` classes, which are auto-discovered.
**Previously:** the README walked you through creating `kettle.inc.php` and adding the `addPsr4()` line by hand, and it was required for table-backed migrations to resolve at all.

### `pop:init` is a guided interview — no flags, no arguments
`pop:init` takes nothing on the command line. Every decision it used to read from flags and arguments is now a prompt, which means the command is self-documenting: run it, and it tells you what it needs, instead of you reading the README to find out.

```text
What is the namespace of your app? [App]
What is the name of your app? [App]
Is this a CLI-only application? [Y/N]
What is the URL of your app? [http://localhost]
Initialize a stand-alone CLI application? [Y/N]
Would you like to configure a database? [Y/N]
Would you like to install a front-end? [Y/N]
```
Seven questions at most, and a CLI-only answer skips three of them — no URL, no front-end, no framework choice. Answering "N" to the database prompt genuinely skips it: no `database/` folder, no `app/config/database.php`, and the `'database' => include ...` line is stripped from the app configs.
**Previously:** `<namespace>` was a required argument, install flavor came from `--web`/`--api`/`--cli`, the URL was always asked, the environment was asked and never asked again, and the database config file was copied in regardless.

### One `Http\Controller` serves HTML and JSON — the web/API split is gone
The biggest thing `pop:init` stopped asking is "web, API, or both?", because there is nothing to choose between any more. A non-CLI install scaffolds a single `App\Http\Controller` namespace whose actions decide their own response format from the request's `Accept` header, using the new `pop-http` negotiation methods.

```php
public function index(): void
{
    if ($this->request->acceptsHtml(AcceptSpecificity::Loose)) {
        $this->prepareView('index.phtml');
        $this->view->title = 'Welcome';
        $this->send();
    } else {
        $this->sendJson(200, ['message' => 'Index page']);
    }
}
```
`AbstractController` carries both halves — `send()` renders the view as HTML, `sendJson()` encodes and sets the JSON headers — so `error()` and `maintenance()` negotiate the same way, and an API client gets a JSON 404 where a browser gets the error page. `/` and `/api` both route here; the URL is a convention for your clients, not a switch. `AcceptSpecificity::Loose` is what keeps `curl`'s bare `Accept: */*` on the JSON side while a browser still gets HTML.

The payoff is the app footprint. Six scaffolding trees (`web`, `api`, `web-api`, `web-cli`, `api-cli`, `web-api-cli`) collapsed to two — `full` and `cli` — and a full install ships one `AbstractController`/`IndexController` pair instead of the duplicated `Http\Web\*` + `Http\Api\*` sets that had to be kept in sync across six trees.
**Previously:** web and API were separate namespaces with near-identical, separately-maintained controllers, chosen once at install time by flag. Wanting both later meant re-running init or copying a controller tree by hand; serving both formats from one route was not something the scaffolding did.

### The `pop:*` namespace hands `app:*` to your application
Kettle's own application commands moved to `pop:init`, `pop:env`, `pop:status`, `pop:down` and `pop:up`, and the scaffolded app's default namespace became `App`. That is one change, not two: vacating `app:` is what makes `App` a safe default, because the prefix your own `create:command` commands would naturally want is now free.

```bash
./kettle create:command app:send-email    # yours
./kettle app:send-email                   # run it
./kettle pop:status                       # Kettle's own
./kettle help app                         # just your commands
```
The result is a help screen where the `pop:` group is unambiguously Kettle's plumbing and everything under `app:` is yours — which `help <namespace>` can now filter on.
**Previously:** `app:*` was Kettle's, and the default namespace was `MyApp`. See the BC guide: the rename has no aliases.

### Namespaces are normalized however you type them
Whatever you enter at the namespace prompt is parsed into three forms: a valid PHP namespace, a kebab-case slug, and a human-readable display name. Segments are split on `\`, `/`, hyphens, underscores and camelCase boundaries, then re-cased.

```text
typed in          namespace      slug           display name
my-user-app   ->  MyUserApp      my-user-app    My User App
MyApp         ->  MyApp          my-app         My App
"My\Users\App"  ->  My\Users\App   my-users-app   My Users App
```
The namespace form is what lands in `composer.json` and your class files, the slug names the stand-alone CLI script (`script/my-users-app`), and the display name becomes the default app name and the generated `Application::FULL_NAME`. A namespace that can't yield a valid segment throws rather than scaffolding something broken. Accept every default and you get `App` / `app` / `App`.
**Previously:** the namespace was used as typed, and the script name was a flat `strtolower(str_replace('\\', '-', ...))` — so `MyApp` produced `script/myapp` and a `FULL_NAME` of `MyApp`.

### `composer create-project` offers to initialize the app for you
The `popphp/framework` skeleton registers `Pop\Kettle\Application::check` as a `post-autoload-dump` script, so installing the framework ends by asking whether to scaffold:

```text
composer create-project popphp/framework my-project

Do you want to initialize your application now? [Y/N]
```
Answer `y` and `pop:init` runs immediately, in the folder Composer just created — one command from nothing to a running application. The check is guarded on there being no `.env` yet, so it stays quiet on every later `composer install` or `composer update` in an already-initialized project. That guard is also why init's own `composer dump-autoload` doesn't re-trigger it: `.env` is written before the dump runs.
**Previously:** `create-project` left you at a bare skeleton, and you had to know to copy the `kettle` script and run `app:init` yourself as a separate documented step.

### Smaller additions
- New PHP API: `Model\Queue` — `configure()`, `buildWorker()`, `clear()`, `jobsSummary()`, `tasksSummary()`; `Model\Application::createCommand()`, `resolveAppInstance()` and `parseNamespace()`.
- `queue:jobs`, `queue:tasks`, `queue:work` and `queue:scheduler` are exempt from the "Application in Production" confirmation prompt.
- `Event\Console::maintenanceDisplay()`/`productionDisplay()` accept an injected `Console`.
- An unrecognized command now prints a `Try './kettle help' for help` hint; scaffolded CLI apps print the same line using their own script name.
- `pop:init` creates a writable `data/` folder and `.empty` placeholders under `database/`.
- Scaffolded app `Application` classes gain `NAME`/`FULL_NAME`/`VERSION` constants and multi-connection `initDb()`, and emit an `Application` class where v6 emitted a `Module`. The generated views ship a dark/light-mode landing page.
- The `.env` template ships `QUEUE_ADAPTER`/`QUEUE_PRIORITY`/`QUEUE_LEASE`; queue/db config reloads `$_ENV` via Dotenv so a chained `db:install` sees freshly written values.
- `db:reset`/`db:clear` handle SQLite (row deletes in place of unsupported `TRUNCATE`).

---

## pop-log — 4.0.4 → 5.0.0

**Summary:** A true PSR-3 logger with placeholder interpolation, context processors, pluggable formatters (including JSON Lines), and two new writers — a real RFC-3164 UDP syslog writer and a stream/stdout writer.
**Feature count:** 10

### PSR-3 conformance
`Logger` implements `Psr\Log\LoggerInterface`, so it can be handed to any framework or library that type-hints a PSR-3 logger. Level constants are now PSR-3 level strings (`Logger::ERROR === 'error'`), messages accept `string|Stringable`, and an invalid level throws `Psr\Log\InvalidArgumentException`.

```php
// New
class Logger implements LoggerInterface
{
    const ERROR = LogLevel::ERROR;

    public function log(mixed $level, string|Stringable $message, array $context = []): void
}

someThirdPartyLib(new Logger(new File(__DIR__ . '/logs/app.log')));
```
**Previously:** not possible — `Logger` implemented no interface, levels were ints, and `log()`/`info()` returned `Logger`. Passing it to PSR-3-typed code required a hand-written adapter.

### `{placeholder}` message interpolation
Messages can embed `{key}` placeholders substituted from scalar/`Stringable` context values, per the PSR-3 spec. A consumed key is removed from the context so writers don't also duplicate it in the serialized blob; the reserved keys `timestamp`, `name` and `format` are never treated as placeholders.

```php
// New
$log->info('User {user} logged in from {ip}', ['user' => 'nick', 'ip' => '1.2.3.4']);
// 2026-08-09 12:32:32  info  INFO  User nick logged in from 1.2.3.4
```
**Previously:** not possible — the message was passed through verbatim; you `sprintf()`'d values in yourself, and context values were only ever appended as a separate `key=value;` blob.

### Context-enrichment processors
Callables with the signature `callable(array $context): array` enrich every entry's context before it is written — request IDs, user IDs, a default serialization format. They run in registration order, each seeing the previous one's output, and run *before* interpolation so an injected value is immediately usable as a `{placeholder}`. A throwing processor aborts the `log()` call before any writer runs.

```php
// New
$log->addProcessor(function (array $context): array {
    $context['request_id'] = bin2hex(random_bytes(8));
    return $context;
});
$log->info('Handling request {request_id}');
```
**Previously:** not possible — no hook between `log()` and the writers; every call site passed the same context keys by hand.

### Pluggable `Formatter\*` classes
Output shape is a first-class, swappable concern via `FormatterInterface` with four shipped implementations — `Line` (tab-separated), `Csv`, `Tsv` and `NdJson`. `File` auto-selects from the file extension, and you can override it (or supply your own) via the writer's second constructor argument.

```php
// New
interface FormatterInterface {
    public function format(string $level, string $message, array $context): string;
}

$log = new Logger(new File(__DIR__ . '/logs/app.log', new Formatter\NdJson()));
```
**Previously:** not possible — `Writer\File::writeLog()` was one hard-coded `switch` with the line-building inlined. Changing the format meant subclassing `File` and reimplementing `writeLog()`.

### JSON Lines (NDJSON) output
`Formatter\NdJson` emits one self-contained JSON object per line with `timestamp`, `level`, `name`, `message` and a nested `context` object — the format log aggregators expect. `File` auto-selects it for `.jsonl` and `.ndjson`, and it is the default for the new `Stream` writer. Encoding uses `JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR` so a bad value can't kill the entry.

```php
// New
$log = new Logger(new File(__DIR__ . '/logs/app.jsonl'));
$log->info('Just a info message');
// {"timestamp":"...","level":"info","name":"INFO","message":"Just a info message","context":{}}
```
**Previously:** not possible. The only JSON option was `.json`, which read the whole file, decoded it, appended one entry and rewrote everything pretty-printed — unusable for tailing and O(n) per write.

### `Writer\Stream` with `stdout()` / `stderr()` factories
Sends entries to any writable PHP stream — `STDOUT`/`STDERR`, an `fopen()` resource, or a stream URL string. Built for 12-factor/containerized deployments and CLI scripts. Ownership is tracked: a stream the writer opened from a string is closed in its destructor, a caller-supplied resource never is.

```php
// New
$log = new Logger(Stream::stdout());              // NdJson by default
$log = new Logger(new Stream(STDERR, new Formatter\Line()));
```
**Previously:** not possible — the four writers were File, Mail, Database and Http. Logging to stdout meant pointing `File` at `php://stdout`, which its append path handled poorly.

### `Writer\Syslog` — real RFC-3164 packets over UDP
Builds an actual `<PRI>HEADER TAG: MSG` syslog packet and sends it over UDP to rsyslog, syslog-ng, Papertrail or any network collector. `PRI` is computed as `facility * 8 + severity`, the HEADER timestamp uses the RFC's space-padded shape, TAG/HOSTNAME are stripped of whitespace and control chars, and packets are truncated to the 1024-byte limit.

```php
// New
$log = new Logger(new Syslog(Facility::LOCAL0, 'logs.mydomain.com', 514, 'my-app'));
$log->alert('Look Out! Something serious happened!');
```
**Previously:** not possible. The library only *followed* the RFC-3164 severity model; nothing emitted a syslog packet or spoke to a daemon.

### `Facility` enum
A backed enum covering all 24 RFC-3164 facilities (`KERNEL`, `USER`, `MAIL`, `DAEMON`, `AUTH`, `CRON`, `LOCAL0`–`LOCAL7`, …), giving the syslog writer type-safe facility selection instead of magic ints.

```php
// New
enum Facility: int { case KERNEL = 0; /* ... */ case LOCAL0 = 16; /* ... */ }
new Syslog(Facility::LOCAL4);
```
**Previously:** not possible — no facility concept existed.

### `Level` helper class
A final utility normalizing between PSR-3 level strings and RFC severity ints in both directions, and the single validation point for levels across `Logger` and every writer.

```php
// New
Level::toSeverity('error');   // 3   (also accepts 3 and '3')
Level::fromSeverity(3);       // 'error'
Level::toName('error');       // 'ERROR'
```
**Previously:** not possible as a reusable API — the mapping was a `protected array $levels` inside `Logger` (int => display name only), with no string-to-int direction.

### `Context` utility class
A public final class exposing the two operations writers and custom formatters need: `serialize()` turns a context array into text/JSON/serialized output (honoring `$context['format']`, stripping reserved keys), and `sanitize()` strips CR/LF to prevent an embedded newline forging a fake log line or mail header.

```php
// New
Context::serialize(['foo' => 'bar', 'format' => 'json']); // {"foo":"bar"}
Context::sanitize("line1\nline2");                        // "line1 line2"
```
**Previously:** partially — serialization existed only as `AbstractWriter::getContext()`, reachable only with a writer instance; there was no sanitization at all.

### Smaller additions
- **Per-writer exception isolation:** `log()` wraps each `writeLog()` in try/catch, so one failing writer no longer prevents the rest; the first exception is re-thrown after every writer has had its turn.
- Level limits accept level strings — `setLogLimit(Logger::ERROR)` instead of `setLogLimit(3)`; legacy ints still work.
- `getLevel()`/`getLogLevel()` accept `string|int`, resolving a display name from either.
- `Writer\File::getFormatter()` exposes the resolved format handler.
- Concurrency-safe `.xml`/`.json` writes: the read-modify-write cycle runs under `flock(LOCK_EX)`, instead of the unlocked pair that could silently drop a concurrent writer's entry.
- Log-injection hardening in `Writer\Mail`: subject and body fields are sanitized, so a newline in a message can no longer forge an extra mail header.
- `AbstractWriter::sanitize()` is available as a protected helper to any custom writer.

---

## pop-mail — 4.0.7 → 5.0.0

**Summary:** `Message` is rebuilt on top of `Pop\Mime\Message`, adding a batch-send transport contract, reusable pre-rendered bodies, RFC-compliant address handling, injectable HTTP handlers, and traits for building custom message parts.
**Feature count:** 10

### Batch-sending transports (`BatchTransportInterface`)
A new optional companion to `TransportInterface` for transports that can push many messages through a provider's native bulk endpoint. `Mailer::sendFromQueue()` and `sendFromDir()` funnel through a private `dispatch()` that auto-detects it, so a batch-capable transport is used with no caller change.

```php
// New
class MyBulkTransport extends AbstractTransport implements BatchTransportInterface
{
    public function sendBatch(array $messages): int  // Message[] -> count sent
    {
        return $this->api->bulkSend($messages);
    }
}

$mailer->sendFromQueue($queue);   // one bulk call instead of N sends
```
**Previously:** not possible — those methods hard-looped `$this->transport->send($message)` one message at a time, and there was no interface to advertise bulk capability.

### Pre-rendered body reuse across repeated sends
`render()`, `renderAsLines()`, `toByteStream()` and `AbstractSmtp::streamMessage()` all accept an optional already-rendered `$body`. `sendBcc()` uses it: the body is rendered once before the BCC loop, so **attachments are base64-encoded once instead of once per BCC recipient**.

```php
// New
$body = $message->getBodyContent();      // encode attachments once
foreach ($recipients as $to) {
    $message->setBcc($to);
    $message->toByteStream($buffer, $body);
}
```
**Previously:** not possible — `render()`/`toByteStream()` took only `array $omitHeaders`, and every BCC iteration re-ran `getBody()`, re-chunking and re-base64-encoding every attachment.

### `Message` is now a real `Pop\Mime\Message`
`Message extends Pop\Mime\Message`, so `addPart()`/`setBody()` accept **any** `Pop\Mime\Part` — including nested multipart parts, inline/`cid:` parts and custom content types — and the whole `pop-mime` API is available on a mail message. `addPart()` also auto-infers the multipart subtype.

```php
// New
$related = new Part();
$related->setSubType('related');
$related->addPart(Html::create('<img src="cid:logo">'));
$related->addPart(Attachment::create('/logo.png', 'image/png', 'inline'));

$message->addPart($related);   // any Pop\Mime\Part is a valid message part
```
**Previously:** not possible — `addPart(PartInterface $part)` only accepted `pop-mail`'s own part classes, and the subtype logic could only pick between `multipart/alternative` and `multipart/mixed`. Nested multipart and `multipart/related` were unreachable.

### String accessors: `getBodyContent()` / `getHeaderValue()`
Because the base class's `getHeader()` returns a `Part\Header` object and `getBody()` a `Part\Body`, v5 adds flat-string companions. The upshot: you now get *both* the structured object (multiple values, parameters, encoded words) and the plain string.

```php
// New
$message->getHeaderValue('To');           // ?string  "Some Name <you@domain.com>"
$message->getHeader('To');                // ?Pop\Mime\Part\Header — values, params
$message->getBodyContent();               // ?string  fully rendered body
$message->getBody();                      // Pop\Mime\Part\Body — encoding, isFile()
```
**Previously:** `getHeader()`/`getBody()` returned strings and nothing else — the parsed header object was not reachable from a `Message` at all.

### RFC-compliant address parsing via `AddressList`
Every address setter normalizes its input — string, `email => name` array, plain list, or `stdClass{mailbox,host}` — through `AddressList::parse()`, then renders the header from the parsed list. Display names containing commas, quoting and encoded words survive round-tripping.

```php
// New
$message->setTo('"Doe, John" <john@domain.com>, jane@domain.com');
$message->getTo();  // ['john@domain.com' => 'Doe, John', 'jane@domain.com' => null]
```
**Previously:** arrays worked, but a string was stored verbatim and split on every raw comma — the example above yielded two garbage entries.

### Injectable HTTP handler on the API clients
`Api\AbstractHttpClient` gained `setHandler()`/`getHandler()`/`hasHandler()`, and `AbstractOffice365::requestToken()` passes it into the OAuth token client it builds internally. That internal client was never exposed, so its transport was previously impossible to intercept.

```php
// New
$office365->setHandler(new Pop\Http\Client\Handler\Mock($cannedTokenResponse));
$office365->requestToken();
```
**Previously:** not possible — `requestToken()` constructed its own client with no seam.

### `CharsetAwareTrait` and `PartContentTrait` for custom parts
Two new traits port the conveniences that used to live on the deleted `AbstractMessage`/`AbstractPart` onto any class extending `Pop\Mime\Part`: `setContentType()`/`getCharSet()`/`setCharSet()` and `getContent()`/`setContent()`/`renderAsLines()`.

```php
// New
class Ical extends Pop\Mime\Part
{
    use Pop\Mail\Message\CharsetAwareTrait;
    use Pop\Mail\Message\PartContentTrait;
}

$part = new Ical();
$part->setContentType('text/calendar')->setCharSet('utf-8');
$part->setContent($icsData);
$message->addPart($part);
```
**Previously:** you had to extend `AbstractPart`, whose constructor fixed content/content-type/encoding up front and which was not interoperable with `Pop\Mime\Part`.

### Inline attachments and explicit attachment options
`Attachment::create()`/`createFromContent()` expose `$contentType`, `$disposition`, a typed `Encoding` enum and a `$split` chunking control as first-class parameters.

```php
// New
Attachment::create('/logo.png', 'image/png', 'inline');
Attachment::createFromContent($pdfBytes, 'invoice.pdf', 'application/pdf');
```
**Previously:** inline disposition was **not possible** — the constructor unconditionally wrote `Content-Disposition: attachment`. Options came as a loose array with string encoding constants.

### RFC 2047 decoding without ext-imap
`Message::decodeText()` delegates to `Pop\Mime\Part\Header\EncodedWord::decode()`.

```php
// New
Message::decodeText('=?UTF-8?B?SGVsbG8gV29ybGQ=?=');   // no IMAP extension needed
```
**Previously:** it called `imap_mime_header_decode()`, so decoding a MIME-encoded subject fatally failed on any build without ext-imap.

### O(1) queue de-duplication
`Queue` keeps parallel hash indexes, so `addRecipient()`/`addMessage()` dedupe in constant time.

```php
// New
foreach ($tenThousandRecipients as $r) {
    $queue->addRecipient($r);   // constant-time dedup, not a linear rescan
}
```
**Previously:** both used `in_array($x, ..., true)`, making queue building O(n²).

### Smaller additions
- `Queue::prepare()` substitutes `[{key}]` placeholders in the **subject** unconditionally; in v6 the substitution lived inside the parts loop, so attachment-only messages never got their subject templated.
- `Message::getBoundary()` auto-generates the boundary *and* emits `MIME-Version: 1.0` on demand, so both actually reach the wire.
- `getHeadersAsString()` appends `charset="..."` to `Content-Type` when a charset is set and none is present, and skips empty-valued headers.
- `Attachment::getFilename()` returns the real on-disk source path (what `curl_file_create()` needs) while `getBasename()` returns the header-decoded basename.
- `Client\Google` gained a protected `gmailService(): Gmail` factory seam, so the Gmail service can be substituted in tests.
- `EsmtpTransport` documents its runtime `__call()`-mixed-in handler methods as `@method` annotations for IDE completion and static analysis.

---

## pop-mime — 2.0.3 → 3.0.0

**Summary:** A real RFC 5322/2047 header layer (tokenizer, address parsing, encoded-words with no ext-imap, structural folding) plus part factories, an `Encoding` enum, ID generation, and lazy/streamed attachment bodies.
**Feature count:** 10

### Part factories (`Part::text()`, `html()`, `attachment()`, `attachmentFromContent()`)
Static constructors that build a complete part — `Content-Type` header, `Content-Disposition`, and body — in one call. `attachment()` auto-detects the content type from the file extension, and `attachmentFromContent()` does the same for in-memory bytes with no file on disk.

```php
// New
$message->addParts([
    Part::html('<h1>Hello</h1>'),
    Part::text('Hello'),
    Part::attachment('/path/test.pdf'),                    // type auto-detected
    Part::attachmentFromContent($pdfBytes, 'invoice.pdf'), // no file needed
]);
```
**Previously:** three manual steps per part, with the content type always hand-written; and there was **no way at all** to create a file-flagged attachment part from in-memory content — `setContentFromFile()` required a real path.

### `Address` / `AddressList` — RFC 5322 address parsing
New value objects with `parse()`/`render()`. Splitting is token-driven, so a comma inside a quoted display name never splits the list, and group-shaped input is detected and kept opaque rather than mis-split.

```php
// New
$list = AddressList::parse('"Doe, John" <john@doe.com>, Jane Doe <jane@doe.com>');
$list->count();                          // 2
$list->getAddresses()[0]->getName();     // "Doe, John"
echo $list->render();

$list = new AddressList([new Address('john@doe.com', 'Doe, John')]);
```
**Previously:** not possible — no address concept existed anywhere; callers `explode(',', ...)`'d themselves and got the quoted-comma case wrong.

### `EncodedWord` — RFC 2047 encode/decode with no ext-imap
Handles both B and Q encoded-words, converts non-UTF-8 charsets via mbstring when available, joins adjacent encoded-words per §6.2, and splits long input into ≤75-char words on UTF-8 character boundaries.

```php
// New
echo EncodedWord::encode('José García');                       // =?UTF-8?B?Sm9zw6kgR2FyY8OtYQ==?=
echo EncodedWord::decode('=?UTF-8?B?Sm9zw6kgR2FyY8OtYQ==?='); // José García
```
**Previously:** decode-only, only for attachment filenames, gated behind `function_exists('imap_mime_header_decode')` — no ext-imap meant no decoding at all. Encoding did not exist in any form.

### Automatic non-ASCII header encoding on render
`Value::render()` now takes the header name and encodes accordingly: address headers are routed through `AddressList::parse()->render()` so each display name is encoded independently and the addr-spec is never touched; every other header value goes through `EncodedWord::encode()`. Plain ASCII passes through untouched.

```php
// New
$message->addHeader('To', 'José García <jose@example.com>');
// renders: To: =?UTF-8?B?Sm9zw6kgR2FyY8OtYQ==?= <jose@example.com>
```
**Previously:** not possible — values were emitted verbatim, so UTF-8 display names went out raw and non-conformant.

### `Lexer` / `Token` — reusable RFC 5322 tokenizer
Scans a header into `ATOM`, `QUOTED_STRING`, `DELIMITER` and `FWS` tokens with byte offsets, unescaping quoted-pairs and skipping nesting-aware comments. Two statics make it usable standalone: `unfold()` collapses obs-fold sequences, and `findTopLevelDelimiter()` locates a delimiter that is not inside a quoted string or comment.

```php
// New
$tokens = (new Lexer('Content-Type: text/plain; name="a;b"'))->tokenize();
$colon  = Lexer::findTopLevelDelimiter($line, ':');
$flat   = Lexer::unfold("Content-Disposition: form-data;\r\n\tname=x");
```
**Previously:** not possible — all parsing was `strpos`/`preg_match_all` string surgery that broke on any delimiter inside quotes.

### Structurally-correct header folding
`Header::render()` folds via a token-aware `fold()` instead of `wordwrap()`. Fold points are only ever at FWS or after a delimiter — never inside an atom, quoted string, encoded-word, or `<angle-addr>`, all treated as atomic unbreakable spans.

```php
// New
$header->setWrap(76)->setIndent("\t");
echo $header;   // folds only at safe boundaries; unfolds back to the identical value
```
**Previously:** `wordwrap()`, which happily split mid-address and mid-encoded-word, corrupting the value.

### Message-ID and Content-ID generation
`Part::generateId()` builds a unique `<hash@domain>`. `Message::setMessageId()` and `Part::setContentId()` generate and set the header in one call, or accept an explicit ID — living on `Part` so a nested inline-image part can carry its own `cid:` target.

```php
// New
$message->setMessageId();                    // Message-ID: <b38b…d2@localhost>
$inline = Part::attachment('logo.png');
$inline->setContentId(null, 'example.com');  // Content-ID
```
**Previously:** not possible — no ID generation or header helper existed.

### `Body\Encoding` backed enum
Replaces the old string constants and adds the identity transfer encodings `BINARY`, `_7BIT`, `_8BIT`. `toHeaderValue()` maps a case to its wire token (returning `null` for the HTTP-form-only `URL`/`RAW_URL`), and `fromHeaderValue()` maps a parsed header back case-insensitively.

```php
// New
use Pop\Mime\Part\Body\Encoding;

$body = new Body('Hello World!', Encoding::QUOTED_PRINTABLE);
$part = Part::attachment('test.pdf', null, 'attachment', Encoding::BINARY);
Encoding::fromHeaderValue('QUOTED-PRINTABLE');  // Encoding::QUOTED_PRINTABLE
```
**Previously:** four untyped string constants validated by a fallthrough `switch` that silently ignored anything else; `binary`/`7bit`/`8bit` were unsupported, and both directions of the header mapping were hand-coded chains.

### Lazy and streamed attachment bodies
`setContentFromFile()` records the path instead of slurping it, and materializes only when the content is actually read or rendered. For the common base64 attachment case, rendering streams through `php://filter/convert.base64-encode` rather than holding raw and encoded copies in memory at once.

```php
// New
$body = new Part\Body();
$body->setContentFromFile('/path/huge.pdf', Encoding::BASE64);  // no I/O yet
echo $body->render();                                            // streamed base64
```
**Previously:** `setContentFromFile()` did `file_get_contents()` immediately, then `render()` built a second full base64 copy in memory.

### Folded / continuation header parsing
`Message::parseHeaders()` unfolds obs-fold continuation lines before splitting, and finds each header's name using a top-level colon, so a colon inside a quoted parameter no longer starts a bogus header.

```php
// New
$headers = Message::parseHeaders("Content-Disposition: form-data; name=image;\r\n\tfilename=\"a:b.jpg\"\r\n");
// one header, filename parameter intact
```
**Previously:** `preg_match_all('/[a-zA-Z-]+:/', ...)` over the whole blob — a folded value was silently truncated and any `word:` inside a value was mistaken for a new header.

### Smaller additions
- `Part::inferSubType()` — sets the multipart subtype from the nested parts (`mixed` if any part is a file, else `alternative` if both text and HTML are present).
- `Value::getDecodedValue()` — the value with any encoded-words decoded.
- `Body::isBinaryEncoding()`, `is7BitEncoding()`, `is8BitEncoding()` predicates.
- Parameter rendering quotes on any of `\s ; , = " \` and escapes embedded quotes/backslashes, so a filename containing a quote or semicolon survives a render/parse round trip.
- `Header::getValueIndex()` returns `int|bool` (v6 declared `bool` while returning the index, making the position unusable under strict types).

---

## pop-nav — 4.1.5 → 5.0.0

**Summary:** Active-link detection becomes controllable and actually correct, the render cache invalidates on tree mutation, and node labels are escaped against XSS.
**Feature count:** 4

### Explicit current URL — `setCurrentUrl()` / `getCurrentUrl()` and the `currentUrl` config key
The `on`/`off` active-link class is decided by comparing each rendered `href` against the current URL. v7 lets you supply that URL directly, either via the setter or a `currentUrl` config key; it takes precedence over `$_SERVER['REQUEST_URI']`, which is now only the fallback. This makes active-link rendering work from CLI, queue workers and unit tests, and lets you highlight a link that differs from the literal request URI.

```php
// New
$nav = new Nav($tree, ['on' => 'link-on', 'off' => 'link-off', 'currentUrl' => '/pages']);
echo $nav; // /pages gets class="link-on" with no $_SERVER involved
```
**Previously:** not possible through the API — `NavBuilder` read `$_SERVER['REQUEST_URI']` directly, so the only workaround was to fake the superglobal before rendering.

### Render-cache invalidation on tree changes
`Nav` caches the built `Pop\Dom\Child` and reuses it on every render. `setTree()`, `addBranch()` and `addLeaf()` each reset that cache, so tree changes made after a first render are reflected on the next one.

```php
// New
echo $nav;                                    // renders
$nav->addBranch(['name' => 'Orders', 'href' => '/orders']);
echo $nav;                                    // 'Orders' is there
```
**Previously:** the second render returned the stale cached markup; you had to remember to call `rebuild()` after every mutation. (`rebuild()` still exists in v7 for forcing one.)

### XSS-safe escaping of node labels
Node `name` values pass through `htmlspecialchars($name, ENT_QUOTES)` when the `<a>` child is created, so labels coming from a database, CMS or user input can no longer inject markup into the rendered nav.

```php
// New
$nav = new Nav([['name' => 'Tom & <b>Jerry</b>', 'href' => '/tj']]);
echo $nav; // <a href="/tj">Tom &amp; &lt;b&gt;Jerry&lt;/b&gt;</a>
```
**Previously:** the raw `name` was injected into the anchor verbatim — any HTML or `<script>` in a label was rendered as live markup.

### Active-link comparison now works for URLs with query strings
When the current URL carries a query string, v7 strips it correctly before comparing against each link's `href`, so a page reached at `/pages?tab=2` still highlights the `/pages` link.

```php
// New
$nav->setCurrentUrl('/pages?tab=2');
echo $nav; // <a href="/pages" class="link-on">
```
**Previously:** broken — the code did `substr($url, strpos($url, '?'))`, keeping the query string and discarding the path, so the comparison could never match and **the `on` class was never applied for any URL with a query string**.

### Smaller additions
- The current-URL lookup is null-safe: with no `currentUrl` set and no `$_SERVER['REQUEST_URI']` (CLI/tests), v7 skips the comparison cleanly, where v6 emitted a deprecation on every render.
- README gains documented sections for href resolution rules, per-node `attributes`, `returnFalse()`, `addBranch()`/`addLeaf()` semantics, `baseUrl`/`currentUrl` config, ACL strict-mode behavior and ACL policies.

> **Note:** `pop-nav` renders through `pop-dom`, which now escapes attribute values, and gates items through `pop-acl`, which now treats `'*'` as a wildcard permission. Both change rendered output without any `pop-nav` code change — see the BC-breaks document.

---

## pop-paginator — 4.0.3 → 5.0.0

**Summary:** A small hardening release: output escaping, constructor argument validation via a typed exception, getters that no longer throw before the first render, and an out-of-range page fix.
**Feature count:** 5

### HTML escaping of the request URI and carried-over `$_GET` values
`Range::createRange()` and `Form::createForm()` run `$_SERVER['REQUEST_URI']` through `htmlspecialchars(..., ENT_QUOTES)` before embedding it in `href`/`action` attributes, and `Form` additionally escapes every `$_GET` key and value it re-emits as hidden inputs (including nested array keys/values).

```php
// New
$_SERVER['REQUEST_URI'] = '/items"><script>alert(1)</script>';
echo Paginator::createRange(30)->getLinkRange(1)[1];
// <a href="/items&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;?page=2">2</a>
```
**Previously:** not possible — you sanitized `$_SERVER['REQUEST_URI']` and `$_GET` yourself before instantiating the paginator, or post-processed the rendered HTML.

### Constructor validation with a typed `Pop\Paginator\Exception`
The constructor rejects `total < 0`, `perPage < 1` and `range < 1` up front. Previously the bad value survived construction and blew up later at render time as an uncatchable-by-type `DivisionByZeroError` (or silently produced nothing). This also gives the previously-unused `Exception` class an actual purpose.

```php
// New
try {
    $paginator = Paginator::createRange(100, 0); // perPage must be >= 1
} catch (Pop\Paginator\Exception $exception) {
    // caught at construction, with a message naming the bad argument
}
```
**Previously:** constructed fine; `echo $paginator` later threw `DivisionByZeroError` (an `\Error`, not a `Pop\Paginator\Exception`), and negative totals / zero ranges just rendered empty.

### `getNumberOfPages()` works immediately after construction
The constructor calls `calculateRange()`, so `numberOfPages`, `start` and `end` are populated before any rendering. You can inspect the page count to drive a query `LIMIT`/`OFFSET` without first casting the paginator to a string.

```php
// New
$paginator = Paginator::createRange(4512, 10, 10);
echo $paginator->getNumberOfPages(); // 452
```
**Previously:** threw `TypeError: getNumberOfPages(): Return value must be of type int, null returned` unless you first rendered the paginator or manually called `calculateRange()`.

### Out-of-bounds page numbers render the last range block
`calculateRange()` computes a single `lastBlockStart`, so any page past the end clamps into the final block.

```php
// New
$paginator = Paginator::createRange(200, 10, 10); // 20 pages
$paginator->getLinkRange(25);                     // « ‹ 11 12 … 20
```
**Previously:** `getLinkRange(25)` returned `[]` and `echo $paginator` printed nothing — the pagination bar vanished on any over-range `?page=`.

### `Range` getters return `''` instead of throwing when unset
`getSeparator()`, `getClassOn()` and `getClassOff()` coalesce their nullable backing properties to `''`, so they are safe to call on a freshly-built `Range`.

```php
// New
$paginator = Paginator::createRange(42);
echo $paginator->getClassOn(); // '' — no exception
```
**Previously:** each threw a `TypeError` unless the matching setter had been called first.

### Smaller additions
- `Exception` is now a documented part of the public contract rather than a dead class.
- README gains "Determining the Current Page" and "Getters" sections documenting the existing explicit-page override, `setQueryKey()`, `setSeparator()`, `setClassOn()`/`setClassOff()`, `setInputSeparator()` and `wrapLinks()` — all of which already existed in 4.0.3.
- No new classes, methods or constructor parameters were added; `Paginator`, `PaginatorInterface` and `Form` have no API-surface changes at all.

---

## pop-pdf — 5.2.12 → 6.2.0

**Summary:** A native `Pop\Pdf\Extract` engine replaces the third-party PDF reader, with native PDF merging, image-only/OCR page detection, real HTML table layout and Unicode CID font embedding built on top of it — then AES encryption with reader permissions in 6.1.0, and HTML `<form>` markup compiled straight into interactive AcroForm fields in 6.2.0.
**Feature count:** 20

### Native `Pop\Pdf\Extract` PDF reader / text-extraction engine
An entirely new, dependency-free namespace (39 new classes) that parses xref tables *and* xref streams, object streams (PDF 1.5+ compressed), all standard stream filters (Flate, LZW, ASCII85, ASCIIHex, RunLength), and falls back to a brute-force repair scan for damaged files. On top sits a content-stream interpreter (text/graphics matrices, `q`/`Q`, marked content, `/ActualText`, nested Form XObjects) and a font/encoding resolver (`/ToUnicode` CMaps, Type0/CID fonts, WinAnsi/MacRoman/Standard/Symbol encodings, Adobe Glyph List, cmap/post fallback). `smalot/pdfparser` is removed.

```php
// New — same public call, now native; also usable directly
$text = Pdf::extractTextFromFile('doc.pdf', [1, 2, 3], 50);

$doc   = Pop\Pdf\Extract\Document::fromFile('doc.pdf');
$pages = Pop\Pdf\Extract\Content\PageWalker::walk($doc);
$runs  = (new Pop\Pdf\Extract\Content\Interpreter())->run($doc, $pages[0]->content, $pages[0]->resources);
```
**Previously:** `extractTextFromFile()` existed but was a thin wrapper over `\Smalot\PdfParser\Parser` — a required third-party dependency, with no low-level PDF-reading API of `pop-pdf`'s own.

### PDF merging
`Build\Merger` reads N source PDFs through the Extract engine, renumbers each source's object graph at an increasing offset, rewrites all indirect references, and splices every `/Pages` subtree under one new master node — producing a normal mutable `Document` you can still add to before writing.

```php
// New
$doc = Pdf::merge(['one.pdf', 'two.pdf', 'three.pdf']);
$doc = Pdf::mergeRawData([$pdfStream1, $pdfStream2]);
Pdf::writeToFile($doc, 'merged.pdf');
```
**Previously:** Not possible — no merge API

### Image-only page detection (OCR routing)
`Content\PageClassifier` inspects a page's operators to decide whether it is *nothing but* a single scanned/drawn image (one `Do` XObject, no text or paint operators). Lets you route scans to OCR up front instead of inferring "probably a scan" from an empty extraction result.

```php
// New
if (Pdf::isImageOnlyDocument('deed.pdf')) { /* send to OCR */ }

$pages = Pdf::getImageOnlyPages('deed.pdf');   // [0 => true, 1 => false, 2 => true]
$bool  = Pdf::isImageOnlyData($pdfStream);
```
**Previously:** Not possible.

### HTML `<table>` layout with colspan/rowspan and repeating headers
New `Build\Html\Table\{Grid, Cell, Layout}`. `Grid::build()` walks the DOM into an explicit `[row][col] => Cell` structure with proper colspan/rowspan slot claiming and header detection; `Layout::render()` does two-pass column sizing (explicit widths honored first, remaining space distributed by natural content width) then paginated row rendering that repeats the header after a page break, with per-cell borders and backgrounds.

```php
// New — tables just work through the normal HTML path
$parser = new Pop\Pdf\Build\Html\Parser($document);
$parser->parseHtml('<table><thead><tr><th>Item</th><th>Qty</th></tr></thead>'
    . '<tbody><tr><td colspan="2">Widgets</td></tr></tbody></table>');
$parser->parseCss('th { background-color: #ddd; border-width: 1px; border-color: #333; }');
$parser->process();
```
**Previously:** An experimental `<table>` branch was never fully completed. 

### HTML `<form>` markup becomes interactive PDF form fields
`Build\Html\Form\Layout` turns every control in a parsed `<form>` subtree into a `Document\Page\Field\*` object, block-positioned the same way `Table\Layout` positions table rows. `<input>`, `<textarea>`, `<select>` and `<button>` map onto `Field\Text`, `Field\Choice` and `Field\Button`; each `<form>` becomes a `Document\Form` named from its `id` or `name`; and a control with no `<form>` ancestor still renders, into an implicit `__default__` form. A control's size comes from `size`/`rows`/`cols`, a `width`/`height` attribute or CSS — percentages resolved against the page — and `border-width`/`border-color`/`background-color` carry onto the compiled field's own appearance. There is no flag to turn any of it on.

```php
// New
$document = Pdf::importFromHtml(
    '<form id="signup"><input type="text" name="full_name" value="Alex Doe">'
    . '<select name="country"><option value="ca">Canada</option></select>'
    . '<button>Send</button></form>'
);
$document->getForm('signup');   // a real Document\Form, its field indices filled in at compile time
```
**Previously:** form markup was inert — the parser walked past `<input>`, `<select>`, `<textarea>` and `<button>` and produced nothing at all. A fillable PDF had to be assembled field by field in PHP.

### True grouped radio buttons, with drawn checkbox and radio appearances
Two or more `Field\Button` widgets sharing a field name and marked `setRadio()` now compile to one shared, non-visual parent field plus one child widget each, so choosing one option deselects the rest in a conforming reader. Checked state moved onto a flag of its own: `setValue()` carries the export name a reader reports for a widget when it *is* checked, and `setChecked()`/`isChecked()` say whether it is checked right now. Every checkbox and radio also gets generated on and off appearance streams — a filled square or a filled circle — so the widget draws itself rather than relying on the reader to synthesize one.

```php
// New
$email = (new Field\Button('contact_method'))->setWidth(16)->setHeight(16)
    ->setRadio()->setValue('email')->setChecked();
$phone = (new Field\Button('contact_method'))->setWidth(16)->setHeight(16)
    ->setRadio()->setValue('phone');
```
The pair compiles under one `/FT /Btn /T(contact_method)` parent carrying `/V /email`, each child pointing back at it through `/Parent` and carrying its own `/AP` on/off pair, and the form counts one field for the group rather than two.
**Previously:** radio groups were not supported and the README said so — same-named buttons compiled as independent top-level fields, so nothing deselected anything else, and `setValue()` doubled as the checked flag. Neither checkboxes nor radios carried an appearance stream.

### Field borders, backgrounds and push-button captions
`Field\AbstractField` gained `setBorderWidth()`, `setBorderColor()` and `setBackgroundColor()`, compiled together into the field's `/MK` appearance-characteristics dictionary and its `/BS` border style. Border width is the gate: `0`, the default, omits both the style and the color. `Field\Button` gained `setCaption()`, drawn into a real appearance stream using the field's own registered font.

```php
// New
$field->setBorderWidth(1)->setBorderColor([153, 153, 153])->setBackgroundColor([255, 255, 255]);
// compiles to /MK << /BC [0.6 0.6 0.6] /BG [1 1 1] >> and /BS << /W 1 >>

(new Field\Button('submit'))->setPushButton()->setCaption('Send')->setFont(Font::HELVETICA);
```
**Previously:** not available — a field compiled with no border, no background and no caption of its own, so every rectangle and label around a form had to be drawn beside it as separate `Path` and `Text` objects.

### CID / Unicode embedded font output
Embedded TrueType/OpenType fonts are now compiled as composite fonts — `/Type0` + `/Encoding /Identity-H` + a `/CIDFontType2` descendant + a generated `/ToUnicode` CMap — with text emitted as glyph-ID hex strings. Any script the embedded font has glyphs for (Cyrillic, Greek, etc.) renders correctly and still copies/extracts.

```php
// New — no new API needed; the compiler picks the CID path automatically
$font = new Font('/path/to/DejaVuSans.ttf');
$document->embedFont($font);
$page->addText(new Page\Text('123 ПРИВІТ:', 36), $font->getName(), 50, 600);
```
**Previously:** Every font — standard *and* embedded TrueType — was emitted with a single-byte encoding and raw UTF-8 bytes written straight into `(...)Tj`, so non-Latin text rendered as mojibake with no workaround.

### Glyph-coverage API on `Document\Font`
Public methods to ask a font what it can actually render, and to fail loudly instead of emitting garbage.

```php
// New
$font->isCid();                          // embedded TrueType/OpenType?
$font->hasGlyph(0x041F);                 // UTF-16BE code unit
$font->getGlyphId(0x041F);               // ?int — GID in a CID font
$font->requireGlyphCoverage($string);    // throws, naming char + U+XXXX
$font->stringToGidHex($string);          // '04110412...' for a CID content stream
Font::stringToCodeUnits($string);        // static: UTF-8 -> UTF-16BE
```
**Previously:** Not possible — no way to query coverage; unsupported characters were silently written out and rendered as garbage.

### Standard fonts now render their full WinAnsi repertoire
`Text::encodeWinAnsi()` transcodes UTF-8 to Windows-1252 before writing a literal PDF string, so accented Latin letters, curly quotes and en/em dashes come out correct with the built-in fonts.

```php
// New — renders correctly with Font::ARIAL, no embedding needed
$page->addText(new Page\Text("café — 'quoted' †", 12), Font::ARIAL, 50, 600);
```
**Previously:** Raw UTF-8 bytes went into a single-byte string, mojibaking in every viewer even though the font covered the characters.

### Modern-PDF import rebuilt on the Extract engine
`Build\Parser`'s internals were replaced with a shared import pipeline running on `Extract\Document`. The public API is unchanged, but import now follows real xref tables/streams and object streams, repairs broken files, resolves inherited page attributes down the page tree, and preserves `/Rotate`, `/CropBox`, `/Group` and non-font resources.

```php
// New — same call, now handles PDF 1.5+ compressed/object-stream files
$document = Pdf::importFromFile('modern-acrobat-output.pdf');
```
**Previously:** Import was a single `preg_match_all('/\d*\s\d*\sobj(.*?)endobj/sm', ...)` scan — no xref following, no object-stream support, no repair fallback.

### CSS borders and backgrounds on any element
`Build\Html\Parser::drawBox()` plus `border-width`/`border-color`/`background-color` handling in `prepareNodeStyles()` for tag, `#id` and `.class` selectors — usable on a `<div>`, not just table cells.

```php
// New
$parser->parseCss('div.box { border-width: 1px; border-color: #333333; background-color: #eeeeee; }');
```
**Previously:** Not possible — no border or background-color support of any kind.

### One-call HTML-to-PDF on the `Pdf` facade
```php
// New
$document = Pdf::importFromHtml('<h1>Hello</h1><p>World</p>');
$document = Pdf::importFromHtmlFile('invoice.html');
```
**Previously:** Not on the facade — you instantiated `Build\Html\Parser`, called `parseHtml()`, `process()`, then `document()` yourself.

### CourierNew standard font family
`Standard\CourierNew`, `CourierNewBold`, `CourierNewItalic`, `CourierNewBoldItalic` metric classes were added.
**Previously:** The `Font::COURIER_NEW*` constants existed but the backing classes did not, so using them threw "That standard font class was not found."

### Encryption, passwords and reader permissions
`Document\Security` on a document encrypts everything written — page content streams, embedded images, embedded font files, and the literal strings the library authors. AES-256 by default, AES-128 on request. A `Document\Permissions` object narrows what a reader allows once the document is open; all eight permissions start allowed, so only what is taken away needs naming.

```php
// New
$security = new Security('open-me', 'admin123');
$security->setPermissions((new Permissions())->allowPrinting(false)->allowCopying(false));

$document->setSecurity($security);
Pdf::writeToFile($document, filename: 'protected.pdf');
```
**Previously:** not available — there was no encryption of any kind, and no way to set a password or a permission flag on generated output. An owner password left unset is generated for you, so the permissions cannot be bypassed by leaving it blank.

### Opening password-protected PDFs
Every method that reads an existing PDF takes an optional password: `importFromFile()`, `importRawData()`, `extractTextFromFile()`, `extractTextFromData()` and the four image-only classification methods. `merge()` and `mergeRawData()` take an array keyed the same way as the sources, so a batch can mix protected and unprotected files. Either the user or the owner password opens a document.

```php
// New
$document = Pdf::importFromFile('protected.pdf', password: 'open-me');
$text     = Pdf::extractTextFromFile('protected.pdf', password: 'open-me');
$merged   = Pdf::merge(['plain.pdf', 'protected.pdf'], new Document(), [1 => 'open-me']);
```
**Previously:** not available — an encrypted PDF could not be read at all. Opening is transparent: the resulting `Document` reports `hasSecurity()` as `false`, so writing it back out produces an unencrypted file unless `setSecurity()` is called again.

### A color on a named style
`Document\Style` takes a `Pop\Color` implementation as a fourth argument, or through `setColor()`, and it becomes the fill color of every string drawn under that style name. The color is written inside its own graphics-state save and restore, so it paints the text it belongs to and nothing drawn after it.

```php
// New
$document->addStyle(
    Style::create('heading', font: Font::HELVETICA_BOLD, size: 20, color: new Rgb(0, 102, 204))
);
$page->addText(new Text('Report'), 'heading', x: 100, y: 700);
```
**Previously:** a style carried a font and a size only; color had to be set on each `Text` object individually.

### Character wrapping works with any font
`Page\Text::setCharWrap()` wraps on a multibyte-aware word wrap, so the count is in characters rather than bytes and accented text and CJK break where you would expect — with a standard font or an embedded one.

```php
// New
$text = new Text($paragraph, size: 11);
$text->setCharWrap(80, leading: 14);
```
**Previously:** character wrapping ran on a byte-based wrap that mis-split multibyte text.

### Page size control for HTML parsing
`Build\Html\Parser` takes a page size as its second constructor argument, and `parseString()`, `parseFile()`, `parseUri()`, `Pdf::importFromHtml()`, `importFromHtmlFile()` and `importFromHtmlUri()` all take it as a third — either a `Page` size constant or a `[width, height]` array. Pages default to `LETTER`.

```php
// New
$parser   = new Parser(new Document(), pageSize: Page::A4);
$document = Pdf::importFromHtmlFile('invoice.html', new Document(), Page::A4);
```
**Previously:** HTML parsing had no page-size control at the entry point; the size had to be set on the parser after constructing it.

### `Pdf::importFromHtmlUri()`
The facade gained the URL counterpart to `importFromHtml()` and `importFromHtmlFile()`, returning a finished `Document` from a remote page in one call.

```php
// New
$document = Pdf::importFromHtmlUri('https://example.com/report.html');
```
**Previously:** not available on the facade — `Build\Html\Parser::parseUri()` had to be driven directly.

### Smaller additions
- `Page::addImage()` accepts an image filename string as well as a `Page\Image` object, so a one-line image placement no longer needs `Image::createImageFromFile()` first.
- Inline `style` attributes, `<style>` blocks and linked stylesheets now apply correctly in the HTML parser, including `setDefaultStyle()` overrides.
- `Page\Text::setFont()`/`hasFont()`/`getFont()` — a resolved `Document\Font` attached to a text object, selecting the CID vs. WinAnsi vs. literal output path.
- `Page\Text::escape()` is now `static`, callable without an instance.
- `Page\Text\Stream::measureHeight(array $fonts): float` — measure a stream's real wrapped height without rendering, so pagination decisions match actual output.
- `Build\Html\Parser::getPage()`, `setYOverride()`, and public `getCurrentY()`/`newPage()`/`getStringLines()`/`prepareNodeStyles()` — cursor/page control for custom layout.
- `Build\Font\TrueType` retains the codepoint→glyph-ID map, and `AbstractFont` scales metrics for fonts whose `unitsPerEm` isn't 1000.
- `Field\Choice::addOption()` takes an optional display label alongside the export value, compiling to
  `/Opt [ [(ca) (Canada)] ]` — the value a reader submits and the text it shows no longer have to be the same
  string.
- Encrypted PDFs raise a clear `Extract\Exception` when no password is supplied, or the wrong one is, instead of producing garbage; RC4 and revision-5 encryption are refused by name.
- Resource-exhaustion guards throughout the reader: a per-document 64 MB decode budget shared across every stream, per-filter output caps, recursion depth caps, CMap range caps, and per-page exception isolation so one corrupt page doesn't abort a whole document.

---

## pop-queue — 2.1.3 → 3.0.0

**Summary:** From a fire-and-forget pop/push queue to a crash-safe, observable job system: leased reservations with a dead-letter store, a worker registry, daemon loops with signal handling, lifecycle events, per-job delay/timeout/backoff, an in-memory test adapter, and HMAC-signed payloads.
**Feature count:** 11

### Lease-based reserve/release/delete/bury with a dead-letter store
`AdapterInterface` replaced the single-step `pop()` with an explicit lifecycle: `reserve()` atomically claims a job and leases it for `$leaseSeconds` (default 60, a constructor arg on every adapter), then the worker resolves it with `delete()` (ack), `release()` (retry) or `bury()` (terminal). **If the worker dies holding a job, the lease expires and the job is reclaimed instead of being stranded.** Buried jobs land in a per-adapter dead-letter store that can be inspected and replayed.

```php
// New
$job = $adapter->reserve();          // atomically claimed + leased
$adapter->delete($job);              // ack
// ...or
$adapter->release($job, 30);         // back to pending, delayed 30s
$adapter->bury($job, 'gave up');     // to the dead-letter store

$adapter->getDeadJobs();
$adapter->retryDeadJob($jobId);      // replay back to pending
$adapter->clearDead();
$adapter->count();                   // pending + reserved
```
**Previously:** `pop()` removed the job with no lease and no ack — a worker that crashed mid-job lost it outright. Failed jobs were only readable via `getFailedJob(int $index)`/`getFailedJobs()`/`clearFailed()`, with no way to requeue one; there was no bury, no release.

### `Pop\Queue\Registry\*` — worker observability
An entirely new namespace giving every worker process an identity, a heartbeat, and a record of what it is currently working on, in storage every process can see. Backends (`Memory`/`File`/`Database`/`Redis`) are configured independently of the queue adapter, so an SQS-backed queue still gets worker visibility. It distinguishes "stale" (quiet heartbeat, possibly just busy) from "stuck" (quiet *and* holding a job past its own timeout) — the signal worth alerting on.

```php
// New
$registry = new WorkerRegistry(new RegistryRedis());
$worker->setName('billing-worker-01')->setRegistry($registry);
$worker->workLoop();   // registers, heartbeats, deregisters on graceful stop

// from a dashboard / health check elsewhere:
$registry->getWorkers();        // WorkerRecord[] keyed by worker ID
$registry->getStuckWorkers();
$registry->prune(3600);         // reap records from processes long gone
```
`WorkerRecord` exposes `getHost()`, `getPid()`, `getMode()`, `getCurrentJobId()`, `getCurrentJobDuration()`, `getJobsProcessed()`, `getJobsFailed()`, `isStale()`, `isLikelyStuck()`.
**Previously:** not possible — no worker identity, heartbeat, or introspection of any kind.

### Worker daemon mode with signal handling
Long-running loops that service queues continuously instead of being triggered from cron every minute. Both install SIGTERM/SIGINT handlers (when `ext-pcntl` is loaded) that request a graceful stop at the next iteration boundary — a job in flight always runs to completion. They sleep only when a full pass found nothing.

```php
// New
$worker->workLoop(1);   // jobs, forever, 1s idle backoff
$worker->runLoop(1);    // scheduled tasks, forever (separate process)

$worker->stop();        // graceful stop; kill/Ctrl-C do the same
$worker->isStopped();
```
**Previously:** not possible — only single-pass `work()`/`workAll()`/`run()`/`runAll()`, so continuous operation meant an external cron trigger, with no shutdown handling.

### Lifecycle events on `Queue` and `Worker`
Both classes can fire `Pop\Event\Manager` events around execution, reusing the same event system `Pop\Application` uses. If no manager is set, the `Application`'s manager is used as a fallback; if neither exists, firing is a silent no-op.

```php
// New
$events = new Manager();
$events->on('queue.job.post', function($job, $queue) {
    StatsD::timing("queue.{$queue->getName()}.duration_seconds", $job->getDuration());
});
$queue->setEvents($events);
```
Queue events: `queue.job.pre|post|failed|buried`, `queue.task.pre|post|failed`. Worker events: `worker.work_loop.tick|idle|shutdown`, `worker.run_loop.tick|idle|shutdown`.
**Previously:** not possible — neither class had any hook points.

### Per-job delay, timeout and retry backoff
`delay()` makes a job ineligible until a time; `setTimeout()` interrupts a long-running job; `setBackoff()` spaces out retries (fixed seconds or a per-attempt schedule that holds at its last value).

```php
// New
$job->delay(60);                       // or an absolute ts, or '2026-12-01 09:00:00'
$job->setTimeout(30);
$job->setBackoff([10, 30, 60]);        // 10s after 1st failure, 30s after 2nd, 60s thereafter

$job->isAvailable();
$job->getBackoffDelay();
```
Timeouts are enforced by `symfony/process` for exec jobs (which can actually kill the child) and by a `pcntl` alarm throwing `Process\TimeoutException` for callable/command jobs. `delay()` is honored by all five adapters — SQS translates it into `DelaySeconds`.
**Previously:** not possible — a failed job was re-pushed for immediate retry, jobs ran untimed, and the only control was `setRunUntil()` (an expiry, not a start time).

### `Memory` adapter and `Queue::fake()`
A full-fidelity adapter keeping everything in PHP arrays — no server, extension or disk. It implements the entire contract including delay eligibility, backoff on `release()`, lease expiry, the dead-letter store, task scheduling and `claimTaskRun()`.

```php
// New
$queue = Queue::fake();                          // Memory-backed, zero setup
$queue = Queue::fake('test-queue', 'FILO', 30);  // name, priority, lease seconds
```
**Previously:** not possible — testing meant standing up Redis, a database, or a writable directory.

### `PayloadSigner` — HMAC-signed job payloads
Every persistent adapter serializes jobs and unserializes them on read, which is an object-injection gap for anyone able to write to the storage directly. Set a key once at bootstrap and every adapter prepends a raw 32-byte HMAC-SHA256 on write and `hash_equals()`-verifies before unserializing. Opt-in — with no key set, both `sign()` and `verify()` are exact pass-throughs.

```php
// New
PayloadSigner::setKey($_ENV['QUEUE_SIGNING_KEY']);   // once, at bootstrap
```
**Previously:** not possible — adapters called plain `serialize()`/`unserialize()` with no integrity check.

### Per-queue weights on `Worker`
Queues can be registered with a weight so a worker servicing several expresses which matter more. `getQueues()`, `workAll()`, `runAll()` and the iterator all traverse in weight order (highest first, stable for ties). `work()` also became callable with no argument, trying every queue in weight order and returning the first job claimed.

```php
// New
$worker->addQueue($queue1, 10);   // high
$worker->addQueue($queue2, 1);    // low
$job = $worker->work();           // tries $queue1 first
```
**Previously:** `addQueue()` took no weight, queues were serviced in insertion order, and `work()` required a queue name.

### Atomic task claiming across multiple workers
`TaskAdapterInterface::claimTaskRun(string $taskId, string $window)` lets several workers share one adapter storage without running the same scheduled task twice for the same due-window. A claim persists up to 90 seconds.

```php
// New — called for you by Queue::run(); adapters must now implement it
$adapter->claimTaskRun($taskId, (string)intdiv(time(), 60));  // true if this caller won
```
**Previously:** not possible — every worker invoked around the same moment ran every due task independently, duplicating side effects.

### Non-shell `exec` and argv-form application commands
`setExec()`/`Job::exec()` and `setCommand()`/`Job::command()` now accept `string|array`. Exec jobs run through `symfony/process`: a string goes through the shell, while an argv array runs with **no shell at all**, so metacharacters in an argument are inert. The array form is also the only way to pass a command argument containing spaces.

```php
// New
Job::exec(['ls', '-la']);                        // no shell involved
Job::exec('ls -la | wc -l');                     // shell, as before
Job::command(['notify', 'Hello there, world']);  // argument with spaces
```
**Previously:** both were `string`-only; exec ran through `exec()` with no timeout support and no way to bypass the shell.

### Fair, bulk task evaluation as public API
`getScheduledTasks()` and `evaluateTasksOnce()` split fetching from evaluating, so `Worker::runAll()` gives every queue one shared pass per tick rather than letting one queue's 60-second sub-minute loop block the others. `TaskAdapterInterface::getAllTasks()` fetches every task in one call instead of a list-then-fetch-each N+1.

```php
// New
$tasks = $queue->getScheduledTasks();             // taskId => Task, one round trip
$ran   = $queue->evaluateTasksOnce($tasks, $app); // jobId => Task, for those that ran
```
**Previously:** `run()` was a monolith, and each sub-minute task busy-looped for 60 seconds inside it before the next was even considered.

### Smaller additions
- `AbstractJob::getDuration()` — run time in seconds, `null` unless the job both started and completed.
- `Redis` adapter constructor gained `?string $password` and `?array $context`, enabling AUTH and TLS connections.
- `Sqs::push()` translates a job's `delay()` into the message's `DelaySeconds` (clamped to AWS's 900s cap; skipped on `.fifo` queues).
- `AdapterInterface::count()` — pending + reserved count, replacing index-based `getStart()`/`getEnd()`/`getStatus()`.
- A command job borrows its description from the dispatched `AbstractCommand` when none was set, so queued command jobs aren't anonymous in the registry.
- `Worker::setName()`/`getName()`/`hasName()` — an operator-facing label surfaced in the registry record.
- New `Process\TimeoutException`, so a timeout kill is distinguishable from an ordinary job failure.
- `aws/aws-sdk-php` moved from a hard requirement to `suggest`, so installing `pop-queue` no longer pulls in the AWS SDK.

---

## pop-session — 4.0.4 → 5.0.0

**Summary:** Pluggable save handlers, early lock release, a public expiration sweep, JSON serialization, and secure-by-default cookie/fixation hardening.
**Feature count:** 6

### Custom session save handlers (`Session::setHandler()`)
A static `setHandler(SessionHandlerInterface $handler)` registers any PHP save handler (Redis, database, encrypted files) before the singleton boots; the constructor then calls `session_set_save_handler()` prior to `session_start()`. It throws if the instance already exists or a session is already active, so misuse fails loudly instead of silently doing nothing.

```php
// New
Session::setHandler(new MyRedisSessionHandler()); // \SessionHandlerInterface
$sess = Session::getInstance();
```
**Previously:** not possible through the component — the constructor went straight to `session_start()`, so you had to call `session_set_save_handler()` yourself before ever touching `Session::getInstance()`, outside the API.

### `close()` — release the session write lock
`Session::close()` wraps `session_write_close()`, flushing session data and freeing PHP's per-session file lock while the request keeps running. This is the standard fix for concurrent AJAX/long-poll requests from the same client serializing behind one another.

```php
// New
$sess = Session::getInstance();
$sess->userId = 1;
$sess->close(); // lock released; long-running work can proceed
```
**Previously:** not possible via the API — only `kill()` existed, which destroys the session entirely. Releasing the lock meant calling `session_write_close()` directly.

### Public `sweep()` on `Session` and `SessionNamespace`
`sweep()` manually runs the request-hop and time-expiration checks, removing any values past their limit, and returns `$this` for chaining. It is part of `SessionInterface`, so it is guaranteed on both the global session and namespaces.

```php
// New
Session::getInstance()->sweep();
(new SessionNamespace('MyApp'))->sweep();
```
**Previously:** not possible — the equivalent logic lived in private methods on each class and only fired implicitly from `init()` and repeat `getInstance()` calls.

### Secure-by-default session cookie flags
Cookie params are now always applied, not just when `$options` is non-empty, and the defaults harden: `httponly` defaults to `true` and `samesite` defaults to your `php.ini` value or `'Lax'` when unset. Explicit options still override both.

```php
// New
$sess = Session::getInstance(); // HttpOnly + SameSite=Lax applied automatically

$sess = Session::getInstance(['samesite' => 'None', 'secure' => true, 'httponly' => false]);
```
**Previously:** `session_set_cookie_params()` was only called at all if you passed a non-empty `$options` array, and every flag defaulted to whatever `php.ini` said — no HttpOnly or SameSite unless you set them by hand.

### `strict_mode` option (session fixation defense)
A new `strict_mode` option, defaulting to `true`, is passed through to `session_start(['use_strict_mode' => '1'])`, making PHP reject uninitialized session IDs supplied by the client. Set it to `false` to opt out.

```php
// New
$sess = Session::getInstance(['strict_mode' => true]); // default
```
**Previously:** not possible — `session_start()` was called with no arguments, so strict mode depended entirely on the ini setting.

### `JsonSerializable` support
`AbstractSession` implements `JsonSerializable` with `jsonSerialize(): array` returning `toArray()`, so both `Session` and `SessionNamespace` can be handed straight to `json_encode()` — with the internal `_POP_SESSION_` bookkeeping key stripped out.

```php
// New
$sess->foo = 'bar';
echo json_encode($sess); // {"foo":"bar"}
```
**Previously:** `json_encode($sess)` encoded the object's own (private/empty) properties rather than the session data; you wrote `json_encode($sess->toArray())`.

### Smaller additions
- `SessionInterface` declares `sweep(): SessionInterface`, so the contract is enforced on any custom implementation.
- New reusable protected helpers on `AbstractSession` — `checkRequestValue()`, `checkRequestValues()`, `checkExpirationValue()`, `checkExpirationValues()` — operate on caller-supplied arrays by reference, making the hop/expiration machinery available to subclasses instead of being duplicated private code.
- The constructor now attaches to an already-active session: id/name and `init()` are set outside the `session_id() == ''` branch, so `getInstance()` interoperates with a session started by other code (in v6 those were only assigned when Pop itself started the session, leaving the object unusable otherwise).
- `kill()` guards on `session_status() === PHP_SESSION_ACTIVE`, so it is safe to call when no session is active.

---

## pop-storage — 2.1.3 → 3.0.0

**Summary:** Streaming I/O, presigned/SAS temporary URLs, recursive + fully paginated listings, a typed exception hierarchy with path-traversal rejection, and an injectable HTTP handler for Azure.
**Feature count:** 8

### Streaming file I/O
`putFileStream()` and `fetchFileStream()` move file contents through a PHP stream resource instead of buffering the whole file in memory, making large uploads/downloads practical. Both are on `StorageInterface`, so all three adapters implement them.

```php
// New
$resource = fopen('/path/to/large-video.mp4', 'r');
$storage->putFileStream('large-video.mp4', $resource);
fclose($resource);

$out = $storage->fetchFileStream('large-video.mp4'); // readable resource
```
**Previously:** not possible — only `putFileContents(string $filename, string $fileContents)` / `fetchFile()`, both of which hold the entire file in a PHP string.

### Temporary (presigned) URLs
`getTemporaryUrl(string $filename, int $expiresInSeconds = 900): string` returns a time-limited read URL without exposing credentials or making the object public. S3 builds it via `createPresignedRequest()`; Azure builds a SAS-token URL; Local throws `UnsupportedOperationException`.

```php
// New
$url = $storage->getTemporaryUrl('test.pdf');        // 15 min default
$url = $storage->getTemporaryUrl('test.pdf', 3600);
```
**Previously:** not possible — no such method on any adapter; you dropped down to the raw `S3Client` yourself, and Azure had no SAS support at all.

### Azure SAS token generation
`AuthInterface::generateSasToken()` produces a service SAS query string for a blob (HMAC-SHA256 over the Azure `2025-01-05` string-to-sign, `sr=b`, `spr=https`), with a settable permission string. It backs Azure's `getTemporaryUrl()` but is public for direct use.

```php
// New
public function generateSasToken(
    string $resourcePath, int $expiresInSeconds, string $permissions = 'r', ?\DateTime $expiresAt = null
): string;

$token = $storage->adapter()->getAuth()->generateSasToken('/my-container/test.pdf', 3600, 'r');
```
**Previously:** not possible — `Auth` had only `signRequest()` for shared-key request signing.

### Recursive directory/file listing
All three listing methods gained a `bool $recursive = false` parameter across the interface, `Storage`, and every adapter. Results are relative paths that round-trip straight back into `fetchFile()`/`deleteFile()`.

```php
// New
// ['test.pdf', 'foo/test2.pdf', 'foo/bar/test3.pdf']
$files = $storage->listFiles(null, true);
$dirs  = $storage->listDirs(null, true);
$all   = $storage->listAll(null, true);
```
**Previously:** not possible — one level deep only; you had to `chdir()` into each directory and re-list manually.

### Full pagination on S3 and Azure listings
S3 listings iterate `getPaginator('ListObjects', $params)` instead of a single call, and Azure's new `walkBlobs()` follows the `NextMarker` continuation token across pages. Listings are no longer silently truncated at the service page limit (1000 objects for S3).

```php
// New — S3::listFiles() internals
foreach ($this->client->getPaginator('ListObjects', $params) as $page) {
    foreach ($page['Contents'] ?? [] as $object) { /* ... */ }
}
```
**Previously:** a single un-paginated request per listing, so anything past the first page was silently dropped.

### Typed exception hierarchy
Twelve typed exceptions under `Pop\Storage\Exception\*`, all extending `Pop\Storage\Exception`, so callers can branch on the failure or use one catch-all: `FileNotFoundException`, `DirectoryNotFoundException`, `UnableToWriteFileException`, `UnableToReadFileException`, `UnableToDeleteFileException`, `UnableToCopyFileException`, `UnableToMoveFileException`, `UnableToCreateDirectoryException`, `UnableToDeleteDirectoryException`, `UnableToGenerateTemporaryUrlException`, `UnsupportedOperationException`, `PathTraversalException`.

```php
// New
try {
    $contents = $storage->fetchFile('test.pdf');
} catch (Pop\Storage\Exception\FileNotFoundException $e) {
    // handle the missing file specifically
} catch (Pop\Storage\Exception $e) {
    // or catch any storage failure
}
```
**Previously:** no typed failures — each adapter threw exactly one generic exception and nothing else; failed writes/deletes were silent no-ops, and the two old exception classes extended `\Exception` directly with no common base.

### Path-traversal rejection
`AbstractAdapter::scrub()` splits every path on `/` and `\` and throws `PathTraversalException` on any `..` segment. It applies to every path-taking method on every adapter, **including the attacker-controlled `name` value in an uploaded `$_FILES` array**. A single leading `/`, `\`, `./` or `.\` is still normalized away.

```php
// New
$storage->fetchFile('../../etc/passwd'); // throws PathTraversalException
```
**Previously:** not possible — `scrub()` only stripped a leading slash or `./`; `..` segments passed through and resolved.

### Injectable HTTP handler on the Azure adapter
`setHandler()`/`getHandler()`/`hasHandler()` let you inject a `Pop\Http\Client\Handler\HandlerInterface` that `initClient()` applies to every client it builds, so Azure blob traffic can be driven by a mock (or any alternate transport) rather than live cURL.

```php
// New
$handler = new Pop\Http\Client\Handler\Mock();
$handler->queue(new Pop\Http\Client\Response(['code' => 200]));
$storage->adapter()->setHandler($handler);
```
**Previously:** not possible — `initClient()` always constructed a client with the default handler and there was no seam.

### Smaller additions
- `S3::setBaseDir()` normalizes the bucket to always carry the `s3://` prefix, so callers can pass either form.
- Azure `MAX_LIST_PAGES` (10000) — a finite backstop that throws if a malformed/repeating `NextMarker` would otherwise loop forever; overridable in subclasses.
- Azure `DEFAULT_CONTENT_TYPE` (`application/octet-stream`) fallback for extension-less names.
- `S3::rmdir()` batches deletions through the `DeleteObjects` API (1000 keys per request) instead of one `unlink()` round trip per file.
- Azure gained `currentPrefix()`/`resolveUri()`, so `chdir()` state is honored consistently on every blob operation.
- `Local::rmdir()` and `S3::rmdir()` raise `DirectoryNotFoundException` for a missing directory rather than emitting warnings.

---

## pop-utils — 2.4.2 → 3.0.0

**Summary:** `pop-utils` became the dependency-free foundation layer of v7, absorbing the framework's `AbstractModel` and publishing the debugger contracts that let other components integrate with `pop-debug` without depending on it.
**Feature count:** 4

### `Pop\Utils\AbstractModel` — the shared model base for every Pop app
The abstract model class moved down from `Pop\Model\AbstractModel` in `popphp` into `pop-utils`, so any app or library can build models on a common ancestor without pulling in the full framework. It is an empty stub extending `ArrayObject`, inheriting property access, array access, iteration, countability, and serialize/unserialize.

```php
// New
use Pop\Utils\AbstractModel;

class User extends AbstractModel {}

$user = new User(['id' => 1, 'name' => 'Nick']);
echo $user->name;    // 'Nick'
echo $user['name'];  // 'Nick'
```
**Previously:** you had to extend `Pop\Model\AbstractModel`, which lived in `popphp/popphp` — so **every model-bearing library inherited a dependency on the whole application framework**. In v7 `pop-db`'s `AbstractDataModel` and `pop-kettle`'s generated models all extend the `pop-utils` class instead.

### `DebuggerInterface` / `DebuggerHandlerInterface` — debugger contracts without a pop-debug dependency
Two new interfaces define what a debugger and a debug handler look like, so a component can accept and drive a debugger purely by type hint. `DebuggerInterface` is minimal (`addHandler()`, `save()`); `DebuggerHandlerInterface` is the full handler contract — name/data/start/end/elapsed accessors, `start()`/`stop()`, PSR-3 `setLogger()`/`setLoggingParams()`, plus `prepare()`, `prepareMessage()` and `log()`.

```php
// New — pop-db's query profiler, with no pop-debug dependency
use Pop\Utils\DebuggerInterface;

public function setDebugger(DebuggerInterface $debugger): Profiler
{
    $this->debugger = $debugger;
    return $this;
}
```
**Previously:** not possible — there was no shared contract, so a component either had to require `popphp/pop-debug` to type-hint the concrete `Pop\Debug\Debugger`, or fall back to untyped `mixed`/duck typing. In v7 `Pop\Debug\Debugger` implements `DebuggerInterface`, and `pop-db`'s profiler type-hints the interface only.

### `Arr::toArray()` — one public normalizer for anything array-like
New public static method that resolves any array-like value into a plain array, handling plain arrays, `AbstractArray` descendants, any object exposing `toArray()`, native `\ArrayObject`, and any `\Traversable`, returning `[]` for anything else.

```php
// New
public static function toArray(mixed $value): array

$data = Arr::toArray($collection);   // Collection, ArrayObject, Traversable, or plain array
```
**Previously:** not exposed. The logic existed only as duplicated inline code — `AbstractArray::toArray()` had its own cascade, `Collection::getDataAsArray()` had a second copy (with a different branch order), and roughly a dozen `Arr` methods each repeated the same `instanceof AbstractArray` check. All of those now delegate to the single public method, which also means `Arr::divide()`, `slice()`, `join()`, `prepend()`, `sort()`, `map()`, `filter()` and friends normalize `\ArrayObject`/`\Traversable`/`toArray()`-bearing inputs consistently, not just `AbstractArray`.

### Standalone component — the `popphp/popphp` dependency is gone
`composer.json` dropped `"popphp/popphp": "^4.4.0 || ^5.0"` entirely, leaving only `php`, `ext-json` and `psr/log`. Library authors can now depend on `pop-utils`' array objects, collections, callables, string/number/file helpers and model base **without dragging in the application framework**, its router, dispatcher and service container.

```php
// New composer.json
"require": {
    "php": ">=8.4.0",
    "psr/log": "^3.0",
    "ext-json": "*"
}
```
**Previously:** requiring `pop-utils` transitively required all of `popphp`. The single coupling point was `functions.php`, where `app_date()` called `Pop\App::env()` to resolve the timezone; in v7 that reads `$_ENV[$env] ?? $envDefault` directly.

### Smaller additions
- `psr/log` is now a direct dependency, so `DebuggerHandlerInterface` can type-hint `Psr\Log\LoggerInterface`.
- `File::getFileMimeType()` resolves the mime type straight from the extension lookup table instead of constructing a throwaway `File` instance, and returns `null` for an extension-less filename.
- `app_time()` correctly returns `int|null` (a real Unix timestamp) rather than being declared `string|null`.
- All 24 global helper functions in `functions.php` are intact with identical names, parameter lists and defaults — a full reflection dump of every public/protected member across both branches shows no removals.

---

## pop-validator — 4.8.1 → 5.0.0

**Summary:** A small, targeted release: 19 more `Has*` validators became usable from rule strings, `Has*`/`Count*`/`Required` gained support for pre-set input, and a new `ValueComparisonTrait` exposes reusable comparison helpers.
**Feature count:** 5

### 19 additional `Has*` validators usable from rule strings
`Rule::$hasClasses` grew from 10 to 29 entries, so `Rule::parse()` now wraps the value as `[$field => $value]` for the comparison and DateTime members of the `Has*` family. This makes them work **for the first time** in `ValidatorSet::createFromRules()` and anywhere else rule strings are consumed.

```php
// New
$set = Pop\Validator\ValidatorSet::createFromRules('users.age:has_one_greater_than:18');
$set->evaluate(['users' => [['age' => 21], ['age' => 15]]]); // true
```
**Previously:** not possible — the same call threw `Error: The evaluated value must be an array of node name and value, e.g. ['node' => 3].` (`has_one_in` was worse: it silently returned `false`).

Newly registered: `HasOneIn`, `HasOneGreaterThan`, `HasOneGreaterThanEqual`, `HasOneLessThan`, `HasOneLessThanEqual`, the four `HasOnlyOne*` comparison equivalents, and the ten `HasOneDateTime*` / `HasOnlyOneDateTime*` classes.

### Whole-array validators honor pre-set input
The `Has*` and `Required` validators changed their guard from `if (!is_array($input))` to `if (!is_array($this->input))`, so input supplied ahead of time via `setInput()` (or left over from a prior `evaluate()`) is now used instead of rejected.

```php
// New
$validator = new Pop\Validator\HasOne('users');
$validator->setInput(['users' => [['username' => 'john_doe']]]);
$validator->evaluate(); // true
```
**Previously:** not possible — threw `Error: The evaluated input must be an array.` because the check inspected the (null) `$input` argument rather than the stored property. You had to pass the array to `evaluate()` on every call.

Affected: `HasOne`, `HasOnlyOne`, `HasOneNotEmpty`, the six `HasCount*`, the twelve `HasOne*`/`HasOnlyOne*` comparison classes, the six `Count*`, and `Required`.

### `Has*` validators work when added to a partly eager-loaded `ValidatorSet`
`ValidatorSet::evaluate()`'s second pass — the one that catches validators registered *after* a `load()` call — now applies the same `Rule::isHasClass()` special-casing as the main loop and passes the full input array.

```php
// New
$set = Pop\Validator\ValidatorSet::load([new Pop\Validator\IsNotEmpty()], 'username');
$set->addValidator('users', 'HasOne', 'users');          // added after the eager load
$set->evaluate(['username' => 'bob', 'users' => [['age' => 21]]]); // true
```
**Previously:** returned `false` with a spurious error — that branch handed the validator either a single field value or nothing, neither of which a `Has*` validator can use. Mixing eager loading with a later `Has*` `addValidator()` was effectively unusable.

### `ValueComparisonTrait` — reusable comparison helpers for custom validators
A new trait factors the contains/in/starts-with/ends-with matching logic out of ten validators into named protected methods, available to any custom validator you write: `resolveInputValue()`, `containsMatch()`, `containsNoneMatch()`, `inMatch()`, `inNoneMatch()`, `resolveComparisonValueString()`, `startsWithMatch()`, `endsWithMatch()`.

```php
// New
class ContainsAny extends Pop\Validator\AbstractValidator
{
    use Pop\Validator\ValueComparisonTrait;

    public function evaluate(mixed $input = null): bool
    {
        if ($input !== null) { $this->input = $input; }
        return $this->containsMatch($this->value, $this->resolveInputValue());
    }
}
```
**Previously:** not possible — the logic was duplicated inline inside `Contains`, `NotContains`, `In`, `NotIn`, `StartsWith`, `NotStartsWith`, `EndsWith` and `NotEndsWith`, with no shared entry point.

### `static` return types for subclass-friendly fluent chaining
`AbstractValidator::setValue()`, `RegEx::setNumberToSatisfy()`, `ValidatorSet::add()/load()/createFromRules()` and `Condition::create()/createFromRule()` declare `: static` instead of the concrete base class.

```php
// New
class MySet extends Pop\Validator\ValidatorSet {}
$set = MySet::createFromRules('username:alpha_numeric'); // statically typed as MySet
```
**Previously:** the runtime object was already correct, but the declared return type was the base class, so subclass-specific methods on the result were invisible to IDEs and static analysis.

### Smaller additions
- `Rule::isHasClass()`/`isHasOneClass()` default a `null` `$prefix` back to `Pop\Validator\`, so passing `null` explicitly is safe under strict types.
- README gained four new documented sections — multi-pattern `RegEx`, nested/related data with the `Has*` family, `getAvailableValidators()`, and nested-field references — plus a corrected 96-entry validator table (v6's table was missing 20 `Has*` classes that already shipped).

---

## pop-view — 4.0.4 → 5.0.0

**Summary:** An opt-in compile-to-PHP + disk-cache render path for stream templates, working multi-block layout inheritance, data-aware `{{@include}}`, and a `getContributingFiles()` dependency map.
**Feature count:** 7

### Opt-in compiled/cached stream rendering
`Template\Stream` accepts a cache directory (2nd constructor arg or `setCacheDir()`). With one set, `render()` compiles the fully-resolved template into real PHP once, writes it to the cache dir, and thereafter just `include`s the compiled file — no re-parsing on any subsequent render, including across requests. Output is identical to the uncached path, so it is a pure performance opt-in.

```php
// New
$view = new View(new Stream('index.html', '/path/to/cache/dir'), $data);
echo $view;

// or: (new Stream('index.html'))->setCacheDir('/path/to/cache/dir');
```
**Previously:** not possible — `Stream::__construct(string $template)` took only the template, and `render()` unconditionally re-ran the full string-substitution pipeline on every call.

### `Template\Stream\Compiler` — templates translated into native PHP
A new compiler that turns a resolved template into PHP source using real `foreach`/`if` control structures and `$data[...]` lookups instead of `str_replace()` passes. It covers every shape `Parser` supports: scalars, `[{name[index]}]` array-index scalars, top-level and in-loop conditionals, numeric lists of scalars, numeric lists of named-field rows, and nested named sub-loops. Literal text is emitted through an escaping helper, so a template containing a literal `<?php` can never execute as code in the generated file.

```php
// New
$php = Compiler::compile('[{rows}]<h4>[{title}]</h4>[{/rows}]');
// => PHP source with a real foreach over $data['rows']
```
**Previously:** not possible — `Stream\Parser` was the only engine, and it only ever did delimiter-scanning string substitution.

### `Template\Stream\Cache` — content-hash keyed compiled-template cache
Keyed by `hash('sha256', $resolvedTemplate)` — the hash of the template *after* `@extends`/`@include`/blocks have been merged — so any content change anywhere in the inheritance chain automatically produces a new key and a fresh compile. `get()` additionally takes the newest contributing-file mtime and treats an older cache file as a miss; `put()` writes to a temp file and `rename()`s it into place so a concurrent reader never sees a partial file.

```php
// New
$cache = new Cache('/path/to/cache/dir');
$key   = Cache::key($resolvedTemplate);          // sha256 content hash
$src   = $cache->get($key, $newestSourceMtime);  // null on miss/stale
$cache->put($key, $compiledSource);              // atomic write
```
**Previously:** not possible — nothing in the component ever wrote to disk.

### Working multi-block layout inheritance
A child template can now override any number of named blocks from its parent layout, and `{{parent}}` resolves against the correct same-named parent block for each. This is the difference between a layout with one editable region and a real layout system.

```php
// New — parent.html declares {{header}}…{{/header}} and {{sidebar}}…{{/sidebar}};
// child.html can override both, and {{parent}} inside each resolves to that block's parent content.
$view = new View(new Stream('child-multi.html'), ['title' => 'Hello']);
```
**Previously:** not possible beyond a single block — `parseBlocks()` looped over every child block but wrote every resolved result into a block hard-coded as `'header'`, so any layout with a second named block silently lost it.

### `{{@include}}` now resolves against real render data
Includes splice the included template's *resolved-but-unrendered* text into the including template at construction time, deferring all substitution to the outer template's single `render($data)` pass. This is what makes conditionals, loops and placeholders inside an included partial see the actual view data.

```php
// New — inc/conditional.html contains [{if(foo)}]<p>Foo is [{foo}]</p>[{else}]<p>No foo</p>[{/if}]
$view = new View(new Stream('include-conditional.html'), ['foo' => 'bar']);
echo $view; // <p>Foo is bar</p>
```
**Previously:** the include was rendered immediately at construction with the still-empty data, so conditionals inside an included file were evaluated against nothing and stripped before the caller's data ever arrived.

### `Stream::getContributingFiles()`
Returns `path => mtime` for every file that contributed to the resolved template, walked across the entire `@extends`/`@include` chain (each nested `Stream` merges its own map upward). It drives the cache's staleness check and is directly useful for build tooling, watchers and dependency tracking.

```php
// New
$template = new Stream('child.html');
foreach ($template->getContributingFiles() as $path => $mtime) { /* ... */ }
```
**Previously:** not possible — the parent/include chain was consumed during construction and left no record of which files were touched.

### Path-traversal guard on `@extends`/`@include` targets
`Stream::assertSafeTemplatePath()` rejects absolute paths (`/`, `\`, `C:\`) and any `..` segment in a template target, throwing `Stream\Exception`. This closes an arbitrary-file-read hole where template content could reach outside the template directory.

```php
// New
new Stream('{{@include ../../../etc/passwd}}'); // throws Pop\View\Template\Stream\Exception
```
**Previously:** not possible to prevent — the target was concatenated onto the template dir and loaded with no validation.

### Smaller additions
- `Stream::getCacheDir()` and `hasCacheDir()` accessors alongside `setCacheDir()`.
- `Compiler` raises `Stream\Exception` with actionable messages for template shapes it can't compile — an unclosed `[{if(...)}]`, or a conditional that spans a loop boundary — telling the caller to render without a cache dir or fix the template.
- `Compiler` deliberately improves on `Parser` where `Parser` misbehaves: a multi-row nested sub-loop no longer drops earlier rows' output, in-loop conditional substitution gets a stringability guard, a missing/`null` loop key renders as zero iterations instead of leaking literal tags, and `ArrayAccess` loop data throws a clear exception rather than silently misbehaving.
- `Parser::stringifyReplace()` normalizes non-string scalars before substitution.

---

## pop-parser — NEW in v7 (1.0.4)

**Summary:** A new, dependency-free component (`Pop\Parser`) that breaks free-form US/CA street addresses and personal names down into their component fields, returning an immutable result object that reports how confident the parse was.
**Feature count:** 10

> This package is new in v7 and bundled in the metapackage. There is nothing to migrate — no v6 component offered address or name parsing.

### `Address\AddressParser` — free-form US/CA address parsing
Parses a one-line or multi-line address into street number, street name, route type, direction, unit, city, state (code and full name), postal code, ZIP+4 and country. Extraction is token-position based and scans right-to-left from the postal code, so a state code like `CA` can never be mistaken for the country code `CA`, and a route-type word inside a street or city name (`Park Granada`, `Beverly Hills`) is not mis-claimed as a suffix.

```php
// New
$result = (new AddressParser())->parse('123 Main St Apt 4B, Springfield, IL 62704');

$result->getStreetNumber(); // '123'
$result->getStreetName();   // 'Main'
$result->getRouteType();    // 'St'
$result->getUnit();         // 'Apt 4B'
$result->getCity();         // 'Springfield'
$result->getStateCode();    // 'IL'
$result->getStateName();    // 'Illinois'
$result->getPostalCode();   // '62704'
$result->getCountry();      // 'US'

$result->getFullAddress();  // '123 Main St, Apt 4B, Springfield, IL 62704'
```
**Previously:** not available — parsing a free-form address meant hand-rolling regexes or pulling in a third-party library.

### `Name\NameParser` — personal name parsing
Parses a name into salutation, firstname, initials, middlename, nickname, lastname prefix, lastname and suffix, through an ordered extraction pipeline. It normalizes case (`MACDONALD` → `Macdonald`, while `McDonald`/`O'Brien` are left as typed), folds recognized lastname prefixes (`van`, `von`, `de`, `della`, `St.`) into their own field, treats single letters as initials, pulls parenthetical or quoted segments out as nicknames, and never silently drops an unrecognized token.

```php
// New
$result = (new NameParser())->parse('Dr. John Michael Smith Jr.');

$result->toArray();
// [
//     'salutation' => 'Dr.', 'firstname' => 'John', 'initials' => null,
//     'middlename' => 'Michael', 'nickname' => null, 'lastnamePrefix' => null,
//     'lastname' => 'Smith', 'suffix' => 'Jr', 'credentials' => null,
//     'confidence' => 1.0,
// ]

$result->getFullName(); // 'John Michael Smith'
```
**Previously:** not available at all — an app had to add a third-party name parser or split names by hand.

### "Last, First" and multi-segment comma format
A comma anywhere in the input automatically switches `NameParser` to `"Last, First Middle[, Suffix]"` parsing, with separate code paths for the lastname segment, the given-name segment and any further comma segments. Extra segments past the third are all consumed rather than dropped, and a leftover from the lastname segment is absorbed into middlename instead of overwriting the firstname.

```php
// New
$result = (new NameParser())->parse('Smith, John Michael, Jr');

$result->getFirstname();  // 'John'
$result->getMiddlename(); // 'Michael'
$result->getLastname();   // 'Smith'
$result->getSuffix();     // 'Jr'
```
**Previously:** not available — comma-inverted names had to be detected and split by the application before parsing.

### `Address\AddressValues` — reusable reference data
A static data provider exposing the same lookup sets the address parser validates against: the full USPS route-type designator map, a shorter everyday list, directionals, USPS unit/secondary designators, and US states plus Canadian provinces. Useful for populating or validating address form fields against exactly what the parser will accept.

```php
// New
$values = new AddressValues();

$values->getStates('US')['IL'];  // 'Illinois'
$values->getStateCodes('CA');    // ['AB', 'BC', 'MB', 'NB', ...]
$values->getUnitTypes();         // ['DEPARTMENT', 'APARTMENT', 'PENTHOUSE', ... 'APT', '#']
```
**Previously:** not available — there was no bundled state, province or unit-type dataset to look these up against. Note that city detection is purely positional; the component carries no embedded per-state city list.

### PO Box, ZIP+4 and Canadian postal handling
`AddressParser` recognizes `PO Box 1234`, `P.O. Box 1234`, `POB 1234` and `Box 1234`, flagging them via `isPoBox()`. ZIP+4 input is split into `getPostalCode()`/`getZip4()` whether written as `62704-1234` or `627041234`, and Canadian postal codes are matched with or without the internal space, setting the country to `CA`.

```php
// New
$result = (new AddressParser())->parse('PO Box 1234, Springfield, IL 62704');
$result->isPoBox();       // true
$result->getStreetName(); // 'PO Box 1234'

$result = (new AddressParser())->parse('55 Yonge St, Toronto, ON M4B 1B3');
$result->getPostalCode(); // 'M4B1B3'
$result->getStateName();  // 'Ontario'
$result->getCountry();    // 'CA'
```
**Previously:** not available — PO Box detection and CA/ZIP+4 normalization had to be written per-application.

### Shared `AbstractParser` / `ParserInterface` contract
Both parsers extend `Pop\Parser\AbstractParser`, which holds the `data`/`result`/`error` state with `getData()`, `setData()`, `getResult()`, `hasError()` and `getErrorMessage()`. Every parser therefore has the same shape — construct with or without data, call `parse()`, read the fields off the result it hands back — which makes it straightforward to add a new parser to the component.

```php
// New
$parser = new AddressParser('123 Main St, Springfield, IL 62704');
$result = $parser->parse();      // the result object
$parser->getResult() === $result; // true — the parser keeps the last one
$parser->getData();               // the original string back
// parse() with no data set anywhere throws Pop\Parser\Exception
```
**Previously:** not available — there was no shared parser contract to implement against.

### `parse()` returns an immutable result object
`AddressParser::parse()` and `NameParser::parse()` return an `AddressResult` or `NameResult` rather than the parser itself, and the parser holds no parsed fields at all. Every `get*()`/`has*()`, `toArray()` and the string cast live on that result, so two parses on one parser produce two independent results instead of one object whose state is overwritten by whichever call ran last.

```php
// New
$parser = new NameParser();
$first  = $parser->parse('Jane Doe');
$second = $parser->parse('John Smith');

$first->getFirstname();  // 'Jane' — unaffected by the second parse
$second->getFirstname(); // 'John'
```
**Previously:** not available. Both results share a `Pop\Parser\ResultInterface` (`toArray()`, `getConfidence()`, `isConfident()`) and an `AbstractResult` base.

### Confidence scoring on every result
Every result reports how much of the input it matched outright rather than guessed, as a `0.0`–`1.0` score, with `isConfident()` for a threshold check (default `0.7`). It starts at `1.0` and drops only for specific, individually identifiable guesses, so an address that genuinely has no city still scores `1.0` rather than being penalized for a field that was never there.

```php
// New
(new AddressParser())->parse('123 Main St, Springfield, IL 62704')->getConfidence();        // 1.0
(new AddressParser())->parse('123 Main St Apt 4B, Springfield, IL 62704')->getConfidence(); // 0.75
$loose = (new AddressParser())->parse('Main St Springfield IL');
$loose->getConfidence();  // 0.5
$loose->isConfident();    // false — below the 0.7 default
```
**Previously:** not available — a parse either produced fields or did not, with no way to tell a confident result from a guess short of inspecting every field yourself.

### Case normalization for dirty imports
An all-uppercase or all-lowercase field is title-cased on the way out, so data exported from a legacy system in screaming caps comes back readable. Anything already carrying mixed case is left exactly as typed, which is what keeps `McDonald`, `O'Brien` and `van der Berg` intact rather than flattened to `Mcdonald`.

```php
// New
$result = (new NameParser())->parse('JOHN SMITH');
$result->getFirstname(); // 'John'
$result->getLastname();  // 'Smith'

(new NameParser())->parse('McDonald, RonalD')->getFirstname(); // 'RonalD' — mixed case untouched
(new AddressParser())->parse('456 oak avenue, portland, or 97201')->getCity(); // 'Portland'
```
**Previously:** not available — normalizing case was the application's job, and doing it naively broke the names that need an interior capital.

### Professional credentials on a name
`NameParser` recognizes credentials and degrees — PhD, MD, Esq, JD, MBA, RN, DDS, DVM, CPA, CFA, PE, RPh, DNP among them — as a field of their own, so they no longer have to be mistaken for a generational suffix or left stuck on the lastname.

```php
// New
$result = (new NameParser())->parse('Dr. John Q. Smith Jr, PhD');

$result->getSuffix();       // 'Jr'
$result->getCredentials();  // 'PhD'
$result->hasCredentials();  // true
(string) $result;           // 'Dr. John Q. Smith Jr PhD'
```
**Previously:** not available.

### Smaller additions
- **Zero third-party runtime dependencies** — `composer.json` requires only `php >=8.4.0`. A `theiconic/name-parser` dependency present during early development was removed once `NameParser` was written natively.
- `getStreetName(false)` returns the bare street name with the directional stripped; `getStreetName()` re-applies it in its original prefix or suffix position (`N Elm` vs `Elm N`).
- `getNickname(true)` returns the nickname wrapped in parentheses, matching how `__toString()` renders it.
- Each `parse()` builds a fresh result, so re-parsing on the same instance can never append onto or overwrite a prior one — the earlier result object stays valid.
- Multi-word state and province names resolve (`New York`, `District of Columbia`, `Prince Edward Island`), not just two-letter codes.
- Country is recognized only from an unambiguous standalone segment (`USA`, `United States`, `CAN`, `Canada`); a bare two-letter `CA` is never promoted to a country.
- `AddressParser::parseStreetAddress()` parses just a street line and returns the street fields as an array.

---

