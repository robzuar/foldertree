<?php
namespace AppBundle\Util;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;

class MailHelper
{
    protected $mailer;
    protected $em;
    protected $container;

    public function __construct(\Swift_Mailer $mailer, EntityManager $em, Container $container)
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->em = $em;
        $this->container = $container;
    }

    public function sendEmail($allvalues)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $userlogued = $this->em->getRepository('AppBundle:Usuario')->find($user->getId());
        /** @var Usuario $user */
        $parametroemail = $this->container->get('service_container')->getParameter('sendemail');
        $arraygrupotousuarios = [];


        $entgrupo = $this->em->getRepository('AppBundle:GrupoCambios')->findBy(['enabled' => 1]);

        if(count($entgrupo) >= 1) {
            foreach ($entgrupo as $grupo) {
                $entidad = $this->em->getRepository('AppBundle:Group')->find($grupo);
                $arraygrupotousuarios[] = $this->em->getRepository('AppBundle:Usuario')->getUserCambios($entidad);
            }
        }

        $newarray = array_unique($arraygrupotousuarios);

        if(count($newarray)  >= 1){
            $subject = 'Se ha Creado un nuevo Archivo: ';

            foreach ($arraygrupotousuarios as $usuario) {


                $strTo = $usuario->getEmail();

                $strSubject = $subject;
                $strBody = $this->renderView('AppBundle:Fileimg:correoaviso.html.twig',
                    [
                        'creador' => $userlogued->getNombres() . ' ' . $userlogued->getApellidos(),
                        'receptor' => $usuario,
                        'allvalues' => $allvalues

                    ]
                );

                $message = (new \Swift_Message('My important subject here'))
                    ->setFrom($this->container->getParameter('mailer_user'))
                    ->setTo($strTo)
                    ->setSubject($strSubject)
                    ->setBody($strBody, 'text/html')
                ;
                $this->get('mailer')->send($message);
            }
        }
        return true;//new Response('success');
    }
}