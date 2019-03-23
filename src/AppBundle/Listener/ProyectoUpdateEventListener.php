<?php

namespace AppBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Proyecto;

class ProyectoUpdateEventListener
{
    public function preUpdate(LifecycleEventArgs $args)
    {

    }
} 