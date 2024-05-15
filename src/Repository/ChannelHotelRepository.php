<?php

namespace App\Repository;

use App\Entity\ChannelHotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChannelHotel>
 *
 * @method ChannelHotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChannelHotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChannelHotel[]    findAll()
 * @method ChannelHotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChannelHotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChannelHotel::class);
    }

//    /**
//     * @return ChannelHotel[] Returns an array of ChannelHotel objects
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

//    public function findOneBySomeField($value): ?ChannelHotel
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
