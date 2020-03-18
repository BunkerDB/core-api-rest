<?php
declare(strict_types=1);


namespace Cratia\Rest\EventSubscribers;


use Cratia\ORM\Model\Events\Events;
use Cratia\Rest\Dependencies\ErrorBag;
use Doctrine\Common\EventSubscriber;
use stdClass;

/**
 * Class ActiveRecord
 * @package Cratia\Rest\EventSubscribers
 */
class ModelEventsOnError implements EventSubscriber
{
    /**
     * @var ErrorBag
     */
    private $errorBag;

    /**
     * EventSubscriberAdapter constructor.
     * @param ErrorBag $errorBag
     */
    public function __construct(ErrorBag $errorBag)
    {
        $this->errorBag = $errorBag;
    }

    public function __call($name, $arguments)
    {
        $attach = new stdClass();
        $attach->event = $name;
        $attach->payload = $arguments;
        $this->errorBag->attach($attach);
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [Events::ON_ERROR];
    }
}