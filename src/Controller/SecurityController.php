<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Services\MessageService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Message;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/inscription", name="secu_registration")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface  $encoder
     * @param MailerService $mailerService
     * @return Response
     */
    public function registration(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface  $encoder, \Swift_Mailer $mailer, MessageService $messageService) {
   
        $user = new User();

    

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();
            
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $em->persist($user);
            $em->flush();
        
            
            // Envoi mail
            $email = $data->getEmail();
            $message = (new \Swift_Message("Nouveau Contact"))
            ->setFrom('do_not_reply@gmail.com')
            ->setTo('fabien.orsn@gmail.com')
            ->setBody(
                $this->renderView('email/inscription.html.twig', ['email' => $email]),
                'text/html'
            );

            $mailer->send($message);
            
            $messageService->addSuccess('Votre compte est bien crée');

            
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/admin", name="security_admin")
     */
    public function admin()
    {
        return $this->render('security/admin.html.twig', []);
    }

    /**
     * @Route("/profile/{id}", name="security_profile")
     */
    public function profile($id)
    {
        return $this->render('security/profile.html.twig', [
            'user' => $this->getUser($id)->getUsername()
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
