<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Log;

/**
 * Group controller.
 *
 * @Route("group")
 */
class GroupController extends CrudController
{
    const ENTITY_NAME = "Group";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Group";
    const TYPE_NAMESPACE = "AppBundle\\Form\\GroupType";
    const SINGULAR_NAME = "Grupo de Correo";
    const PLURAL_NAME = "Grupos de Correo";

    /**
     * Creates a new entity.
     * @param $idgrupo
     * @param Request $request
     * @Route("/groupadduser/{idgrupo}", name="app_group_add_user")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function usertogroupAction(Request $request, $idgrupo)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Fileimg $parent */
        $grupo = $em->getRepository('AppBundle:Group')->find($idgrupo);
        $usgr = $em->getRepository('AppBundle:Usuario')->getUserGroups($grupo);

        //var_dump($usgr);die();
        if(!isset($usgr)) {
            $usgr = null;
        }

        if ($request->isMethod('POST')) {

            //var_dump($usgr);die();

            if(isset($usgr)) {
                foreach ($usgr as $us) {
                    $entusuario = $em->getRepository('AppBundle:Usuario')->find($us);
                    $entusuario->removeGroup($grupo);
                    $em->persist($entusuario);
                    $em->flush();
                }
            }


            $allusuarios = $request->get('userarray');

            foreach($allusuarios as $user){

                $entidadusuario = $em->getRepository('AppBundle:Usuario')->find($user);

                $entidadusuario->addGroup($grupo);

                $em->persist($entidadusuario);
                $em->flush();
            }

            $user = $this->getUser();
            $log = new Log($user);
            $log->setEntidadId($grupo->getId());
            $log->setNombre($grupo->getName());
            $log->setAccion('Edita Usuarios a Grupos de Correo');
            $log->setEntidad('Grupos de Correo');
            $em->persist($log);
            $em->flush();

            return new Response('success');
        }

        $usuarios = $em->getRepository('AppBundle:Usuario')->findAll();

        return [
            'idgrupo' => $idgrupo,
            'usuarios' => $usuarios,
            'usgr' => $usgr,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME        ];
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
        $entity = $em->getRepository('AppBundle:Group')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $usuarios = $em->getRepository('AppBundle:Usuario')->getUserGroups($entity);

        return [
            'entity' => $entity,
            'usuarios' => $usuarios,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }
}
