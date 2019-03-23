<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Category;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class DefaultController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="redirect")
     */
    public function redirectAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * Lists all Cargo entities.
     *
     * @Route("/error_errorIE", name="default_error_errorIE")
     * @Method("GET")
     * @Template()
     */
    public function errorIEAction(Request $request)
    {
        return array('template' => "empty");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/menu", name="menu")
     */
    public function menuAction()
    {

        $em = $this->getDoctrine()->getManager();

        $menus = $em->getRepository('AppBundle:Category')->findFirstMenu();

        return $this->render(
            'default/menu.html.twig',
            [
                "menus" => $menus
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/anteproyectos", name="anteproyectos")
     */
    public function dashAnteproyectos()
    {
        $user = $this->getUser();

        if(in_array('app-anteproyecto',$user->getAccesoNames())){
            return $this->redirectToRoute('app_anteproyecto_index'
        );
        }else{
            return $this->render('default/index.html.twig'
                ,array(
                    'accesserror' => 'Sin Acceso a AnteProyecto'
                )
            );
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/datastore", name="datastore")
     */
    public function dashDatastore()
    {
        $user = $this->getUser();

        if(in_array('app-datastore',$user->getAccesoNames())){
            return $this->render('default/dashDatastore.html.twig');
        }else{
            return $this->render('default/index.html.twig'
                ,array(
                        'accesserror' => 'Sin Acceso a DataStore'
                    )
                );
        }

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/usuarios", name="usuarios")
     */
    public function dashUsuario()
    {
        $user = $this->getUser();

        if(in_array('app-usuarios',$user->getAccesoNames())){
            return $this->render('default/dashUsuarios.html.twig');
        }else{
            return $this->render('default/index.html.twig'
                ,array(
                    'accesserror' => 'Sin Acceso a Configuración de Usuarios'
                )
            );
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/go/goindex", name="go")
     */
    public function dashGo()
    {
        $user = $this->getUser();

        if(in_array('app-go',$user->getAccesoNames())){
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $roles = $user->getRoles();
            $element = null;
            $value = 0;
            $goestados = $em->getRepository('AppBundle:Goestado')->findAll();
            $estadoPendiente = $em->getRepository('AppBundle:Goestado')->findOneBy(
                [
                    'name'  => 'PENDIENTE'
                ]
            );

            if(in_array('ROLE_SUPER_ADMIN',$roles)) {
                $proyectos = $em->getRepository('AppBundle:Proyectogo')->findAll();
            }else{
                $element = 'inicio';
                $proyectos = $em->getRepository('AppBundle:Proyectogo')->getProyectos($goestados, $user->getId(), $element, $value, $user->getRoles());
                $proyectos = $proyectos->getResult();
            }
            if(in_array('ROLE_SUPER_ADMIN',$roles)) {
                $element = null;
                $tareasPendientes = $em->getRepository('AppBundle:Taskdocument')->findBy(
                    [
                        'estado'  =>  $estadoPendiente
                    ]
                );
            }else{
                $element = 'inicio';
                $tareasPendientes = $em->getRepository('AppBundle:Taskdocument')->getTasks($goestados, $user->getId(), $element, $value, $user->getRoles());
                $tareasPendientes = $tareasPendientes->getResult();
            }

            if(in_array('ROLE_SUPER_ADMIN',$roles)) {
                $element = null;
                $tareasAsignadas = $em->getRepository('AppBundle:Taskdocument')->getTasks($goestados, $user->getId(), $element, $value, $user->getRoles());
                $tareasAsignadas = $tareasAsignadas->getResult();
            }else{
                $tareasAsignadas = null;
            }


            return $this->render('default/dashGo.html.twig',
                    [
                        'proyectos'         => $proyectos,
                        'tareaspendientes'            => $tareasPendientes,
                        'tareasasignadas'            => $tareasAsignadas,
                    ]
                );
        }else{
            return $this->render('default/index.html.twig'
                    ,[
                        'accesserror' => 'Sin Acceso a Imagina Go'
                    ]
                );
        }
    }
}
