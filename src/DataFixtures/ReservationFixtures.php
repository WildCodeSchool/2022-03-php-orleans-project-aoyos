<?php

namespace App\DataFixtures;

use App\Config\ReservationStatus;
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

    public const NUMBER_RESERVATIONS = 50;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $totalFormulas = count(self::FORMULAS) - 1;
        $status = ReservationStatus::cases();
        $totalStatus = count($status) - 1;

        for ($i = 0; $i < self::NUMBER_RESERVATIONS; $i++) {
            $reservation = new Reservation();
            $reservation->setLastname($faker->lastName());
            $reservation->setFirstName($faker->firstName());
            $reservation->setCompany($faker->company());
            $reservation->setEmail($faker->email());
            $reservation->setPhone($faker->phoneNumber());
            $reservation->setFormula(self::FORMULAS[rand(0, $totalFormulas)]);
            $reservation->setEventType($faker->sentence(4));
            $reservation->setAddress($faker->address());
            $reservation->setDateStart($faker->dateTimeInInterval('+1 week', '+1 days'));
            $reservation->setDateEnd($faker->dateTimeInInterval('+1 week', '+2 days'));
            $reservation->setAttendees($faker->randomNumber(3, true));
            $reservation->setComment($faker->paragraph());
            $reservation->setStatus($status[rand(0, $totalStatus)]->name);

            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
