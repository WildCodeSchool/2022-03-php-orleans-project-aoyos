<?php

namespace App\DataFixtures;

use App\Entity\Production;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $production = new Production();
            $production->setTitle($faker->sentence(3));
            $production->setDescription($faker->sentence(10));
            $manager->persist($production);
        }
        $manager->flush();
    }
}
