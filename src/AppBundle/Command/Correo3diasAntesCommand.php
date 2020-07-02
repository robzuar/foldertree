<?php
namespace AppBundle\Command;


use AppBundle\Entity\Logmail;
use AppBundle\Entity\Anteproyecto;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class Correo3diasAntesCommand extends ContainerAwareCommand
{
    /**
     * @var ContainerInterface
     */
    private $container;

    protected function configure()
    {
        $this

            ->setName('correo:aviso3dias')
            ->setDescription('Correos de avisos 3 dias antes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine')->getManager();


        $num = 3;
        $entities = $em
            ->getRepository('AppBundle:Anteproyecto')
            ->getAnteproyectosByDays($num);
var_dump($entities);
        if($entities) {
            foreach ($entities as $anteproyecto) {
                $fecha = $anteproyecto->getDateexpiration();
                $fecha = $fecha->format("d-m-Y");

                $strBody = "<html>
                            <head></head>
                            <body>
                            <h1>
                                Estimado(a)
                            </h1>
                         ";
                $strBody .= "
                            <p>
                                En un plazo de " . $num . "  días, con fecha  " . $fecha . "  vence el siguiente anteproyecto : " . $anteproyecto->getNombre() . " 
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


                $strTo = $anteproyecto->getCreatedBy()->getEmail();
                $emailfrom = $this->getContainer()->getParameter('address');//'develsoftcl@gmail.com';//$input->getArgument('emailfrom');
                $emailto = $anteproyecto->getCreatedBy()->getEmail();//'klowncero@gmail.com';// $anteproyecto->getCreatedBy()


                if ($emailfrom && $emailto) {

                    $message = \Swift_Message::newInstance()
                        ->setSubject($strSubject)
                        ->setFrom($emailfrom)
                        ->setTo($strTo)
                        ->setContentType("text/html")
                        ->setBody($strBody);
                    $this->getContainer()->get('mailer')->send($message);
                    $logmail = new Logmail($anteproyecto->getCreatedBy(), 'correo3dias', $anteproyecto->getNombre());
                }

                $entgrupo = $em->getRepository('AppBundle:GrupoAnteproyecto')->findBy(['enabled' => 1]);
                if (count($entgrupo) >= 1) {
                    foreach ($entgrupo as $grupo) {
                        $entidad = $em->getRepository('AppBundle:GrupoAnteproyecto')->find($grupo);
                        $arraygrupotousuarios[] = $em->getRepository('AppBundle:Usuario')->getUserGrupoAnteProyecto($entidad);
                    }
                    $newarray = array_unique($arraygrupotousuarios[0]);
                    //var_dump($newarray);exit;

                    if (count($newarray) >= 1) {
                        foreach ($newarray as $usuario) {
                            //var_dump($usuario);exit;
                            $message = \Swift_Message::newInstance()
                                ->setSubject($strSubject)
                                ->setFrom($emailfrom)
                                ->setTo($usuario->getEmail())
                                ->setContentType("text/html")
                                ->setBody($strBody);
                            $this->getContainer()->get('mailer')->send($message);

                            $logmail = new Logmail($usuario, 'correo3dias', $anteproyecto->getNombre());
                            $em->persist($logmail);
                            $em->flush();
                        }
                    }
                }
            }
        }else{
            $user = $em->getRepository('AppBundle:Usuario')->findOneBy(
                [
                    'nombres'    => 'Roberto Alfredo',
                    'apellidos'     => 'Zuñiga Araya'
                ]
            );

            $logmail = new Logmail($user, 'correo3dias', 'Sin movimiento');
            $em->persist($logmail);
            $em->flush();
        }
    }
}
