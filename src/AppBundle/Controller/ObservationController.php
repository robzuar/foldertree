<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Observation controller.
 *
 * @Route("observation")
 */
class ObservationController extends CrudController
{
    const ENTITY_NAME = "Observation";
    const ENTITY_NAMESPACE = "AppBundle\\Entity\\Observation";
    const TYPE_NAMESPACE = "AppBundle\\Form\\ObservationType";
    const SINGULAR_NAME = "Observación";
    const PLURAL_NAME = "Observaciones";

}