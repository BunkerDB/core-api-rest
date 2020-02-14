<?php
declare(strict_types=1);


namespace Cratia\Rest\Handlers;

use Cratia\Rest\Actions\ActionError;
use Cratia\Rest\Actions\ActionErrorPayload;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Slim\Interfaces\CallableResolverInterface;
use Throwable;

/**
 * Class HttpErrorHandler
 * @package Cratia\Rest\Handlers
 */
class HttpErrorHandler extends SlimErrorHandler
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * HttpErrorHandler constructor.
     * @param ContainerInterface $container
     * @param CallableResolverInterface $callableResolver
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ContainerInterface $container, CallableResolverInterface $callableResolver, ResponseFactoryInterface $responseFactory)
    {
        $this->container = $container;
        $this->displayErrorDetails = $container->get('settings')['displayErrorDetails'];
        parent::__construct($callableResolver, $responseFactory);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = 500;
        $error = new ActionError(
            $statusCode,
            ActionError::SERVER_ERROR,
            'An internal error has occurred while processing your request.'
        );

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $error->setCode($exception->getCode());
            $error->setDescription($exception->getMessage());

            if ($exception instanceof HttpNotFoundException) {
                $error->setType(ActionError::RESOURCE_NOT_FOUND);
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $error->setType(ActionError::NOT_ALLOWED);
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $error->setType(ActionError::UNAUTHENTICATED);
            } elseif ($exception instanceof HttpForbiddenException) {
                $error->setType(ActionError::INSUFFICIENT_PRIVILEGES);
            } elseif ($exception instanceof HttpBadRequestException) {
                $error->setType(ActionError::BAD_REQUEST);
            } elseif ($exception instanceof HttpNotImplementedException) {
                $error->setType(ActionError::NOT_IMPLEMENTED);
            }
        }

        if (
            !($exception instanceof HttpException)
            && ($exception instanceof Exception || $exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $error
                ->setDescription($exception->getMessage())
                ->setExtraInfo(
                    [
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => $exception->getTrace(),
                    ]
                );
        }

        $payload = new ActionErrorPayload($this->getContainer(), $statusCode, $error);
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
