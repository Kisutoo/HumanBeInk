<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\Color;
use App\Entity\Detail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SimulationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('size', RangeType::class, [
                'label' => 'Taille'
            ])
            ->add('color', EntityType::class, [
                'class' => Color::class,
                'label' => 'Couleur'
                
            ])
            ->add('area', EntityType::class, [
                'class' => Area::class,
                'label' => 'Zone'
            ])
            ->add('detail', EntityType::class, [
                'class' => Detail::class,
                'label' => 'Détails'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
