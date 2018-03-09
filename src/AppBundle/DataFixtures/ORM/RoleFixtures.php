<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class RoleFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        Fixtures::load(
            __DIR__.'/role.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }

    public function role($index)
    {
        $roleNames = [
            'ADMIN',
            'VISITOR',
            'OWNER',
            'PROGRAMMER',
        ];

        return $roleNames[$index - 1];
    }
}