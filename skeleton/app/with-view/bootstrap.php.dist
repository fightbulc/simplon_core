<?php

//
// enforce typed
//

declare(strict_types=1);

use App\AppContext;
use Simplon\Core\Core;
use Simplon\Core\CoreContext;
use Simplon\Core\Middleware\ExceptionMiddleware;
use Simplon\Core\Middleware\RouteMiddleware;
use Simplon\Core\Storage\SessionHandler\SessionRedis;

require __DIR__ . '/../vendor/simplon/core/src/autoload.php';

//
// instantiate AppContext
//

$appContext = new AppContext();

//
// components queue
//

$components = [];

//
// middleware queue
//

$middleware = [
    (new ExceptionMiddleware())->setIsProduction(getenv('APP_ENV') !== CoreContext::APP_ENV_DEV),
    new RouteMiddleware($components),
];

//
// Redis session handler
//

$sessionHandler = getenv('REDIS_HOST') ? new SessionRedis(getenv('REDIS_HOST'), (int)getenv('REDIS_PORT')) : null;

//
// run core with app data
//

(new Core())->withSession(60, $sessionHandler)->run($middleware);