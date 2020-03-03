<?php

namespace App\Form\Partenaire;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\Contact;
use App\Entity\Partenaire;
use App\Form\AppTypeAbstract;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartenaireType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->buildFormNameEnableContent($builder);
        $builder
            ->add('referent', EntityType::class, [
                self::CSS_CLASS => Contact::class,
                self::LABEL=>'Référent',
                self::CHOICE_LABEL => 'fullname',
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
            ->add('add_city', EntityType::class, [
                self::CSS_CLASS => City::class,
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
            ->add('category', EntityType::class, [
                self::CSS_CLASS => Category::class,
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
            ->add('circonscription', CheckboxType::class,
                [
                    self::LABEL => ' ',
                    self::REQUIRED => false,
                ])
            ->add('mail1', EmailType::class,
                [
                self::LABEL => 'Principale',
                self::ATTR=>[self::PLACEHOLDER=>'Principal',self::MAXLENGTH=>'255'],
                self::REQUIRED => false,
                ])
            ->add('mail2', EmailType::class,
                [
                    self::LABEL => 'Secondaire',
                    self::ATTR=>[self::PLACEHOLDER=>'Secondaire',self::MAXLENGTH=>'255'],
                    self::REQUIRED => false,
                ])
            ->add('phone1',TelType::class,
                [
                    self::LABEL => 'Principale',
                    self::ATTR=>[self::PLACEHOLDER=>'Principal',self::MAXLENGTH=>'255'],
                    self::REQUIRED => false,
                ])
            ->add('phone2',TelType::class,
                [
                    self::LABEL => 'Secondaire',
                    self::ATTR=>[self::PLACEHOLDER=>'Secondaire',self::MAXLENGTH=>'255'],
                    self::REQUIRED => false,
                ])
            ->add('add_comp1', TextType::class,
                [
                    self::LABEL => 'Rue',
                    self::REQUIRED => false,
                    self::ATTR=>[self::PLACEHOLDER=>'N° Rue',self::MAXLENGTH=>'255'],
                ])
            ->add('add_comp2',TextType::class,
                [
                    self::LABEL => 'Complement',
                    self::ATTR=>[self::PLACEHOLDER=>'Complément d\'adresse',self::MAXLENGTH=>'255'],
                    self::REQUIRED => false,
                ])
            ->add('add_cp',TextType::class,
                [
                    self::LABEL => 'Code postal',
                    self::ATTR=>[self::PLACEHOLDER=>'Code postal',self::MAXLENGTH=>'255'],
                    self::REQUIRED => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Partenaire::class,
        ]);
    }
}
