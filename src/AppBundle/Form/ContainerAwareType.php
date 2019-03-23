<?php
namespace AppBundle\Form;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class ContainerAwareType extends AbstractType implements ContainerAwareInterface
{

    protected $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'container' => $this->container
        ));
    }

    public function getName() {
        return 'container_aware';
    }

    public function getParent() {
        return 'form';
    }
}