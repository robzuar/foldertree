<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Permission controller.
 *
 * @Route("permission")
 */
class PermissionController extends CrudController
{
    const ENTITY_NAME = "Permission";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Permission";
    const TYPE_NAMESPACE = "AppBundle\\Form\\PermissionType";
    const SINGULAR_NAME = "Grupo Permission";
    const PLURAL_NAME = "Grupos Permission";
    
}
