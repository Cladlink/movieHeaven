<?php
/**
 * Created by PhpStorm.
 * User: mlucile
 * Date: 26/11/16
 * Time: 21:48
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class addFilmForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titreFilm')
            ->add('dureeFilm')
            ->add('dateFilm', DateType::class, ['widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
                'html5' => false,
            ])
            ->add('prixFilm')
            ->add('quantiteFilm')
            ->add('imageFilm', FileType::class)
            ->add('typeFilmId')
            ->add('realisateurId');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Film'
        ]);
    }


}