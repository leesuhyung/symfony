<?php
namespace AppBundle\Service;

use AppBundle\Entity\Board;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class BoardService
{
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function create(Board $board, User $user)
    {
        $board->setUser($user);
        $board->setEntity($board->getEntity());
        $board->setTitle($board->getTitle());
        $board->setContents($board->getContents());
        $this->om->persist($board);
        $this->om->flush();

        return $board;
    }

    public function update(Board $board)
    {
        $board->setEntity($board->getEntity());
        $board->setTitle($board->getTitle());
        $board->setContents($board->getContents());
        $board->setUpdatedAt(new \DateTime());
        $this->om->persist($board);
        $this->om->flush();

        return $board;
    }

    public function delete(Board $board)
    {
        $this->om->remove($board);
        $this->om->flush();
    }
}