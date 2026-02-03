<?php

namespace App\Repository;

use App\Entity\Flash;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Flash>
 */
class FlashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Flash::class);
    }


    public function paginateFlashs(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder("r")
            ->where("r.TattooType = :type")
            ->setParameter("type", "Flash"),
            $page,
            8,
            [
                "sort_field_name" => "category_id",
                "sort_direction_name" => "ASC"
            ]
        );
    }

    // public function paginateWannaDos(int $page): PaginationInterface
    // {
    //     return $this->paginator->paginate(
    //         $this->createQueryBuilder("r")
    //         ->where("r.TattooType = :type")
    //         ->setParameter("type", "WannaDo"),
    //         $page,
    //         99,
    //         [
    //             "sort_field_name" => "category_id",
    //             "sort_direction_name" => "ASC"
    //         ]
    //     );
    // }

    public function getWannaDo(): array
        {
            $em = $this->getEntityManager();

            $subQb = $em->createQueryBuilder();
            $subQb->select('s2')
                ->from('App\Entity\Flash', 's2')
                ->where('s2.TattooType = :type')
                ->setParameter("type", "WannaDo");

            return $subQb->getQuery()->getResult();
    }

    public function paginateFlashsWithCategories(int $page, array $categoryArray): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder("r")
            ->where("r.category IN (:categoryArray)")
            ->setParameter("categoryArray", $categoryArray),
            $page,
            8,
            [
                "sort_field_name" => "category_id",
                "sort_direction_name" => "ASC"
            ]
        );
    }

    public function paginateLikedFlashsWithCategories(int $page, int $user ,array $categoryArray): PaginationInterface
    {
        $params = new ArrayCollection([
            "categoryArray" => $categoryArray, 
            "userId" => $user,
        ]);

        return $this->paginator->paginate(
            $this->createQueryBuilder("r")
            ->join('r.users', 'se2')
            ->where("r.category IN (:categoryArray)")
            ->andWhere("se2.id = :userId")
            ->setParameter("categoryArray", $categoryArray)
            ->setParameter("userId", $user),
            $page,
            8,
            [
                "sort_field_name" => "category_id",
                "sort_direction_name" => "ASC"
            ]
        );
    }

    public function paginateLikedFlashs(int $page, int $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder("s2")
                ->join('s2.users', 'se2')
                ->where('se2.id = :userId')
                ->setParameter("userId", $user),
            $page,
            8,
            [
                "sort_field_name" => "category_id",
                "sort_direction_name" => "ASC"
            ]
        );
    }

        public function likedFLashs(int $user): array
        {
            $em = $this->getEntityManager();

            $subQb = $em->createQueryBuilder();
            $subQb->select('s2')
                ->from('App\Entity\Flash', 's2')
                ->join('s2.users', 'se2')
                ->where('se2.id = :userId')
                ->setParameter("userId", $user);

            return $subQb->getQuery()->getResult();
        }


    //    /**
    //     * @return Flash[] Returns an array of Flash objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Flash
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



    public function findByName(string $name)
    {
        
        return $this->createQueryBuilder('u')
            ->where('u.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }
}
