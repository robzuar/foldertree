<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Log;

/**
 * Class CrudController
 * @package AppBundle\Controller
 */
abstract class CrudController extends Controller
{
    const ENTITY_NAME = null;
    const ENTITY_NAMESPACE = null;
    const TYPE_NAMESPACE = null;
    const PLURAL_NAME = null;
    const SINGULAR_NAME = null;

    /**
     * @Route("/")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return [
            'template' => 'index',
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME,
            'routes' => $this->getRoutesForEntity()
        ];
    }

    /**
     * Lists all entities.
     *
     * @Route("/all/results")
     * @Method("GET")
     * @Template()
     */
    public function resultsAction()
    {
        $entities = $this->getRepository()->findAll();

        return [
            'entities' => $entities,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Creates a new entity.
     * @param Request $request
     * @Route("/new")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function newAction(Request $request)
    {
        $entityName = $this::ENTITY_NAMESPACE;
        $entity = new $entityName;
        $form = $this->createForm($this::TYPE_NAMESPACE, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->setLog($entity, 'Nuevo');

            return new Response('success');
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
            'routes' => $this->getRoutesForEntity(),
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Finds and displays a entity.
     * @param $id
     * @Route("/{id}")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }
        return [
            'entity' => $entity,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $request
     * @param $id
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function editAction(Request $request, $id)
    {
        $entityName = $this::ENTITY_NAMESPACE;
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createForm($this::TYPE_NAMESPACE, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->setLog($entity, 'Editar');

            return new Response('success');
        }

        return [
            'entity' => $entity,
            'form' => $editForm->createView(),
            'routes' => $this->getRoutesForEntity(),
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Delete a entity.
     * @Route("/{id}")
     * @Method("POST")
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $entityName = $this::ENTITY_NAMESPACE;
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        $this->setLog($entity, 'Eliminar');

        return new Response('success');
    }

    /**
     * Enabled Usuario entity.
     *
     * @Route("/{id}/enabled")
     * @Method("POST")
     * @param $id
     * @return Response
     */
    public function enabledAction($id)
    {
        $entityName = $this::ENTITY_NAMESPACE;
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }

        $em = $this->getDoctrine()->getManager();

        if($entity->getEnabled(true)){
            $entity->setEnabled(false);
        }else{
            $entity->setEnabled(true);
        }

        $em->persist($entity);
        $em->flush();

        $this->setLog($entity, 'Activo/no Activo');

        return new Response('success');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function getRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return $repository = $em->getRepository('AppBundle:'.$this::ENTITY_NAME);
    }


    /**
     * @return array
     */
    public function getRoutesForEntity()
    {
        return [
            'results' => "app_".strtolower($this::ENTITY_NAME)."_results",
            'show' => "app_".strtolower($this::ENTITY_NAME)."_show",
            'new' => "app_".strtolower($this::ENTITY_NAME)."_new",
            'edit' => "app_".strtolower($this::ENTITY_NAME)."_edit",
            'delete' => "app_".strtolower($this::ENTITY_NAME)."_delete",
            'enabled' => "app_".strtolower($this::ENTITY_NAME)."_enabled"
        ];
    }

    public function setLog($entity, $accion){
        $em = $this->getDoctrine()->getManager();
        $nombre = "";
        $path = "";
        $entidad = "";
        if($this::ENTITY_NAME == "Category"){
            $entidad = 'Carpeta';
            $nombre = $entity->getTitle();
            $cat = $em->getRepository('AppBundle:Category');
            $trees = $cat->getPath($entity);
            foreach ($trees as $tree){
                $path = $path .' / '.$tree;
            }
        }elseif ($this::ENTITY_NAME  == "Fileimg"){
            $entidad = 'Archivo';
            $nombre = $entity->getLink();
            $cat = $em->getRepository('AppBundle:Category');
            $trees = $cat->getPath($entity);
            foreach ($trees as $tree){
                $path = $path .' / '.$tree;
            }
        }elseif ($this::ENTITY_NAME  == "Group"){
            $entidad = 'Grupo de Correo';
            $nombre = $entity->getName();
        }elseif ($this::ENTITY_NAME  == "Permiso"){
            $entidad = 'Grupo de Permiso';
            $nombre = $entity->getName();
        }elseif ($this::ENTITY_NAME  == "Proyecto"){
            $entidad = 'Proyecto';
            $nombre = $entity->getNombre();
        }elseif ($this::ENTITY_NAME  == "Usuario"){
            $entidad = 'Usuario';
            $nombre = $entity->getNombres(). "" . $entity->getApellidos();
        }elseif ($this::ENTITY_NAME  == "Anteproyecto"){
            $entidad = 'Anteproyecto';
            $nombre = $entity->getNombre();
        }
        $user = $this->getUser();
        $log = new Log($user);
        $log->setEntidadId($entity->getId());
        $log->setNombre($nombre);
        $log->setPath($path);
        $log->setAccion($accion);
        $log->setEntidad($entidad);
        $em->persist($log);
        $em->flush();
    }

    public function sendEmailNew($entity, $subject, $body,  $comment, $receivers)
    {
        $emails= [];
        $emailsReceivers= [];

        if($body == 'startproject') {
            $body = $this->renderView('@App/Emails/newemail.html.twig',
                [
                    'entity'    => $entity

                ]
            );

            foreach ($receivers as $receiver){
                $emails[] = $receiver->getEmail();
            }

        }

        $addresses = array_unique(array_merge($emails, $emailsReceivers));
        //$addresses = 'roberto.zuniga.araya@gmail.com';

        $message = (new \Swift_Message('My important subject here'))
            ->setFrom($this->container->getParameter('mailer_user'))
            ->setTo($addresses)
            ->setSubject($subject)
            ->setBody($body, 'text/html')
        ;
        $this->get('mailer')->send($message);

    }
}
