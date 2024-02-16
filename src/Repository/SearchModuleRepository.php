<?php

namespace App\Repository;

use App\Entity\SearchModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchModule>
 *
 * @method SearchModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchModule[]    findAll()
 * @method SearchModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchModule::class);
    }

//    /**
//     * @return SearchModule[] Returns an array of SearchModule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SearchModule
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
