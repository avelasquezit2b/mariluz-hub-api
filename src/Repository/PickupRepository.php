<?php

namespace App\Repository;

use App\Entity\Pickup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pickup>
 *
 * @method Pickup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pickup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pickup[]    findAll()
 * @method Pickup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PickupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pickup::class);
    }

//    /**
//     * @return Pickup[] Returns an array of Pickup objects
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

//    public function findOneBySomeField($value): ?Pickup
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
