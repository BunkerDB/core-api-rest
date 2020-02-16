<?php


namespace Tests\Cratia\Rest\Actions\Observability;


use Tests\Cratia\Rest\TestCase;

class ErrorTest extends TestCase
{
    public function testGet()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Cratia\Rest\Actions\Observability\Error::action::23");
        $this->expectExceptionCode(0);
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', '/error');
        $response = $app->handle($request);
    }
}