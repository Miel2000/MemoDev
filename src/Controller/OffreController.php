<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use App\Services\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OffreController extends AbstractController
{
    /**
     * @Route("/offre", name="offres")
     */
    public function index(OffreRepository $offres, UserInterface $user): Response
    {

        $all_offres_from_one_user = $offres->findBy(['user' => $this->getUser()->getId()]);

        return $this->render('offre/index.html.twig', [
            'controller_name' => 'OffreController',
            'offres' => $all_offres_from_one_user
        ]);
    }

    /**
     * @Route("/offre/creation", name="offre_creation")
     * @param Request $req
     * @param OffreRepository $offre
     * @param EntityManagerInterface $manager
     * @param MessageService $messageService
     * @return Response
     */
    public function create(
        Request $req,
        OffreRepository $offre,
        EntityManagerInterface $manager,
        UserInterface $user,
        MessageService $messageService
        ): Response
    {
        
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre , [
            'method' => 'POST'
            ]);
            
        // $offre->setChrono(1);
        $form->handleRequest($req);
        
        if($form->isSubmitted() && $form->isValid()){
           
            // dd($chrono);
            // $offre->setChrono($chrono++);
            $offre->setUser($user);
           
            $manager->persist($offre);
            $manager->flush();
            
            $messageService->addSuccess('Votre offre est enregistrée en base de donnée');
             return $this->redirectToRoute('offres');
        } 
         
        return $this->render('offre/create.html.twig', [
            'form' => $form->createView(),
       
        ]);
    }

    /**
     * @Route("/offre/detail/{id}", name="offre_details")
     */
    public function detail($id, OffreRepository $offre): Response
    {

        $detail_une_offre = $offre->findBy([ 'id' => $id]);
        
        return $this->render('offre/detail.html.twig', [
          'offre' => $detail_une_offre

        ]); 
    }

    /**
     * @Route("/offre/modification/{id}", name="offre_modification")
     * @param Request $req
     * @param Offre $offre
     * @param EntityManagerInterface $manager
     * @param UserInterface $user
     * @param MessageService $messageService
     * @return Response
     */
    public function modifier( 
     Request $req,
     Offre $offre,
     EntityManagerInterface $manager,
     UserInterface $user,
     MessageService $messageService ): Response
    {

        $form = $this->createForm(OffreType::class, $offre, [
            'method' => 'POST'
        ]);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            $offre->setUser($user);

            $manager->persist($offre);
            $manager->flush();

            $messageService->addSuccess('Votre offre est bien modifée');


            return $this->redirectToRoute('offres');
        }

        return $this->render('offre/create.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/offre/suppression/{id}", name="offre_suppression")
     */
    public function suppression($id, EntityManagerInterface $em, Offre $offre): Response 
    {

        $em->remove($offre);
        $em->flush();

        return $this->redirectToRoute('offres');
    }


}
