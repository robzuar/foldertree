<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Logmail controller.
 *
 * @Route("logmail")
 */
class LogmailController extends CrudController
{
    const ENTITY_NAME = "Logmail";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Logmail";
    const TYPE_NAMESPACE = "AppBundle\\Form\\LogmailType";
    const SINGULAR_NAME = "Grupo Logmail";
    const PLURAL_NAME = "Grupos Logmail";

}
