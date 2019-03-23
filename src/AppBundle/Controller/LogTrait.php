<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Log;


/**
 * Class LogTrait
 * @package AppBundle\Controller
 */
trait LogTrait
{
    /**
     * Do the magic.
     *
     * @param  $string
     */
    public function onActionMain($string)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();
        $log = new Log($usuario);
        $log->setAccion($string);
        $em->persist($log);
        $em->flush();
    }
}
