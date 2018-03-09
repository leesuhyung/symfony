<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class UserFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        Fixtures::load(
            __DIR__.'/user.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }

}