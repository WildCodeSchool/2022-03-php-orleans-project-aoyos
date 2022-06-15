<?php

namespace App\DataFixtures;

use App\Entity\MusicalStyle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MusicalStyleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $musicalStyles = ['Généraliste', 'Chanteur', 'Soul', 'Musique DJ saxophoniste',
        'House', 'Deep house', 'Électro', 'Pop/folk', 'Musique swing', 'Rock', 'Rap', 'Hip-Hop',
        'Groove', 'Musique latino', 'Funk'];
        for ($i = 0; $i < count($musicalStyles); $i++) {
            $musicalStyle = new MusicalStyle();
            $musicalStyle->setName($musicalStyles[$i]);
            $manager->persist($musicalStyle);
        }
        $manager->flush();
    }
}
