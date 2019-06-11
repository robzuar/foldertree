<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Log;

/**
 * GrupoCambios controller.
 *
 * @Route("grupocambios")
 */
class GrupoCambiosController extends CrudController
{
    const ENTITY_NAME = "GrupoCambios";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\GrupoCambios";
    const TYPE_NAMESPACE = "AppBundle\\Form\\GrupoCambiosType";
    const SINGULAR_NAME = "Grupo de Cambios";
    const PLURAL_NAME = "Grupos de Cambios";

    /**
     * Creates a new entity.
     * @param $idgrupo
     * @param Request $request
     * @Route("/grupocambiosadduser/{idgrupo}", name="app_grupocambios_add_user")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function usertocambiosAction(Request $request, $idgrupo)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Fileimg $parent */
        $grupo = $em->getRepository('AppBundle:GrupoCambios')->find($idgrupo);
        $usgr = $em->getRepository('AppBundle:Usuario')->getUserGrupoCambios($grupo);

        if(!isset($usgr)) {
            $usgr = null;
        }

        if ($request->isMethod('POST')) {

            if(isset($usgr)) {
                foreach ($usgr as $us) {

                    $entusuario = $em->getRepository('AppBundle:Usuario')->find($us);
                    $entusuario->removeGrupoCambios($grupo);
                    $em->persist($entusuario);
                    $em->flush();
                }
            }

            $allusuarios = $request->get('userarray');

            foreach($allusuarios as $user){

                $entidadusuario = $em->getRepository('AppBundle:Usuario')->find($user);
                $entidadusuario->addGrupoCambios($grupo);
                $em->persist($entidadusuario);
                $em->flush();
            }

            $user = $this->getUser();
            $log = new Log($user);
            $log->setEntidadId($grupo->getId());
            $log->setNombre($grupo->getName());
            $log->setAccion('Edita Grupos de Cambios');
            $log->setEntidad('GrupoCambios');
            $em->persist($log);
            $em->flush();
            return new Response('success');
        }

        $usuarios = $em->getRepository('AppBundle:Usuario')->findAll();

        return [
            'idgrupo' => $idgrupo,
            'usuarios' => $usuarios,
            'usgr' => $usgr
        ];
    }

    /**
     * @param $listadousuarios
     * @param $listadogrupos
     * @return bool
     */
    public function correoAviso($listadousuarios, $listadogrupos,$archivos, $idCategory)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Usuario $user */
        $user = $this->getUser();
        $parametroemail = $this->get('service_container')->getParameter('sendemail');
        $cat = $em->getRepository('AppBundle:Category');
        $entity = $em->getRepository('AppBundle:Category')->find($idCategory);
        $htmlTree = $cat->getPath($entity);
        $arraygrupotousuarios = [];

        if(count($listadogrupos) >= 1) {
            foreach ($listadogrupos as $grupo) {
                $entgrupo = $em->getRepository('AppBundle:Group')->find($grupo);
                $arraygrupotousuarios[] = $em->getRepository('AppBundle:Usuario')->getUserGroups($entgrupo);
            }
        }

        if(count($listadousuarios) >= 1) {
            foreach($listadousuarios as $usua) {
                $arraygrupotousuarios[] = $em->getRepository('AppBundle:Usuario')->find($usua);
            }
        }

        $newarray = array_unique($arraygrupotousuarios);

        if($newarray  >= 1){
            $subject = 'Se ha Creado un nuevo Archivo: ';

            foreach ($arraygrupotousuarios as $usuario) {

                $strTo = $usuario->getEmail();

                $strSubject = $subject;
                $strBody = $this->renderView('AppBundle:Fileimg:correoaviso.html.twig',
                    [
                        'creador' => $user->getNombres() . ' ' . $user->getApellidos(),
                        'receptor' => $usuario,
                        'archivos' => $archivos,
                        'menu' => $htmlTree
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
        return true;//new Response('success');
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
        $entity = $em->getRepository('AppBundle:GrupoCambios')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $usuarios = $em->getRepository('AppBundle:Usuario')->getUserGrupoCambios($entity);

        return [
            'entity' => $entity,
            'usuarios' => $usuarios,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

}
