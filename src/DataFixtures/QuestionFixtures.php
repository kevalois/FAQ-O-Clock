<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('FR_fr');

        //creer des questions
        for ($i = 1; $i <= 20; $i++){
            $question = new Question();
            $question->setEntitled($faker->text($maxNbChars = 239));
        }

        $manager->flush();
    }
}
