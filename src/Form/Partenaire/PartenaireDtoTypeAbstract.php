<?php

namespace App\Form\Partenaire;

use App\Dto\ContactDto;
use App\Dto\PartenaireDto;
use App\Entity\City;
use App\Entity\Partenaire;
use App\Form\AppTypeAbstract;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartenaireDtoTypeAbstract extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $binaire = [
            'Visible' => PartenaireDto::TRUE,
            'Masqué' => PartenaireDto::FALSE,
        ];
        $circonscription = [
            'Oui' => PartenaireDto::TRUE,
            'En dehors' => PartenaireDto::FALSE,
        ];

        $builder
            ->add('wordSearch', TextType::class,
                [
                    self::LABEL => 'Mot à chercher',
                    self::REQUIRED => false,
                ])
            ->add('enable', ChoiceType::class, [
                'choices' => $binaire,
                self::MULTIPLE => false,
                'expanded' => false,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                'mapped' => true,
                self::LABEL => 'Afficher',
                self::REQUIRED=>false
            ])
            ->add('circonscription', ChoiceType::class, [
                'choices' => $circonscription,
                self::MULTIPLE => false,
                'expanded' => false,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                'mapped' => true,
                self::LABEL => 'Circonscription',
                self::REQUIRED=>false
            ])
            ->add('city', EntityType::class, [
                self::CSS_CLASS => City::class,
                self::LABEL=>'Ville',
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => false,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
            ])
            ->add('page', TextType::class,
                [
                    self::LABEL => 'page',
                    self::DATA=>'1',
                    self::REQUIRED => false,
                ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'partenaire';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PartenaireDto::class,
        ]);
    }
}
