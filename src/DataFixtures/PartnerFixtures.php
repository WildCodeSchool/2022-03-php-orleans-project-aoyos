<?php

namespace App\DataFixtures;

use App\Entity\Partner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PartnerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $partner = new Partner();
            $partner->setName($faker->name());
            $partner->setImage('https://picsum.photos/200');
            $manager->persist($partner);
        }
        $manager->flush();
    }
}
