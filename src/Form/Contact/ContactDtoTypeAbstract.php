<?php

namespace App\Form\Contact;

use App\Dto\ContactDto;
use App\Entity\Contact;
use App\Entity\Fonction;
use App\Entity\Civilite;
use App\Entity\Role;
use App\Form\AppTypeAbstract;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactDtoTypeAbstract extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $binaire = [
            ''=>'',
            'Visible' => ContactDto::TRUE,
            'Masqué' => ContactDto::FALSE,
        ];

        $builder
            ->add('fonction', EntityType::class, [
                self::CSS_CLASS => Fonction::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => false,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->select('c')
                        ->where('c.enable = true')
                        ->orderBy('c.name', 'ASC');
                }
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
            ->add('role', EntityType::class, [
                self::CSS_CLASS => Role::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => false,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->select('c')
                        ->where('c.enable = true')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('civilite', EntityType::class, [
                self::CSS_CLASS => Civilite::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => false,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->select('c')
                        ->where('c.enable = true')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('wordSearch', TextType::class,
                [
                    self::LABEL => 'Mot à chercher',
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactDto::class,
        ]);
    }
}
