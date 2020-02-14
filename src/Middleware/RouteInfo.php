<?php
declare(strict_types=1);


namespace Cratia\Rest\Middleware;


use Cratia\Rest\Dependencies\AppManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Routing\Route;
use Slim\Routing\RouteContext;

/**
 * Class RouteInfo
 * @package Cratia\Rest\Middleware
 */
class RouteInfo implements MiddlewareInterface
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
        /** @var RouteContext $routeContext */
        $routeContext = RouteContext::fromRequest($request);
        /** @var Route $route */
        $route = $routeContext->getRoute();

        if ($this->getContainer()->has(AppManager::class) &&
            ($route instanceof RouteInterface)
        ) {
            /** @var AppManager $appManager */
            $appManager = $this->getContainer()->has(AppManager::class);
            $appManager->setRoute($route);
        }

        $response = $handler->handle($request);
        return $response;
    }
}