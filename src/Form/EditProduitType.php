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

class EditProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[ 
                'label'=>'Nom  du produit',
                'required'=>false,
                'attr'=>[ 
                    'placeholder'=>'Saisez le nom du produit' 
                    ]
                    ])
            ->add('prix', NumberType::class,[ 
                'label'=>'Prix du produit' ,
                'required'=>false,
                'attr'=>[ 
                    'placeholder'=> 'Saisez le prix du produit'
                ]
            ])
            ->add('editPhoto', FileType::class,[ 
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
                            "image/web"
                        ],
                        'mimeTypesMessage'=>'Format non géré'
                    ])
                ]
            ])
            ->add('description', TextareaType::class,[ 
                'label'=>'Description du produit',
                'required'=>false,
                'attr'=>[ 
                    'placeholder'=>'Saisisez une description du produit'
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
