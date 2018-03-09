<?php
namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Board;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;

class BoardSerializerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'format' => 'json',
            ]
        ];
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();
        $object = $event->getObject();

        if ($object instanceof Board) {
            $searchDate = new \DateTime();
            $visitor->setData('searchDate', $searchDate);
        }
    }
}