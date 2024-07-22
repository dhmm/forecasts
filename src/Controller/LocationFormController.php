<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationFormTestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('location-form')]
class LocationFormController extends AbstractController
{
    #[Route('/new')]
    public function new(): Response
    {
        $location = new Location();        
        $location->setCountryCode('ES');

        $form = $this->createForm(LocationFormTestType::class, $location);        

        return $this->render('location_form/new.html.twig', [
            'form' => $form,
        ]);
    }
}
