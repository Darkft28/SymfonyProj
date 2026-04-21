<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Catégories
        $categories = [];
        $categoryNames = ['Technologie', 'Voyage', 'Cuisine', 'Sport', 'Culture'];
        foreach ($categoryNames as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($faker->sentence());
            $manager->persist($category);
            $categories[] = $category;
        }

        // Utilisateurs
        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->unique()->safeEmail());
            $user->setUsername($faker->unique()->userName());
            $user->setBio($faker->paragraph());
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $users[] = $user;
        }

        // Articles
        $articles = [];
        for ($i = 0; $i < 20; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence(6));
            $article->setContent($faker->paragraphs(4, true));
            $article->setPublishedAt($faker->dateTimeBetween('-1 year', 'now'));
            $article->setUpdatedAt($faker->optional()->dateTimeBetween('-6 months', 'now'));
            $article->setSlug($faker->unique()->slug(4));
            $article->setImage($faker->optional()->imageUrl(800, 600, 'nature'));
            $article->setStatus($faker->randomElement(['draft', 'published']));
            $article->setAuthor($faker->randomElement($users));
            $article->setCategory($faker->optional()->randomElement($categories));
            $manager->persist($article);
            $articles[] = $article;
        }

        // Commentaires
        for ($i = 0; $i < 40; $i++) {
            $comment = new Comment();
            $comment->setContent($faker->paragraph());
            $comment->setCreatedAt($faker->dateTimeBetween('-6 months', 'now'));
            $comment->setAuthor($faker->randomElement($users));
            $comment->setArticle($faker->randomElement($articles));
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
