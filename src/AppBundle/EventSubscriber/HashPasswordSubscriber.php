<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class HashPasswordSubscriber implements EventSubscriber
{
    private $passwordEncoder;

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
        );
    }

    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $this->encodePassword($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof User) {
            $this->encodePassword($entity);
            $em = $args->getEntityManager();
            $meta = $em->getClassMetadata(get_class($entity));
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }

    public function encodePassword(User $user)
    {
        $encoded = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encoded);
    }
}