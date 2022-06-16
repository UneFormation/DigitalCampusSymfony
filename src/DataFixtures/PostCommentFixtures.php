<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\PostComment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostCommentFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $count = $this->faker->numberBetween(30, 50);
        for ($a = 0; $a < $count; $a++) {
            $randomPostId = $this->faker->numberBetween(0, 9);
            /** @var Post $randomPost */
            $randomPost = $this->getReference('post-' . $randomPostId);

            $parent = $this->createComment($manager, $randomPost);
            $subCommentCount = $this->faker->numberBetween(0, 10);
            for ($b = 0; $b < $subCommentCount; $b++) {
                $this->createComment($manager, $randomPost, $parent);
            }
        }

        $manager->flush();
    }

    public function createComment(ObjectManager $manager, Post $post, PostComment $parent = null)
    {
        $randomUserId = $this->faker->numberBetween(0, 4);
        /** @var User $randomUser */
        $randomUser = $this->getReference('user-' . $randomUserId);

        $minDate = $post->getPublishedDate()->format('c');
        if ($parent) {
            $minDate = $parent->getDate()->format('c');
        }

        $date = \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween($minDate, 'now'));

        $comment = new PostComment();
        $comment->setAuthor($randomUser);
        $comment->setTitle($this->faker->sentence(5));
        $comment->setContent($this->faker->realText());
        $comment->setParentPost($parent);
        $comment->setDate($date);
        $comment->setPost($post);

        $manager->persist($comment);
        return $comment;
    }

    public function getDependencies(): array
    {
        return [
            PostFixtures::class,
            UserFixtures::class,
        ];
    }
}
