<?php

namespace App\Repository;

use App\Entity\ActivityFee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityFee>
 *
 * @method ActivityFee|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityFee|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityFee[]    findAll()
 * @method ActivityFee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityFeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityFee::class);
    }

//    /**
//     * @return ActivityFee[] Returns an array of ActivityFee objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ActivityFee
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
