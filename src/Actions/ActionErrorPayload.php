<?php
declare(strict_types=1);


namespace Cratia\Rest\Actions;


use Cratia\Rest\Dependencies\DebugBag;
use JsonSerializable;
use Psr\Container\ContainerInterface;

/**
 * Class ActionErrorPayload
 * @package Cratia\Rest\Actions
 */
class ActionErrorPayload implements JsonSerializable
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var ActionError|null
     */
    private $error;

    /**
     * @param ContainerInterface $container
     * @param int $statusCode
     * @param ActionError|null $error
     */
    public function __construct(
        ContainerInterface $container,
        int $statusCode = 500,
        ?ActionError $error = null
    )
    {
        $this->container = $container;
        $this->statusCode = $statusCode;
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return ActionError|null
     */
    public function getError(): ?ActionError
    {
        return $this->error;
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

        if ($this->error !== null) {
            $payload['error'] = $this->error;
        }

        if ($this->getContainer()->has(DebugBag::class)) {
            $payload['debug'] = $this->getContainer()->get(DebugBag::class);
        }

        return $payload;
    }
}