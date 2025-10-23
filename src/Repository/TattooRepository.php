<?php

namespace App\Repository;

use App\Entity\Tattoo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tattoo>
 */
class TattooRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tattoo::class);
    }

    public function getSimulations($id)
    {
        return $this->createQueryBuilder('s')
        ->andWhere('s.user = :user')
        ->setParameter('user', $id)
        ->orderBy('s.finalPrice', 'DESC')
        ->getQuery()
        ->getResult();
    }
}
