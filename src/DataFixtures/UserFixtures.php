<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@aoyos.com');
        $admin->setUsername('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'adminaoyos'
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $djUnvalidated = new User();
        $djUnvalidated->setEmail('dj1@exemple.com');
        $djUnvalidated->setUsername('djnonvalidé');
        $djUnvalidated->setRoles(['ROLE_USER']);
        $this->addReference('user_' . 0, $djUnvalidated);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $djUnvalidated,
            'dj1'
        );
        $djUnvalidated->setPassword($hashedPassword);
        $manager->persist($djUnvalidated);

        $djValidated = new User();
        $djValidated->setEmail('dj2@exemple.com');
        $djValidated->setUsername('dj2');
        $djValidated->setRoles(['ROLE_DJ']);
        $this->addReference('user_' . 1, $djValidated);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $djValidated,
            'dj2'
        );
        $djValidated->setPassword($hashedPassword);
        $manager->persist($djValidated);

        $djValidated = new User();
        $djValidated ->setEmail('dj3@exemple.com');
        $djValidated->setUsername('dj3');
        $djValidated->setRoles(['ROLE_DJ']);
        $this->addReference('user_' . 2, $djValidated);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $djValidated,
            'dj3'
        );
        $djValidated->setPassword($hashedPassword);
        $manager->persist($djValidated);

        $manager->flush();
    }
}
