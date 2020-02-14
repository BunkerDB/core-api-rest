<?php
declare(strict_types=1);


namespace Cratia\Rest\Actions;

use JsonSerializable;
use stdClass;

/**
 * Class ActionError
 * @package Cratia\Rest\Actions
 */
class ActionError implements JsonSerializable
{
    public const BAD_REQUEST = 'BAD_REQUEST';
    public const INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    public const NOT_ALLOWED = 'NOT_ALLOWED';
    public const NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';
    public const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const SERVER_ERROR = 'SERVER_ERROR';
    public const UNAUTHENTICATED = 'UNAUTHENTICATED';
    public const VALIDATION_ERROR = 'VALIDATION_ERROR';
    public const VERIFICATION_ERROR = 'VERIFICATION_ERROR';

    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array|stdClass|JsonSerializable
     */
    private $extraInfo;

    /**
     * @param int $code
     * @param string $type
     * @param string|null $description
     */
    public function __construct(int $code, string $type, ?string $description)
    {
        $this->code = $code;
        $this->type = $type;
        $this->description = $description;
        $this->extraInfo = [];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return self
     */
    public function setDescription(?string $description = null): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param int $code
     * @return ActionError
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return array|JsonSerializable|stdClass
     */
    public function getExtraInfo()
    {
        return $this->extraInfo;
    }

    /**
     * @param array|JsonSerializable|stdClass $extraInfo
     * @return ActionError
     */
    public function setExtraInfo($extraInfo)
    {
        $this->extraInfo = $extraInfo;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $payload = [
            'type' => $this->type,
            'description' => $this->description,
        ];

        if (
            !is_null($this->getExtraInfo()) &&
            !empty($this->getExtraInfo()) &&
            (
                (is_object($this->getExtraInfo()) && ($this->getExtraInfo() instanceof JsonSerializable)) ||
                (is_array($this->getExtraInfo()))
            )
        ) {
            $payload['extra'] = $this->getExtraInfo();
        }

        return $payload;
    }
}