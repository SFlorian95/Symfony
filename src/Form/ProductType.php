<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*
         * contraintes du champ image 
         *      si l'entité posséde un identifiant vide : ajouter la contrainte NotBlank
         *      si l'entité posséde un identifiant non vide : supprimer la contrainte NotBlank
         * 
         * options['data'] : accès à l'entité
         */
        $entity = $options['data'];
        $constraintsImage =[
                    new Image([
                        'mimeTypes' => ['image/jpeg','image/png','image/gif','image/svg+xml','image/webp'],
                        'mimeTypesMessage' => 'Veuillez sélectionner une image'
                    ])
                ];
        
        //ajouter la contrainte NotBlank uniquement l'entité posséde un identifiant vide (insertion)
        
        if(!$entity->getId()){
            array_push(
                    $constraintsImage, 
                    new NotBlank([
                'message' => 'Veuillez sélectionner une image'
            ])
        );
    }
        
        //dd($options);
        
        /*
         * méthode add : ajouter un champ de saisie à votre formulaire
         * 3 paramètres : 
         *      - identifiant du champ : à utiliser dans la vue 
         *      -type du champ: https://symfony.com/doc/current/reference/forms/types.html
         *      -options du champs de saisie : tableau associatif
         *      -clé constraints: contraintes de validation 
         */
        $builder
            ->add('name',TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un nom de produit"
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 150,
                        'minMessage' => 'Le nom du produit doit comporter au minimum {{ limit }} caractères',
                        'maxMessage' => 'Le nom du produit doit comporter au maximum {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('price',MoneyType::class, [
                //'currency'=>'JPY',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un prix"
                        ]),
                    new GreaterThan([
                        'message' => 'Veuillez saisir un prix supérieur à 0',
                        'value' => 0
                    ])
                ]
            ])
            ->add('description',TextareaType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir une description"
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'La description du produit doit comporter au minimum {{ limit }} caractères'                      
                    ])
                    
                ]
            ])
            ->add('image',FileType::class, [
                'constraints' => $constraintsImage,
                'help' => 'Veuillez sélectionner une image au format JPG',
                'data_class' => null
            ])
            /*
             * champ de saisie en relation avec une entité
             *      utiliser EntityType
             *      paramètres:
             *              class : cibler l'entité
             *              choice label : propriété à afficher dans le champ de saisie
             * 
             *              expanded : affichage de plusieurs champs; par défaut false
             *              
             *              multiple : sélection multiple; par défaut false
             * 
             * liste déroulante : expanded false / multiple false
             * boutons radio : expanded true / multiple false
             * checkbox : expanded true / multiple true
             * 
             * Pour une relation ManyToMany : 
             *      Obligation de créer des cases à cocher
             *      constraints Count : comptage du nombre de sélection 
             */
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => "Veuillez sélectionner une catégorie"
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
