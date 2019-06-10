<?php

namespace AppBundle\Controller;

use App\Form\FileTaskDocumentType;
use AppBundle\Entity\Progoanalyst;
use AppBundle\Entity\Taskdocument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Log;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Proyectogo;
use AppBundle\Entity\Note;

/**
 * Class GoController
 * @package AppBundle\Controller
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class GoController extends Controller
{
    const ENTITY_NAME = "Proyectogo";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Proyectogo";
    const TYPE_NAMESPACE = "AppBundle\\Form\\ProyectogoType";
    const TYPE_NAMESPACE_NOTE = "AppBundle\\Form\\NoteType";
    const TYPE_NAMESPACE_TASK = "AppBundle\\Form\\TaskdocumentFileType";
    const SINGULAR_NAME = "Proyecto";
    const PLURAL_NAME = "Proyectos";


    /**
     * @Route("/go/")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Document')->findAll();
        $encargado = $em->getRepository('AppBundle:Usuario')->getUserByRole('ROLE_ENCARGADO');
        $analista = $em->getRepository('AppBundle:Usuario')->getUserByRole('ROLE_ANALISTA');

        return [
            'documents' => $entities,
            'encargado' => $encargado,
            'analista'  => $analista,
            'template' => 'index',
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME,
            'routes' => $this->getRoutesForEntity()
        ];
    }

    /**
     * Lists all entities.
     *
     * @Route("/go/all/results")
     * @Method("GET")
     * @Template()
     */
    public function resultsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $this->getRepository()->findAll();
        //$analysts = $em->getRepository('AppBundle:Progoanalyst')->findAll();

        return [
            'entities' => $entities,
            //'analysts' => $analysts,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Creates a new entity.
     * @param Request $request
     * @Route("/go/new")
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
     * @Route("/go/{id}")
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
     * @Route("/go/{id}/edit")
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
     * @Route("/go/{id}")
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

        return new Response('success');
    }

    /**
     * Enabled Usuario entity.
     *
     * @Route("/go/{id}/enabled")
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
        
        return new Response('success');
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $request
     * @param $id
     * @Route("/go/{id}/edit_analyst", name="app_go_edit_analyst")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function editAnalystAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Proyectogo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createForm($this::TYPE_NAMESPACE_ANALYST, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //$this->correoCambios($entity->getId());
            $this->correoAviso('analyst', $entity);
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
     * Displays a form to edit an existing entity.
     * @param $request
     * @param $id
     * @Route("/go/{id}/edit_incharge", name="app_go_edit_incharge")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function editInchargeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Proyectogo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createForm($this::TYPE_NAMESPACE_INCHARGE, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //$this->correoCambios($entity->getId());
            $this->correoAviso('incharge', $entity);
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
     * Finds and displays a entity.
     * @param $val
     * @Route("viewproyects/{val}", name="app_go_viewproyects")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function viewproyectsAction($val)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $analysts = $em->getRepository('AppBundle:Progoanalyst')->findAll();

        //var_dump($user);exit();
        if($val == 'encargado') {
            $entity = $em->getRepository('AppBundle:Proyectogo')->findBy(
                [
                    'incharge' => $user
                ]
            );

        }elseif($val == 'analista'){
            $entity = $em->getRepository('AppBundle:Proyectogo')->getProyectogosByProfile($val,$user);
        }

        return [
            'entities' => $entity,
            'analysts' => $analysts,
            'valencargado' => $val,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Finds and displays a entity.
     * @param $val
     * @Route("viewtasks/{val}", name="app_go_viewtasks")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function viewtasksAction($val)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entity = $em->getRepository('AppBundle:Taskdocument')->getTaskDocsByUser($user, $val);

        return [
            'entities' => $entity,
            'valencargado' => $val,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Finds and displays a entity.
     * @param $id
     * @param $val
     * @Route("viewtasks/{id}/{val}", name="app_go_viewtasks_byproyect")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function viewtasksbyproyectsAction($id,$val)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entity = $em->getRepository('AppBundle:Taskdocument')->getTasksByUserProyect($id, $user, $val);

        return [
            'entities' => $entity,
            'valencargado' => $val,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Finds and displays a entity.
     * @param $id
     * @param $val
     * @Route("viewtasks/{id}/{val}", name="app_go_viewtasks_byproyect_user")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function viewtasksbyproyectsuserAction($id,$val)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entity = $em->getRepository('AppBundle:Taskdocument')->getTasksByProyectUser($id, $user, $val);

        return [
            'entities' => $entity,
            'valencargado' => $val,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }



    /**
     * Displays a form to edit an existing entity.
     * @param $request
     * @param $id
     * @Route("/go/{id}/inchargetoproyect", name="app_go_inchargetoproyect")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function inchargetoproyectAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Proyectogo')->findAll();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        if ($request->getMethod() === 'POST') {
            //dump($request);die();
            $entities = $request->request->get('proyectos');
            $usuario = $em->getRepository('AppBundle:Usuario')->find($id);
            foreach($entities as $proyecto){
                $entity = $em->getRepository('AppBundle:Proyectogo')->find($proyecto);

                $entity->setIncharge($usuario);
                $em->persist($entity);
                $em->flush();
                $this->correoAviso('incharge', $entity);
            }
            return $this->redirect($this->generateUrl('app_go_indexusuarios'));

        }


        return [
            'entities' => $entity,
            'id'        => $id,
            'singular' => 'Encargado a Proyectogos',
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $request
     * @param $id
     * @Route("/go/{id}/analysttoproyect", name="app_go_analysttoproyect")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function analysttoproyectAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Proyectogo')->findAll();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        if ($request->getMethod() === 'POST') {
            //dump($request);die();
            $entities = $request->request->get('proyectos');
            $usuario = $em->getRepository('AppBundle:Usuario')->find($id);
            foreach($entities as $proyecto){
                $entity = $em->getRepository('AppBundle:Proyectogo')->find($proyecto);

                $entity->setAnalyst($usuario);
                $em->persist($entity);
                $em->flush();
                $this->correoAviso('analyst', $entity);
            }
            return $this->redirect($this->generateUrl('app_go_indexusuarios'));

        }

        return [
            'entities' => $entity,
            'id'        => $id,
            'singular' => '{{ sys_analyst  }}: a Proyectogos',
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $idproy
     * @param $iddoc
     * @Route("/go/{idproy}/{iddoc}/adddocumentstoproyects", name="app_go_adddocumentstoproyects")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function adddocumentstoproyectsAction($idproy, $iddoc)
    {
        $em = $this->getDoctrine()->getManager();

        $proyecto = $em->getRepository('AppBundle:Proyectogo')->find($idproy);
        $documento = $em->getRepository('AppBundle:Document')->find($iddoc);
        $usuario = $this->getUser();
        $entity = new Taskdocument($usuario);
        $entity->setProyectogo($proyecto);
        $entity->setDocument($documento);
        try {
            $em->persist($entity);
            $em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
            return new Response('duplicate');
        }

        $this->correoAviso('newdocument', $entity);

        return new Response('success');
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $idproy
     * @param $idencargado
     * @Route("/go/{idproy}/{idencargado}/addencargado", name="app_go_addencargado")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function addencargadoAction($idproy, $idencargado)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Proyectogo')->find($idproy);
        $encargado = $em->getRepository('AppBundle:Usuario')->find($idencargado);

        if ($entity->getIncharge() != $encargado) {
            $entity->setIncharge($encargado);
            $em->persist($entity);
            $em->flush();

            $this->correoAviso('incharge', $entity);
        }else{
            return new Response('duplicate');
        }
        return new Response('success');
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $idproy
     * @param $idanalista
     * @Route("/go/{idproy}/{idanalista}/addanalista", name="app_go_addanalista")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function addanalistaAction($idproy, $idanalista)
    {
        $em = $this->getDoctrine()->getManager();

        $proyectogo = $em->getRepository('AppBundle:Proyectogo')->find($idproy);
        $analista = $em->getRepository('AppBundle:Usuario')->find($idanalista);
        $entity = new Progoanalyst();

        $entity->setAnalyst($analista);
        $entity->setProyectogo($proyectogo);
        /*
        if($entity->hasAnalyst($analista)){
            return new Response('duplicate');
        }
         */

        try {
            $em->persist($entity);
            $em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
            return new Response('duplicate');
        }

        $this->correoAviso('analyst', $proyectogo);

        return new Response('success');
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $id
     * @Route("/go/{id}/rejecttask", name="app_go_rejecttask")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function rejecttaskAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Taskdocument')->find($id);
        $archivo = $entity->getFile();

        $entity->setEstado('RECHAZADO');
        $entity->setFile(NULL);
        $entity->setLink(NULL);
        $em->persist($entity);
        $em->flush();

        $this->correoAviso('reject', $entity);

        return new Response('success');
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $id
     * @Route("/go/{id}/approvetask", name="app_go_approvetask")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function approvetaskAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime();
        //$fecha = $fecha->format('Y-m-d');

        $entity = $em->getRepository('AppBundle:Taskdocument')->find($id);
        $entity->setEstado('APROBADO');
        $entity->setDatecompleted($fecha);
        $em->persist($entity);
        $em->flush();

        $this->correoAviso('approve', $entity);

        return new Response('success');
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $id
     * @param $valor
     * @Route("/go/addusuario/{id}/{valor}", name="app_go_usuarios_perfil")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function addRole($id,$valor)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Usuario')->find($id);
        if($valor == 1){
            $entity->addRole('ROLE_ENCARGADO');
        }elseif($valor == 2){
            $entity->addRole('ROLE_ANALISTA');
        }
        $em->persist($entity);
        $em->flush();
        return new Response('success');
    }
    /**
     * Creates a new entity.
     * @param $id
     * @param Request $request
     * @Route("/go/newnotetask/{id}", name="app_go_newnotetask")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function newnotetaskAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $entity = new Note($usuario);
        $form = $this->createForm($this::TYPE_NAMESPACE_NOTE, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task = $em->getRepository('AppBundle:Taskdocument')->find($id);
            $entity->setTask($task);
            $em->persist($entity);
            $em->flush();

            // ... persist the $product variable or any other work
            //$this->correoAviso('newnote', $entity);
            return $this->redirect($this->generateUrl('go'));
            //return new Response('success');
        }

        return [
            'id'  => $id,
            'form' => $form->createView(),
            'singular' => 'Nota',
            'plural' => 'Notas'
        ];
    }

    /**
     * Finds and displays a entity.
     * @param $id
     * @Route("viewnotes/{id}", name="app_go_viewnotes")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function viewnotesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $task = $em->getRepository('AppBundle:Taskdocument')->find($id);
        $entity = $em->getRepository('AppBundle:Note')->findBy(
            [
                'task' => $id
            ]
        );

        return [
            'tarea' => $task,
            'entities' => $entity,
            'singular' => 'Comentario',
            'plural' => 'Comentarios'
        ];
    }

    /**
     * Finds and displays a entity.
     * @Route("/go/viewallproyects/", name="app_go_viewallproyects")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function viewallproyectsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Proyectogo')->findAll();
        $analyst = $em->getRepository('AppBundle:Progoanalyst')->findAll();

        return [
            'entities' => $entity,
            'analysts' => $analyst,
            'valencargado' => 'admin',
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Finds and displays a entity.
     * @Route("(go/viewalltasks/", name="app_go_viewalltasks")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function viewalltasksAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Taskdocument')->findBy( array(), array('proyectogo' => 'ASC') );

        return [
            'entities' => $entity,
            'valencargado' => 'admin',
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * @param $accion
     * @param $entity
     * @return bool
     */
    public function correoAviso($accion, $entity)
    {
        /** @var Usuario $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if($accion == 'newfile'){
            $usuario = $em->getRepository('AppBundle:Usuario')->find($entity->getProyectogo()->getIncharge());
            $strSubject = 'Se ha subido un nuevo archivo a Proyecto: ' . $entity->getProyectogo()->getName();
        }elseif($accion == 'incharge') {
            $usuario = $em->getRepository('AppBundle:Usuario')->find($entity->getIncharge());
            $strSubject = 'Se ha asignado a Proyecto: '. $entity->getName() .' como Encargado';
        }elseif($accion == 'analyst') {
            $analistas = $entity;
            $strSubject = 'Se ha asignado a Proyecto: '. $entity->getName() .' como {{ sys_analyst  }}:';
        }elseif($accion == 'reject') {
            $usuario = $em->getRepository('AppBundle:Usuario')->find($entity->getProyectogo()->getAnalysts());
            $strSubject = 'Se ha rechazado un archivo a Proyecto: '. $entity->getProyectogo()->getName() .' en el cual Ud es Analista';
        }elseif($accion == 'approve') {
            $usuario = $em->getRepository('AppBundle:Usuario')->find($entity->getProyectogo()->getAnalysts());
            $strSubject = 'Se ha Aprobado un archivo a Proyecto: '. $entity->getProyectogo()->getName() .' en el cual Ud es Analista';
        }elseif($accion == 'newdocument') {
            $analistas = $entity->getProyectogo();
            $strSubject = 'Se ha asignado una nueva tarea a Proyecto: '. $entity->getProyectogo()->getName() .' en el cual Ud es Analista';
        }

        if($accion === 'analyst' || $accion === 'newdocument') {
            //var_dump($analistas); exit();die();

            $newanalyst = $em->getRepository('AppBundle:Progoanalyst')->findBy(
                ['proyectogo' => $analistas]
            );

            if(count($newanalyst) > 0) {
                foreach ($newanalyst as $analista) {
                    //var_dump($analista);

                    $usuario = $em->getRepository('AppBundle:Usuario')->find($analista->getAnalyst());
                    $strTo = $usuario->getEmail();
                    $strBody = $this->renderView('AppBundle:Go:correoaviso.html.twig',
                        [
                            'emisor' => $user,
                            'receptor' => $usuario,
                            'entity' => $entity,
                            'accion' => $accion,
                            'subject' => $strSubject
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
        }else {
            $strTo = $usuario->getEmail();
            $strBody = $this->renderView('AppBundle:Go:correoaviso.html.twig',
                [
                    'emisor'    => $user,
                    'receptor'  => $usuario,
                    'entity'     => $entity,
                    'accion'    => $accion,
                    'subject'   => $strSubject
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

        return true;//new Response('success');
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
            'results'               => "app_go_results",
            'show'                  => "app_go_show",
            'new'                   => "app_go_new",
            'edit'                  => "app_go_edit",
            //'edit_analyst'          => "app_go_edit_analyst",
            //'edit_incharge'         => "app_go_edit_incharge",
            'delete'                => "app_go_delete",
            'usuarios_results'      => "app_go_usuarios",
            'enabled'               => "app_go_enabled",
            //'inchargetoproyect'     => "app_go_inchargetoproyect",
            //'analysttoproyect'      => "app_go_analysttoproyect"
        ];
    }
}
