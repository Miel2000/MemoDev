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
        
        // ************* DO NOT DELETE *******************
        // $userId = $this->getUser()->getId();
        // $offres = $offres->findBy(['id' => $userId]);
        // dd($offres->findBy(['id' => $this->getUser()])); 
        // dd($this->getUser()->getEmail()); 
        //  dd($offres->findAll());
        //  $all_offres = $offres->findAll();
        //  foreach( $all_offres as $offre) {
        //     if($offre->getUser()->getId() == $userId){
        //         dd($offre);
        //     };
        //  }
        // dd($offres->findBy(['id' => $this->getUser('id')]));

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
    public function create(Request $req, OffreRepository $offre, EntityManagerInterface $manager, UserInterface $user, MessageService $messageService ): Response
    {
  
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre , [
            'method' => 'POST'
        ]);

        $form->handleRequest($req);
     
        if($form->isSubmitted() && $form->isValid()){

            
            
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

        //  dd($id);
        $detail_une_offre = $offre->findBy([ 'id' => $id]);
        
      
        // dd($offre->findBy(['user' => $this->getUser($id) ]));

        return $this->render('offre/detail.html.twig', [
          'offre' => $detail_une_offre

        ]); 
    }

    /**
     * @Route("/offre/modification/{id}", name="offre_modification")
     */
    public function modifier(Request $req, Offre $offre, EntityManagerInterface $manager, UserInterface $user): Response
    {

        //  dd($req);
      
        $form = $this->createForm(OffreType::class, $offre, [
            'method' => 'POST'
        ]);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            $offre->setUser($user);

            $manager->persist($offre);
            $manager->flush();


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

        // $monOffre = $offre->findBy(['id' => $id]);
        $em->remove($offre);
        $em->flush();

        return $this->redirectToRoute('offres');
        // dd($id);

        return $this->render('offre/create.html.twig', [
           'id' => $id
        ]);
    }


}
