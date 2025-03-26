<?php

namespace App\Repository;

use App\Entity\State;
use App\Entity\Unit;
use App\Entity\Usage;
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

    public function turnOnUnit(int $unitId): void
    {
        $entityManager = $this->getEntityManager();
        $unit = $this->find($unitId);

        if ($unit) {
            $state = $this->getEntityManager()->getRepository(State::class)->findOneBy(['state' => 'Allumée']);
            $unit->setState($state);
            $entityManager->persist($unit);
            $entityManager->flush();
        }
    }

    public function turnOffUnit(int $unitId): void
    {
        $entityManager = $this->getEntityManager();
        $unit = $this->find($unitId);

        if ($unit) {
            $state = $this->getEntityManager()->getRepository(State::class)->findOneBy(['state' => 'Éteinte']);
            $unit->setState($state);
            $entityManager->persist($unit);
            $entityManager->flush();
        }
    }

//    public function freeUnits(): void
//    {
//        $conn = $this->getEntityManager()->getConnection();
//        $sql = '
//            SELECT cu.unit_id
//            FROM commanded_unit cu
//            JOIN `order` o ON cu.orders_id = o.id
//            WHERE o.end_date < CURRENT_DATE();
//        ';
//        $stmt = $conn->prepare($sql);
//        $result = $stmt->executeQuery()->fetchAllAssociative();
//
//        $unitIds = array_map(fn($unit) => $unit['unit_id'], $result);
//
//        if (!empty($unitIds)) {
//            $units = $this->findBy(['id' => $unitIds]);
//
//            $usage = $this->getEntityManager()->getRepository(Usage::class)->findOneBy(['type' => 'Inactive']);
//
//            foreach ($units as $unit) {
//                $unit->setUsage($usage);
//                $this->getEntityManager()->persist($unit);
//            }
//
//            $this->getEntityManager()->flush();
//
//            $deleteSql = 'DELETE FROM commanded_unit WHERE unit_id IN (:unitIds)';
//            $deleteStmt = $conn->prepare($deleteSql);
//            $deleteStmt->executeQuery(['unitIds' => $unitIds]);
//        }
//    }

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
