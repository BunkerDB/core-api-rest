<?php
declare(strict_types=1);


namespace Cratia\Rest\Dependencies;


use Cratia\Rest\Common\Functions;
use JsonSerializable;
use StdClass;

/**
 * Class DebugBag
 * @package Cratia\Rest\Dependencies
 */
class DebugBag extends Bag implements JsonSerializable
{
    /**
     * @param string $subject
     * @param $time
     * @return $this
     */
    public function addRunTime($subject, $time)
    {
        $performance = new StdClass;
        $performance->subject = $subject;
        $performance->run_time = Functions::pettyRunTime($time);
        $performance->memmory = intval(memory_get_usage() / 1024 / 1024) . ' MB';
        $this->attach($performance);
        return $this;
    }
}