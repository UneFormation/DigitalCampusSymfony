<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(private UserPasswordHasherInterface $passwordEncoder,
    private SluggerInterface $slugger)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $count = $this->faker->numberBetween(5, 20);
        for ($a = 0; $a < $count; $a++) {
            $this->createUser($manager);
        }

        $this->createUser($manager, [
            'email' => 'david.patiashvili@uneformation.fr',
            'firstname' => 'David',
            'lastname' => 'Patiashvili',
            'password' => 'DavidPatiashvili',
            'roles' => ['ROLE_AUTHOR', 'ROLE_ADMINISTRATOR'],
        ]);

        $manager->flush();
    }

    public function createUser(ObjectManager $manager, array $data = [])
    {
        static $index = 0;

        $data = array_replace(
            [
                'email' => $this->faker->email(),
                'firstname' => $this->faker->firstName(),
                'lastname' => $this->faker->lastName(),
                'password' => $this->faker->password(),
                'roles' => ['ROLE_AUTHOR'],
            ],
            $data,
        );
        $user = (new User())
            ->setEmail($data['email'])
            ->setFirstname($data['firstname'])
            ->setLastname($data['lastname'])
            ->setSlug($this->slugger->slug($data['firstname'] . ' ' . $data['lastname'])->lower())
            ->setRoles($data['roles']);

        $user->setPassword($this->passwordEncoder->hashPassword($user, $data['password']));
        $manager->persist($user);
        $this->setReference('user-' . $index++, $user);
    }
}
