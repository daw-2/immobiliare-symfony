<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'Votre message a été envoyé');

            $email = (new Email())
                ->from($contact->getEmail())
                ->to('hello@immobiliare.com')
                ->subject('[Demande] Un nouveau contact')
                ->html('
                    On a une nouvelle demande de contact <br /> <br />
                    - Email: '.$contact->getEmail().' <br />
                    - Nom: '.$contact->getName().' <br />
                    - Message: '.$contact->getMessage().'
                ');

            $mailer->send($email);

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
