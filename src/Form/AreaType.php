<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // Ajout d'un champ texte pour le nom
            ->add('areaName', TextType::class, [
                'label' => 'Nom de la zone'
            ])
        // Ajout d'un champ de séléction pour le multiplicateur
            ->add('multiplicator', NumberType::class, [
                'label' => 'Multiplicateur'
            ])
        // Ajout d'un champ pour savoir si oui ou non il s'agit d'une zone sensible
            ->add('sensibility', CheckboxType::class, [
                'label' => 'Sensible',
                'required'   => false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'user'
        ]);
    }
}
