<?php
declare(strict_types=1);


namespace Cratia\Rest\Dependencies;

use Cratia\Rest\Interfaces\IContext;
use Slim\Interfaces\RouteInterface;

/**
 * Class AppManager
 * @package Cratia\Rest\Dependencies
 */
class AppManager
{
    /**
     * @var self
     */
    private static $_instance;

    /**
     * self constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return $this
     */
    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @var null|RouteInterface
     */
    private $route = null;

    /**
     * @var IContext
     */
    private $context = null;

    /**
     * @param RouteInterface $route
     * @return $this
     */
    public function setRoute(RouteInterface $route): AppManager
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return RouteInterface|null
     */
    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }

    /**
     * @param IContext $context
     * @return AppManager
     */
    public function setContext(IContext $context): AppManager
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return IContext
     */
    public function getContext(): IContext
    {
        return $this->context;
    }
}