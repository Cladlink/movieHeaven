<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emailUtilisateur', EmailType::class)
            ->add('plainPassword', RepeatedType::class,
                [ 'type' => PasswordType::class,
                    'mapped' => true])
            ->add('nomUtilisateur')
            ->add('prenomUtilisateur')
            ->add('adresseUtilisateur')
            ->add('villeUtilisateur')
            ->add('codePostalUtilisateur')
            ->add('captchaCode', 'Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType', array(
                'captchaConfig' => 'ExampleCaptcha'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Utilisateur'
        ]);
    }
}