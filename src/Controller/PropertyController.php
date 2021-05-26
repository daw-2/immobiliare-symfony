<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/annonce/creer", name="property_create")
     */
    public function create(Request $request)
    {
        $property = new Property();
        $form = $this->createFormBuilder($property)
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('price', MoneyType::class, ['divisor' => 100])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            dump($property);
        }

        return $this->render('property/create.html.twig', [
            'form' => $form->createView(),
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
