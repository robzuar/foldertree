<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Log;
use AppBundle\Form\LogType;

/**
 * Log controller.
 *
 * @Route("/log")
 */
class LogController extends Controller
{
    const ENTITY_NAME = "Log";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Log";
    const TYPE_NAMESPACE = "AppBundle\\Form\\LogType";
    const SINGULAR_NAME = "Log";
    const PLURAL_NAME = "Logs";

    /**
     * Return a index template
     *
     * @Route("/", name="log")
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
     * Lists all Log entities.
     *
     * @Route("/results", name="app_log_results")
     * @Method("GET")
     * @Template()
     */
    public function resultsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Log')->findAll();

        return array(
            'entities' => $entities,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        );
    }

    /**
     * Creates a new Log entity.
     *
     * @Route("/", name="app_log_create")
     * @Method("POST")
     * @Template("AppBundle:Log:new.html.twig")
     */
    public function createAction($accion, $entidad)
    {
        $usuario = $this->get('security.context')->getToken()->getUser();
        $log = new Log($usuario);

        $log->setAccion($accion);
        $log->setEntidad($entidad);

        $em = $this->getDoctrine()->getManager();
        $em->persist($log);
        $em->flush();


    }

    /**
     * Creates a form to create a Log entity.
     *
     * @param Log $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Log $entity)
    {
        $form = $this->createForm(new LogType(), $entity, array(
            'action' => $this->generateUrl('app_log_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Log entity.
     *
     * @Route("/new", name="app_log_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $usuario = $this->get('security.context')->getToken()->getUser();
        $entity = new Log($usuario);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Log entity.
     *
     * @Route("/{id}", name="app_log_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Log')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Log entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Log entity.
     *
     * @Route("/{id}/edit", name="app_log_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Log')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Log entity.');
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
     * Creates a form to edit a Log entity.
     *
     * @param Log $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Log $entity)
    {
        $form = $this->createForm(new LogType(), $entity, array(
            'action' => $this->generateUrl('app_log_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Log entity.
     *
     * @Route("/{id}", name="app_log_update")
     * @Method("PUT")
     * @Template("AppBundle:Log:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Log')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Log entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('app_log_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Log entity.
     *
     * @Route("/{id}", name="app_log_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Log')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Log entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('log'));
    }

    /**
     * Creates a form to delete a Log entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_log_delete', array('id' => $id)))
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
