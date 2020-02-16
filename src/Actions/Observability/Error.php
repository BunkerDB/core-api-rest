<?php
declare(strict_types=1);


namespace Cratia\Rest\Actions\Observability;


use Cratia\Rest\Actions\Action;
use Exception;

/**
 * Class Error
 * @package App\Application\Actions\Observability
 */
class Error extends Action
{
    const ERROR_MESSAGE = __METHOD__;

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function action()
    {
        throw new Exception(self::ERROR_MESSAGE);
    }
}