<?php
declare(strict_types=1);

use Cratia\Rest\Dependencies\DebugBag;
use Cratia\Rest\Dependencies\ErrorBag;
use Cratia\Rest\Dependencies\ErrorManager;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $logger->pushProcessor(new UidProcessor());
            $logger->pushProcessor(new MemoryUsageProcessor());
            $logger->pushProcessor(new IntrospectionProcessor());
            $logger->pushProcessor(new WebProcessor());

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        ErrorManager::class => function (ContainerInterface $c) {
            return ErrorManager::getInstance();
        },

        ErrorBag::class => function (ContainerInterface $c) {
            return ErrorBag::getInstance();
        },

        DebugBag::class => function (ContainerInterface $c) {
            return DebugBag::getInstance();
        }
    ]);
};
