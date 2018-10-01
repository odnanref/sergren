<?php

namespace App\Form;

use App\Entity\SellingContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SellingContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $numberchoice = array(0 => 0);
        for ($i = 1 ; $i <= 10 ; $i++) {
            $numberchoice[$i] = $i;
        }
        $builder
            ->add('name')
            ->add('phone1')
            ->add('phone2')
            ->add('email')
            ->add('bestcontacttime', ChoiceType::class, array('choices' => 
                array('Manhã' => 'Manhã',
                    'Tarde' => 'Tarde',
                    'Noite' => 'Noite'
                )
            ))
            ->add('brand')
            ->add('model')
            ->add('yearmade')
            ->add('yearmodel')
            ->add('millage')
            ->add('collor')
            ->add('fuel', ChoiceType::class, ['choices' => [
                'Gasolina' => 'Gasolina',
                'Flex (Gasolina/Alcool)' => 'Flex (Gasolina/Alcool)',
                'Flex (Gasolina/Elétrico' =>'Flex (Gasolina/Elétrico',
                'Elétrico' => 'Elétrico'
                ]
            ])
            ->add('doors', ChoiceType::class, array('choices' =>
                 array('2' => '2',
                     '3' => '3',
                     '4' => '4'
                 )
                
            ))
            ->add('gears', ChoiceType::class, array('choices' => [
                'Manual' => 'Manual',
                'Automático' => 'Automático'
            ]))
            ->add('state_chassi', ChoiceType::class, array('choices' => $numberchoice))
            ->add('engine', ChoiceType::class, array('choices' => $numberchoice))
            ->add('glass', ChoiceType::class, array('choices' => $numberchoice))
            ->add('exhaust', ChoiceType::class, array('choices' => $numberchoice))
            ->add('tires', ChoiceType::class, array('choices' => $numberchoice))
            ->add('transmission', ChoiceType::class, array('choices' => $numberchoice))
            ->add('interior', ChoiceType::class, array('choices' => $numberchoice))
            ->add('hasdamage')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SellingContact::class,
        ]);
    }
}
