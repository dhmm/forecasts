<?php

namespace App\DataFixtures;

use App\Entity\Forecast;
use App\Entity\Location;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $location = $this->createLocation('Barcelona','ES', 41.38879, 2.15899);
        $manager->persist($location);
        $forecast = $this->createForecast($location, '2024-01-01', 21);
        $manager->persist($forecast);
        $forecast = $this->createForecast($location, '2024-01-02', 22);
        $manager->persist($forecast);
        $forecast = $this->createForecast($location, '2024-01-03', 23);
        $manager->persist($forecast);
        $forecast = $this->createForecast($location, '2024-01-04', 24);
        $manager->persist($forecast);

        $location = $this->createLocation('Berlin','DE', 52.5200, 13.4050);
        $manager->persist($location);
        $location = $this->createLocation('Paris', 'FR', 48.8566, 2.3522);
        $manager->persist($location);
        $location = $this->createLocation('Warsaw', 'PL', 52.2297, 21.0122);
        $manager->persist($location);
        $location = $this->createLocation('Delhi', 'IN', 28.7041, 77.1025);
        $manager->persist($location);

        $manager->flush();
    }


    private function createLocation(
        string $name, 
        string $countryCode,
        float $latitude,
        float $longitude) : Location
    {
        $location = new Location();
        $location
            ->setName($name)
            ->setCountryCode($countryCode)
            ->setLatitude($latitude)
            ->setLongitude($longitude)
        ;
        return $location;
    }

    private function createForecast(Location $location, string $dateString, int $celsius) : Forecast
    {
        $forecast = new Forecast();
        $forecast
            ->setLocation($location)
            ->setDate(new \DateTime($dateString))
            ->setCelsius($celsius)
        ;
        return $forecast;
    }
}
