<?php

declare(strict_types=1);

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Location extends Fixtures
{

    public function load(ObjectManager $manager)
    {
        //@TODO: add more locations

        $locations = [
            ['Grabowo', 18.13, 54.11],
            ['Gdańsk', 18.61, 54.37],
            ['Klein Ammensleben', 11.51, 52.22],
            ['Raḑwān', 41.51, 22.22],
            ['costam', 179, 59],
            ['Powiatkościerski', 18, 54],
            ['Grodziec', 18, 52],
            ['Hněvošice', 18, 50],
            ['Szentlőrinc', 18, 46],
        ];
    }
}
