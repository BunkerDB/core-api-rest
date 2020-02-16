<?php


namespace Tests\Cratia\Rest;


use Cratia\Rest\Context;
use Cratia\Rest\Interfaces\IContext;
use DateTime;
use Exception;

class ContextTest extends TestCase
{

    public function testConstructor1()
    {
        $context = new Context(false, false, false, false, false);
        $this->assertInstanceOf(Context::class, $context);
        $this->assertFalse($context->getOtype());
        $this->assertFalse($context->getOid());
        $this->assertFalse($context->getPeriodId());
        $this->assertFalse($context->getPeriodStart());
        $this->assertFalse($context->getPeriodEnd());
    }

    public function testConstructor2()
    {
        $context = new Context('brand', 1, false, false, false);
        $this->assertInstanceOf(Context::class, $context);
        $this->assertIsNotBool($context->getOtype());
        $this->assertIsNotBool($context->getOid());
        $this->assertEquals('brand', $context->getOtype());
        $this->assertEquals(1, $context->getOid());
        $this->assertFalse($context->getPeriodId());
        $this->assertFalse($context->getPeriodStart());
        $this->assertFalse($context->getPeriodEnd());
    }

    /**
     * @throws Exception
     */
    public function testCreateByData1()
    {
        $data =
            [
                Context::OTYPE => 'brand',
                Context::OID => 1,
                Context::PERIOD_ID => 'mnt',
                Context::PERIOD_START => '2020-01-01',
                Context::PERIOD_END => '2020-01-31',
            ];

        $context = Context::createByData($data);
        $this->assertInstanceOf(Context::class, $context);
        $this->assertIsNotBool($context->getOtype());
        $this->assertIsNotBool($context->getOid());
        $this->assertIsNotBool($context->getPeriodId());
        $this->assertIsNotBool($context->getPeriodStart());
        $this->assertIsNotBool($context->getPeriodEnd());

        $this->assertEquals($data[Context::OTYPE], $context->getOtype());
        $this->assertEquals($data[Context::OID], $context->getOid());
        $this->assertEquals($data[Context::PERIOD_ID], $context->getPeriodId());
        $this->assertEquals(new DateTime($data[Context::PERIOD_START]), $context->getPeriodStart());
        $this->assertEquals(new DateTime($data[Context::PERIOD_END]), $context->getPeriodEnd());
    }

    /**
     * @throws Exception
     */
    public function testCreateByData2()
    {
        $data =
            [
                Context::OTYPE => 'brand',
                Context::OID => 1,
                Context::PERIOD_ID => 'mnt',
                Context::PERIOD_START => new DateTime('2020-01-01'),
                Context::PERIOD_END => new DateTime('2020-01-31'),
            ];

        $context = Context::createByData($data);
        $this->assertInstanceOf(Context::class, $context);
        $this->assertIsNotBool($context->getOtype());
        $this->assertIsNotBool($context->getOid());
        $this->assertIsNotBool($context->getPeriodId());
        $this->assertIsNotBool($context->getPeriodStart());
        $this->assertIsNotBool($context->getPeriodEnd());

        $this->assertEquals($data[Context::OTYPE], $context->getOtype());
        $this->assertEquals($data[Context::OID], $context->getOid());
        $this->assertEquals($data[Context::PERIOD_ID], $context->getPeriodId());
        $this->assertEquals($data[Context::PERIOD_START], $context->getPeriodStart());
        $this->assertEquals($data[Context::PERIOD_END], $context->getPeriodEnd());
    }


    public function testCreateByData3()
    {
        $data =
            [
                Context::OTYPE => 'brand',
                Context::OID => 1,
                Context::PERIOD_ID => 'mnt',
                Context::PERIOD_START => '2020-011',
                Context::PERIOD_END => '2020-011',
            ];

        $context = Context::createByData($data);
        $this->assertInstanceOf(Context::class, $context);
        $this->assertIsNotBool($context->getOtype());
        $this->assertIsNotBool($context->getOid());
        $this->assertIsNotBool($context->getPeriodId());
        $this->assertFalse($context->getPeriodStart());
        $this->assertFalse($context->getPeriodEnd());

        $this->assertEquals($data[Context::OTYPE], $context->getOtype());
        $this->assertEquals($data[Context::OID], $context->getOid());
        $this->assertEquals($data[Context::PERIOD_ID], $context->getPeriodId());
    }


    public function testCreateByData4()
    {
        $data =
            [
            ];

        $context = Context::createByData($data);
        $this->assertInstanceOf(Context::class, $context);
        $this->assertFalse($context->getOtype());
        $this->assertFalse($context->getOid());
        $this->assertFalse($context->getPeriodId());
        $this->assertFalse($context->getPeriodStart());
        $this->assertFalse($context->getPeriodEnd());
    }


    public function testCreateByRequest1()
    {
        $request = $this->createRequest(
            'GET',
            '/ping',
            [
                'HTTP_ACCEPT' => 'application/json',
                IContext::CONTEXT_HEADER => '{}'
            ]
        );

        $context = Context::createByRequest($request);
        $this->assertInstanceOf(Context::class, $context);
        $this->assertFalse($context->getOtype());
        $this->assertFalse($context->getOid());
        $this->assertFalse($context->getPeriodId());
        $this->assertFalse($context->getPeriodStart());
        $this->assertFalse($context->getPeriodEnd());
    }

    public function testCreateByRequest2()
    {
        $request = $this->createRequest(
            'GET',
            '/ping',
            [
                'HTTP_ACCEPT' => 'application/json',
                IContext::CONTEXT_HEADER => '{"otype": "brand", "oid":"1"}'
            ]
        );

        $context = Context::createByRequest($request);
        $this->assertInstanceOf(Context::class, $context);
        $this->assertFalse($context->getPeriodId());
        $this->assertFalse($context->getPeriodStart());
        $this->assertFalse($context->getPeriodEnd());
        $this->assertEquals('brand', $context->getOtype());
        $this->assertEquals(1, $context->getOid());
    }

    /**
     * @throws Exception
     */
    public function testCreateByRequest3()
    {
        $data =
            [
                Context::OTYPE => 'brand',
                Context::OID => 1,
                Context::PERIOD_ID => 'mnt',
                Context::PERIOD_START => '2020-01-01',
                Context::PERIOD_END => '2020-01-31',
            ];

        $request = $this->createRequest(
            'GET',
            '/ping',
            [
                'HTTP_ACCEPT' => 'application/json',
                IContext::CONTEXT_HEADER => json_encode($data)
            ]
        );

        $context = Context::createByRequest($request);
        $this->assertInstanceOf(Context::class, $context);
        $this->assertNotFalse($context->getOtype());
        $this->assertNotFalse($context->getOid());
        $this->assertNotFalse($context->getPeriodId());
        $this->assertNotFalse($context->getPeriodStart());
        $this->assertNotFalse($context->getPeriodEnd());

        $this->assertEquals($data[Context::OTYPE], $context->getOtype());
        $this->assertEquals($data[Context::OID], $context->getOid());
        $this->assertEquals($data[Context::PERIOD_ID], $context->getPeriodId());
        $this->assertEquals(new DateTime($data[Context::PERIOD_START]), $context->getPeriodStart());
        $this->assertEquals(new DateTime($data[Context::PERIOD_END]), $context->getPeriodEnd());
        $this->assertNotFalse(json_encode($context));
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }
}