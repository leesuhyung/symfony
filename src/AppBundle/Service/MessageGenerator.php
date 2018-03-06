<?php

namespace AppBundle\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getHappyMessage()
    {
        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');
        $this->logger->info('GetDate is '. $date);
    }
}