<?php

namespace App\Repository;

use App\Entity\ClasseTherapeutique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClasseTherapeutique>
 *
 * @method ClasseTherapeutique|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClasseTherapeutique|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClasseTherapeutique[]    findAll()
 * @method ClasseTherapeutique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClasseTherapeutiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClasseTherapeutique::class);
    }

    public function save(ClasseTherapeutique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClasseTherapeutique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ClasseTherapeutique[] Returns an array of ClasseTherapeutique objects
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

//    public function findOneBySomeField($value): ?ClasseTherapeutique
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
