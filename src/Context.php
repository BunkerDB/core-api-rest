<?php
declare(strict_types=1);


namespace Cratia\Rest;

use Cratia\Rest\Interfaces\IContext;
use DateTime;
use Exception;
use JsonSerializable;
use Psr\Http\Message\ServerRequestInterface;
use stdClass;


/**
 * Class Context
 * @package Cratia\Rest
 */
class Context implements IContext, JsonSerializable
{
    /**
     * @var string|false
     */
    private $otype;

    /**
     * @var string|false
     */
    private $oid;

    /**
     * @var string|false
     * @values $period_id = cmp,mnt,ann,wkl
     */
    private $period_id;

    /**
     * @var DateTime|false
     */
    private $period_start;

    /**
     * @var DateTime|false
     */
    private $period_end;

    /**
     * Context constructor.
     * @param false|string $otype
     * @param false|string $oid
     * @param false|string $period_id
     * @param DateTime|false $period_start
     * @param DateTime|false $period_end
     */
    public function __construct($otype, $oid, $period_id, $period_start, $period_end)
    {
        $this->otype = $otype;
        $this->oid = $oid;
        $this->period_id = $period_id;
        $this->period_start = $period_start;
        $this->period_end = $period_end;
    }

    /**
     * @return false|string
     */
    public function getOtype()
    {
        return $this->otype;
    }

    /**
     * @return false|string
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * @return false|string
     */
    public function getPeriodId()
    {
        return $this->period_id;
    }

    /**
     * @return DateTime|false
     */
    public function getPeriodStart()
    {
        return $this->period_start;
    }

    /**
     * @return DateTime|false
     */
    public function getPeriodEnd()
    {
        return $this->period_end;
    }

    /**
     * @param ServerRequestInterface $request
     * @return IContext
     */
    public static function createByRequest(ServerRequestInterface $request): IContext
    {
        $contextHeader = $request->getHeader(self::CONTEXT_HEADER);
        if (is_array($contextHeader) && count($contextHeader) > 0 && isset($contextHeader[0]) && !empty($contextHeader[0])) {
            //$contextHeader = {"otype":"brand","oid":"-1","period_id":"mnt","period_start":"2020-01-01","period_end":"2020-01-31"}
            $contextHeader = json_decode(array_shift($contextHeader), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $contextHeader = false;
            }
        } else {
            $contextHeader = false;
        }

        if ($contextHeader !== false) {
            return Context::createByData($contextHeader);
        } else {
            return new ContextNull();
        }
    }

    /**
     * @param array $data
     * @return Context
     */
    public static function createByData(array $data): IContext
    {
        $payload = new stdClass();

        // OTYPE
        $payload->{self::OTYPE} = (isset($data[self::OTYPE]))
            ? $data[self::OTYPE]
            : false;

        // OID
        $payload->{self::OID} = (isset($data[self::OID]))
            ? $data[self::OID]
            : false;

        // PERIOD_ID
        $payload->{self::PERIOD_ID} = (isset($data[self::PERIOD_ID]))
            ? $data[self::PERIOD_ID]
            : false;

        // PERIOD_START
        try {
            if (isset($data[self::PERIOD_START])) {
                if ($data[self::PERIOD_START] instanceof DateTime) {
                    $payload->{self::PERIOD_START} = $data[self::PERIOD_START];
                } else {
                    $payload->{self::PERIOD_START} = new DateTime($data[self::PERIOD_START]);
                }
            } else {
                $payload->{self::PERIOD_START} = false;
            }
        } catch (Exception $e) {
            $payload->{self::PERIOD_START} = false;
        }

        // PERIOD_END
        try {
            if (isset($data[self::PERIOD_END])) {
                if ($data[self::PERIOD_END] instanceof DateTime) {
                    $payload->{self::PERIOD_END} = $data[self::PERIOD_END];
                } else {
                    $payload->{self::PERIOD_END} = new DateTime($data[self::PERIOD_END]);
                }
            } else {
                $payload->{self::PERIOD_END} = false;
            }
        } catch (Exception $e) {
            $payload->{self::PERIOD_END} = false;
        }

        return new self(
            $payload->{self::OTYPE},
            $payload->{self::OID},
            $payload->{self::PERIOD_ID},
            $payload->{self::PERIOD_START},
            $payload->{self::PERIOD_END}
        );
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
        return [
            self::OTYPE => $this->{self::OTYPE},
            self::OID => $this->{self::OID},
            self::PERIOD_ID => $this->{self::PERIOD_ID},
            self::PERIOD_START => $this->{self::PERIOD_START},
            self::PERIOD_END => $this->{self::PERIOD_END},
        ];
    }
}