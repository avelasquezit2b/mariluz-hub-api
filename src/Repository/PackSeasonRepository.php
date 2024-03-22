<?php

namespace App\Repository;

use App\Entity\PackSeason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PackSeason>
 *
 * @method PackSeason|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackSeason|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackSeason[]    findAll()
 * @method PackSeason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackSeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackSeason::class);
    }

//    /**
//     * @return PackSeason[] Returns an array of PackSeason objects
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

//    public function findOneBySomeField($value): ?PackSeason
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
