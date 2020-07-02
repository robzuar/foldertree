<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\LogProyecto;
use AppBundle\Form\LogProyectoType;

/**
 * LogProyecto controller.
 *
 * @Route("/logproyecto")
 */
class LogProyectoController extends Controller
{
    const ENTITY_NAME = "LogProyecto";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\LogProyecto";
    const TYPE_NAMESPACE = "AppBundle\\Form\\LogProyectoType";
    const SINGULAR_NAME = "Log de Proyecto";
    const PLURAL_NAME = "Logs de Proyectos";

    /**
     * Return a index template
     *
     * @Route("/", name="logproyecto")
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
     * Lists all LogProyecto entities.
     *
     * @Route("/results", name="app_logproyecto_results")
     * @Method("GET")
     * @Template()
     */
    public function resultsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:LogProyecto')->findBy( array(), array('id' => 'DESC') );
        $proyectos = $em->getRepository('AppBundle:Proyecto')->findAll();


        return array(
            'entities' => $entities,
            'proyectos' => $proyectos,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        );
    }

    /**
     * Creates a new LogProyecto entity.
     *
     * @Route("/", name="app_logproyecto_create")
     * @Method("POST")
     * @Template("AppBundle:LogProyecto:new.html.twig")
     */
    public function createAction($accion, $entidad)
    {
        $usuario = $this->get('security.context')->getToken()->getUser();
        $logproyecto = new LogProyecto($usuario);

        $logproyecto->setAccion($accion);
        $logproyecto->setEntidad($entidad);

        $em = $this->getDoctrine()->getManager();
        $em->persist($logproyecto);
        $em->flush();


    }

    /**
     * Creates a form to create a LogProyecto entity.
     *
     * @param LogProyecto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(LogProyecto $entity)
    {
        $form = $this->createForm(new LogProyectoType(), $entity, array(
            'action' => $this->generateUrl('app_logproyecto_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new LogProyecto entity.
     *
     * @Route("/new", name="app_logproyecto_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $usuario = $this->get('security.context')->getToken()->getUser();
        $entity = new LogProyecto($usuario);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a LogProyecto entity.
     *
     * @Route("/{id}", name="app_logproyecto_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:LogProyecto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LogProyecto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing LogProyecto entity.
     *
     * @Route("/{id}/edit", name="app_logproyecto_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:LogProyecto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LogProyecto entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a LogProyecto entity.
     *
     * @param LogProyecto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(LogProyecto $entity)
    {
        $form = $this->createForm(new LogProyectoType(), $entity, array(
            'action' => $this->generateUrl('app_logproyecto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing LogProyecto entity.
     *
     * @Route("/{id}", name="app_logproyecto_update")
     * @Method("PUT")
     * @Template("AppBundle:LogProyecto:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:LogProyecto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LogProyecto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('app_logproyecto_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a LogProyecto entity.
     *
     * @Route("/{id}", name="app_logproyecto_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:LogProyecto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find LogProyecto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('logproyecto'));
    }

    /**
     * Creates a form to delete a LogProyecto entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_logproyecto_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
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
}
