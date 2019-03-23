<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
/**
 * Class AnteproyectoType
 * @package AppBundle\Form
 */
class AnteproyectoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add( 'comuna',
                EntityType::class,
                [
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $repository){
                        return $repository->createQueryBuilder('p')
                            ->addOrderBy('p.name', 'ASC');
                    },
                    'placeholder'   => 'Seleccione una Comuna',
                    'class' => 'AppBundle:Comuna',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'choices_as_values' => true,
                    'expanded' => false,
                    'required' => false
                ]
            )
            ->add('nombre',
                TextType::class,
                [
                    'label' => 'Nombre Anteproyecto',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('dateadmision',
                DateType::class,
                [
                    'label' => 'Fecha de Ingreso',
                    'widget' => 'single_text',
                    'format'    => 'dd-MM-yyyy',
                    'attr' => ['class' => 'form-control js-datepicker'],
                    'html5'      => false
                ]
            )
            ->add('observationone',
                TextareaType::class,
                [
                    'label' => '1° Observación Ingreso',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('observationtwo',
                TextareaType::class,
                [
                    'label' => '2° Observación Ingreso',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('resolution',
                TextType::class,
                [
                    'label' => 'N° Resolución Aprobación',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('dateresolution',
                DateType::class,
                [
                    'label' => 'Fecha de Resolución Aprobación',
                    'widget' => 'single_text',
                    'format'    => 'dd-MM-yyyy',
                    'attr' => ['class' => 'form-control js-datepicker'],
                    'html5'      => false
                ]
            )
            ->add('dateexpiration',
                DateType::class,
                [
                    'label' => 'Fecha de Vencimiento',
                    'widget' => 'single_text',
                    'format'    => 'dd-MM-yyyy',
                    'attr' => ['class' => 'form-control js-datepicker'],
                    'html5'      => false,
                    //'view_timezone' => 'America/Santiago'
                    //'format'        => 'dd.MM.yyyy',

                ]
            )
            ->add('revisor',
                TextType::class,
                [
                    'label' => 'Nombre Revisor Independiente',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            ->add('comments',
                TextareaType::class,
                [
                    'label' => 'Comentarios',
                    'attr' =>
                        [
                            'class' => 'form-control'
                        ]
                ]
            )
            /*
            ->add('file',
                FileType::class,
                [
                    'label' => 'Archivo PDF'
                ]
            )
            */
            /*
            ->add( 'reviser',
                EntityType::class,
                [
                    'placeholder'   => 'Revisor Independiente',
                    'class' => 'AppBundle:Reviser',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'choices_as_values' => true,
                    'expanded' => false,
                    'required' => false
                ]
            )
            ->add( 'observacion',
                EntityType::class,
                [
                    'placeholder'   => 'Seleccionar Observación',
                    'class' => 'AppBundle:Observation',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'choices_as_values' => true,
                    'expanded' => false,
                    'required' => false
                ]
            )
            */
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Anteproyecto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_anteproyecto';
    }


}
