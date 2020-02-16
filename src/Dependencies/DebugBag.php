<?php
declare(strict_types=1);


namespace Cratia\Rest\Dependencies;


use Cratia\Rest\Common\Functions;
use JsonSerializable;
use SplObjectStorage;
use stdClass;

/**
 * Class DebugBag
 * @package Cratia\Rest\Dependencies
 */
class DebugBag extends SplObjectStorage implements JsonSerializable
{
    /**
     * @var self
     */
    private static $_instance;

    /**
     * self constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return $this
     */
    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $return = [];
        $this->rewind();
        foreach ($this as $object) {
            if ($object instanceof JsonSerializable) {
                $return[] = $object;
            } elseif (($_ = json_encode($object)) !== false &&
                json_last_error() === JSON_ERROR_NONE
            ) {
                $return[] = $object;
            }
        }
        return $return;
    }

    /**
     * @param $subject
     * @param $time
     * @return self
     */
    public function addRunTime($subject, $time): self
    {
        $performance = new stdClass;
        $performance->subject = $subject;
        $performance->run_time = Functions::pettyRunTime($time);
        $performance->memmory = intval(memory_get_usage() / 1024 / 1024) . ' MB';
        $this->attach($performance);
        return $this;
    }
}