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
               ->orderBy('s.size', 'ASC')
               ->setMaxResults(1)
               ->getQuery()
               ->getResult()
           ;
    }
    
    public function getClosestSizeMinus(int $size)
    // Fonction qui permet de retourner l'enregistement le plus proche de la taille fournie
    // en paramètres (en allant chercher au dessus de celle-ci). 
    {
        return $this->createQueryBuilder('s')
               // Prépare le moule de la requête SQL
               ->andWhere('s.size <= :size')
               // On séléctionne les enregistrements dont la colonne taille est 
               // inférieur ou égal à la taille rentrée en paramètres
               ->setParameter('size', $size)
               // Lie le paramètre :size à la variable $size
               // Doctrine se charge d’échapper et de typer correctement la valeur
               ->orderBy('s.size', 'DESC')
               // Trie les résultats du plus grand au plus petit
               ->setMaxResults(1)
               // Ne conserve qu’un seul enregistrement (le plus proche inférieur)
               ->getQuery()
               ->getResult()
               // Exécute la requête compilée et renvoie le résultat
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
