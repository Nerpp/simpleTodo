<?php

namespace App\DataFixtures;

use App\Entity\Listing;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 20; $i++) {
            $listing = new Listing();
            $listing->setName('liste ' . $i);
            $manager->persist($listing);
            $allList[] = $listing;
        }

        foreach ($allList as $list) {
            for ($i = 0; $i < 6; $i++) {
                $task = new Task();
                $task->setTitle('tache ' . $i);
                $task->setState(rand(0, 1));
                $task->setListing($list);
                $manager->persist($task);
            }
        }

        $manager->flush();
    }
}
