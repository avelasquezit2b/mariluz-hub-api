<?php

namespace App\Repository;

use App\Entity\ActivityBooking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityBooking>
 *
 * @method ActivityBooking|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityBooking|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityBooking[]    findAll()
 * @method ActivityBooking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityBookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityBooking::class);
    }

//    /**
//     * @return ActivityBooking[] Returns an array of ActivityBooking objects
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

//    public function findOneBySomeField($value): ?ActivityBooking
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
