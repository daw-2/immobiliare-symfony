<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function hello(Request $request)
    {
        $name = 'Matthieu';
        dump($name, [1, 2, 3], $request);

        // return new Response('<body>Hello '.$name.'</body>');
        return $this->render('welcome/hello.html.twig', [
            'name' => $name,
        ]);
    }
}
