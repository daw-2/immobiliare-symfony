<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('price', MoneyType::class, [
                'divisor' => 100,
            ])
            ->add('surface', RangeType::class, [
                'attr' => [
                    'min' => 10,
                    'max' => 400,
                    'value' => 10,
                    'class' => 'w-100',
                ],
            ])
            ->add('rooms', ChoiceType::class, [
                'label' => 'Nombre de pièces',
                'choices' => [
                    'Studio' => 1,
                    'T2' => 2,
                    'T3' => 3,
                    'T4' => 4,
                    'T5' => 5,
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('sold', CheckboxType::class, [
                'label' => 'Vendu ?',
            ])
            ->add('image', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
