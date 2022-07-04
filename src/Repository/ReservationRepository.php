<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function add(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTaken(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.artist is NOT NULL')
            ->orderBy('r.dateStart', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findTakenWithSearch(string $eventType): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.artist is NOT NULL')
            ->andWhere('r.eventType LIKE :eventType')
            ->setParameter('eventType', '%' . $eventType . '%')
            ->orderBy('r.dateStart', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */


    public function findLikeEventType(string $eventType): array
    {
        return $this->createQueryBuilder('r')
           ->andWhere('r.eventType LIKE :eventType')
           ->setParameter('eventType', '%' . $eventType . '%')
           ->getQuery()
           ->getResult()
        ;
    }
}
