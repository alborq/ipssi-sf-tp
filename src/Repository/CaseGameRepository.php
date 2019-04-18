<?php

namespace App\Repository;

use App\Entity\CaseGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CaseGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method CaseGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method CaseGame[]    findAll()
 * @method CaseGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaseGameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CaseGame::class);
    }

    // /**
    //  * @return CaseGame[] Returns an array of CaseGame objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CaseGame
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
