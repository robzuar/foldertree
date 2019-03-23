<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Acceso controller.
 *
 * @Route("acceso")
 */
class AccesoController extends CrudController
{
    const ENTITY_NAME = "Acceso";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Acceso";
    const TYPE_NAMESPACE = "AppBundle\\Form\\AccesoType";
    const SINGULAR_NAME = "Grupo Acceso";
    const PLURAL_NAME = "Grupos Acceso";


    /**
     * Creates a new entity.
     * @param $idgrupo
     * @param Request $request
     * @Route("/accesoadduser/{idgrupo}", name="app_acceso_add_user")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function usertoaccesoAction(Request $request, $idgrupo)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Fileimg $parent */
        $grupo = $em->getRepository('AppBundle:Acceso')->find($idgrupo);
        $usgr = $em->getRepository('AppBundle:Usuario')->getUserAccesos($grupo);

        if ($request->isMethod('POST')) {
            foreach($usgr as $us){
                $entusuario = $em->getRepository('AppBundle:Usuario')->find($us);
                $entusuario->removeAcceso($grupo);

                $em->persist($entusuario);
                $em->flush();
            }

            $allusuarios = $request->get('userarray');

            foreach($allusuarios as $user){
                $entidadusuario = $em->getRepository('AppBundle:Usuario')->find($user);
                $entidadusuario->addAcceso($grupo);

                $em->persist($entidadusuario);
                $em->flush();
            }

            return new Response('success');
        }

        $usuarios = $em->getRepository('AppBundle:Usuario')->findAll();

        return [
            'idgrupo' => $idgrupo,
            'usuarios' => $usuarios,
            'usgr' => $usgr
        ];
    }
}
