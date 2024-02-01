<?php

namespace App\Repository;

use App\Entity\CancellationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CancellationType>
 *
 * @method CancellationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CancellationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CancellationType[]    findAll()
 * @method CancellationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CancellationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CancellationType::class);
    }

//    /**
//     * @return CancellationType[] Returns an array of CancellationType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CancellationType
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
