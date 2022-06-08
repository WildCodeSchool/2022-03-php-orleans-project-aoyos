<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence());
            $article->setCreatedAt($faker->dateTimeBetween('-200 week', '-1 day'));
            $article->setDescription($faker->paragraph(3));
            $article->setImage($faker->imageUrl(350, 65, 'animals', true));
            $article->setAuthor($faker->firstname() . ' ' . $faker->lastname());

            $manager->persist($article);
        }

        $manager->flush();
    }
}
