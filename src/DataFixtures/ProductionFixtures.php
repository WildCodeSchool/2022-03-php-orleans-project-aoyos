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
            $production->setTitle($faker->sentence(4));
            $production->setDescription($faker->paragraph(50));
            $imageName = 'production' . $i . '.webp';
            copy('https://picsum.photos/200/300?random=' . $i, 'public/uploads/images/production/' . $imageName);
            $production->setImageProduction($imageName);
            $manager->persist($production);
        }
        $manager->flush();
    }
}
