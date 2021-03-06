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
            $article->setCreatedAt($faker->dateTimeBetween('-200 week'));
            $article->setDescription($faker->paragraph(3));
            $imageName = 'article' . $i . '.webp';
            copy('https://picsum.photos/200/300?random=' . $i, 'public/uploads/images/article/' . $imageName);
            $article->setImage($imageName);
            $article->setAuthor($faker->name());

            $manager->persist($article);
        }

        $manager->flush();
    }
}
