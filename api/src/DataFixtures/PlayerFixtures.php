<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlayerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       for ($i = 0; $i < 20; $i++) {
            $player = new Player();
            $player->setName('Player '.$i);
            $player->setUsername('player_'.$i);
            $player->setPassword('password_'.$i);
            $manager->persist($player);
        }

        $manager->flush();
    }
}
