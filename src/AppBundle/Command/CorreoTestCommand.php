<?php
namespace AppBundle\Command;

use AppBundle\Entity\Anteproyecto;
use AppBundle\Entity\Logmail;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class CorreoTestCommand extends ContainerAwareCommand
{
    /**
     * @var ContainerInterface
     */
    private $container;

    protected function configure()
    {
        $this

            ->setName('correo:correotest')
            ->setDescription('Correos de test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $strBody = "<html>
                            <head></head>
                            <body>
                            <h1>
                                Estimado(a)
                            </h1>
                         ";
        $strBody .= "
                            <p>
                                test 
                            </p>    
                     
                            ";
        $strBody .= "
                            <br>
                            <br>
                            <br>
                            <p>
                                Saludos cordiales,
                            </p>
                            <br>
                            <br>
                            <p>
                                <strong>
                                   Comunicaciones Imagina
                                </strong>
                            </p>
                            <br>
                            </body>
                        </html>";

        $strSubject = 'AVISO VENCIMIENTO ANTEPROYECTO';


        $strTo = 'roberto.zuniga.araya@gmail.com';
        $emailfrom = $this->getContainer()->getParameter('address');//'develsoftcl@gmail.com';//$input->getArgument('emailfrom');
        $emailto = 'roberto.zuniga.araya@gmail.com';//'klowncero@gmail.com';// $anteproyecto->getCreatedBy()


        if ($emailfrom && $emailto) {

            $message = \Swift_Message::newInstance()
                ->setSubject($strSubject)
                ->setFrom($emailfrom)
                ->setTo($strTo)
                ->setContentType("text/html")
                ->setBody($strBody);
            $this->getContainer()->get('mailer')->send($message);
        }
    }
}
