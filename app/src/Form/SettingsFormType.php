<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SettingsFormType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('avatar', FileType::class, [
            'label' => 'Avatar',
            'mapped' => false,
            'required' => false,
            'multiple' => false,
            'attr' => [
                'placeholder' => 'Selecciona una imagen (JPG, PNG, WEBP)'
            ],
            'constraints' => [
                new File([
                    'maxSize' => '2048k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ],
                    'mimeTypesMessage' => 'Por favor sube tu avatar',
                ])
            ]
       ])
        ->add('username',
        TextType::class,
        [
            'label' => 'Nombre de Usuario: ',
            'attr'  => [
                'placeholder' => 'Nombre de Usuario',
                'minLength' => 3,
                'maxLength' => 40
            ],
            'constraints' => [new Length(['min' => 3, 'max' => 40])],

        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}