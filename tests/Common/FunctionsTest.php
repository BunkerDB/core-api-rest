<?php


namespace Tests\Cratia\Rest\Common;


use Cratia\Rest\Common\Functions;
use Tests\Cratia\Rest\TestCase;

class FunctionsTest extends TestCase
{
    public function testPettyRunTime1()
    {
        $time = Functions::pettyRunTime(0);

        $this->assertEquals('0 ms', $time);
    }

    public function testPettyRunTime2()
    {
        $time = Functions::pettyRunTime(0.5);

        $this->assertEquals('500 ms', $time);
    }

    public function testPettyRunTime3()
    {
        $time = Functions::pettyRunTime(1);

        $this->assertEquals('1 second', $time);
    }

    public function testPettyRunTime4()
    {
        $time = Functions::pettyRunTime(2);

        $this->assertEquals('2 seconds', $time);
    }

    public function testPettyRunTime5()
    {
        $time = Functions::pettyRunTime(-2);

        $this->assertEquals('-2000 ms', $time);
    }
}