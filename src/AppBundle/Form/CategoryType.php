<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class CategoryType
 * @package AppBundle\Form
 */
class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
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
            ->add('proyecto', CheckboxType::class,
                [
                    'label' => 'Es un menu que requiere proyectos?',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ],
                ]
            )
            ->add('imagine', CheckboxType::class,
                [
                    'label' => 'Es un menu que requiere imagenes previas?',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ],
                ]
            )
            ->add( 'encargado',
                EntityType::class,
                [
                    'label'   => 'Encargado de Menú',
                    'class' => 'AppBundle:Usuario',
                    'choice_label' => 'fullName',
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
            'data_class' => 'AppBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_category';
    }


}
