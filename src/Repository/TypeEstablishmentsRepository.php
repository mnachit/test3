<?php

namespace App\Repository;

use App\Entity\TypeEstablishments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeEstablishments>
 *
 * @method TypeEstablishments|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeEstablishments|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeEstablishments[]    findAll()
 * @method TypeEstablishments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeEstablishmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeEstablishments::class);
    }

    public function save(TypeEstablishments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypeEstablishments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TypeEstablishments[] Returns an array of TypeEstablishments objects
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

//    public function findOneBySomeField($value): ?TypeEstablishments
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
