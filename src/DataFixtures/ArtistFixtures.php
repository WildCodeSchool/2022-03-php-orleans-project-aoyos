<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Artist;
use Faker\Factory;

class ArtistFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $numberArtists = 5;
        $totalMusicalStyles = count(MusicalStyleFixtures::MUSICALSTYLES);

        $faker = Factory::create();

        for ($i = 0; $i <= $numberArtists; $i++) {
            $artist = new Artist();

            $artist->setFirstname($faker->firstName());
            $artist->setLastname($faker->lastName());
            $artist->setBirthdate($faker->dateTime());
            $artist->setPhone($faker->phoneNumber());
            $artist->setEmail($faker->email());
            $artist->setAddress($faker->address());
            $artist->setArtistName($faker->word());
            $artist->setEquipment($faker->words(3, true));
            $artist->setMessage($faker->sentence());
            $artist->addMusicalStyle(
                $this->getReference('musicalstyle_' . $faker->numberBetween(0, $totalMusicalStyles))
            );


            $manager->persist($artist);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            MusicalStyleFixtures::class,
        ];
    }
}
