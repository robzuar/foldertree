<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Goassistent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Goassistent controller.
 *
 * @Route("goassistent")
 */
class GoassistentController extends CrudController
{
    const ENTITY_NAME = "Goassistent";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Goassistent";
    const TYPE_NAMESPACE = "AppBundle\\Form\\GoassistentType";
    const SINGULAR_NAME = "Asistente";
    const PLURAL_NAME = "Asistentes";

    /**
     * Creates a new entity.
     * @param $idTask
     * @param $idSelection
     * @param $idProyecto
     * @Route("/newasign/{idTask}/{idSelection}/{idProyecto}", name="app_goassistent_newasign")
     * @Method("POST")
     * @return Response
     */
    public function newtaskAction( $idSelection, $idTask, $idProyecto)
    {
        //echo $idSelection;
        //echo $idTask;exit();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $assistent = $em->getRepository('AppBundle:Usuario')->find($idSelection);
        $proyecto = $em->getRepository('AppBundle:Proyectogo')->find($idProyecto);
        $task = $em->getRepository('AppBundle:Taskdocument')->findOneBy(
            [
                'document'  => $idTask,
                'proyectogo'    => $proyecto
            ]
        );
        $doc = $em->getRepository('AppBundle:Document')->find($idTask);
        if($doc->getIsfile()) {
            $entity = new Goassistent($user);
            $entity->setAssistent($assistent);
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
     * @Route("/removeasign/{idPerson}/{idTask}/{idProyecto}/", name="app_goassistent_removeasign")
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
        $gotask = $em->getRepository('AppBundle:Goassistent')->findOneBy(
            [
                'task'  => $task->getId(),
                'assistent'    => $idPerson
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
     * @Route("/newasigngroup/{idTask}/{idSelection}/{idProyecto}", name="app_goassistent_newasigngroup")
     * @Method("POST")
     * @return Response
     */
    public function newgroupassignAction( $idSelection, $idTask, $idProyecto)
    {
        $arrayTask = explode(',', $idTask);
        $arrayAssistents = explode(',', $idSelection);
        ///var_dump($arrayTask);var_dump($arrayAssistents);die();
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
                foreach ($arrayAssistents as $newassistent) {
                    $contador = $contador + 1;
                    $this->setAssistentToGroup($task, $newassistent);
                }
            }
        }
        return new Response('success');
    }

    public function setAssistentToGroup($task, $newassistent){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $error = 0;
        $goassistent = $em->getRepository('AppBundle:Goassistent')->findOneBy(
            [
                'assistent'   => intval($newassistent),
                'task'      => $task
            ]);
        if(is_null($goassistent)) {
            $entity = new Goassistent($user);
            $assistent = $em->getRepository('AppBundle:Usuario')->find(intval($newassistent));
            $entity->setAssistent($assistent);
            $entity->setTask($task);
            $em->persist($entity);
            $em->flush();
        }
    }
}