<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UsuarioType
 * @package AppBundle\Form
 */
class UsuarioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('username');

        $roles = [
            'ROLE_USER'         => 'ROLE_USER',
            'ROLE_ADMIN'        => 'ROLE_ADMIN',
            'ROLE_SUPER_ADMIN'  => 'ROLE_SUPER_ADMIN',
            'ROLE GO REVISOR'      => 'ROLE_ANALISTA',
            'ROLE GO RESPONSABLE'  => 'ROLE_ENCARGADO'
        ];

        $builder
            ->add('email',
                EmailType::class,
                [
                    'label' => 'form.email',
                    'translation_domain' => 'FOSUserBundle',
                    'constraints' =>
                        [
                            new NotBlank
                            (
                                [
                                    "message" => "El campo Email no puede estar vacio"
                                ]
                            )
                            ,
                            new Email
                            (
                                [
                                    "message" => "Favor de ingresar un Email valido"
                                ]
                            ),
                        ]
                ]
            )
            ->add('nombres',
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
                                    "message" => "El campo Nombres no puede estar vacio"
                                ]
                            )
                        ]
                ]
            )
            ->add('apellidos',
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
                                    "message" => "El campo Apellidos no puede estar vacio"
                                ]
                            )
                        ]
                ]
            )

            ->add('roles',
                ChoiceType::class,
                [
                    'label'   => 'Roles',
                    'choices' => $roles,
                    'multiple' => true,
                    'expanded' => false
                ]
            )

            ->add('accesos',
                EntityType::class,
                [
                    'label'   => 'Accesos a Modulo',
                    'class' => 'AppBundle:Acceso',
                    'choice_label' => 'name',
                    'multiple' => true,
                    'choices_as_values' => true,
                    'expanded' => false
                ]
            )

            ->add( 'groups',
                EntityType::class,
                [
                    'label'   => 'Grupos de Correo',
                    'class' => 'AppBundle:Group',
                    'choice_label' => 'name',
                    'multiple' => true,
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
            'data_class' => 'AppBundle\Entity\Usuario',
            'csrf_token_id' => 'registration',
            'groups' => [],
            'accesos'   => [],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_usuario';
    }


}
