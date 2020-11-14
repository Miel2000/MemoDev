<?php 

namespace App\Services;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;


class MailerService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var Environement;
     */
    private $twig;

    /**
     * MailerService contrusctor
     * @param MailerInterface $mailer
     * @param Environment $twig
     */

     public function __construct(MailerInterface $mailer, Environment $twig)
     {
         $this->mailer = $mailer;
         $this->twig = $twig;
     }

     /**
      * @param string $subject 
      * @param string $from 
      * @param string $to 
      * @param string $template 
      * @param array $parameters 
      * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface 
      * @throws \Twig\Error\LoaderError 
      * @throws \Twig\Error\RuntimeError 
      * @throws \Twig\Error\SyntaxeError 
      */ 
      public function send(string $subject, string $from, string $to, string $template, array $parameters)
      {
          $email = (new Email())
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->html(
                    $this->twig->render($template, $parameters), 'text/html'
                );

            $this->mailer->send($email);
      }
     
}