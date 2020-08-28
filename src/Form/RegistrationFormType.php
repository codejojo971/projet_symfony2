<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class ,[
                'constraints' => [
                    new NotBlank(['message'=>'Email manquant.']),
                    new Length([
                        'max'               => 180,
                        'maxMessage'        => 'L\'adreese email ne peut contenir plus de {{ limit }} caractères.'
                        ]),
                    new Email(['message'    => 'Cette adresse n\'est pas une adresse email valide'])    
                ]
            ])
            ->add('pseudo', TextType::class, [
                'constraints' => [
                    new NotBlank(['message'=>'Pseudo manquant.']),
                    new Length([
                        'min'       => 3,
                        'minMessage'=> 'Le pseudo doit contenir au mons {{ limit }} caractères.',
                        'max'       => 30,
                        'maxMessage'=> 'Le pseudo ne peut contenir plus de {{ limit }} caractères.'
                    ]),
                    new Regex([
                        'pattern'=> '/^[a-zA-Z0-9_-]+$/',
                        'message' => 'Le pseudo ne peut contenir que des chiffres, lettres, tirets et underscores.'
                    ])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
