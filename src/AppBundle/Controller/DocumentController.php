<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Document;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Document controller.
 *
 * @Route("document")
 */
class DocumentController extends CrudController
{
    const ENTITY_NAME = "Document";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Document";
    const TYPE_NAMESPACE = "AppBundle\\Form\\DocumentType";
    const SINGULAR_NAME = "Documento";
    const PLURAL_NAME = "Documentos";


    /**
     * Lists all document entities.
     *
     * @Route("/results", name="app_document_results")
     * @Method("GET")
     * @Template()
     */
    public function resultsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('AppBundle:Document');
        $queryresultv= $em->getRepository('AppBundle:Document')->getEnabled();

        $options = array('decorate' => false,
            'representationField' => 'slug',
            'html' => false);
        $htmlTree = $repo->buildTree($queryresultv->getArrayResult(), $options);


        return [
            'entities'      => $htmlTree,
            //'tree'      => json_encode($htmlTree),
            'tree'      => $htmlTree,
            'singular' => $this::SINGULAR_NAME,
            'plural' => $this::PLURAL_NAME
        ];
    }

    /**
     * Creates a new entity.
     * @param $text
     * @param $id
     * @Route("/treenew/{id}/{text}", name="app_document_tree_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function newtreeAction($id, $text)
    {
        $em = $this->getDoctrine()->getManager();

        $parent = $em->getRepository('AppBundle:Document')->find($id);

        $entity = new Document();
        $entity->setParent($parent);
        $entity->setName($text);
        $em->persist($entity);
        $em->flush();

        return new Response('success');
    }

    /**
     * Creates a new entity.
     * @param $text
     * @param $id
     * @param $idparent
     * @Route("/treerename/{id}/{text}/{idparent}", name="app_document_tree_rename")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function renametreeAction($id,$idparent, $text)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Document')->find($id);
        $parent = $em->getRepository('AppBundle:Document')->find($idparent);

        $entity->setName($text);
        $entity->setParent($parent);
        $em->persist($entity);
        $em->flush();

        return new Response('success');
    }

    /**
     * Creates a new entity.
     * @param $text
     * @param $id
     * @param $val
     * @param $isfile
     * @Route("/document/treeup/{id}/{text}/{val}/{isfile}", name="app_document_tree_up")
     * @Method({"GET", "POST"})
     * @Template()
     * @return JsonResponse|Response
     */
    public function up($id, $text, $val,$isfile)
    {
        $em = $this->getDoctrine()->getManager();
        $parent = $em->getRepository('AppBundle:Document')->find($id);

        if($isfile == 'file'){
            $valfile = true;
        }else{
            $valfile = false;
        }

        if($val === 'new'){
            $entity = new Document();
            $entity->setName($text);
            $entity->setParent($parent);
            $entity->setIsfile($valfile);
        }else{
            $entity = $em->getRepository('AppBundle:Document')->find($id);
            $entity->setName($text);
        }

        $em->persist($entity);
        $em->flush();
        if($val === 'new'){
            return new JsonResponse(
                [
                    'respuesta'     => 'success',
                    'id'            => $entity->getId()
                ]
            );
        }else {
            return new Response('success');
        }
    }

    /**
     * @param $parentid
     * @param $id
     * @Route("/document/treemove/{id}/{parentid}", name_="app_document_tree_move")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function move($id, $parentid)
    {
        $em = $this->getDoctrine()->getManager();
        $treeRepository = $em->getRepository('AppBundle:Document');

        $entity = $em->getRepository('AppBundle:Document')->find($id);
        $parentfolder = $em->getRepository('AppBundle:Document')->find($parentid);

        $treeRepository ->persistAsLastChildOf($entity, $parentfolder);

        $em->flush();

        return new Response('success');
    }

    /**
     * @param $id
     * @Route("/document/treedelete/{id}", name_="app_document_tree_delete")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array|Response
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Document')->find($id);
        //$treeRepository = $em->getRepository('AppBundle:Document');
        //$treeRepository->removeFromTree($entity);
        $entity->setEnabled(false);
        $em->persist($entity);
        $em->flush();

        return new Response('success');
    }

    /**
     * @param $id
     * @Route("/document/treechange/{id}", name_="app_document_tree_change")
     * @Method("POST")
     * @Template()
     * @return Response
     */
    public function change($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Document')->find($id);

        if($entity->getIsfile() === true){
            $entity->setIsfile(false);
        }else{
            $root = $em->getRepository('AppBundle:Document')->haveChild($id);
            //dump(count($root));exit();
            if(count($root) == 0) {
                $entity->setIsfile(true);
            }else{
                return new Response('cant');
            }
        }

        $em->persist($entity);
        $em->flush();

        return new Response('success');
    }
}