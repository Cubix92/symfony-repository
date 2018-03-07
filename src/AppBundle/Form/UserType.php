<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nazwa użytkownika',
                'attr' => array('placeholder' => 'Wpisz swoją nazwę')
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email',
                'attr' => array('placeholder' => 'Wpisz swój adres email')
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Pola z hasłami powinny do siebie pasować.',
                'options' => array(
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Wpisz swój adres email'
                    ),
                ),
                'required' => true,
                'first_options'  => array('label' => 'Hasło'),
                'second_options' => array('label' => 'Powtórz hasło')
            ))
            ->add('save', SubmitType::class, array('label' => 'Zarejestruj się', 'attr' =>
                    array('class' => 'btn btn-primary btn-lg btn-block login-button'))
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'attr' => array('novalidate' => 'novalidate')
        ));
    }
}
