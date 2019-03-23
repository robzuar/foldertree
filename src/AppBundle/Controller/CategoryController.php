<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Category;
use AppBundle\Entity\Log;

/**
 * Category controller.
 *
 * @Route("category")
 */
class CategoryController extends CrudController
{
    const ENTITY_NAME = "Category";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Category";
    const TYPE_NAMESPACE = "AppBundle\\Form\\CategoryType";
    const SINGULAR_NAME = "Carpeta";
    const PLURAL_NAME = "Carpetas";

    /**
     * Creates a new entity.
     * @param Request $request
     * @Route("/new", name="app_category_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new category();
        $form = $this->createForm($this::TYPE_NAMESPACE, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setFirst(true);

            $em->persist($entity);
            $em->flush();

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
            $log->setAccion('Nuevo');
            $log->setEntidad('Carpeta');
            $em->persist($log);
            $em->flush();

            return new Response('success');
        }

        return [
            'entity'    => $entity,
            'form'      => $form->createView(),
            'singular'  => self::SINGULAR_NAME,
            'plural'    => self::PLURAL_NAME
        ];
    }

    /**
     * Lists all entities.
     *
     * @Route("/all/results")
     * @Method("GET")
     * @Template()
     */
    public function resultsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Category')->findFirstMenu();

        return [
            'entities'  => $entities,
            'singular'  => self::SINGULAR_NAME,
            'plural'    => self::PLURAL_NAME
        ];
    }

    /**
     * Lists all entities.
     *
     * @Route("/files/{id}/{pagina}/level", name="category_level")
     * @param  $id
     * @param  $pagina
     * @Method({"GET","POST"})
     * @Template()
     * @return array|Response
     */
    public function levelAction($id, $pagina)
    {
        $valorpagina = $pagina;
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('AppBundle:Category');
        $queryresultv= $em->getRepository('AppBundle:Category')->getEnabled();

        $options = array('decorate' => false,
            'representationField' => 'slug',
            'html' => false);
        $htmlTree = $repo->buildTree($queryresultv->getArrayResult(), $options);
        /*
        $htmlTree = $repo->childrenHierarchy(
            null,
            false,
            [
                'decorate' => false,
                'representationField' => 'slug',
                'html' => false
            ]
        );
*/
        $usuario =  $this->getUser();
        $entity = $em->getRepository('AppBundle:Category')->find($id);
        $root = $em->getRepository('AppBundle:Category')->find($entity->getRoot());
        $proyecto = $em->getRepository('AppBundle:Category')->find($entity->getRoot());
        $menu = $entity->getChildren();

        //dump($encargado);die();
        $isEncargado = false;

        if($entity->getEncargado()) {
            if ($entity->getEncargado()->getId() == $usuario->getId()) {
                $isEncargado = true;
            }
        }else{
            if($root->getEncargado()) {
                if ($root->getEncargado()->getId() == $usuario->getId()) {
                    $isEncargado = true;
                }
            }
        }

        return [
            'entities'      => $htmlTree,
            'entidad'       => $entity,
            'proyectoview'  => $proyecto->getProyecto(),
            'singular'      => self::SINGULAR_NAME,
            'plural'        => self::PLURAL_NAME,
            'routes'        => $this->getRoutesForEntity(),
            'menu'          => $menu,
            'pictures'      => $root->getImagine(),
            'cargarpagina'  => $valorpagina,
            'idpagina'      => $id,
            'isencargado'   => $isEncargado
        ];
    }


    /**
     * Creates a new entity.
     * @param $id
     * @param $text
     * @Route("/folder/{id}/{text}/new", name="category_new_folder")
     * @Method("GET")
     * @return Response
     */
    public function nuevoAction($id, $text)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Category(false);
        $parent = $em->getRepository('AppBundle:Category')->find((int)$id);
        $entity->setParent($parent);
        $entity->setTitle($text);
        $entity->setFirst(false);
        $em->persist($entity);
        $em->flush();

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
        $log->setAccion('Nuevo');
        $log->setEntidad('Carpeta');
        $em->persist($log);
        $em->flush();

        if (null != $entity->getId()){
            return new Response('success');
        }

        return new Response('fail');
    }

    /**
     * Lists all entities.
     *
     * @Route("/folder/{id}", name="category_load_folder")
     * @param  $id
     * @Method({"GET","POST"})
     * @Template()
     * @return array|Response
     */
    public function loadfolderAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Category')->find($id);
        $level = $entity->getLevel();
        $cat = $em->getRepository('AppBundle:Category');
        $htmlTree = $cat->getPath($entity);

        //var_dump($htmlTree);die();

        if($level == 1 || $level == 0){
            $proyecto = $em->getRepository('AppBundle:Proyecto')->findOneBy(
                [
                    'category' => $id
                ]
            );
        }else {
            $categoryproyecto = $em->getRepository('AppBundle:Category')->findOneBy(
                [
                    'title' => $htmlTree[1]->getTitle(),

                ]
            );
            $proyecto = $em->getRepository('AppBundle:Proyecto')->findOneBy(
                [
                    'category' => $categoryproyecto->getId()
                ]
            );
        }

        $files = $em->getRepository('AppBundle:Fileimg')->findBy(
            [
                'category'  => $id,
                'activo'    => true
            ]
        );
        $numberFiles = count($files);

        $entproyecto = $em->getRepository('AppBundle:Category')->find($entity->getRoot());
        $isEncargado = false;

        $usuario = $this->getUser();
        if($entity->getEncargado()) {
            if ($entity->getEncargado()->getId() == $usuario->getId()) {
                $isEncargado = true;
            }
        }else{
            $root = $em->getRepository('AppBundle:Category')->find($entity->getRoot());
            if($root->getEncargado()) {
                if ($root->getEncargado()->getId() == $usuario->getId()) {
                    $isEncargado = true;
                }
            }
        }

        return [
            'proyecto'      => $proyecto,
            'files'         => $files,
            'numberfile'    => $numberFiles,
            'idfolder'      => $id,
            'proyectoview'  => $entproyecto->getProyecto(),
            'menus'         => $htmlTree,
            'entidad'       => $entity,
            'pictures'      => $entproyecto->getImagine(),
            'isencargado'   => $isEncargado
        ];
    }

    /**
     * Lists all entities.
     *
     * @Route("/folderpermisos/{id}", name="category_load_folder_permisos")
     * @param  $id
     * @Method({"GET","POST"})
     * @return Response
     */
    public function loadfolderpermisosAction($id)
    {
        //var_dump($id); die();
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getUser();
        $entity = $em->getRepository('AppBundle:Category')->getCategoryPermisoByUser($usuario->getId(),$id);
        $category = $em->getRepository('AppBundle:Category')->find($id);
        $encargado = $category->getEncargado();

        //var_dump($entity);die();

        if($usuario == $encargado){
            return new Response('success');
        }else{
            $root = $em->getRepository('AppBundle:Category')->find($category->getRoot());
            $encargado = $root->getEncargado();

            if($usuario == $encargado) {
                return new Response('success');
            }
        }

        if(isset($entity[0])){
            if (count($entity[0]) != 0) {
                return new Response('success');
            } else {
                return new Response('fail');
            }
        }else {
            return new Response('fail');
        }
    }


    /**
     * Creates a new entity.
     * @param $idcategoria
     * @param Request $request
     * @Route("/categoriaaddperemiso/{idcategoria}", name="app_categoria_add_permiso")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function permisotocategoriaAction(Request $request, $idcategoria)
    {
        $em = $this->getDoctrine()->getManager();

        $categorias = $em->getRepository('AppBundle:Category');

        $categoria = $em->getRepository('AppBundle:Category')->find($idcategoria);
        $childrens = $categorias->getChildren($categoria);

        //dump($children);die;
        $usgr = $em->getRepository('AppBundle:Permiso')->getPermisosByCategory($idcategoria);

        if(!isset($usgr)) {
            $usgr = null;
        }

        if ($request->isMethod('POST')) {
            if(isset($usgr)) {
                foreach ($usgr as $us) {

                    $entusuario = $em->getRepository('AppBundle:Permiso')->find($us);
                    $categoria->removePermiso($entusuario);
                    $em->persist($categoria);
                    $em->flush();
                }
            }

            $allusuarios = $request->get('userarray');

            foreach($allusuarios as $user){

                $entidadusuario = $em->getRepository('AppBundle:Permiso')->find($user);
                $categoria->addPermiso($entidadusuario);
                foreach($childrens as $children){
                    $children->addPermiso($entidadusuario);
                    $em->persist($children);
                    $em->flush();
                }
                $em->persist($categoria);
                $em->flush();
            }

            $path = "";
            $cat = $em->getRepository('AppBundle:Category');
            $trees = $cat->getPath($categoria);
            foreach ($trees as $tree){
                $path = $path .' / '.$tree;
            }
            $user = $this->getUser();
            $log = new Log($user);
            $log->setEntidadId($categoria->getId());
            $log->setNombre($categoria->getTitle());
            $log->setPath($path);
            $log->setAccion('Edita Grupos de Permiso a Carpeta');
            $log->setEntidad('Permiso');
            $em->persist($log);
            $em->flush();

            return new Response('success');
        }

        $allgrupospermisos = $em->getRepository('AppBundle:Permiso')->findAll();

        return [
            'idcategoria'   => $idcategoria,
            'grupos'        => $allgrupospermisos,
            'usgr'          => $usgr
        ];
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $request
     * @param $id
     * @Route("/{id}/editname", name_="app_category_editname")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function editnameAction(Request $request, $id)
    {
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createForm('AppBundle\Form\CategoryEditType', $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

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
            $log->setAccion('Editar');
            $log->setEntidad('Carpeta');
            $em->persist($log);
            $em->flush();
            return new Response('success');
        }

        return [
            'entity'    => $entity,
            'form'      => $editForm->createView(),
            'routes'    => $this->getRoutesForEntity(),
            'singular'  => $this::SINGULAR_NAME,
            'plural'    => $this::PLURAL_NAME
        ];
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $request
     * @param $id
     * @Route("/{id}/edit", name_="app_category_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createForm('AppBundle\Form\CategoryType', $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

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
            $log->setAccion('Editar');
            $log->setEntidad('Carpeta');
            $em->persist($log);
            $em->flush();
            return new Response('success');
        }

        return [
            'entity'    => $entity,
            'form'      => $editForm->createView(),
            'routes'    => $this->getRoutesForEntity(),
            'singular'  => $this::SINGULAR_NAME,
            'plural'    => $this::PLURAL_NAME
        ];
    }
    /**
     * Enabled Category entity.
     *
     * @Route("/{id}/enabled", name="category_enabled")
     * @Method("POST")
     * @param $id
     * @return Response
     */
    public function enabledAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }

        $em = $this->getDoctrine()->getManager();

        if($entity->getEnabled() === true){
            $entity->setEnabled(false);
        }else{
            $entity->setEnabled(true);
        }

        $em->persist($entity);
        $em->flush();

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
        if($entity->getEnabled(true)){
            $log->setAccion('Habilitar');
        }else{
            $log->setAccion('Deshabilitar');
        }

        $log->setEntidad('Carpeta');
        $em->persist($log);
        $em->flush();

        return new Response('success');

    }

    /**
     * @param $parentid
     * @param $id
     * @Route("/{id}/{parentid}/move", name_="app_category_move")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function move($id, $parentid)
    {
        $em = $this->getDoctrine()->getManager();
        $treeRepository = $em->getRepository('AppBundle:Category');

        $entity = $em->getRepository('AppBundle:Category')->find($id);
        $parentfolder = $em->getRepository('AppBundle:Category')->find($parentid);

        $treeRepository ->persistAsLastChildOf($entity, $parentfolder);

        $em->flush();

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
        $log->setAccion('Mover');
        $log->setEntidad('Carpeta');
        $em->persist($log);
        $em->flush();

        return $this->redirect($this->generateUrl('datastore'));
    }

    /**
     * Finds and displays a entity.
     * @param $id
     * @Route("/showusers/{id}", name_="app_categoria_show_users_permiso")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array
     */
    public function showuserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $usgr = $em->getRepository('AppBundle:Permiso')->getPermisosByCategory($id);

        if(!isset($usgr)) {
            $usgr = null;
        }

        $arrusers = [];
        if(isset($usgr)) {
            foreach ($usgr as $us) {

                $entusuario = $em->getRepository('AppBundle:Permiso')->find($us);

                $arrusersone = $em->getRepository('AppBundle:usuario')->getUserPermisos($entusuario);
                foreach ($arrusersone  as $key => $value){
                    array_push($arrusers, $value);
                }


            }
        }
        //dump($arrusers);die();
        $newarrusers = array_unique($arrusers);
//dump($newarrusers);die();
        return [
            'usuarios' => $arrusers,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function getRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return $repository = $em->getRepository('AppBundle:'.$this::ENTITY_NAME);
    }


    /**
     * @return array
     */
    public function getRoutesForEntity()
    {
        return [
            'results'   => "app_".strtolower($this::ENTITY_NAME)."_results",
            'show'      => "app_".strtolower($this::ENTITY_NAME)."_show",
            'new'       => "app_".strtolower($this::ENTITY_NAME)."_new",
            'edit'      => "app_".strtolower($this::ENTITY_NAME)."_edit",
            'delete'    => "app_".strtolower($this::ENTITY_NAME)."_delete",
            'enabled'   => "app_".strtolower($this::ENTITY_NAME)."_enabled"
        ];
    }

}