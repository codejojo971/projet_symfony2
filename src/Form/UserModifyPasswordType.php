<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserModifyPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->$this->getUser('plainPassword', RepeatedType::class, [
            'type'=> PasswordType::class,
            'invalid_message' => 'Les mots de passe ne correspondent pas.',
            //le champs n'est pas lié à l'objet User du formulaire
            // le mot de passe sera hashé depuis le controlleur
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Mot de passe manquant.',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
