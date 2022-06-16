<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $count = $this->faker->numberBetween(10, 30);
        for ($a = 1; $a <= $count; $a++) {
            $randomUserId = $this->faker->numberBetween(0, 4);
            /** @var User $randomUser */
            $randomUser = $this->getReference('user-' . $randomUserId);

            $randomCategoryId = $this->faker->numberBetween(0, 4);
            /** @var Category $randomCategory */
            $randomCategory = $this->getReference('category-' . $randomCategoryId);

            $post = (new Post())
                ->setTitle($this->faker->sentence(6))
                ->setContent($this->faker->realText())
                ->setUpdatedAt(new \DateTime())
                ->setAuthor($randomUser)
                ->setCategory($randomCategory);

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies() : array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }

}
