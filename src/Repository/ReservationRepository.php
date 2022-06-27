<?php

namespace App\Repository;

use App\Entity\Artist;
use App\Entity\Reservation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
            ->getResult();
    }

    public function findByArtistByDate(Artist $artist, string $condition): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.artist = :artist')
            ->setParameter('artist', $artist)
            ->andWhere('r.dateEnd ' . $condition . ' current_date()')
            ->orderBy('r.dateEnd', 'DESC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */


    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
