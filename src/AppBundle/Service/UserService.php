<?php
namespace AppBundle\Service;


use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class UserService
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function create(User $user)
    {
        $user->setEmail($user->getEmail());
        $user->setName($user->getName());
        $user->setPassword($user->getPassword());
        $this->om->persist($user);
        $this->om->flush();

        return $user;
    }

    public function update(User $user)
    {
        $user->setEmail($user->getEmail());
        $user->setName($user->getName());
        $user->setPassword($user->getPassword());
        $user->setUpdatedAt(new \DateTime());
        $this->om->persist($user);
        $this->om->flush();

        return $user;
    }

    public function delete(User $user)
    {
        $this->om->remove($user);
        $this->om->flush();
    }
}