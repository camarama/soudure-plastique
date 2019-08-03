<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class AgreeTermsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', ChoiceType::class, [
                'label' => 'Période souhaitée de démarrage des travaux',
                'choices' => [
                    'Dans 1 semaine' => "semaine prochaine",
                    'Dans 2 semaines' => "Dans 2 semaines",
                    'Dans 1 mois' => "Dans 1 mois",
                    'Plus Tard' => "Dans 2 mois"
                ],
                'group_by' => function($choice, $key, $value)
                    {
                        if ($choice <= new \DateTime('+3 week'))
                        {
                            return 'Rapidement';
                        } else
                            {
                                return 'Après';
                            }
                    },
                'attr' => [
                    'class' => 'border-secondary mb-4 custom-select text-muted'
                ],
                /*'preferred_choices' => function($choice, $key, $value)
                    {
                        return $choice <= new \DateTime('+2 week');
                    },*/

            ])
            ->add('agree_term', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Veuillez accepter d'abord les conditions générales."
                    ])
                ],
                'label' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
