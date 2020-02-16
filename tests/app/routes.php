<?php
declare(strict_types=1);

use Cratia\Rest\Actions\Observability\Ping;
use Cratia\Rest\Actions\Observability\Error;
use Slim\App;

return function (App $app) {
    $app->get('/ping', Ping::class);
    $app->get('/error', Error::class);
};
