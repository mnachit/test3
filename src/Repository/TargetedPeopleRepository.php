<?php

namespace App\Repository;

use App\Entity\TargetedPeople;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TargetedPeople>
 *
 * @method TargetedPeople|null find($id, $lockMode = null, $lockVersion = null)
 * @method TargetedPeople|null findOneBy(array $criteria, array $orderBy = null)
 * @method TargetedPeople[]    findAll()
 * @method TargetedPeople[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TargetedPeopleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TargetedPeople::class);
    }

    public function save(TargetedPeople $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TargetedPeople $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TargetedPeople[] Returns an array of TargetedPeople objects
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

//    public function findOneBySomeField($value): ?TargetedPeople
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
