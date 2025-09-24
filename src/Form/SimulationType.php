<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\Size;
use App\Entity\Color;
use App\Entity\Detail;
use App\Repository\SizeRepository;
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
        // $sizeRepository = new SizeRepository();
        // $sizes = $sizeRepository->findAll([], ["size" => "DESC"]); 
        // dd($sizes);
        
        $builder
            ->add('size', RangeType::class, [
                'attr' => ['max' => 150],
                'label' => 'Taille'
            ])
            ->add('color', EntityType::class, [
                'class' => Color::class,
                'placeholder'  => 'Choisissez un type de couleur',
                'label' => 'Couleur'
                
            ])
            ->add('area', EntityType::class, [
                'class' => Area::class,
                'placeholder'  => 'Choisissez une zone à tatouer',
                'label' => 'Zone'
            ])
            ->add('detail', EntityType::class, [
                'class' => Detail::class,
                'placeholder'  => 'Choisissez le nombre de détails',
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
