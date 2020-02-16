<?php
declare(strict_types=1);


namespace Cratia\Rest\Actions\Observability;


use Cratia\Rest\Actions\Action;

/**
 * Class Ping
 * @package Cratia\Rest\Actions\Observability
 */
class Ping extends Action
{
    const DATA = ['status' => 'ok'];
    /**
     * @inheritDoc
     */
    protected function action()
    {
        return self::DATA;
    }
}