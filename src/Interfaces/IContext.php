<?php
declare(strict_types=1);


namespace Cratia\Rest\Interfaces;


use DateTime;

/**
 * Interface IContext
 * @package Cratia\Rest\Interfaces
 */
interface IContext
{
    const OID = 'oid';
    const OTYPE = 'otype';
    const PERIOD_ID = 'period_id';
    const PERIOD_START = 'period_start';
    const PERIOD_END = 'period_end';
    const CONTEXT_HEADER = 'HTTP_BAPI_CONTEXT';

    /**
     * @return false|string
     */
    public function getOtype();

    /**
     * @return false|string
     */
    public function getOid();

    /**
     * @return false|string
     */
    public function getPeriodId();

    /**
     * @return DateTime|false
     */
    public function getPeriodStart();

    /**
     * @return DateTime|false
     */
    public function getPeriodEnd();
}