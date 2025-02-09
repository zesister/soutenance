<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[ 
                'required'=> false,
                'attr'=>[  
                'placeholder' => 'saisir le nom du produit'
                ]
            ])
            ->add('prix',NumberType::class,[ 
                'required'=> false,
                'attr'=>[ 
                    'placeholder'=>'saisir le nom du produit'
                ]
            ])
            ->add('photo', FileType::class,[ 
                'label'=>'Photo',
                'required'=>false,
                'constraints'=>[ 
                    new File([ 
                        'mimeTypes'=>[ 
                            "image/png",
                            "image/jpg",
                            "image/jpeg",
                            "image/gif",
                            "image/jfif",
                            "image/webp"
                        ],
                         'mimeTypesMessage'=>'Format non géré'
                    ])
                ]
            ])

            ->add('description', TextareaType::class,[ 
               'label'=>'Description du produit',
               'required' =>false,
               'attr'=>[ 
                'placeholder'=>'Saisir une description du produit'
            ]
         ])
            ->add('enregistrer', SubmitType::class)
        ;
    }
        
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
