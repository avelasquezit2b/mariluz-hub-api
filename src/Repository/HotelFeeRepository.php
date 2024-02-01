<?php

namespace App\Repository;

use App\Entity\HotelFee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HotelFee>
 *
 * @method HotelFee|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelFee|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelFee[]    findAll()
 * @method HotelFee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelFeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HotelFee::class);
    }

//    /**
//     * @return HotelFee[] Returns an array of HotelFee objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HotelFee
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
