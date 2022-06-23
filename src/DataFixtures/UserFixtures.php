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
        $djUnvalidated->setEmail('dj@exemple.com');
        $djUnvalidated->setUsername('dj1');
        $djUnvalidated->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $djUnvalidated,
            'djnonvalidé'
        );
        $djUnvalidated->setPassword($hashedPassword);
        $manager->persist($djUnvalidated);

        $manager->flush();
    }
}
