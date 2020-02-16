<?php
declare(strict_types=1);


namespace Tests\Cratia\Rest\Actions\Observability;


use Cratia\Rest\Actions\ActionDataPayload;
use Cratia\Rest\Actions\Observability\Ping;
use Cratia\Rest\Dependencies\DebugBag;
use Cratia\Rest\Dependencies\ErrorBag;
use Tests\Cratia\Rest\TestCase;
use function DI\string;

class PingTest extends TestCase
{
    public function testGet()
    {
        $app = $this->getAppInstance();
        $expectedPayloadData = new ActionDataPayload($app->getContainer(), Ping::DATA, 200);

        $request = $this->createRequest('GET', '/ping');
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $payload = json_decode($payload, true);


        $this->assertEqualsCanonicalizing(json_encode($expectedPayloadData), json_encode($payload));
        $this->assertEquals(200, $payload['statusCode']);
        $this->assertEquals(Ping::DATA, $payload['data']);
        $this->assertEquals([], $payload['error']);
        $this->assertEmpty($payload['error']);
        $this->assertEquals($app->getContainer()->get(ErrorBag::class)->count(), count($payload['error']));
        $this->assertEquals($app->getContainer()->get(DebugBag::class)->count(), count($payload['debug']));
    }
}