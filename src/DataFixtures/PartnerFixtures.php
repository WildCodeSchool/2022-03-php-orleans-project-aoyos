<?php

namespace App\DataFixtures;

use App\Entity\Partner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PartnerFixtures extends Fixture
{
    public const PARTNERSIMG = [
        'https://aoyos.fr/wp-content/uploads/2022/01/Sans-titre-1-1-300x300.png',
        'https://aoyos.fr/wp-content/uploads/2022/01/Sans-titre-1-2-300x300.png',
        'https://aoyos.fr/wp-content/uploads/2022/01/Sans-titre-1-3-300x300.png',
        'https://aoyos.fr/wp-content/uploads/2022/01/Sans-titre-1-300x300.png',
        'https://aoyos.fr/wp-content/uploads/2022/01/Sans-titre-1-5-300x300.png'];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i <= 4; $i++) {
            $partner = new Partner();
            $partner->setName($faker->name());
            $partner->setImage(self::PARTNERSIMG[$i]);
            $manager->persist($partner);
        }
        $manager->flush();
    }
}
