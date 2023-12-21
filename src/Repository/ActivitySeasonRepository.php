<?php

namespace App\Repository;

use App\Entity\ActivitySeason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivitySeason>
 *
 * @method ActivitySeason|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivitySeason|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivitySeason[]    findAll()
 * @method ActivitySeason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivitySeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivitySeason::class);
    }

//    /**
//     * @return ActivitySeason[] Returns an array of ActivitySeason objects
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

//    public function findOneBySomeField($value): ?ActivitySeason
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
