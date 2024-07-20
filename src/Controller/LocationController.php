<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStackInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/location-dummy')]
class LocationController extends AbstractController
{
    #[Route('/create')]
    public function create(LocationRepository $locationRepository): JsonResponse
    {
        $location = new Location();
        $location
            ->setName('Szczecin')
            ->setCountryCode('PL')
            ->setLatitude(53.4285)
            ->setLongitude(14.5528);

        $locationRepository->save($location, true);

        return new JsonResponse([
            'id' => $location->getId(),
        ]);
    }

    #[Route('/edit')]
    public function edit(LocationRepository $locationRepository): JsonResponse
    {
        $location = $locationRepository->find(9);
        $location->setName('Komotini');

        $locationRepository->save($location, true);

        return new JsonResponse([
            'id' => $location->getId(),
            'name' => $location->getName(),
        ]);
    }
    #[Route('/remove/{id}')]
    public function remove(
        LocationRepository $locationRepository,
        int $id
    ): JsonResponse {
        $location = $locationRepository->find($id);
        $locationRepository->remove(entity: $location, flush: true);

        return new JsonResponse(null);
    }

    #[Route('/show/{location_id}')]
    public function show(
        #[MapEntity(mapping: ['location_id' => 'id'])]
        Location $location
    ): JsonResponse
    {     
        $json = [
            'id' => $location->getId(),
            'name' => $location->getName(),
            'countryCode' => $location->getCountryCode(),
            'latitude' => $location->getLatitude(),
            'longitude' => $location->getlongitude(),            
        ];

        foreach($location->getForecasts() as $forecast)
        {
            $json['forecasts'][$forecast->getDate()->format('Y-m-d')] = [                
                'celsius' => $forecast->getCelsius()
            ];
        }
        return new JsonResponse($json);
    }

    #[Route('/')]
    public function index(LocationRepository $locationRepository): JsonResponse
    {
        $locations = $locationRepository->findAllWithForecasts();


        $json = [];
        foreach ($locations as $location) {
            $locationJson = [
                'id' => $location->getId(),
                'name' => $location->getName(),
                'countryCode' => $location->getCountryCode(),
                'latitude' => $location->getLatitude(),
                'longitude' => $location->getlongitude(),
            ];
        
            foreach($location->getForecasts() as $forecast)
            {
                $locationJson['forecasts'][$forecast->getDate()->format('Y-m-d')] = [                
                    'celsius' => $forecast->getCelsius()
                ];
            }

            $json [] = $locationJson;
        }
        return new JsonResponse($json);
    }
}
