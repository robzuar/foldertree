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
        $this->templating = $templating;
        $this->container = $container;
    }

    /**
     * @param $request
     * @param $subject
     * @param $body
     * @param $comment
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendEmail($request, $subject, $body,  $comment)
    {
        $emails= [];

        if($body == 'comment') {
            $body = $this->templating->render('email\new_comment.html.twig',
                [
                    'request' => $request,
                    'comment' => $comment

                ]
            );
            //$emails[] = $comment->getUsr
        }else if($body == 'newrequest'){
            $body = $this->templating->render('email\new_ticket.html.twig',
                [
                    'request' => $request

                ]
            );
            $emailSupports = $this->findEmailUserSupports();

            foreach ($emailSupports as $emailSupport) {
                $emails[] = $emailSupport;
            }
        }
        $emails[] = $request->getUser()->getLogin();

        if(!is_null($request->getEmail())){
            $emails[] = $request->getEmail();
        }

        $addresses = array_unique($emails);
        //var_dump($addresses);die();

        foreach ($addresses  as $email){
            $message = (new \Swift_Message('My important subject here'))
                ->setFrom($this->container->getParameter('mailer_sender'))
                ->setTo($email)
                ->setSubject($subject)
                ->setBody($body, 'text/html')
            ;
            $this->mailer->send($message);
        }
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