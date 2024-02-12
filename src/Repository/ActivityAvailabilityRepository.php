<?php

namespace App\Repository;

use App\Entity\ActivityAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityAvailability>
 *
 * @method ActivityAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityAvailability[]    findAll()
 * @method ActivityAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityAvailability::class);
    }

//    /**
//     * @return ActivityAvailability[] Returns an array of ActivityAvailability objects
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

//    public function findOneBySomeField($value): ?ActivityAvailability
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
