<?php

namespace App\Repository;

use App\Entity\ItineraryDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItineraryDay>
 *
 * @method ItineraryDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItineraryDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItineraryDay[]    findAll()
 * @method ItineraryDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItineraryDayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItineraryDay::class);
    }

//    /**
//     * @return ItineraryDay[] Returns an array of ItineraryDay objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ItineraryDay
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
