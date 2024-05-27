<?php

namespace App\Repository;

use App\Entity\AboutUsModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AboutUsModule>
 *
 * @method AboutUsModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method AboutUsModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method AboutUsModule[]    findAll()
 * @method AboutUsModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AboutUsModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AboutUsModule::class);
    }

//    /**
//     * @return AboutUsModule[] Returns an array of AboutUsModule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AboutUsModule
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
