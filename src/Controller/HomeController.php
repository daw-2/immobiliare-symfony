<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PropertyRepository $repository): Response
    {
        $properties = $repository->findAllGreaterThanPrice(500000);
        dump($properties);
        // Attention, pas de order by RAND avec Doctrine
        // On mélange le tableau
        shuffle($properties);
        // On prend les 4 premiers du tableau
        $properties = array_slice($properties, 0, 4);
        dump($properties);

        return $this->render('home/index.html.twig', [
            'properties' => $properties,
        ]);
    }
}
