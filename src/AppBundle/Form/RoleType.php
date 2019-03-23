<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RoleType
 * @package AppBundle\Form
 */
class RoleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',
                TextType::class,
                [
                    'label' => 'Nombre',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ],
                    'constraints' =>
                        [
                            new NotBlank
                            (
                                [
                                    "message" => "El campo Nombre no puede estar vacio"
                                ]
                            )
                        ]
                ]
            )
            ->add('displayName',
                TextType::class,
                [
                    'label' => 'Nombre Visible',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ],
                    'constraints' =>
                        [
                            new NotBlank
                            (
                                [
                                    "message" => "El campo Nombre Visible no puede estar vacio"
                                ]
                            )
                        ]
                ]
            )
            ->add('description',
                TextType::class,
                [
                    'label' => 'Descripción',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ],
                    'constraints' =>
                        [
                            new NotBlank
                            (
                                [
                                    "message" => "El campo Descripción no puede estar vacio"
                                ]
                            )
                        ]
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
            'data_class' => 'AppBundle\Entity\Role'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_role';
    }


}
