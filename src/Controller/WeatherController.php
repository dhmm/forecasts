<?php
declare(strict_types=1);

namespace App\Controller;

use Exception;
use App\Model\HighlanderApiDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception as ExceptionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/{_locale}/weather'  , requirements: [
    '_locale' => 'en|de'
])]
class WeatherController extends AbstractController
{
    #[Route('/{countryCode<[A-Za-z][A-Za-z]>}/{city<[A-Za-z]+>}')]
    public function forecast(string $countryCode, string $city) : Response
    {
        $dummyData = [
            [
                'date' => new \DateTime('28-6-2024'),
                'temperature' => 17,
                'feels_like' => 16,
                'pressure' => 900,
                'humidity' => 17,
                'wind' => 7.2,
                'cloudiness' => 45,
                'icon' => 'sun',
            ],
            [
                'date' => new \DateTime('28-6-2024'),
                'temperature' => 17,
                'feels_like' => 16,
                'pressure' => 560,
                'humidity' => 17,
                'wind' => 7.2,
                'cloudiness' => 95,
                'icon' => 'sun',
            ],
            [
                'date' => new \DateTime('28-6-2024'),
                'temperature' => 17,
                'feels_like' => 16,
                'pressure' => 899,
                'humidity' => 17,
                'wind' => 7.2,
                'cloudiness' => 45,
                'icon' => 'sun',
            ]
        ];

        return $this->render('weather/forecast.html.twig' , ['dummyData' => $dummyData]);
    }
    #[Route('/highlander-says/api')]
    public function highlanderSaysApi(
        #[MapQueryString] ?HighlanderApiDTO $dto = null
    ) : Response
    {        
        if(!$dto) {
            $dto = new HighlanderApiDTO();
            $dto->threshold = 50;
            $dto->trials = 1;
        }

        for($i=0;$i<$dto->trials;$i++) {
            $draw = random_int(0,100);
            $forecast = $draw < $dto->threshold ? "It's going to rain" : "It's going to be sunny";
            $forecasts [] = $forecast;
        }

        $json = [
            'forecasts' => $forecasts,
            'threshold' => $dto->threshold,            
        ];

        return new JsonResponse($json);
        // return $this->json($json);
        
        // return $this->file(
        //     __DIR__.'/logo-black.png',
        //     'demo.txt',
        //     ResponseHeaderBag::DISPOSITION_INLINE
        // );
    }

    #[Route('/highlander-says/{threshold<\d+>}')]
    public function highlanderSays( 
        Request $request, 
        RequestStack $requestStack,
        TranslatorInterface $translator,
        ?int $threshold = null,
        #[MapQueryParameter] ?string $format = 'html'
    ) : Response
    {      
        $session = $requestStack->getSession();
        if($threshold) {
            $session->set('threshold' , $threshold);
            $this->addFlash("info" , $translator->trans('weather.highlander_says.success' , [
                '%threshold%' => $threshold
            ]) );
        } else {
            $threshold = $session->get('threshold' , 50);          
        }

        $trials = $request->get('trials' , 1);
        
        $forecasts =[];

        for($i=0;$i<$trials;$i++) {
            $draw = random_int(0,100);
            $forecast = $draw < $threshold ? "It's going to rain" : "It's going to be sunny";
            $forecasts [] = $forecast;
        }

        //return response
        $html = $this->renderView("weather/highlander_says.{$format}.twig",  [
            'forecasts' => $forecasts,
            'threshold' => $threshold,
            'format' => $format          
        ]);

        $response = new Response($html);

        return $response;
    }

    #[Route('/highlander-says/{guess}')]
    public function highlanderSaysGuess(string $guess) : Response
    {
        $availableGuesses = ['snow' , 'rain' , 'hail'];

        if(!in_array($guess, $availableGuesses))
        {
            throw $this->createNotFoundException('This guess is not found');
            // throw new NotFoundHttpException('This guess is not found');
            // throw new BadRequestHttpException('Bad request');
            // throw new Exception('An exception');
        }

        $forecast = "It's going to $guess";

        //return response
        return $this->render('weather/highlander_says.html.twig',  [
                'forecasts' => [$forecast]
            ]
        );
    }
}