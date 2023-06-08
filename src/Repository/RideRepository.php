<?php

namespace App\Repository;

use App\Entity\Ride;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ride>
 *
 * @method Ride|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ride|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ride[]    findAll()
 * @method Ride[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ride::class);
    }

    public function save(Ride $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ride $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search($from = null, $to = null, $date = null): array {
        if($from === null || $to === null){
            return $this->createQueryBuilder('r')
        ->getQuery()
        ->getResult();
        }
        if($date === null) {
            return $this->createQueryBuilder('r')
        ->where("r.departure LIKE :dep")
        ->andWhere("r.destination LIKE :des")
        ->setParameters(["dep" => '%' . $from . '%', "des" => '%' . $to . '%'])
        ->getQuery()
        ->getResult();
        }
        return $this->createQueryBuilder('r')
        ->where("r.departure LIKE :dep")
        ->andWhere("r.destination LIKE :des")
        ->andWhere("r.date = :date")
        ->setParameters(["dep" => '%' . $from . '%', "des" => '%' . $to . '%', "date" => $date])
        ->getQuery()
        ->getResult();
    }



//    /**
//     * @return Ride[] Returns an array of Ride objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ride
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
