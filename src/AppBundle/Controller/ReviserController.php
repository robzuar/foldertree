<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reviser controller.
 *
 * @Route("reviser")
 */
class ReviserController extends CrudController
{
    const ENTITY_NAME = "Reviser";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Reviser";
    const TYPE_NAMESPACE = "AppBundle\\Form\\ReviserType";
    const SINGULAR_NAME = "Reviser";
    const PLURAL_NAME = "Revisers";

}
