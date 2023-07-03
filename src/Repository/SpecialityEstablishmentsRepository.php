<?php

namespace App\Repository;

use App\Entity\SpecialityEstablishments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpecialityEstablishments>
 *
 * @method SpecialityEstablishments|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecialityEstablishments|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecialityEstablishments[]    findAll()
 * @method SpecialityEstablishments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialityEstablishmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecialityEstablishments::class);
    }

    public function save(SpecialityEstablishments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SpecialityEstablishments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SpecialityEstablishments[] Returns an array of SpecialityEstablishments objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SpecialityEstablishments
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
