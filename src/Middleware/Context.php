<?php
declare(strict_types=1);


namespace Cratia\Rest\Middleware;

use Cratia\Rest\ContextNull;
use Cratia\Rest\Dependencies\AppManager;
use Cratia\Rest\Interfaces\IContext;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Context
 * @package Cratia\Rest\Middleware
 */
class Context implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Context constructor.
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

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->process($request, $handler);
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->getContainer()->has(AppManager::class)) {
            /** @var AppManager $appManager */
            $appManager = $this->getContainer()->get(AppManager::class);
            if ($request->hasHeader(IContext::CONTEXT_HEADER)) {
                $appManager->setContext(\Cratia\Rest\Context::createByRequest($request));
            } else {
                $appManager->setContext(new ContextNull());
            }
        }
        $response = $handler->handle($request);
        return $response;
    }
}