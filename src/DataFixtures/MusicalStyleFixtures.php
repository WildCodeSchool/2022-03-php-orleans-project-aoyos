<?php

namespace App\DataFixtures;

use App\Entity\MusicalStyle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MusicalStyleFixtures extends Fixture
{
    public const MUSICALSTYLES = ['Généraliste', 'Chanteur', 'Soul', 'Musique DJ saxophoniste',
    'House', 'Deep house', 'Électro', 'Pop/folk', 'Musique swing', 'Rock', 'Rap', 'Hip-Hop',
    'Groove', 'Musique latino', 'Funk'];

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::MUSICALSTYLES); $i++) {
            $musicalStyle = new MusicalStyle();
            $musicalStyle->setName(self::MUSICALSTYLES[$i]);
            $manager->persist($musicalStyle);
        }
        $manager->flush();
    }
}
