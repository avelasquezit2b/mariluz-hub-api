<?php

namespace App\Repository;

use App\Entity\PensionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PensionType>
 *
 * @method PensionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PensionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PensionType[]    findAll()
 * @method PensionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PensionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PensionType::class);
    }

//    /**
//     * @return PensionType[] Returns an array of PensionType objects
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

//    public function findOneBySomeField($value): ?PensionType
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
