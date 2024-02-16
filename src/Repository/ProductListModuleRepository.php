<?php

namespace App\Repository;

use App\Entity\ProductListModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductListModule>
 *
 * @method ProductListModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductListModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductListModule[]    findAll()
 * @method ProductListModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductListModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductListModule::class);
    }

//    /**
//     * @return ProductListModule[] Returns an array of ProductListModule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductListModule
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
