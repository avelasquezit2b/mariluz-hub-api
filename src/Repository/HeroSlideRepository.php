<?php

namespace App\Repository;

use App\Entity\HeroSlide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HeroSlide>
 *
 * @method HeroSlide|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeroSlide|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeroSlide[]    findAll()
 * @method HeroSlide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeroSlideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeroSlide::class);
    }

//    /**
//     * @return HeroSlide[] Returns an array of HeroSlide objects
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

//    public function findOneBySomeField($value): ?HeroSlide
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
