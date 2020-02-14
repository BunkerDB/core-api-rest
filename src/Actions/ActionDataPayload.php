<?php
declare(strict_types=1);


namespace Cratia\Rest\Actions;

use Cratia\Rest\Dependencies\DebugBag;
use Cratia\Rest\Dependencies\ErrorBag;
use JsonSerializable;
use Psr\Container\ContainerInterface;

/**
 * Class ActionDataPayload
 * @package Cratia\Rest\Actions
 */
class ActionDataPayload implements JsonSerializable
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array|object|null
     */
    private $data;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     * @param int $statusCode
     * @param array|object $data
     */
    public function __construct(
        ContainerInterface $container,
        $data,
        int $statusCode = 200
    )
    {
        $this->container = $container;
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array|object|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $payload = [
            'statusCode' => $this->statusCode,
        ];

        if (!is_null($this->data)) {
            $payload['data'] = $this->data;
        } else {
            $payload['data'] = [];
        }

        if ($this->getContainer()->has(ErrorBag::class)) {
            $payload['error'] = $this->getContainer()->get(ErrorBag::class);
        }

        if ($this->getContainer()->has(DebugBag::class)) {
            $payload['debug'] = $this->getContainer()->get(DebugBag::class);
        }

        return $payload;
    }
}
