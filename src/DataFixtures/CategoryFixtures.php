<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CategoryFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $total = 0;
        $count = $this->faker->numberBetween(5, 20);
        for ($a = 1; $a <= $count; $a++) {
            $parent = new Category();
            $parent->setTitle($this->faker->word());

            $manager->persist($parent);
            $this->setReference('category-' . $total++, $parent);

            $childCount = $this->faker->numberBetween(0, 10);
            for ($b = 0; $b < $childCount; $b++) {
                $category = new Category();
                $category->setParent($parent);
                $category->setTitle($this->faker->word());

                $manager->persist($category);
                $this->setReference('category-' . $total++, $category);

                $subChildCount = $this->faker->numberBetween(0, 10);
                for ($c = 0; $c < $subChildCount; $c++) {
                    $subCategory = new Category();
                    $subCategory->setParent($category);
                    $subCategory->setTitle($this->faker->word());

                    $manager->persist($subCategory);
                    $this->setReference('category-' . $total++, $subCategory);
                }
            }

        }

        $manager->flush();
    }
}
