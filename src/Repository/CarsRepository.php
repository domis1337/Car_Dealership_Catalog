<?php

namespace App\Repository;

use App\Entity\Cars;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cars|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cars|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cars[]    findAll()
 * @method Cars[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cars::class);
    }

    /**
     * @return Cars[]
     */
    public function findByBrandField($value, $column)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.brand like :val')
			->setParameter('val', '%'.$value.'%')
            ->orderBy('c.'.$column, 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
	
	public function findOneByIdField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id like :val')
			->setParameter('val', '%'.$value.'%')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
    
}
