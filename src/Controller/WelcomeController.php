<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/demo", name="demo")
     */
    public function demo(Request $request)
    {
        $name = 'Matthieu';
        dump($name, [1, 2, 3], $request);

        // return new Response('<body>Hello '.$name.'</body>');
        return $this->render('welcome/hello.html.twig', [
            'name' => $name,
        ]);
    }

    /**
     * @Route("/hello/{name}", name="hello", requirements={"name"="[a-z]{3,8}"})
     * @Route("/adresse/{type}", name="hello", requirements={"type"="facturation|livraison"})
     */
    public function hello($name = 'matthieu')
    {
        return $this->render('welcome/hello.html.twig', [
            'name' => ucfirst($name),
        ]);
    }
}
