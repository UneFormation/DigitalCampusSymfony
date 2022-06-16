<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(private SluggerInterface $slugger)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $count = $this->faker->numberBetween(5, 6);
        for ($a = 1; $a <= $count; $a++) {
            $parent = $this->createCategory($manager);
            $childCount = $this->faker->numberBetween(0, 1);
            for ($b = 0; $b < $childCount; $b++) {
                $category = $this->createCategory($manager, $parent);

                $subChildCount = $this->faker->numberBetween(0, 2);
                for ($c = 0; $c < $subChildCount; $c++) {
                    $this->createCategory($manager, $category);
                }
            }
        }

        $manager->flush();
    }

    public function createCategory(ObjectManager $manager, Category $parent = null): Category
    {
        static $total = 0; $titles = [];

        do {
            $title = $this->faker->word();
        } while (in_array($title, $titles, true));
        $titles[] = $title;

        $category = new Category();
        $category->setParent($parent);
        $category->setTitle($title);
        $category->setSlug($this->slugger->slug($title)->lower());

        $manager->persist($category);
        $this->setReference('category-' . $total++, $category);

        return $category;
    }
}
