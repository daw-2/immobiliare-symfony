<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @Route("/nos-annonces", name="property_index")
     *
     * Permet de voir les annonces.
     */
    public function index(PropertyRepository $repository): Response
    {
        // Pour récupérer les annonces, 2 solutions
        // $properties = $this->getDoctrine()->getRepository(Property::class)->findAll();
        $properties = $repository->findAll();
        dump($properties);

        return $this->render('property/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/annonce/{id}", name="property_show")
     *
     * Permet de voir une seul annonce.
     */
    // public function show(PropertyRepository $repository, $id)
    public function show(Property $property)
    {
        /* $property = $repository->find($id);

        if (!$property) {
            throw $this->createNotFoundException();
        } */

        return $this->render('property/show.html.twig', [
            'property' => $property,
        ]);
    }
}
