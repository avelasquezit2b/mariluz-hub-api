<?php

namespace App\Repository;

use App\Entity\OccupancyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OccupancyType>
 *
 * @method OccupancyType|null find($id, $lockMode = null, $lockVersion = null)
 * @method OccupancyType|null findOneBy(array $criteria, array $orderBy = null)
 * @method OccupancyType[]    findAll()
 * @method OccupancyType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OccupancyTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OccupancyType::class);
    }

//    /**
//     * @return OccupancyType[] Returns an array of OccupancyType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OccupancyType
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
