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
use AppBundle\Controller\CrudController;

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

        $strBody='Testom';
        $strSubject = 'Test';
        $strTo = 'roberto.zuniga.araya@gmail.com';

        $mailer =  $this->getContainer()->get('app_mailer');
        dump($mailer->sendEmail($strTo, $strSubject,$strBody));

        echo 'fin';

    }
}
