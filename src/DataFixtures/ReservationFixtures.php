<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReservationFixtures extends Fixture
{
    public const FORMULAS = [
        'Solo',
        'Standard',
        'Sur mesure',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $numberReservations = 10;
        $totalFormulas = count(self::FORMULAS);

        for ($i = 0; $i < $numberReservations; $i++) {
            $reservation = new Reservation();
            $reservation->setLastname($faker->lastName());
            $reservation->setFirstName($faker->firstName());
            $reservation->setCompany($faker->words(2, true));
            $reservation->setEmail($faker->email());
            $reservation->setPhone($faker->phoneNumber());
            $reservation->setFormula(self::FORMULAS[rand(0, $totalFormulas - 1)]);
            $reservation->setEventType($faker->sentence(4));
            $reservation->setAddress($faker->address());
            $reservation->setDateStart($faker->dateTimeInInterval('+1 week', '+1 days'));
            $reservation->setDateEnd($faker->dateTimeInInterval('+1 week', '+2 days'));
            $reservation->setAttendees($faker->randomNumber(3, true));
            $reservation->setComment($faker->paragraph());

            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
