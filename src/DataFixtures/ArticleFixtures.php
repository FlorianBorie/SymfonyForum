<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        //créer 3 catégorie fakées
        for ($i = 1; $i <= 3; $i++){
            $categorie = new Category();
            $categorie->setTitle($faker->sentence())
                      ->setDescription($faker->paragraph());

            $manager->persist($categorie);

            //créer entre 4 et 6 articles
            for ($j = 1; $j <= mt_rand(4, 6); $j++){
                $article = new Article();

                $content = '<p>' .join($faker->paragraphs(5), '</p><p>' . '</p>');

                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('- 6 months'))
                    ->setCategory($categorie);

                $manager->persist($article);

                // On donne des commentaires à l'article
                for ($k = 1; $k <= mt_rand(4, 10); $k++){
                    $comment = new Comment();

                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                    $days = (new \DateTime())->diff($article->getCreatedAt())->days;
                    $minimum = '-' . $days . 'days';


                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
