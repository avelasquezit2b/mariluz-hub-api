<?php

namespace App\Repository;

use App\Entity\PackPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PackPrice>
 *
 * @method PackPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackPrice[]    findAll()
 * @method PackPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackPrice::class);
    }

//    /**
//     * @return PackPrice[] Returns an array of PackPrice objects
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

//    public function findOneBySomeField($value): ?PackPrice
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
