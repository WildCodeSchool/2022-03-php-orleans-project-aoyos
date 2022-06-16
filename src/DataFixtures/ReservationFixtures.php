<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReservationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $numberReservations = 10;

        for ($i = 0; $i < $numberReservations; $i++) {
            $reservation = new Reservation();
            $reservation->setLastname($faker->lastName());
            $reservation->setFirstName($faker->firstName());
            $reservation->setCompany($faker->words(2, true));
            $reservation->setEmail($faker->email());
            $reservation->setPhone($faker->phoneNumber());
            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
