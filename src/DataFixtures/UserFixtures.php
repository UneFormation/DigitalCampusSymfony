<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $count = $this->faker->numberBetween(5, 20);
        for ($a = 0; $a < $count; $a++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setFirstname($this->faker->firstName());
            $user->setLastname($this->faker->lastName());
            $user->setRoles(['ROLE_AUTHOR']);
            $user->setPassword(
                $this->passwordEncoder->hashPassword(
                    $user,
                    $this->faker->password()
                )
            );

            $manager->persist($user);
            $this->setReference('user-' . $a, $user);
        }

        $manager->flush();
    }
}
