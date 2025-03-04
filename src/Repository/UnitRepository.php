<?php

namespace App\Repository;

use App\Entity\Unit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Unit>
 */
class UnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unit::class);
    }

    public function findAvailableUnits(int $limit)
    {
        $query = '
        select unit.id
        from unit
        where unit.id NOT IN (
	        select unit_id
	        from commanded_unit
	        left join `order` on commanded_unit.orders_id = `order`.id
	        where end_date > current_date()
        )
        limit ' . $limit . ';';

        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        $result = $stmt->executeQuery()->fetchAllAssociative();

        return array_map(fn($unit) => $unit['id'], $result);
    }

//    /**
//     * @return Unit[] Returns an array of Unit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Unit
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
