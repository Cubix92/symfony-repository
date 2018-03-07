<?php

namespace AppBundle\Service;

use AppBundle\Entity\Log;

class Logger
{
    protected $tokenStorage;

    protected $entityManager;

    public function __construct($entityManager, $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function log($message)
    {
        $usr = $this->tokenStorage->getToken()->getUser();

        $log = new Log();
        $log->setName($message)
            ->setDatetime(date('Y-m-d H:i:s'))
            ->setUser($usr->getUsername());

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}