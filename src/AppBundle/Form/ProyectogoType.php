<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ProyectogoType
 * @package AppBundle\Form
 */
class ProyectogoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,
                [
                    'label' => 'Nombre',
                    'attr' => ['class' => 'form-control'],
                    //'constraints' => [ new NotBlank(["message" => "El campo nombre no puede estar vacio"])],
                    'required' => false,
                ]
            )
            ->add('inmobiliaria',TextType::class,
                [
                    'label' => 'Inmobiliaria',
                    'attr' => ['class' => 'form-control'],
                    //'constraints' => [ new NotBlank(["message" => "El campo inmobiliaria no puede estar vacio"])],
                    'required' => false,
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
            'data_class' => 'AppBundle\Entity\Proyectogo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_proyectogo';
    }


}
