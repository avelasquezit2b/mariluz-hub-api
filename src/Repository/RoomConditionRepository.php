<?php

namespace App\Repository;

use App\Entity\RoomCondition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomCondition>
 *
 * @method RoomCondition|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomCondition|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomCondition[]    findAll()
 * @method RoomCondition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomConditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomCondition::class);
    }

//    /**
//     * @return RoomCondition[] Returns an array of RoomCondition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RoomCondition
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
