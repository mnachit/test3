<?php

namespace App\Repository;

use App\Entity\EmplacementPublication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmplacementPublication>
 *
 * @method EmplacementPublication|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmplacementPublication|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmplacementPublication[]    findAll()
 * @method EmplacementPublication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmplacementPublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmplacementPublication::class);
    }

    public function save(EmplacementPublication $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EmplacementPublication $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EmplacementPublication[] Returns an array of EmplacementPublication objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EmplacementPublication
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
