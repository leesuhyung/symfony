<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Board;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class GetDateListener
{
    private $logger;

    /**
     * GetDateListener constructor.
     * @param $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Board) {
            $date = new \DateTime();
            $date = $date->format('Y-m-d H:i:s');
            $this->logger->info('GetDate is '. $date);
        }
    }
}