<?php

namespace App\Form;

use App\Entity\Info;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siret', NumberType::class, [
                'label' => 'Siret',
                'label_attr' => [
                    'class' => 'col-sm-3 text-right control-label col-form-label'
                ],
                'help' => 'Ex. 802 954 785 00028'
            ])
            ->add('telephone', TelType::class, [
                'label' => 'N° Tel',
                'label_attr' => [
                    'class' => 'col-sm-3 text-right control-label col-form-label'
                ],
                'help' => 'Ex. 06 52 40 10 07'
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'col-sm-3 text-right control-label col-form-label'
                ],
                'help' => 'Ex. 452 allée des ormes'
            ])
            ->add('cp', NumberType::class, [
                'label' => 'Code Postal',
                'label_attr' => [
                    'class' => 'col-sm-3 text-right control-label col-form-label'
                ],
                'help' => 'Ex. 59130'
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'col-sm-3 text-right control-label col-form-label'
                ],
                'help' => 'Ex. Lambersart'
            ])
            ->add('pays', CountryType::class, [
                'label' => 'Pays',
                'label_attr' => [
                    'class' => 'col-sm-3 text-right control-label col-form-label'
                ]
            ])
//            ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Info::class,
        ]);
    }
}
