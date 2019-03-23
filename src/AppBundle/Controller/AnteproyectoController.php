<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Anteproyecto;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Anteproyecto controller.
 *
 * @Route("anteproyecto")
 */
class AnteproyectoController extends CrudController
{
    const ENTITY_NAME = "Anteproyecto";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Anteproyecto";
    const TYPE_NAMESPACE = "AppBundle\\Form\\AnteproyectoType";
    const SINGULAR_NAME = "Anteproyecto";
    const PLURAL_NAME = "Anteproyectos";


    /**
     * Creates a new entity.
     * @param $id
     * @param Request $request
     * @Route("/nuevoarchivo/{id}", name="app_anteproyecto_uploadfile")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function newfileAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getUser();
        $idUsuario = $usuario->getId();
        $entity = $em->getRepository('AppBundle:Anteproyecto')->find($id);

        if ($request->getMethod() === 'POST') {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $request->files->get ( 'fileupload');
            $pathDocument = $this->get('service_container')
                    ->getParameter('kernel.root_dir') . '/../web/media/FileAnteproyecto';
            $fecha = new \DateTime();
            $fecha = $fecha->format('dmyHis');
            $extension = $file->guessExtension();
            $fileName = $idUsuario . '' . $fecha . '.' . $extension;
            $nombreoriginal = explode(".", $file->getClientOriginalName())[0];
            $extensionoriginal = explode(".", $file->getClientOriginalName())[1];
            $nombreoriginal = strtoupper($nombreoriginal);

            if (!$file->getError()) {
                $file->move($pathDocument, $fileName);
            }
            $entity->setFile($fileName);
            $entity->setLink($nombreoriginal);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('app_anteproyecto_index'));
        }

        return [
            'idanteproyecto'  => $id
        ];
    }

    /**
     * Finds and displays a entity.
     * @param $id
     * @Route("/upload/{id}", name="app_anteproyecto_upload")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function uploadAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Anteproyecto $entity */
        $entity = $em->getRepository('AppBundle:Anteproyecto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }
        return [
            'entity' => $entity,
            'singular' => $this::SINGULAR_NAME
        ];
    }

}
