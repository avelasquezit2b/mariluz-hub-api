<?php

namespace App\Repository;

use App\Entity\ThemeImageDescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ThemeImageDescription>
 *
 * @method ThemeImageDescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThemeImageDescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThemeImageDescription[]    findAll()
 * @method ThemeImageDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeImageDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThemeImageDescription::class);
    }

//    /**
//     * @return ThemeImageDescription[] Returns an array of ThemeImageDescription objects
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

//    public function findOneBySomeField($value): ?ThemeImageDescription
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
