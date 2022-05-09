<?php

namespace App\Form;

use App\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class UnitType extends AbstractType
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
        ->add('ord', IntegerType::class, [
            'required' => true,           
            'attr' => [
               'min' => 1,
               'max' => 1000,               
            ],
        ])              
        ->add('uploadedSoundFile', FileType::class, [
            'label' => 'Sound track:',  
            'mapped' => false,                                  
            'required' => false,
            'constraints' => [                    
                new File([
                    'maxSize' => 1000000,
                    'mimeTypes' => ['audio/mpeg'],
                    'mimeTypesMessage' => 'File type should be .mp3',
                ]), 
            ]                                            
        ])           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Unit::class,
        ]);
    }
}