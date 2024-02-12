<?php

namespace App\Repository;

use App\Entity\RoomDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomDiscount>
 *
 * @method RoomDiscount|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomDiscount|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomDiscount[]    findAll()
 * @method RoomDiscount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomDiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomDiscount::class);
    }

//    /**
//     * @return RoomDiscount[] Returns an array of RoomDiscount objects
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

//    public function findOneBySomeField($value): ?RoomDiscount
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
