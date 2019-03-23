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
 * Class ProyectoType
 * @package AppBundle\Form
 */
class ProyectoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',
                TextType::class,
                [
                    'attr' =>
                       [
                           'class' => 'form-control'
                       ]
                ]
            )
            ->add('inmobiliaria',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('direccion',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('rut',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('pisos',
                IntegerType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('subterraneos',
                IntegerType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('estacionamientos',
                IntegerType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('estacionamientosVisita',
                IntegerType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('departamentos',
                IntegerType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('bodegas',
                IntegerType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('arquitecto',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('supervisor',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('estado',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('dateend',DateType::Class, [
                        'format'=>'dd/MM/yyyy',
                        'label' => 'Fecha Recepción',
                        'widget' => 'single_text',
                        'attr' => [
                            'class' => 'form-control input-inline datepicker',
                            'data-provide' => 'datepicker',
                        ],
                        'required' => true,
                        'translation_domain' => true,
                    ]
                )
            ->add('dateescritura',DateType::Class, [
                        'format'=>'dd/MM/yyyy',
                        'label' => 'Fecha Recepción',
                        'widget' => 'single_text',
                        'attr' => [
                            'class' => 'form-control input-inline datepicker',
                            'data-provide' => 'datepicker',

                        ],
                        'required' => true,
                        'translation_domain' => true,
                    ]
            )
            ->add('datelast',DateType::Class, [
                        'format'=>'dd/MM/yyyy',
                        'label' => 'Fecha Recepción',
                        'widget' => 'single_text',
                        'attr' => [
                            'class' => 'form-control input-inline datepicker',
                            'data-provide' => 'datepicker',
                        ],
                        'required' => true,
                        'translation_domain' => true,
                    ]
            )
            ->add('datedelivery',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('dateendtext',
                TextType::class,
                [
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
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Proyecto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_proyecto';
    }


}
