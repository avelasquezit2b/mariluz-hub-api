<?php

namespace App\Repository;

use App\Entity\HeroModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HeroModule>
 *
 * @method HeroModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeroModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeroModule[]    findAll()
 * @method HeroModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeroModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeroModule::class);
    }

//    /**
//     * @return HeroModule[] Returns an array of HeroModule objects
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

//    public function findOneBySomeField($value): ?HeroModule
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
