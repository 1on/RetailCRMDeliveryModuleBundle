<?php

namespace RetailCrm\DeliveryModuleBundle;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;


use RetailCrm\DeliveryModuleBundle\Model\ResponseResult;

class SerializeListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ['event' => Events::PRE_SERIALIZE, 'method' => 'onPreSerialize', 'class' => ResponseResult::class],
        ];
    }

    public function onPreSerialize(PreSerializeEvent $event)
    {
        if (is_object($event->getObject())) {
            $event->setType(get_class($event->getObject()));
        } else {
            $event->setType('string');
        }
    }
}
