<pre>
     _                 _                                  
 ___(_)_ __ ___  _ __ | | ___  _ __     ___ ___  _ __ ___ 
/ __| | '_ ` _ \| '_ \| |/ _ \| '_ \   / __/ _ \| '__/ _ \
\__ \ | | | | | | |_) | | (_) | | | | | (_| (_) | | |  __/
|___/_|_| |_| |_| .__/|_|\___/|_| |_|  \___\___/|_|  \___|
                |_|                                       
</pre>

# Introduction

The simplon/core package is a strongly opinionated set of libraries which forms the core of a component based app.
The package requires [PHP7.1+](https://github.com/tpunt/PHP7-Reference/blob/master/php71-reference.md) and is built
against [PSR-7](http://www.php-fig.org/psr/psr-7/) in conjunction with middleware layers.

-------------------------------------------------

# App Structure

An app is mainly made up by some app-wide classes but mainly by its components which are tiny apps in itself.
The general idea is that the app will be easier to communicate and to maintain if its broken down in smaller
pieces hence the focus on components. Further, the app works with inheritence when it comes to the modules of
`config` and `locale`. Inheritence goes from app-level to the component so that the component has access to
app-wide data.

The main concept is `MVC` which means that your `controller` will receive a request, process the data with the
help of whatever is needed and pass it
on to a view or redirect to another resource. Simplon\Core distinguishes between two types of controller:
`ViewController` is the first type which is ment to handle requests which should result in a website. The second
type is a `RestController` which is ment to handle requests for any api related data. Latter will respond with
`JSON structured` data.

Components can only communicate via `events`. They can either `pull` information by other component's `offers`
or `subscribe` to events. Components describe their events in their own events class. `Offers` or `Subscriptions`
are defined as class constants.

It's important to note that all requests will run through a number of pre-defined `Middleware` with the
`RouteMiddleware` as the only required one since it handles all incoming requests.

## Registry

Each component needs to be registered via its own registry class. It requires a method for receiving
the `Context` class and can take optionally methods for referencing `Routes`, `Authentication rules` and
`Events` definition.

## Context

App and components have `Context` classes which hold all essential instances. Essential means instances which
are shared among different classes within the app respectively the component. For example, if you have a storage
for a component you would put the instance creation of that storage in the components context class. Context
classes hold also references to your config- and locale-data.

## Routes

All component related routes are defined in a component based `Route` class. This class holds all route
`patterns` and static methods which are used to build corresponding routes.

## Storage

Storage is handled via `CRUD` classes. Only `MySQL` adapter is available at the moment. A storage is
described by its storage- and model class. If you  want to interact with your data you should go through the
storage class and avoid direct access.

## Outgoing requests

Component related `outgoing requests` are all collected within its own `Requests` class, mainly to aid
transparency and structure. This class is obviously only needed if you have any type of these requests.

-------------------------------------------------

# Bootstrap

Our bootstrap holds all registered components, middlewares and kicks-off the core. The an example taken
from the [skeleton repo](https://github.com/fightbulc/simplon_core_skeleton):

```php
//
// enforce typed
//

declare(strict_types=1);

use App\AppContext;
use App\Components\Contents\ContentsRegistry;
use App\Components\Simple\SimpleRegistry;
use Simplon\Core\Core;
use Simplon\Core\Middleware\ExceptionMiddleware;
use Simplon\Core\Middleware\LocaleMiddleware;
use Simplon\Core\Middleware\RouteMiddleware;

//
// loads optimised composer when getenv('APP_ENV') !== 'dev'
//

require __DIR__ . '/../vendor/simplon/core/src/autoload.php';

//
// instantiate AppContext
//

$appContext = new AppContext();

//
// components queue
//

$components = [
    new ContentsRegistry($appContext),
    new SimpleRegistry($appContext),
];

//
// middleware queue
//

$middleware = [
    new ExceptionMiddleware(),
    new LocaleMiddleware(),
    new RouteMiddleware($components),
];

//
// run core with app data
//

(new Core())->withSession(60)->run($middleware);
```

-------------------------------------------------

# Middleware

Middleware helps us to handle/structure our request/response processing. It is also some sort of simplification by
pre-processing the request e.g. for authentication reasons before it hits the actual controller. Lastly, it's a great
tool due to its scalability and flexiblity. So far there are four classes which come with the core:

## Exception

Wraps all following processing and handles exceptions with [Whoops](https://github.com/filp/whoops).

## Locale

If integrated it will detect a two-letter defined locale within the requesting route (e.g. /en/) or even a region specific
locale with additional three-letters (e.g. /en-us/). If latter is the case the region specific file (e.g. `en-us-locale.php`)
will inherit from the main locale (e.g. `en-locale.php`). This middleware expects an array of accepted locale codes
(e.g. `['en', 'de']`) but will fallback to `['en']` if non is given.

## Route

The RouteMiddleware will check the requested route against all defined routes (only registered components).
It will also kick-off the `event handling` for these components.

## Auth

AuthMiddleware aids authentication against certain `routes`, `user roles` and `temporary tokens`. It requires the
an `AuthConfig` object which holds all required data. Here is an example combined with the middleware queue:

```php
//
// Auth Config
//

$authConfig = new AuthConfig(
	$appContext->getSessionStorage(), // storage for our auth data
	new UserSessionData(), 			  // object for a users auth data
	AuthRoutes::toSignIn() 			  // where to send the user if auth failed
);

// handle a recognised temporary token in your request e.g. ?token=ABCD1234
// is used to give one-time access to the app for 3rd-party services such as facebook

$authConfig->setCallbackVerifyToken(
	function(string $token)
	{
		// check token if valid and
		// return bool to indicate result
	}
);

// load defined authenticated routes from registries

$authConfig->loadRoutesFromComponents($components);

//
// middleware queue
//

$middleware = [
    new ExceptionMiddleware(),
    new LocaleMiddleware(),
    new AuthMiddleware($authConfig),
    new RouteMiddleware($components),
];
```

-------------------------------------------------

# Views