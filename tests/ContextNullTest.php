<?php


namespace Tests\Cratia\Rest;


use Cratia\Rest\ContextNull;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class ContextNullTest extends PHPUnit_TestCase
{
    public function testConstructor1() {
        $context = new ContextNull();

        $this->assertInstanceOf(ContextNull::class, $context);
        $this->assertFalse($context->getOtype());
        $this->assertFalse($context->getOid());
        $this->assertFalse($context->getPeriodId());
        $this->assertFalse($context->getPeriodStart());
        $this->assertFalse($context->getPeriodEnd());
    }
}