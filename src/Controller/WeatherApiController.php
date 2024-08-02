<?php

namespace App\Controller;

use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/weather')]
class WeatherApiController extends AbstractController
{
    #[Route('/json/{id}', name: 'app_weather_api')]
    public function jsonAction(Location $location): Response
    {
        $data = [
            'id' => $location->getId(),
            'name' => $location->getName(),
            'country' => $location->getCountryCode()
        ];

        foreach($location->getForecasts() as $forecast)
        {
            $data['forecasts'][$forecast->getDate()->format('d-m-Y')] = 
            [
                'celsius' => $forecast->getCelsius(),
            ];
        }

        // return new JsonResponse($data);
        // return $this->json($data);
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $response = new Response($json); 
        $response->headers->set('Content-Type' , 'application/json');

        return $response;
    }
}