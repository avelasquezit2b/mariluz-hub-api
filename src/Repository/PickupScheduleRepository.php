<?php

namespace App\Repository;

use App\Entity\PickupSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PickupSchedule>
 *
 * @method PickupSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method PickupSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method PickupSchedule[]    findAll()
 * @method PickupSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PickupScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PickupSchedule::class);
    }

//    /**
//     * @return PickupSchedule[] Returns an array of PickupSchedule objects
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

//    public function findOneBySomeField($value): ?PickupSchedule
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
