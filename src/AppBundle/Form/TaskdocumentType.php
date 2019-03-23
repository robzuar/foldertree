<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class TaskdocumentType
 * @package AppBundle\Form
 */
class TaskdocumentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add( 'proyectogo',
                EntityType::class,
                [
                    'placeholder'   => 'Seleccionar Proyecto',
                    'class' => 'AppBundle:Proyectogo',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'choices_as_values' => true,
                    'expanded' => false,
                    'required' => false
                ]
            )
            ->add( 'document',
                EntityType::class,
                [

                    'placeholder'   => 'Seleccionar Documento',
                    'class' => 'AppBundle:Document',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'choices_as_values' => true,
                    'expanded' => false
                ]
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Taskdocument'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_taskdocument';
    }


}
