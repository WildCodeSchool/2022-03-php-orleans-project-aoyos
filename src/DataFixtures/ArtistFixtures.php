<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Artist;
use Faker\Factory;

class ArtistFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $numberArtists = 5;

        $faker = Factory::create();

        for ($i = 0; $i <= $numberArtists; $i++) {
            $artist = new Artist();

            $artist->setFirstname($faker->firstName());
            $artist->setlastname($faker->lastName());
            $artist->setBirthdate($faker->dateTime());
            $artist->setPhone($faker->phoneNumber());
            $artist->setEmail($faker->email());
            $artist->setAddress($faker->address());

            $manager->persist($artist);
        }
        $manager->flush();
    }
}
