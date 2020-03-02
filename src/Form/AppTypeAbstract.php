<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AppTypeAbstract extends AbstractType
{
    const LABEL = 'label';
    const DATA = 'data';
    const REQUIRED = 'required';
    const ROW_ATTR = 'row_attr';
    const ATTR = 'attr';
    const CHOICE_LABEL = 'choice_label';
    const MULTIPLE = 'multiple';
    const CSS_CLASS = 'class';
    const ROWS = 'rows';
    const GROUP_BY = 'group_by';
    const QUERY_BUILDER = 'query_builder';
    const DISABLED = 'disabled';
    const MAXLENGTH = 'maxlength';
    const PLACEHOLDER = 'placeholder';

    public function buildFormNameEnableContent(FormBuilderInterface $builder): FormBuilderInterface
    {
        return $builder
            ->add('name', TextType::class, [
                self::LABEL => 'Nom',
                self::REQUIRED => true,
                self::ATTR=>[self::MAXLENGTH=>255],
            ])
            ->add('enable', CheckboxType::class,
                [
                    self::LABEL => ' ',
                    self::REQUIRED => false,
                ])
            ->add('content', TextareaType::class, [
                self::LABEL => 'Description',
                self::REQUIRED => false,
                self::ATTR => [self::ROWS => 3, self::CSS_CLASS => 'textarea'],
            ]);
    }
    public function buildFormNameContent(FormBuilderInterface $builder): FormBuilderInterface
    {
        return $builder
            ->add('name', TextType::class, [
                self::LABEL => 'Nom',
                self::REQUIRED => true,
            ])

            ->add('content', TextareaType::class, [
                self::LABEL => 'Description',
                self::REQUIRED => false,
                self::ATTR => [self::ROWS => 3, self::CSS_CLASS => 'textarea'],
            ]);
    }



    public function buildFormUsers(FormBuilderInterface $builder): FormBuilderInterface
    {
        return $builder
            ->add('users', EntityType::class, [
                'class' => User::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => true,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
            ]);
    }

}
