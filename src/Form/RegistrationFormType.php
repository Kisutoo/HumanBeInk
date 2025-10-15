<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudonyme')
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "J'accepte les conditions générales d'utilisations",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Vous devez accepter nos conditions d'utilisation.",
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                "type" => PasswordType::class,
                // Le Type ici fait référence au type d'input qui sera utilisé dans le formulaire (Ici un input Password)
                'mapped' => false,
                // Ceci signifie qu'à la soumission du formulaire, le mot de passe rentré ne sera pas automatiquement
                // assigné à l'entité User
                'attr' => ['autocomplete' => 'new-password'],
                "invalid_message" => "Les mots de passent doivent être les mêmes.",
                "first_options" => ["label" => "Mot de passe"],
                "second_options" => ["label" => "Confirmer le mot de passe"],
                // Assigne des labels aux deux champs mots de passe
                'constraints' => [
                    new NotBlank([
                        "message" => "Veuillez saisir un mot de passe."
                    // Ajoute une contrainte obligeant à saisir un mot de passe
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    // Ajoute une contrainte de taille obligeant l'utilisateur à saisir au minimum 12 caractères
                    new Regex("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%.^&*-]).{12,}$/")
                    // Ajoute une contrainte de format sur le mot de passe obligeant celui-ci à contenir certains caractères

                ],
                
            ])
            ->add('captcha', Recaptcha3Type::class, [
            'constraints' => new Recaptcha3(),
            'action_name' => 'register',
            'locale' => 'fr',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
