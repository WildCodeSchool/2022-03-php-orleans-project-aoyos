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
    public const PAST_EVENTS = 'passes';
    public const FUTURE_EVENTS = 'a-venir';

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

    public function findByArtistByDate(Artist $artist, string $filter): array
    {
        $condition = '';
        if ($filter === self::PAST_EVENTS) {
            $condition = '<';
        } elseif ($filter === self::FUTURE_EVENTS) {
            $condition = '>=';
        }
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
    public function findTakenWithSearch(?string $search): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.artist is NOT NULL')
            ->andWhere('r.eventType LIKE :eventType')
            ->setParameter('eventType', '%' . $search . '%')
            ->orWhere('r.company LIKE :company')
            ->setParameter('company', '%' . $search . '%')
            ->orderBy('r.dateStart', 'DESC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */


    public function findLikeEventType(?string $search = '', ?string $status = ''): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
                        ->andWhere('r.artist is NULL');

        if ($search) {
            $queryBuilder->andWhere('r.eventType LIKE :eventType')
                ->setParameter('eventType', '%' . $search . '%')
                ->orWhere('r.company LIKE :company')
                ->setParameter('company', '%' . $search . '%');
        }

        if ($status) {
            $queryBuilder->andWhere('r.status = :status')
                ->setParameter('status', $status);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findByMusicalStyle(string $musicalStyleName): array
    {
        return $this->createQueryBuilder('r')
            ->innerjoin('r.musicalStyles', 'm')
            ->select('r')
            ->andWhere('r.status = :Waiting')
            ->andWhere('m.name = :musicalStyle')
            ->setParameter('musicalStyle', $musicalStyleName)
            ->setParameter('Waiting', 'Waiting')
            ->getQuery()
            ->getResult();
    }
}
