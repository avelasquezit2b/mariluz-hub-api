<?php

namespace App\Repository;

use App\Entity\MenuModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MenuModule>
 *
 * @method MenuModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuModule[]    findAll()
 * @method MenuModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuModule::class);
    }

//    /**
//     * @return MenuModule[] Returns an array of MenuModule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MenuModule
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
