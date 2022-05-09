<?php

namespace App\Form;

use App\Entity\Library;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LibraryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, [           
            'label' => 'Title:',
            'attr' => ['class' => 'form-control mb-2'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Title is missing.',
                ]),                
            ],
        ])
        ->add('language', ChoiceType::class, [
            'choices' => ['English' => 'EN', 
                          'German' => 'DE', 
                          'Chinese' => 'ZH', 
                          'Spanish' => 'ES',
                          'Italien' => 'IT',
                          'Arabic' => 'AR'
                         ],
            'label' => 'Language:',
            'attr' => ['class' => 'form-control mb-2'],
        ])
        ->add('level', ChoiceType::class, [
            'choices' => ['A1' => 'A1', 
                          'A2' => 'A2', 
                          'B1' => 'B1',
                          'B2' => 'B2',
                          'C1' =>  'C1'
                         ],
            'label' => 'Level:',
            'attr' => ['class' => 'form-control mb-2'],
        ])                               
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Library::class,
        ]);
    }
}