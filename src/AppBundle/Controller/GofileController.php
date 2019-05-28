<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Gofile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Gofile controller.
 *
 * @Route("gofile")
 */
class GofileController extends CrudController
{
    const ENTITY_NAME = "Gofile";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Gofile";
    const TYPE_NAMESPACE = "AppBundle\\Form\\GofileType";
    const SINGULAR_NAME = "Observación";
    const PLURAL_NAME = "Observaciones";

    /**
     * @param Request $request
     * @param $id
     * @Route("/newfile/{id}", name="app_gofile_newfile")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array
     * @throws \Exception
     */
    public function newfileAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Taskdocument')->find($id);
        $idproyecto = $task->getProyectogo()->getId();

        if ($request->getMethod() === 'POST') {
            //echo 'ensubmit';exit();
            //dump( $request->files->get ( 'files'));die();
            $pathDocument = $this->get('service_container')
                    ->getParameter('kernel.root_dir') . '/../web/media/Task';

            $files = $request->files->get ( 'fileupload');
            $user = $this->getUser();
            foreach($files as $file) {
                $entity = new Gofile($user, $task);
                $nombreoriginal = explode(".", $file->getClientOriginalName())[0];
                $extensionoriginal = explode(".", $file->getClientOriginalName())[1];
                $nombreoriginal = strtoupper($nombreoriginal);
                $fecha = new \DateTime();
                $fecha = $fecha->format('dmyHis');
                $fileName = $user->getId() . '' . $fecha ;
                $fileName = strtoupper($fileName).'.' . $extensionoriginal;
                $entity->setFile($fileName);
                $entity->setLink($nombreoriginal);
                $em->persist($entity);
                $em->flush();
                if (!$file->getError()) {
                    $file->move($pathDocument, $fileName);
                }
            }
            //this->correoAviso('newfile', $entity);

            // ... persist the $product variable or any other work

            //return $this->redirect($this->generateUrl('go'));
            return $this->redirectToRoute('go');
        }

        return [
            'idtask'  => $id,
            'idproyecto'  => $idproyecto
        ];
    }

    /**
     * Finds and displays a entity.
     * @param $value
     * @Route("/viewfiles/{value}", name="app_gofile_viewfiles")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function viewfilesAction($value)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Taskdocument')->find($value);
        $files = $em->getRepository('AppBundle:Gofile')->findBy(
            [
                'task'  => $task
            ]
        );

        return [
            'tarea' => $task,
            'files' => $files,
        ];
    }

    /**
     * Enabled Fileimg entity.
     *
     * @Route("/{id}/delete", name="app_gofile_deletefile")
     * @Method("POST")
     * @param $id
     * @return Response
     */
    public function deletefileAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Gofile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }

        $pathDocument = $this->get('service_container')
                ->getParameter('kernel.root_dir') . '/../web/media/Task/';
        unlink($pathDocument."".$entity->getFile());

        $em->remove($entity);
        $em->flush();

        return new Response('success');

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
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Taskdocument')->find($id);

        $entities = $em->getRepository('AppBundle:Gofile')->findBy(['task' => $task]);

        if (!$entities) {
            throw $this->createNotFoundException('Unable to find entity.');
        }
        return [
            'task'      => $task,
            'entities'  => $entities,
            'singular'  => $this::SINGULAR_NAME,
            'plural'    => $this::PLURAL_NAME,
            'idtask'    => $id
        ];
    }
}

