<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $parent = new Category();
        $parent->setTitle($faker->word());
        $manager->persist($parent);

        $category = new Category();
        $category->setParent($parent);
        $category->setTitle($faker->word());
        $manager->persist($category);

        $category = new Category();
        $category->setParent($parent);
        $category->setTitle($faker->word());
        $manager->persist($category);

        $manager->flush();
    }
}
