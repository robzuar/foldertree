<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class MailService
{
    private $mailer;
    private $templating;
    private $container;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating,ContainerInterface $container)
    {
        $this->mailer     = $mailer;
    }

    /**
     * @param $to
     * @param $subject
     * @param $body
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendEmail($to, $subject, $body)
    {
            $from = 'klowncero@gmail.com';
            $message = (new \Swift_Message('My important subject here'))
                ->setFrom($from)
                ->setTo($to)
                ->setSubject($subject)
                ->setBody($body, 'text/html')
            ;
            return $this->mailer->send($message);


    }

    public function findEmailUserSupports(){

        $em = $this->container->get('doctrine')->getEntityManager();
        $teamSupport = $em->getRepository('AppBundle:Team')->findBy(['support' => true]);
        $supportUsers = $em->getRepository('AppBundle:Teamuser')->findBy(['team' => $teamSupport]);
        $supports = [];

        foreach ($supportUsers as $supportUser){
            $supports[] = $supportUser->getUser()->getLogin();
        }
        return $supports;
    }
}