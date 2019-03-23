<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Gochecker;
/**
 * Gochecker controller.
 *
 * @Route("gochecker")
 */
class GocheckerController extends CrudController
{
    const ENTITY_NAME = "Gochecker";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Gochecker";
    const TYPE_NAMESPACE = "AppBundle\\Form\\GocheckerType";
    const SINGULAR_NAME = "Revisor";
    const PLURAL_NAME = "Revisores";

    /**
     * Creates a new entity.
     * @param $idTask
     * @param $idSelection
     * @param $idProyecto
     * @Route("/newasign/{idTask}/{idSelection}/{idProyecto}", name="app_gochecker_newasign")
     * @Method("POST")
     * @return Response
     */
    public function newtaskAction( $idSelection, $idTask, $idProyecto)
    {
        //echo $idSelection;
        //echo $idTask;exit();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $checker = $em->getRepository('AppBundle:Usuario')->find($idSelection);
        $proyecto = $em->getRepository('AppBundle:Proyectogo')->find($idProyecto);
        $task = $em->getRepository('AppBundle:Taskdocument')->findOneBy(
            [
                'document'  => $idTask,
                'proyectogo'    => $proyecto
            ]
        );
        //$task->setAsignedat(new \DateTime());
        $em->persist($task);
        $em->flush();
        $doc = $em->getRepository('AppBundle:Document')->find($idTask);
        if($doc->getIsfile()) {
            $entity = new Gochecker($user);
            $entity->setChecker($checker);
            $entity->setTask($task);
            try {
                $em->persist($entity);
                $em->flush();
            } catch(\Doctrine\DBAL\DBALException $e) {
                return new Response('duplicate');
            }

            return new Response('success');
        }else{
            return new Response('folder');
        }
    }

    /**
     * Creates a new entity.
     * @param $idTask
     * @param $idPerson
     * @param $idProyecto
     * @Route("/removeasign/{idPerson}/{idTask}/{idProyecto}", name="app_gochecker_removeasign")
     * @Method("POST")
     * @return Response
     */
    public function removetaskAction( $idPerson, $idTask, $idProyecto)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Taskdocument')->findOneBy(
            [
                'proyectogo'  => $idProyecto,
                'document'    => $idTask
            ]
        );
        $gotask = $em->getRepository('AppBundle:Gochecker')->findOneBy(
            [
                'task'  => $task->getId(),
                'checker'    => $idPerson
            ]
        );
        try {
            $em->remove($gotask);
            $em->flush();
            return new Response('success');
        } catch(\Doctrine\DBAL\DBALException $e) {
            return new Response('error');
        }
    }

    /**
     * Creates a new entity.
     * @param $idTask
     * @param $idSelection
     * @param $idProyecto
     * @Route("/newasigngroup/{idTask}/{idSelection}/{idProyecto}", name="app_gochecker_newasigngroup")
     * @Method("POST")
     * @return Response
     */
    public function newgroupassignAction( $idSelection, $idTask, $idProyecto)
    {
        $arrayTask = explode(',', $idTask);
        $arrayCheckers = explode(',', $idSelection);
        //var_dump($arrayTask);var_dump($arrayCheckers);die();
        $contador = 0;
        $error = 0;
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $proyecto = $em->getRepository('AppBundle:Proyectogo')->find($idProyecto);

        foreach ($arrayTask as $taskid) {
            $task = $em->getRepository('AppBundle:Taskdocument')->findOneBy(
                [
                    'document' => $taskid,
                    'proyectogo' => $proyecto
                ]
            );
            //var_dump($taskid);die();
            $doc = $em->getRepository('AppBundle:Document')->find(intval($taskid));
            if ($doc->getIsfile()) {
                foreach ($arrayCheckers as $newchecker) {
                    $contador = $contador + 1;
                    $entity = new Gochecker($user);
                    $checker = $em->getRepository('AppBundle:Usuario')->find(intval($newchecker));
                    $entity->setChecker($checker);
                    $entity->setTask($task);
                    try {
                        $em->persist($entity);
                        $em->flush();
                    } catch (\Doctrine\DBAL\DBALException $e) {
                        $error = $error + 1;
                    }
                }
            }
        }
        return new Response('success');
    }
}