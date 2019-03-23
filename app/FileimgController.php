<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Fileimg;
use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Log;

/**
 * Fileimg controller.
 *
 * @Route("fileimg")
 */
class FileimgController extends CrudController
{
    const ENTITY_NAME = "Fileimg";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Fileimg";
    const TYPE_NAMESPACE = "AppBundle\\Form\\FileimgType";
    const SINGULAR_NAME = "Fileimg";
    const PLURAL_NAME = "Fileimgs";

    /**
     * Lists all entities.
     *
     * @Route("/filesall/{idfolder}/{idroot}", name="app_fileimg_listall")
     * @param  $idroot
     * @param $idfolder
     * @Method({"GET","POST"})
     * @Template()
     * @return array|Response
     */
    public function listallAction($idroot, $idfolder)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();

        $files = $em->getRepository('AppBundle:Fileimg')->findBy(
            [
                'category'  => $idfolder,
                'root'      => $idroot
            ]
        );

        $entity = $em->getRepository('AppBundle:Category')->find($idfolder);
        $level = $entity->getLevel();
        $categorybase = $em->getRepository('AppBundle:Category')->find($entity->getRoot());

        if($level == 1){
            $proyecto = $em->getRepository('AppBundle:Proyecto')->findOneBy(
                [
                    'category' => $idfolder
                ]
            );
        }else {

            $root = $entity->getRoot();
            $category = $em->getRepository('AppBundle:Category')->findOneBy(
                [
                    'level' => 1,
                    'root' => $root
                ]
            );

            $proyecto = $em->getRepository('AppBundle:Proyecto')->findOneBy(
                [
                    'category' => $category->getId()
                ]
            );
        }
        $cat = $em->getRepository('AppBundle:Category');
        $htmlTree = $cat->getPath($entity);

        return [
            'proyecto'  => $proyecto,
            'files'  => $files,
            'idfolder' => $idfolder,
            'menus' => $htmlTree,
            'pictures' => $categorybase->getImagine()
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
        $newarray = [];

        if(count($listadogrupos) >= 1) {
            foreach ($listadogrupos as $grupo) {
                $entgrupo = $em->getRepository('AppBundle:Group')->find($grupo);
                /** Usuario $arraygrupotousuarios */
                $arraygrupotousuarios[] = $em->getRepository('AppBundle:Usuario')->getUserGroups($entgrupo);
            }
        }

        //dump($listadousuarios);die('fin');

        if(count($listadousuarios) >= 1) {
            foreach($listadousuarios as $usua) {
                //dump($usua);die('fin');
                $arraygrupotousuarios[0][] = $em->getRepository('AppBundle:Usuario')->find($usua);
            }
        }
        //dump($arraygrupotousuarios);die('fin');
        if(count($listadogrupos) >= 1 || count($listadousuarios) >= 1) {
            $newarray = array_unique($arraygrupotousuarios[0]);
        }

        //dump($newarray);die('fin');
        if($newarray  >= 1){
            $subject = 'Se ha Creado un nuevo Archivo: ';

            foreach ($newarray as $usuario) {

                if($parametroemail == true) {
                    $strTo = $usuario->getEmail();
                }else{
                    $strTo = $user->getEmail();
                    //$strTo = 'roberto.zuniga.araya@gmail.com';
                }

                $strSubject = $subject;
                $strBody = $this->renderView('AppBundle:Fileimg:correoaviso.html.twig',
                    [
                        'creador' => $user->getNombres() . ' ' . $user->getApellidos(),
                        'receptor' => $usuario,
                        'archivos' => $archivos,
                        'menus' => $htmlTree
                    ]
                );

                $develMailerService = $this->get('app_mailer');
                $develMailerService->setTo($strTo);
                $develMailerService->setSubject($strSubject);
                $develMailerService->setFrom($this->get('service_container')
                    ->getParameter('mailer_user'));
                $develMailerService->sendEmail($strBody);
            }
        }
        return true;//new Response('success');
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/nuevoarchivo/{id}", name="app_fileimg_newfile")
     * @Method({"GET", "POST"})
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function newfileAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getUser();

        if ($request->getMethod() === 'POST') {

            $files = $request->files->get ( 'files');
            $nombrearchivos = [];
            $category = $em->getRepository('AppBundle:Category')->find($id);
            $idUsuario = $usuario->getId();


            $version = $request->get('cantversion');

            $increment = 0;

            foreach($files as $file){
                if($version > 0) {
                    for ($i=0; $i < $version; $i++) {
                        $nombreoriginal = explode(".", $file->getClientOriginalName())[0];
                        $extensionoriginal = explode(".", $file->getClientOriginalName())[1];

                        $nombreoriginal = strtoupper($nombreoriginal);
                        $string = explode("_V", $nombreoriginal);
                        $solonombre = $string[0];
                        $solonombre = strtoupper($solonombre);



                        $extension = $file->guessExtension();

                        if($i == 0) {
                            $entity = new Fileimg($usuario);
                            $fecha = new \DateTime();
                            $fecha = $fecha->format('dmyHis');
                            $fileName = $idUsuario . '' . $increment . '' . $fecha;
                            $fileName = strtoupper($fileName).'.' . $extension;
                            $entity->setCategory($category);
                            $entity->setFile($fileName);
                            $entity->setActivo(false);
                            $entity->setLink($solonombre);

                            $em->persist($entity);
                            $em->flush();
                        }else{
                            $original = $em->getRepository('AppBundle:Fileimg')->findBy(
                                [
                                    'link' => $solonombre,
                                ],
                                [
                                    'level' => 'DESC'
                                ]
                            );
                            $entity = new Fileimg($usuario);
                            $fecha = new \DateTime();
                            $fecha = $fecha->format('dmyHis');
                            $fileName = $idUsuario . '' . $increment . '' . $fecha . '.' . $extension;
                            $parent = $em->getRepository('AppBundle:Fileimg')->find($original[0]->getId());
                            $entity->setParent($parent);
                            $entity->setCategory($category);
                            $entity->setFile($fileName);
                            $entity->setActivo(true);
                            $entity->setLink($solonombre);
                            $parent->setActivo(false);

                            $em->persist($parent);
                            $em->persist($entity);
                            $em->flush();
                        }

                    }
                }

                if(isset($file)) {
                    $nombreoriginal =  explode(".", $file->getClientOriginalName())[0];
                    $extensionoriginal =  explode(".", $file->getClientOriginalName())[1];

                    $nombreoriginal = strtoupper($nombreoriginal);
                    $string = explode("_V", $nombreoriginal);
                    $solonombre = $string[0];
                    $original = $em->getRepository('AppBundle:Fileimg')->findBy(
                        [
                            'link' => $solonombre,
                        ],
                        [
                            'level' => 'DESC'
                        ]
                    );

                    $entity = new Fileimg($usuario);
                    $fecha = new \DateTime();
                    $extension = $file->guessExtension();
                    $fecha = $fecha->format('dmyHis');
                    $fileName = $idUsuario . ''.$increment.'' . $fecha . '.' . $extension;
                    $pathDocument = $this->get('service_container')
                            ->getParameter('kernel.root_dir') . '/../web/media/upload';
                    $pathcache = $this->get('service_container')
                            ->getParameter('kernel.root_dir') . '/../web/media/upload/cache';
                    $pathpdf = $this->get('service_container')
                            ->getParameter('kernel.root_dir') . '/../web/media/upload/cache/pdf';
                    $paththumb = $this->get('service_container')
                            ->getParameter('kernel.root_dir') . '/../web/media/upload/cache/home_thumb';
                    if (!file_exists($pathDocument)) {
                        mkdir($pathDocument, 0777);

                    }
                    if (!file_exists($pathcache)) {
                        mkdir($pathcache, 0777);
                    }
                    if (!file_exists($pathpdf)) {
                        mkdir($pathpdf, 0777);
                    }
                    if (!file_exists($paththumb)) {
                        mkdir($paththumb, 0777);
                    }
                    $increment++;

                    if(count($original)  < 1) {
                        if (!$file->getError()) {
                            $file->move($pathDocument, $fileName);
                            $entity->setCategory($category);
                            $entity->setFile($fileName);
                            $entity->setActivo(true);
                            $entity->setLink($solonombre);

                            $em->persist($entity);
                            $em->flush();

                            $path = "";
                            $cat = $em->getRepository('AppBundle:Category');
                            $trees = $cat->getPath($category);
                            foreach ($trees as $tree){
                                $path = $path .' / '.$tree;
                            }
                            $user = $this->getUser();
                            $log = new Log($user);
                            $log->setEntidadId($entity->getId());
                            $log->setNombre($entity->getLink());
                            $log->setPath($path);
                            $log->setAccion('Nuevo');
                            $log->setEntidad('Archivo');
                            $em->persist($log);
                            $em->flush();

                            $nombrearchivos[] =  $solonombre;
                        } else {
                            $fullpath = $pathDocument . '/' . $fileName;
                            if (file_exists($fullpath)) {
                                unlink($fullpath);
                            }
                            $archivoserror[] = $entity;
                        }
                    }else{
                        if (!$file->getError()) {
                            $file->move($pathDocument, $fileName);

                            $parent = $em->getRepository('AppBundle:Fileimg')->find($original[0]->getId());
                            $entity->setParent($parent);
                            $entity->setCategory($category);
                            $entity->setFile($fileName);
                            $entity->setActivo(true);
                            $entity->setLink($solonombre);
                            $parent->setActivo(false);

                            $em->persist($parent);
                            $em->persist($entity);
                            $em->flush();

                            $path = "";
                            $cat = $em->getRepository('AppBundle:Category');
                            $trees = $cat->getPath($category);
                            foreach ($trees as $tree){
                                $path = $path .' / '.$tree;
                            }
                            $user = $this->getUser();
                            $log = new Log($user);
                            $log->setEntidadId($entity->getId());
                            $log->setNombre($entity->getLink());
                            $log->setPath($path);
                            $log->setAccion('Nuevo versión');
                            $log->setEntidad('Archivo');
                            $em->persist($log);
                            $em->flush();

                            $nombrearchivos[] =  $solonombre;

                        } else {
                            $fullpath = $pathDocument . '/' . $fileName;
                            if (file_exists($fullpath)) {
                                unlink($fullpath);
                            }
                            $archivoserror[] = $entity;
                        }

                        if($extension == "gif" || $extension == "jpg" || $extension == "jpeg" || $extension == "png"){
                            $strImageBody = base64_decode($file);

                            //var_dump($strImageBody);die();

                            file_put_contents($pathDocument.'/'.$fileName, $strImageBody);

                        }
                    }

                    unset($entity);
                    unset($fecha);
                    unset($fileName);
                    unset($pathDocument);
                    unset($grupoaccesos);
                    unset($usgr);

                }
            }
            $listadosgrupos =  $request->get('grupoarray');
            $listadousarios =  $request->get('userarray');

            $this->correoAviso($listadousarios, $listadosgrupos, $nombrearchivos, $id);

            $cat = $em->getRepository('AppBundle:category')->find($request->get('idfolder'));



            return $this->redirect($this->generateUrl('category_level',['id' => $id, 'pagina' => 1]));
        }

        $usuarios = $em->getRepository('AppBundle:Usuario')->findAll();
        $grupos = $em->getRepository('AppBundle:Group')->findAll();
        $accesos = $em->getRepository('AppBundle:Acceso')->findAll();

        return [
            'idfolder'  => $id,
            'usuarios'  => $usuarios,
            'grupos'    => $grupos,
            'accesos'   => $accesos
        ];
    }

    /**
     * Enabled Fileimg entity.
     *
     * @Route("/{id}/enabled", name="fileimg_delete_file")
     * @Method("POST")
     * @param $id
     * @return Response
     */
    public function enabledAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Fileimg')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }

        $accion = "";

        if($entity->getId() == $entity->getRoot()){
            $em->remove($entity);
            $accion = "Eliminar";
        }else {

            $level = $entity->getLevel();
            $newleveldown = $level - 1;
            $root = $entity->getRoot();

            $newfile = $em->getRepository('AppBundle:Fileimg')->findOneBy(
                [
                    'level' => $newleveldown,
                    'root' => $root
                ]
            );

            $newfile->setActivo(true);
            $em->persist($newfile);

            $em->remove($entity);
            $accion = "Eliminar";

        }


        $path = "";
        $cat = $em->getRepository('AppBundle:Category');
        $trees = $cat->getPath($entity->getCategory());
        foreach ($trees as $tree){
            $path = $path .' / '.$tree;
        }
        $user = $this->getUser();
        $log = new Log($user);
        $log->setEntidadId($entity->getId());
        $log->setNombre($entity->getLink());
        $log->setPath($path);
        $log->setAccion($accion);
        $log->setEntidad('Archivo');
        $em->persist($log);
        $em->flush();

        return new Response('success');

    }

    /**
     * @param Request $request
     * @return Response
     */
    public function deleteSelectedAction(Request $request)
    {


        return new Response('success');
    }

}
