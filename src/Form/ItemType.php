<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('ord', IntegerType::class, [
            'required' => true,           
            'attr' => [
               'min' => 1,
               'max' => 1000,               
            ],
        ])      
        ->add('type', ChoiceType::class, [
            'choices' => ['Dialog' => 'D', 'Text' => 'T', 'Exercise' => 'E'],
            'label' => 'Item type:'
        ])
        ->add('text', TextType::class, [     
            'required' => false,       
            'label' => 'Text:',
            'attr' => ['class' => 'form-control mb-2'],            
        ])
        ->add('translation', TextType::class, [   
            'required' => false,         
            'label' => 'Translation:',
            'attr' => ['class' => 'form-control mb-2'],            
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
            'data_class' => Item::class,
        ]);
    }
}