<?php
declare(strict_types=1);


namespace Cratia\Rest\Actions;

use Cratia\Pipeline;
use Cratia\Rest\Dependencies\DebugBag;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Slim\Exception\HttpBadRequestException;

/**
 * Class Action
 * @package Cratia\Rest\Actions
 */
abstract class Action
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $args;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Action constructor.
     * @param ContainerInterface $container
     * @param LoggerInterface|null $logger
     */
    public function __construct(ContainerInterface $container, ?LoggerInterface $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return Pipeline::try(function () {
            })
                ->tap(function () {
                    $this->log(LogLevel::INFO, get_class($this) . '::__invoke(...)');
                })
                ->then(function () {
                    return $this->invokeAction();
                })
                ->then(function ($data = null) {
                    return $this->createActionDataPayload($data);
                })
                ->then(function (ActionDataPayload $payload) {
                    return $this->createResponse($payload);
                })
                ->then(function (Response $response) {
                    return $response;
                })
                ->catch(function (Exception $e) {
                    throw $e;
                })
            ();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array|object
     * @throws Exception
     */
    abstract protected function action();

    /**
     * @return array|object
     * @throws Exception
     */
    protected function invokeAction()
    {
        try {
            $time = -microtime(true);
            $result = $this->action();
            $time += microtime(true);
            $this->addRunTime($time);
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * @return array
     * @throws HttpBadRequestException
     */
    protected function getFormData()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpBadRequestException($this->getRequest(), 'Malformed JSON input.');
        }

        return $input;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->getRequest(), "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param array|object|null $data
     * @return ActionDataPayload
     */
    protected function createActionDataPayload($data = null): ActionDataPayload
    {
        return new ActionDataPayload($this->getContainer(), $data, 200);
    }

    /**
     * @param ActionDataPayload $payload
     * @return Response
     */
    protected function createResponse(ActionDataPayload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->getResponse()->getBody()->write($json);
        return $this->getResponse()->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param $time
     */
    protected function addRunTime($time): void
    {
        if ($this->getContainer()->has(DebugBag::class)) {
            /** @var DebugBag $debugBag */
            $debugBag = $this->getContainer()->get(DebugBag::class);
            $debugBag->addRunTime(get_class($this) . '::action()', $time);
        }
    }

    /**
     * @param string $level
     * @param string $message
     */
    protected function log(string $level, string $message = ''): void
    {
        $this->getLogger()->log($level, $message);
    }
}