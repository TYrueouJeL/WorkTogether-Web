<?php

namespace App\Repository;

use App\Entity\CommandedUnit;
use App\Entity\Unit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandedUnit>
 */
class CommandedUnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandedUnit::class);
    }

    public function findActiveByUnit(Unit $unit)
    {
        $now = new \DateTime();
        
        return $this->createQueryBuilder('cu')
            ->join('cu.orders', 'o')
            ->andWhere('cu.unit = :unit')
            ->andWhere('o.endDate >= :now')
            ->setParameter('unit', $unit)
            ->setParameter('now', $now)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return CommandedUnit[] Returns an array of CommandedUnit objects
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

//    public function findOneBySomeField($value): ?CommandedUnit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
