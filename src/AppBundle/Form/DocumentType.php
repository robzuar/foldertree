<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class DocumentType
 * @package AppBundle\Form
 */
class DocumentType extends AbstractType
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
            ->add('isfile', CheckboxType::class,
                [
                    'label'    => 'Es Archivo?',
                    'required' => true,
                ]
            )
            ->add('numfiles',
                IntegerType::class,
                [
                    'label' => 'Número de Archivos',
                    'attr' =>
                        [
                            'class' => 'form-control'
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
            'data_class' => 'AppBundle\Entity\Document'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_document';
    }


}
