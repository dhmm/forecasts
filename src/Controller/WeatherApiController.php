<?php

namespace App\Controller;

use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1/weather')]
class WeatherApiController extends AbstractController
{
    #[Route('/json/{id}')]
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
    #[Route('/jsont/{id}')]
    public function jsonTwigAction(Location $location): Response
    {
        $content = $this->renderView('weather_api/json_twig.json.twig', [
            'location' => $location
        ]);
        $response  =new Response($content);
        $response->headers->set('Content-Type' , 'application/json');

        return $response;
    }
    #[Route('/csvt/{id}')]
    public function csvTwigAction(Location $location): Response
    {
        $content = $this->renderView('weather_api/csv_twig.csv.twig', [
            'location' => $location
        ]);
        $response  =new Response($content);
        $response->headers->set('Content-Type' , 'text/csv');

        return $response;
    }

    #[Route('/serializer/{id}')]
    public function serializerAction(
        Location $location,
        SerializerInterface $serializer,

    ): Response
    {
        // $content = $serializer->serialize($location, 'json');
        // $response  = new Response($content);
        // $response->headers->set('Content-Type' , 'application/json');

        // return $response;
        return $this->json($location);
    }
}
