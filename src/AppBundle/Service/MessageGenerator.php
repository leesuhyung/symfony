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
        $this->logger->info('About to find a happy message!');
    }
}