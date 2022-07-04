<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Artist;
use Faker\Factory;

class ArtistFixtures extends Fixture implements DependentFixtureInterface
{
    public const NUMBER_ARTISTS = 3;
    public const EMAILS = ['dj1@exemple.com', 'dj2@exemple.com', 'dj3@exemple.com'];
    public const ORLEANS_COORDINATES = [47.873527, 1.910865];

    public function load(ObjectManager $manager): void
    {
        $totalMusicalStyles = count(MusicalStyleFixtures::MUSICALSTYLES) - 1;

        $faker = Factory::create();

        for ($i = 0; $i < self::NUMBER_ARTISTS; $i++) {
            $artist = new Artist();

            $artist->setFirstname($faker->firstName());
            $artist->setLastname($faker->lastName());
            $artist->setBirthdate($faker->dateTime());
            $artist->setPhone($faker->phoneNumber());
            $artist->setEmail(self::EMAILS[$i]);
            $artist->setAddress($faker->address());
            $artist->setArtistName($faker->word());
            $artist->setEquipment($faker->words(3, true));
            $artist->setMessage($faker->sentence());
            $artist->setLatitude(self::ORLEANS_COORDINATES[0]);
            $artist->setLongitude(self::ORLEANS_COORDINATES[1]);
            $artist->setUser($this->getReference('user_' . $i));
            $artist->addMusicalStyle(
                $this->getReference('musicalstyle_' . rand(0, $totalMusicalStyles))
            );
            $this->addReference('artist_' . $i, $artist);

            $manager->persist($artist);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            MusicalStyleFixtures::class,
            UserFixtures::class
        ];
    }
}
