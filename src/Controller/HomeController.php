<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="homepage")
     */
    public function home(UserRepository $repo): Response
    {

        $user_list = $repo->findAll();

        return $this->render('home/home.html.twig', [
            'users' => $user_list
        ]);
    }

    
}
