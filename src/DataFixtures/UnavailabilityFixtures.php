<?php

namespace App\DataFixtures;

use App\Entity\Unavailability;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UnavailabilityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < ArtistFixtures::NUMBER_ARTISTS; $i++) {
            $randUnavaibilities = rand(1, 5);
            for ($j = 0; $j < $randUnavaibilities; $j++) {
                $unavailability = new Unavailability();
                $unavailability->setDateStart($faker->dateTimeBetween(
                    '+' . $j . ' week',
                    '+' . ($j + 1) . 'week'
                ));
                $unavailability->setDateEnd($faker->dateTimeBetween(
                    '+' . ($j + 2) . ' week',
                    '+' . ($j + 3) . ' week'
                ));
                $unavailability->setArtist($this->getReference('artist_' . $i));
                $manager->persist($unavailability);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ArtistFixtures::class
        ];
    }
}
