<?php
declare(strict_types=1);


namespace Cratia\Rest\Handlers;

use Cratia\Rest\Dependencies\DebugBag;
use Psr\Container\ContainerInterface;

/**
 * Class ShutdownHandler
 * @package Cratia\Rest\Handlers
 */
class ShutdownHandler
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * ShutdownHandler constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function __invoke()
    {
        $error = error_get_last();
        if (!is_null($error) && is_array($error)) {
            ob_end_clean();
            header('Content-Type: application/json');
            http_response_code(500);
            $payload['errorType'] = "FATAL ERROR: {$error['message']}. ";
            $payload['error'] = $error;

            if ($this->getContainer()->has(DebugBag::class)) {
                $payload['debug'] = $this->getContainer()->get(DebugBag::class);
            }

            die(json_encode($payload, JSON_PRETTY_PRINT));
        }
    }
}