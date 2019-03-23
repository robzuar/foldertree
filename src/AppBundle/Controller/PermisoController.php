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
 * Permiso controller.
 *
 * @Route("permiso")
 */
class PermisoController extends CrudController
{
    const ENTITY_NAME = "Permiso";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Permiso";
    const TYPE_NAMESPACE = "AppBundle\\Form\\PermisoType";
    const SINGULAR_NAME = "Grupo de Permiso";
    const PLURAL_NAME = "Grupos de Permiso";

    /**
     * Creates a new entity.
     * @param $idgrupo
     * @param Request $request
     * @Route("/permisoadduser/{idgrupo}", name="app_permiso_add_user")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function usertopermisoAction(Request $request, $idgrupo)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Fileimg $parent */
        $grupo = $em->getRepository('AppBundle:Permiso')->find($idgrupo);
        $usgr = $em->getRepository('AppBundle:Usuario')->getUserPermisos($grupo);

        if(!isset($usgr)) {
            $usgr = null;
        }

        if ($request->isMethod('POST')) {

            if(isset($usgr)) {
                foreach ($usgr as $us) {

                    $entusuario = $em->getRepository('AppBundle:Usuario')->find($us);
                    $entusuario->removePermiso($grupo);
                    $em->persist($entusuario);
                    $em->flush();
                }
            }

            $allusuarios = $request->get('userarray');

            foreach($allusuarios as $user){

                $entidadusuario = $em->getRepository('AppBundle:Usuario')->find($user);
                $entidadusuario->addPermiso($grupo);
                $em->persist($entidadusuario);
                $em->flush();
            }

            $user = $this->getUser();
            $log = new Log($user);
            $log->setEntidadId($grupo->getId());
            $log->setNombre($grupo->getName());
            $log->setAccion('Edita Grupos de Permiso a Carpeta');
            $log->setEntidad('Permiso');
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
     * Creates a new entity.
     * @param $idcategory
     * @param Request $request
     * @Route("/categoryaddpermiso/{idcategory}", name="app_permiso_new_category")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function permisotocategoryAction(Request $request, $idcategory)
    {

        $em = $this->getDoctrine()->getManager();
        /** @var Fileimg $parent */
        $category = $em->getRepository('AppBundle:Category')->find($idcategory);
        $grupospermiso = $em->getRepository('AppBundle:Usuario')->getUserPermisos($category);
        $nombre ="";
        if ($request->isMethod('POST')) {

            /** @var  Category */
            $entity = new Category();
            $entity->setTitle($request->get('nombre'));
            $entity->setFirst(false);
            $entity->setParent($category);
            $em->persist($entity);
            $em->flush();

            $user = $this->getUser();
            $log = new Log($user);
            $log->setEntidadId($entity->getId());
            $log->setNombre($entity->getTitle());
            $log->setAccion('Nuevo');
            $log->setEntidad('Carpeta');
            $em->persist($log);
            $em->flush();

            $grupopermisos = $request->get('userarray');

            foreach($grupopermisos as $grupopermiso ){

                $entpermiso = $em->getRepository('AppBundle:Permiso')->find($grupopermiso);
                $entity->addPermiso($entpermiso);
                $em->persist($entity);
                $em->flush();
            }


            $path = "";
            $cat = $em->getRepository('AppBundle:Category');
            $trees = $cat->getPath($entity);
            foreach ($trees as $tree){
                $path = $path .' / '.$tree;
            }
            $user = $this->getUser();
            $log = new Log($user);
            $log->setEntidadId($entity->getId());
            $log->setNombre($entity->getTitle());
            $log->setPath($path);
            $log->setAccion('Agregar  Grupos de Permiso a Carpetas');
            $log->setEntidad('Carpetas');
            $em->persist($log);
            $em->flush();

            return new Response('success');
        }

        $grupos = $em->getRepository('AppBundle:Permiso')->findAll();

        return [
            'idcategory' => $idcategory,
            'grupos' => $grupos,
            'grupospermiso' => $grupospermiso
        ];
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
        $entity = $em->getRepository('AppBundle:Permiso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $usuarios = $em->getRepository('AppBundle:Usuario')->getUserPermisos($entity);

        return [
            'entity' => $entity,
            'usuarios' => $usuarios,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }
}
