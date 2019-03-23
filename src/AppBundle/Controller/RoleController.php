<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Role controller.
 *
 * @Route("role")
 */
class RoleController extends CrudController
{
    const ENTITY_NAME = "Role";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Role";
    const TYPE_NAMESPACE = "AppBundle\\Form\\RoleType";
    const SINGULAR_NAME = "Role";
    const PLURAL_NAME = "Role";


    /**
     * Creates a new entity.
     * @param $idgrupo
     * @param Request $request
     * @Route("/roleadduser/{idgrupo}", name="app_role_add_user")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function usertoroleAction(Request $request, $idgrupo)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Fileimg $parent */
        $grupo = $em->getRepository('AppBundle:Role')->find($idgrupo);
        $usgr = $em->getRepository('AppBundle:Usuario')->getUserRoles($grupo);
        $menus = $em->getRepository('AppBundle:Category')->findFirstMenu();

        if ($request->isMethod('POST')) {
            foreach($usgr as $us){
                $entusuario = $em->getRepository('AppBundle:Usuario')->find($us);
                $entusuario->removeRole($grupo);

                $em->persist($entusuario);
                $em->flush();
            }

            $allusuarios = $request->get('userarray');

            foreach($allusuarios as $user){
                $entidadusuario = $em->getRepository('AppBundle:Usuario')->find($user);
                $entidadusuario->addRole($grupo);

                $em->persist($entidadusuario);
                $em->flush();
            }

            return new Response('success');
        }

        $usuarios = $em->getRepository('AppBundle:Usuario')->findAll();

        return [
            'idgrupo' => $idgrupo,
            'usuarios' => $usuarios,
            'usgr' => $usgr,
            'menus' => $menus
        ];
    }
}
