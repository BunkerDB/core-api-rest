<?php
declare(strict_types=1);


namespace Cratia\Rest;

use Cratia\Rest\Interfaces\IContext;
use DateTime;

/**
 * Class ContextNull
 * @package Cratia\Rest
 */
class ContextNull implements IContext
{

    /**
     * @return false|string
     */
    public function getOtype()
    {
        return false;
    }

    /**
     * @return false|string
     */
    public function getOid()
    {
        return false;
    }

    /**
     * @return false|string
     */
    public function getPeriodId()
    {
        return false;
    }

    /**
     * @return DateTime|false
     */
    public function getPeriodStart()
    {
        return false;
    }

    /**
     * @return DateTime|false
     */
    public function getPeriodEnd()
    {
        return false;
    }
}