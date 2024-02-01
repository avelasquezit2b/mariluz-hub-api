<?php

namespace App\Repository;

use App\Entity\HotelCondition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HotelCondition>
 *
 * @method HotelCondition|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelCondition|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelCondition[]    findAll()
 * @method HotelCondition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelConditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HotelCondition::class);
    }

//    /**
//     * @return HotelCondition[] Returns an array of HotelCondition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HotelCondition
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
