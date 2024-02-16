<?php

namespace App\Repository;

use App\Entity\ThemeListModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ThemeListModule>
 *
 * @method ThemeListModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThemeListModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThemeListModule[]    findAll()
 * @method ThemeListModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeListModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThemeListModule::class);
    }

//    /**
//     * @return ThemeListModule[] Returns an array of ThemeListModule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ThemeListModule
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
