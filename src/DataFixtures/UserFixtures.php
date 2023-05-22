<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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
        $user = (new User())
            ->setFirstName('Admin')
            ->setLastName('John')
            ->setDateOfBirth(new DateTime('1990-01-01'))
            ->setEmail('admin@gmail.com');

        $password = $this->passwordHasher->hashPassword($user, 'root');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}
