<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Note;

/**
 * Note controller.
 *
 * @Route("note")
 */
class NoteController extends CrudController
{
    const ENTITY_NAME = "Note";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Note";
    const TYPE_NAMESPACE = "AppBundle\\Form\\NoteType";
    const SINGULAR_NAME = "Observación";
    const PLURAL_NAME = "Observaciones";

    /**
     * Finds and displays a entity.
     * @param $id
     * @Route("viewnotes/{id}", name="app_note_viewnotes")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function viewnotesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
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
     * Creates a new entity.
     * @param $id
     * @param Request $request
     * @Route("/go/newnotetask/{id}", name="app_note_newnotetask")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function newnote(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entity = new Note($user);
        $form = $this->createForm($this::TYPE_NAMESPACE, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $em->getRepository('AppBundle:Taskdocument')->find($id);
            $entity->setTask($task);
            $em->persist($entity);
            try {
                $em->flush();
            } catch (\Exception $e) {
                $status = "fail";
                $message = $e->getMessage();
            }

            // ... persist the $product variable or any other work
            //$this->correoAviso('newnote', $entity);
            return $this->redirect($this->generateUrl('go'));

        }

        return [
            'id'  => $id,
            'form' => $form->createView(),
            'singular' => 'Nota',
            'plural' => 'Notas'
        ];
    }

    /**
     * Creates a new entity.
     * @param $id
     * @param Request $request
     * @Route("/go/savenotetask/{id}", name="app_note_savenotetask")
     * @Method("POST")
     * @return Response
     */
    public function saveNote(Request $request, $id)
    {
        $user = $this->getUser();
        $entity = new Note($user);
            $em = $this->getDoctrine()->getManager();
            $task = $em->getRepository('AppBundle:Taskdocument')->find($id);
        $form = $this->createForm($this::TYPE_NAMESPACE, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setTask($task);
            $em->persist($entity);
            try {
                $em->flush();
                $status = "success";
                $message = " Nueva Nota";
            } catch (\Exception $e) {
                $status = "fail";
                $message = $e->getMessage();
            }

            // ... persist the $product variable or any other work
            //$this->correoAviso('newnote', $entity);
            //return $this->redirect($this->generateUrl('go'));
            $response = array(
                'status' => $status,
                'message' => $message
            );

            return new JsonResponse($response);
        }else{
            return $this->render('AppBundle:Note:newnote.html.twig',
               [
                    'id'  => $id,
                    'form' => $form->createView(),
                    'singular' => 'Nota',
                    'plural' => 'Notas'
                ]
            );

            // ... further modify the response or return it directly

            return $response;
        }
    }

}