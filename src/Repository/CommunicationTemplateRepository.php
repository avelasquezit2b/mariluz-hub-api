<?php

namespace App\Repository;

use App\Entity\CommunicationTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommunicationTemplate>
 *
 * @method CommunicationTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommunicationTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommunicationTemplate[]    findAll()
 * @method CommunicationTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunicationTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommunicationTemplate::class);
    }

//    /**
//     * @return CommunicationTemplate[] Returns an array of CommunicationTemplate objects
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

//    public function findOneBySomeField($value): ?CommunicationTemplate
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
