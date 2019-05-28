<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Proyecto;
use AppBundle\Entity\Proyectogo;
use AppBundle\Entity\Taskdocument;
use AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Proyectogo controller.
 *
 * @Route("proyectogo")
 */
class ProyectogoController extends CrudController
{
    const ENTITY_NAME = "Proyectogo";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Proyectogo";
    const TYPE_NAMESPACE = "AppBundle\\Form\\ProyectogoType";
    const SINGULAR_NAME = "Proyecto Go";
    const PLURAL_NAME = "Proyectos Go";

    /**
     * @Route("/index", name="app_proyectogo_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Document')->findAll();

        return [
            'documents' => $entities,
            'template' => 'index',
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME,
            'routes' => $this->getRoutesForEntity()
        ];
    }

    /**
     * Lists all document entities.
     *
     * @Route("/results", name="app_proyectogo_results")
     * @Method("GET")
     * @Template()
     */
    public function resultsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Proyectogo')->findAll();

        return [
            'entities'      => $entities,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Creates a new entity.
     * @param Request $request
     * @Route("/proyectogo/new")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function newAction(Request $request)
    {
        $entity = new Proyectogo();
        $form = $this->createForm($this::TYPE_NAMESPACE, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //dump($docs);
            $user =$this->getUser();
            $pendiente = $em->getRepository('AppBundle:Goestado')->findOneBy(
                [
                    'name'  => 'PENDIENTE'
                ]
            );
            $estadoFolder = $em->getRepository('AppBundle:Goestado')->findOneBy(
                [
                    'name'  => 'FOLDER'
                ]
            );
            $docs = $em->getRepository('AppBundle:Document')->findBy(['enabled' =>true]);

            foreach ($docs as $doc){
                if($doc->getIsfile() == true) {
                    $newEnt = new Taskdocument($user, $entity, $doc, $pendiente);
                }else{
                    $newEnt = new Taskdocument($user, $entity, $doc, $estadoFolder);
                }
                $em->persist($newEnt);
                $em->flush();
            }

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
     * Lists all document entities.
     * @param $id
     * @Route("/doctree/{id}", name="app_proyectogo_treeview")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function doctreeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('AppBundle:Document');
        $treeDoc = $em->getRepository('AppBundle:Taskdocument')->getDocByProject($id);
        $treeTask = $em->getRepository('AppBundle:Taskdocument')->getTaskByProject($id);
        $queryresultv = $em->getRepository('AppBundle:Taskdocument')->getDocByProject($id);
        $options = array('decorate' => false,
            'representationField' => 'slug',
            'html' => false);
        $htmlTree = $repo->buildTree($treeDoc->getArrayResult(), $options);
        $checker = $em->getRepository('AppBundle:Usuario')->getUserByRole('ROLE_ENCARGADO');
        $assistent = $em->getRepository('AppBundle:Usuario')->getUserByRole('ROLE_ANALISTA');
        //$all =$em->getRepository('AppBundle:Taskdocument')->getDocTaskUsersByProject($id);
        return [
            'entities'      => $htmlTree,
            'query'      => $treeDoc->getResult(),
            //'all'     => $all->getResult(),
            'task'     => $treeTask->getArrayResult(),
            'checker' => $checker,
            'assistent'  => $assistent,
            'idProyecto'    => $id,
            'tree'      => $htmlTree,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * @param $id
     * @Route("/proyectogo/treedelete/{id}", name_="app_proyectogo_tree_delete")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Taskdocument')->find($id);
        $em->remove($entity);
        $em->flush();

        return new Response('success');
    }

    /**
     * Finds and displays a entity.
     * @param $value
     * @param $element
     * @Route("/viewproyects/{element}/{value}", name="app_proyectogo_viewproyects")
     * @Method("POST")
     * @Template()
     * @return array|Response
     */
    public function viewproyectsAction($element, $value)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $roles = $user->getRoles();
        $goestados = $em->getRepository('AppBundle:Goestado')->findAll();
        if(in_array('ROLE_SUPER_ADMIN',$roles)) {
            $entities = $em->getRepository('AppBundle:Proyectogo')->findAll();
        }else{
          $entities = $em->getRepository('AppBundle:Proyectogo')->getProyectos($goestados,$user->getId(),$element, $value,$user->getRoles());
            $entities = $entities->getResult();
        }

        return [
            'entities' => $entities,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
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
            'results'   => "app_".strtolower($this::ENTITY_NAME)."_results",
            'show'      => "app_".strtolower($this::ENTITY_NAME)."_show",
            'new'       => "app_".strtolower($this::ENTITY_NAME)."_new",
            'edit'      => "app_".strtolower($this::ENTITY_NAME)."_edit",
            'delete'    => "app_".strtolower($this::ENTITY_NAME)."_delete",
            'enabled'   => "app_".strtolower($this::ENTITY_NAME)."_enabled"
        ];
    }

    function arrayToObject($obj)
        {
            if (is_object($obj))
            {
                // Gets the properties of the given object with get_object_vars function
                $obj = get_object_vars($obj);
            }

            if (is_array($obj))
            {
                /**
                 * Return array converted to object Using __FUNCTION__
                 * (Magic constant) for recursive calls
                 */
                return array_map(__FUNCTION__, $obj);
            }
            else
            {
                return $obj;
            }
    }

    /**
     * Finds and displays a entity.
     * @Route("/viewallproyects", name="app_proyectogo_viewallproyects")
     * @Method("POST")
     * @Template()
     * @return array|Response
     */
    public function viewallproyectsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $roles = $user->getRoles();
        $entities = "";
        if ( in_array( 'ROLE_SUPER_ADMIN' , $roles ) ) {
            $entities = $em->getRepository('AppBundle:Proyectogo')->findAll();
        }else{
           $entities = $em->getRepository('AppBundle:Proyectogo')->getProyectosNoEmptyStart();
        }
        return [
            'entities' => $entities,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * @param $id
     * @Route("/proyectogo/start/{id}", name_="app_proyectogo_start")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     * @throws \Exception
     */
    public function start($id)
    {
        //$this->startEmail($id);
        $em = $this->getDoctrine()->getManager();

        /** @var Usuario $user */
        $user = $this->getUser();
        $time = new \DateTime();
        $today = $time->format('Y-m-d');
        $arrayUsuariosChecker = [];
        $arrayUsuarios = [];
        $arrayUsuariosAssistent = [];
        $proyecto = $em->getRepository('AppBundle:Proyectogo')->find($id);
        $tasks = $em->getRepository('AppBundle:Taskdocument')->findBy(['proyectogo' => $proyecto]);

        foreach ($tasks as $task) {
            $gocheckers = $em->getRepository('AppBundle:Gochecker')->findBy(['task' => $task]);
            $goassistents = $em->getRepository('AppBundle:Goassistent')->findBy(['task' => $task]);

            if(!is_null($goassistents) && !is_null($gocheckers)) {
                foreach ($goassistents as $assistent) {
                    $arrayUsuarios[] = $em->getRepository('AppBundle:Usuario')->find($assistent->getAssistent()->getId());
                }
                foreach ($gocheckers as $checker) {
                    $arrayUsuarios[] = $em->getRepository('AppBundle:Usuario')->find($checker->getChecker()->getId());
                }
                $task->setAsignedat($time);
                $em->persist($task);
                $em->flush();
            }
        }

        $proyecto->setStartedat($time);
        $em->persist($proyecto);
        $em->flush();
        $newarray = array_unique($arrayUsuarios);
        $strSubject = 'Se ha Iniciado Proyecto Go : ' ;//. $proyecto->getName();
        $strBody = 'startproject';
        $this->sendEmailNew($proyecto, $strSubject, $strBody, null, $newarray );



        return new Response('success');
    }

    /**
     * @param $id
     * @Route("/proyectogo/check/{id}", name_="app_proyectogo_check")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function checkAction($id){

        $em = $this->getDoctrine()->getManager();
        $proyecto = $em->getRepository('AppBundle:Proyectogo')->find($id);
        $tasks = $em->getRepository('AppBundle:Taskdocument')->findBy(['proyectogo' => $proyecto]);
        $estado = $em->getRepository('AppBundle:Goestado')->findOneBy(
            [
                'name'  => 'FOLDER'
            ]
        );
        $state = false;

        //dump($estado);die();
        foreach ($tasks as $task) {
            if($task->getEstado()->getId() != $estado->getId()) {
                $gocheckers = $em->getRepository('AppBundle:Gochecker')->findBy(['task' => $task]);
                $goassistents = $em->getRepository('AppBundle:Goassistent')->findBy(['task' => $task]);

                if (count($goassistents) == 0 || count($gocheckers) == 0) {
                    $state = true;
                }
            }
        }
        if($state == false){
            return $this->render(
                'AppBundle:Proyectogo:check.html.twig',
                [
                    'id'    => $id
                ]
            );
        }else{
            return $this->render(
                'AppBundle:Proyectogo:nocheck.html.twig',
                [
                    'id'    => $id
                ]
            );
        }
    }
}
