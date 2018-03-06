<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BoardRepository extends EntityRepository
{
    public function findAllEntity($entity = null)
    {
        $qb = $this->createQueryBuilder('board')
            ->orderBy('board.id', 'DESC');

        if ($entity !== null) {
            $qb->andWhere('board.entity = :entity')
                ->setParameter('entity', $entity);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array $params
     * @return \Doctrine\ORM\Query
     */
    public function getFindAll(array $params)
    {
        $qb = $this->createQueryBuilder('board')
            ->orderBy('board.id', 'DESC');

        if (array_key_exists('entity', $params) && $params['entity']) {
            $qb->andWhere('board.entity = :entity')
                ->setParameter('entity', $params['entity']);
        }

        return $qb->getQuery();
    }
}