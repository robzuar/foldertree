<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Proyecto;
use AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Log;

/**
 * Proyecto controller.
 *
 * @Route("proyecto")
 */
class ProyectoController extends CrudController
{
    const ENTITY_NAME = "Proyecto";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Proyecto";
    const TYPE_NAMESPACE = "AppBundle\\Form\\ProyectoType";
    const TYPE_NAMESPACE_ANALYST = "AppBundle\\Form\\ProyectoAnalystType";
    const TYPE_NAMESPACE_INCHARGE = "AppBundle\\Form\\ProyectoInchargeType";
    const SINGULAR_NAME = "Proyecto";
    const PLURAL_NAME = "Proyectos";

    /**
     * Creates a new entity.
     * @param $id
     * @param Request $request
     * @Route("/nuevo/{id}")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function nuevoAction(Request $request, $id)
    {
        $entity = new Proyecto();
        $form = $this->createForm($this::TYPE_NAMESPACE, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository('AppBundle:Category')->find($id);
            $entity->setCategory($category);
            $em->persist($entity);
            $em->flush();

            $user = $this->getUser();
            $log = new Log($user);
            $log->setEntidadId($entity->getId());
            $log->setNombre($entity->getNombre());
            $log->setAccion('Nuevo');
            $log->setEntidad('Proyecto');
            $em->persist($log);
            $em->flush();

            return new Response('success');
        }

        return [
            'entity' => $entity,
            'idfolder' => $id,
            'form'   => $form->createView(),
            'routes' => $this->getRoutesForEntity(),
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Displays a form to edit an existing entity.
     * @param $request
     * @param $id
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Proyecto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createForm($this::TYPE_NAMESPACE, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();


            $user = $this->getUser();
            $log = new Log($user);
            $log->setEntidadId($entity->getId());
            $log->setNombre($entity->getNombre());
            $log->setAccion('Editar');
            $log->setEntidad('Proyecto');
            $em->persist($log);
            $em->flush();

            $this->correoCambios($entity->getId());

            return new Response('success');
        }

        return [
            'entity' => $entity,
            'form' => $editForm->createView(),
            'routes' => $this->getRoutesForEntity()
        ];
    }

    /**
     * @param $idproyecto
     * @return bool
     */
    public function correoCambios($idproyecto)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Usuario $user */
        $user = $this->getUser();
        $time = new \DateTime();
        $today = $time->format('Y-m-d');

        $grupos = $em->getRepository('AppBundle:GrupoCambios')->findAll();
        $cambios = $em->getRepository('AppBundle:LogProyecto')->findOneBy(
            [
                'objectId' => $idproyecto
            ],
            [
                'id' => 'DESC'
            ]
        );

        if(!$cambios->getSendit()) {

            $proyecto = $em->getRepository('AppBundle:Proyecto')->find($idproyecto);
            $cat = $em->getRepository('AppBundle:Category');
            $entity = $em->getRepository('AppBundle:Category')->find($proyecto->getCategory());
            //var_dump($entity);
            $htmlTree = $cat->getPath($entity);
            $arraygrupotousuarios = [];

            if (count($grupos) >= 1) {
                foreach ($grupos as $grupo) {
                    $arraygrupotousuarios[] = $em->getRepository('AppBundle:Usuario')->getUserGrupoCambios($grupo);
                }
            }

            $newarray = array_unique($arraygrupotousuarios[0]);

            if ($newarray >= 1) {
                $subject = 'Se ha Modificado un Proyecto ';

                foreach ($newarray as $usuario) {


                    $strTo = $usuario->getEmail();


                    $strSubject = $subject;
                    $strBody = $this->renderView('AppBundle:Default:correocambiosproyecto.html.twig',
                        [
                            'creador' => $user->getNombres() . ' ' . $user->getApellidos(),
                            'receptor' => $usuario,
                            'cambios' => $cambios,
                            'menus' => $htmlTree

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
            $cambios->setSendit(true);
            $em->persist($cambios);
            $em->flush();
        }
        return true;//new Response('success');
    }
}
