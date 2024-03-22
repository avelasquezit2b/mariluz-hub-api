<?php

namespace App\Repository;

use App\Entity\PackFee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PackFee>
 *
 * @method PackFee|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackFee|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackFee[]    findAll()
 * @method PackFee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackFeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackFee::class);
    }

//    /**
//     * @return PackFee[] Returns an array of PackFee objects
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

//    public function findOneBySomeField($value): ?PackFee
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
