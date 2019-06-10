<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Taskdocument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Log;
use vakata\database\Exception;

/**
 * Taskdocument controller.
 *
 * @Route("taskdocument")
 */
class TaskdocumentController extends CrudController
{
    const ENTITY_NAME = "Taskdocument";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Taskdocument";
    const TYPE_NAMESPACE = "AppBundle\\Form\\TaskdocumentType";
    const SINGULAR_NAME = "Tarea con documento";
    const PLURAL_NAME = "Tareas con documentos";

    /**
     * Creates a new entity.
     * @param Request $request
     * @param $idDoc
     * @param $idProyecto
     * @Route("/new", name="app_taskdocument_new")
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function newtaskAction(Request $request, $idDoc, $idProyecto)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $proyecto = $em->getRepository('AppBundle:Proyectogo')->find($idProyecto);
        $doc = $em->getRepository('AppBundle:Document')->find($idDoc);
        $pendiente = $em->getRepository('AppBundle:Goestado')->findOneBy(
            [
                'name'  => 'PENDIENTE'
            ]
        );

        $entity = new Taskdocument($user, $proyecto, $doc, $pendiente);
        $em->persist($entity);
        $em->flush();

        return new Response('success');
    }

    /**
     * Finds and displays a entity.
     * @param $id
     * @Route("/viewtasksfirst/{id}", name="app_taskdocument_viewtasks")
     * @Method("POST")
     * @Template()
     * @return array
     */
    public function viewtasksAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $proyectoGo = $em->getRepository('AppBundle:Proyectogo')->find($id);
        $entities = $em->getRepository('AppBundle:Taskdocument')->FindBy([
            'proyectogo'    => $proyectoGo
        ]);

        return [
            'entities' => $entities,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $id
     * @Route("/rejecttask/{id}", name="app_taskdocument_reject")
     * @Method({"GET", "POST"})
     * @return array|Response
     */
    public function rejecttask($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Taskdocument')->find($id);
        $estado = $em->getRepository('AppBundle:Goestado')->findOneBy(
            [
                'name'  => 'RECHAZADO'
            ]
        );

        $entity->setEstado($estado);
        $em->persist($entity);
        $em->flush();

        $this->emailTask('CHECKER', 'RECHAZADO', $id);

        return new Response('success');
    }

    /**
     * @param $id
     * @Route("/approvetask/{id}", name="app_taskdocument_approve")
     * @Method({"GET", "POST"})
     * @return Response
     * @throws \Exception
     */
    public function approvetask($id)
    {
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime();
        //$fecha = $fecha->format('Y-m-d');
        $estado = $em->getRepository('AppBundle:Goestado')->findOneBy(
            [
                'name'  => 'COMPLETADO'
            ]
        );
        $entity = $em->getRepository('AppBundle:Taskdocument')->find($id);
        $entity->setEstado($estado);
        $entity->setDatecompleted($fecha);
        $em->persist($entity);
        $em->flush();

        $this->emailTask('CHECKER', 'COMPLETADO', $id);

        return new Response('success');
    }

    /**
     * @param $id
     * @Route("/requestreview/{id}", name="app_taskdocument_requesreview")
     * @Method({"GET", "POST"})
     * @return Response
     * @throws \Exception
     */
    public function requestreview($id)
    {
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime();
        //$fecha = $fecha->format('Y-m-d');
        $estado = $em->getRepository('AppBundle:Goestado')->findOneBy(
            [
                'name'  => 'REVISION'
            ]
        );
        $entity = $em->getRepository('AppBundle:Taskdocument')->find($id);
        $doc = $em->getRepository('AppBundle:Document')->find($entity->getDocument()->getId());
        $files = $em->getRepository('AppBundle:Gofile')->findBy(['task' => $entity]);

        if(count($files) > 0){
            $number = $doc->getNumfiles();
            if(is_null($number) || $number == count($files)){
                $entity->setEstado($estado);
                    try {
                        $em->persist($entity);
                        $em->flush();
                        $this->emailTask('ASSISTENT', 'REVISION', $id);

                        $return = 'success';
                    }catch (Exception $e){
                        $return = 'error';
                    }

            }else{
                $return = 'incomplete';
            }
        }else{
            $return = 'nofile';
        }
        return new Response($return);
    }

    /**
     * @param $id
     * @Route("/requestreviewtask/{id}", name="app_taskdocument_requestreview")
     * @Method({"GET", "POST"})
     * @return Response
     * @throws \Exception
     */
    public function requestreviewtask($id)
    {
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime();
        //$fecha = $fecha->format('Y-m-d');
        $estado = $this->getEstado('REVISION');
        $entity = $em->getRepository('AppBundle:Taskdocument')->find($id);
        $entity->setEstado($estado);
        $em->persist($entity);
        $em->flush();

        $this->emailTask('ASSISTENT', 'REVISION', $id);

        return new Response('success');
    }

    function getEstado($string){
        $em = $this->getDoctrine()->getManager();
        $estadoRevision = $em->getRepository('AppBundle:Goestado')->findOneBy(
            [
                'name'  => $string
            ]
        );

        return $estadoRevision;
    }

    /**
     * @param $idtask
     * @param $status
     * @param $work
     * @return bool
     */
    public function emailTask($work, $status, $idtask)
    {
        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository('AppBundle:Taskdocument')->find($idtask);
        $person = "";
        $user = "";

        if($work === 'CHECKER'){
            $person = $em->getRepository('AppBundle:Gochecker')->findOneBy([
                    'task'  => $task
            ]);
            $user = $em->getRepository('AppBundle:Usuario')->find($person->getChecker());
        }else{
            $person = $em->getRepository('AppBundle:Goassistent')->findOneBy([
                    'task'  => $task
            ]);
            $user = $em->getRepository('AppBundle:Usuario')->find($person->getAssistent());
        }

        $parametroemail = $this->get('service_container')->getParameter('sendemail');
        if ($parametroemail == true) {
            $strTo = $user->getEmail();
        } else {
            $strTo = 'roberto.zuniga.araya@gmail.com';
        }

        $strSubject = 'Se ha '.$status.' un Proyecto ';
        $strBody = $this->renderView('AppBundle:Default:goemail.html.twig',
            [
                'receptor' => $user,
                'status' => $status,
                'task' => $task

            ]
        );


        $message = (new \Swift_Message('My important subject here'))
            ->setFrom($this->container->getParameter('mailer_user'))
            ->setTo($strTo)
            ->setSubject($strSubject)
            ->setBody($strBody, 'text/html')
        ;
        $this->get('mailer')->send($message);

        return true;//new Response('success');
    }
}