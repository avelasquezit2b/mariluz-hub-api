<?php

namespace App\Repository;

use App\Entity\ActivitySchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivitySchedule>
 *
 * @method ActivitySchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivitySchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivitySchedule[]    findAll()
 * @method ActivitySchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivitySchedule::class);
    }

//    /**
//     * @return ActivitySchedule[] Returns an array of ActivitySchedule objects
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

//    public function findOneBySomeField($value): ?ActivitySchedule
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
