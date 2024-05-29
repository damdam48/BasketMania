<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $hasher,

    ) {
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = (new User)
            ->setFirstName('admin')
            ->setLastName('admin')
            ->setEmail('admin@mail.com')
            ->setPassword(
                $this->hasher->hashPassword(new User, '123')
            )
            ->setRoles(['ROLE_ADMIN']);


        $manager->persist($user);
        $manager->flush();
    }
}
