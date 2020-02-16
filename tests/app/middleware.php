<?php
declare(strict_types=1);


use Cratia\Rest\Middleware\Context;
use Cratia\Rest\Middleware\RouteInfo;
use Slim\App;

return function (App $app) {
    $app->add(RouteInfo::class);
    $app->add(Context::class);
};
