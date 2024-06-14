<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/weather')]
class WeatherController extends AbstractController
{
    #[Route('/highlander-says/{threshold<\d+>?50}', host:'api.localhost' )]
    public function highlanderSaysApi(int $threshold) : Response
    {        
        $draw = random_int(0,100);
        
        $forecast = $draw < $threshold ? "It's going to rain" : "It's going to be sunny";

        $json = [
            'forecast' => $forecast,
        ];

        return new JsonResponse($json);
    }

    #[Route('/highlander-says/{guess}')]
    public function highlanderSaysGuess(string $guess) : Response
    {
        $forecast = "It's going to $guess";

        //return response
        return $this->render('weather/highlander_says.html.twig',  [
                'forecast' => $forecast
            ]
        );
    }
}