<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Category;
use App\Entity\Property;
use App\Form\BookingType;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PropertyController extends AbstractController
{
    /**
     * @Route("/nos-annonces", name="property_index")
     *
     * Permet de voir les annonces.
     */
    public function index(Request $request, PropertyRepository $repository): Response
    {
        // dump($request->get('surface'));
        $properties = $repository->findAllWithFilters(
            $request->get('surface'), $request->get('budget'), $request->get('category')
        );
        dump($properties);

        // Si on fait une recherche...
        if ($request->get('search')) {
            $properties = $repository->search($request->get('search'));
        }

        // Pour récupérer les annonces
        // $properties = $repository->findAll();
        // dump($properties);

        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            'categories' => $this->getDoctrine()->getRepository(Category::class)->findAll(),
        ]);
    }

    /**
     * @Route("/annonce/creer", name="property_create")
     */
    public function create(Request $request, SluggerInterface $slugger)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $property = new Property();
        // On va lier l'annonce à l'utilisateur connecté
        $property->setOwner($this->getUser());
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            dump($property);
            // L'annonce trop cool => L-annonce-trop-cool => l-annonce-trop-cool
            $property->setSlug(
                $slugger->slug($property->getName())->lower()
            );
            // On insère l'annonce dans la BDD...
            // On récupére l'entity manager (em)
            $em = $this->getDoctrine()->getManager();
            $em->persist($property);
            $em->flush();

            // Ajout du message de succès
            $this->addFlash('success', 'L\'annonce a bien été ajoutée.');
            // $this->addFlash('danger', 'Test erreur');

            return $this->redirectToRoute('property_index');
        }

        return $this->render('property/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annonce/{slug}_{id}", name="property_show")
     *
     * Permet de voir une seule annonce.
     */
    // public function show(PropertyRepository $repository, $id)
    public function show(Request $request, Property $property)
    {
        /* $property = $repository->find($id);

        if (!$property) {
            throw $this->createNotFoundException();
        } */

        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_USER');

            // Calcul du prix de la réservation... 10% du prix d'achat / 12 / 20
            // Dans Booking, on va créer une méthode getDays()
            // et dans Property, getDayPrice()
            $booking->setProperty($property);
            $booking->setPrice(
                $property->getDayPrice() * count($booking->getDays())
            );
            $booking->setUser($this->getUser());

            // Vérification si la réservation est possible
            // par rapport aux réservations existantes...
            if ($booking->isValid()) {
                $this->addFlash(
                    'success',
                    "Votre réservation du {$booking->getStartDate()->format('d/m/Y')} au {$booking->getEndDate()->format('d/m/Y')} de {$booking->getPrice()} euros est bien prise en compte."
                );

                $em = $this->getDoctrine()->getManager();
                $em->persist($booking);
                $em->flush();
            } else {
                // Erreur sur les dates...
                $this->addFlash('danger', 'Les dates ne sont pas disponibles.');
            }
        }

        return $this->render('property/show.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annonce/{id}/editer", name="property_edit")
     */
    public function edit(Request $request, SluggerInterface $slugger, Property $property)
    {
        // Seul le propriétaire de l'annonce peut modifier l'annonce
        if ($property->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $property->setSlug(
                $slugger->slug($property->getName())->lower()
            );

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'annonce a bien été modifiée.');

            return $this->redirectToRoute('property_index');
        }

        return $this->render('property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annonce/{id}/supprimer", name="property_delete")
     */
    public function delete(Request $request, Property $property)
    {
        if ($property->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $token = $request->get('token');

        if (!$this->isCsrfTokenValid('delete-property', $token)) {
            $this->addFlash('danger', 'FAILLE CSRF');
            return $this->redirectToRoute('property_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($property);
        $em->flush();

        $this->addFlash('danger', 'L\'annonce a été supprimée.');

        return $this->redirectToRoute('property_index');
    }
}
