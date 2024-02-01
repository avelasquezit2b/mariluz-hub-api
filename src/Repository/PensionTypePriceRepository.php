<?php

namespace App\Repository;

use App\Entity\PensionTypePrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PensionTypePrice>
 *
 * @method PensionTypePrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method PensionTypePrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method PensionTypePrice[]    findAll()
 * @method PensionTypePrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PensionTypePriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PensionTypePrice::class);
    }

//    /**
//     * @return PensionTypePrice[] Returns an array of PensionTypePrice objects
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

//    public function findOneBySomeField($value): ?PensionTypePrice
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
