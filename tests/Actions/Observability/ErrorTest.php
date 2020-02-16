<?php


namespace Tests\Cratia\Rest\Actions\Observability;


use Cratia\Rest\Actions\ActionError;
use Cratia\Rest\Actions\Observability\Error;
use Cratia\Rest\Handlers\HttpErrorHandler;
use Exception;
use Tests\Cratia\Rest\TestCase;

class ErrorTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGet1()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(Error::ERROR_MESSAGE);
        $this->expectExceptionCode(0);
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', '/error');
        $app->handle($request);
    }

    /**
     * @throws Exception
     */
    public function testGet2()
    {
        $app = $this->getAppInstance();

        // Create Error Handler
        $errorHandler = new HttpErrorHandler(
            $app->getContainer(),
            $app->getCallableResolver(),
            $app->getResponseFactory()
        );

        // Add Error Middleware
        $errorMiddleware = $app->addErrorMiddleware(
            $app->getContainer()->get('settings')['displayErrorDetails'],
            false,
            false
        );
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $request = $this->createRequest('GET', '/error');
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $payload = json_decode($payload, true);

        $this->assertEquals(500, $payload['statusCode']);
        $this->assertIsArray($payload['error']);
        $this->assertIsArray($payload['debug']);
        $this->assertEquals(ActionError::SERVER_ERROR, $payload['error']['type']);
        $this->assertEquals(Error::ERROR_MESSAGE, $payload['error']['description']);
    }
}