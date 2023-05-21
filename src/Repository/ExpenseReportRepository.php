<?php

namespace App\Repository;

use App\Entity\ExpenseReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExpenseReport>
 *
 * @method ExpenseReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenseReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenseReport[]    findAll()
 * @method ExpenseReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenseReport::class);
    }

    public function save(ExpenseReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExpenseReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
