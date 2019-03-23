<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Usuario;
use AppBundle\Form\UsuarioType;
use AppBundle\Entity\Log;

/**
 * Usuario controller.
 *
 * @Route("usuario")
 */
class UsuarioController extends CrudController
{
    const ENTITY_NAME = "Usuario";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Usuario";
    const TYPE_NAMESPACE = "AppBundle\\Form\\UsuarioType";
    const SINGULAR_NAME = "Usuario";
    const PLURAL_NAME = "Usuarios";

    /**
     * Creates a new entity.
     * @param Request $request
     * @Route("/new", name="app_usuario_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Usuario('nuevo');
        $form = $this->createForm($this::TYPE_NAMESPACE, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $permiso = $em->getRepository('AppBundle:Permiso')->findOneBy(array('name'=>'01 GENERAL IMAGINA'));
            $grupo = $em->getRepository('AppBundle:Group')->findOneBy(array('name'=>'01 GENERAL IMAGINA'));
            $entity->addPermiso($permiso);
            $entity->addGroup($grupo);
            $em->persist($entity);
            $em->flush();

            $path = "";
            $user = $this->getUser();
            $log = new Log($user);
            $log->setEntidadId($entity->getId());
            $log->setNombre($entity->getNombres(). "" . $entity->getApellidos());
            $log->setPath($path);
            $log->setAccion('Nuevo');
            $log->setEntidad('Usuario');
            $em->persist($log);
            $em->flush();

            return new Response('success');
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
            'singular' => self::SINGULAR_NAME,
            'plural' => self::PLURAL_NAME,
            'routes' => $this->getRoutesForEntity(),
        ];
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
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }

        $em = $this->getDoctrine()->getManager();

        if($entity->isEnabled()){
            $entity->setEnabled(false);
        }else{
            $entity->setEnabled(true);
        }

        $em->persist($entity);
        $em->flush();

        $user = $this->getUser();
        $log = new Log($user); $log->setEntidadId($entity->getId());
        if($entity->isEnabled()){
            $log->setAccion('Habilitar');
        }else{
            $log->setAccion('Deshabilitar');
        }
        $log->setEntidad('Usuario');
        $em->persist($log);
        $em->flush();

        return new Response('success');
    }

    /**
     * Displays a form to change password of an existing User entity.
     * @param Request $request
     * @Route("/{id}/passwordapp/", name="change_password_app")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function changePasswordAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var  Usuario $entity */
        $entity = $em->getRepository('AppBundle:Usuario')->find($id);


        $editForm = $this->createForm("AppBundle\\Form\\ChangePasswordType", $entity);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $pass = $editForm->getData()->getPlainPassword();

            $userManager =  $this->get('service_container')->get('fos_user.user_manager');
            $entity->setPlainPassword($pass);
            $userManager->updatePassword($entity);



            $user = $this->getUser();
            $log = new Log($user);
            $log->setEntidadId($entity->getId());
            $log->setNombre($entity->getNombres(). "" . $entity->getApellidos());
            $log->setAccion('Nuevo');
            $log->setEntidad('Carpeta');
            $em->persist($log);
            $em->flush();
            return new Response('success');
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'routes' => $this->getRoutesForEntity(),
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        );
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

    /**
     * @Route("usuarios/indexgo", name="app_usuario_indexusuarios")
     * @Method("GET")
     * @Template()
     */
    public function indexusuariosAction()
    {
        return [
            'template' => 'indexusuarios',
            'singular' => 'Usuario',
            'plural' => 'Usuarios',
            'routes' => $this->getRoutesForEntity()
        ];
    }

    /**
     * Lists all entities.
     *
     * @Route("/usuarios/all", name="app_usuario_usuarios")
     * @Method("GET")
     * @Template()
     */
    public function usuariosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Usuario')->findAll();

        return [
            'entities' => $entities,
            'singular' => 'Usuario',
            'plural' => 'Usuarios'
        ];
    }
}
