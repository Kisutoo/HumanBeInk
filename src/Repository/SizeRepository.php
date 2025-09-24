<?php

namespace App\Repository;

use App\Entity\Size;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Size>
 */
class SizeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Size::class);
    }


    public function getClosestSizePlus(int $size)
    {
        return $this->createQueryBuilder('s')
               ->andWhere('s.size >= :size')
               ->setParameter('size', $size)
               ->orderBy('s.id', 'ASC')
               ->setMaxResults(1)
               ->getQuery()
               ->getResult()
           ;
    }
    public function getClosestSizeMinus(int $size)
    {
        return $this->createQueryBuilder('s')
               ->andWhere('s.size <= :size')
               ->setParameter('size', $size)
               ->orderBy('s.id', 'ASC')
               ->setMaxResults(1)
               ->getQuery()
               ->getResult()
           ;
    }
    //    /**
    //     * @return Size[] Returns an array of Size objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Size
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
