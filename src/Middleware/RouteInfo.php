<?php
declare(strict_types=1);


namespace Cratia\Rest\Middleware;


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
        if ($route instanceof RouteInterface) {
            AppManager::getInstance()->setRoute($route);
        }
        $response = $handler->handle($request);
        return $response;
    }
}