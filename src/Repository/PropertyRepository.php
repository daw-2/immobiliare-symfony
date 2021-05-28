<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function findAllGreaterThanPrice($price)
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('c')
            ->innerJoin('p.category', 'c')
            ->where('p.price > :price')
            ->setParameter('price', $price * 100)
            ->orderBy('p.price', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function findAllWithFilters($surface, $budget, $category)
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('c')
            ->innerJoin('p.category', 'c')
            ->orderBy('p.id', 'DESC');

        if ($surface) {
            $qb->andWhere('p.surface > :surface')->setParameter('surface', $surface);
        }

        if ($budget) {
            $qb->andWhere('p.price < :budget')->setParameter('budget', $budget * 100);
        }

        if ($category) {
            $qb->andWhere('p.category = :category')->setParameter('category', $category);
        }

        return $qb->getQuery()->getResult();
    }

    public function search($search)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.name LIKE :search')
            ->setParameter('search', '%'.$search.'%');

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
