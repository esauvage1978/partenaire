<?php

namespace App\Form\Admin;

use App\Entity\Contact;
use App\Entity\Role;
use App\Form\AppTypeAbstract;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                self::LABEL => 'Nom',
                self::REQUIRED => true,
            ])
            ->add('contacts', EntityType::class, [
                self::CSS_CLASS => Contact::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
            ])
            ->add('enable', CheckboxType::class,
                [
                    self::LABEL => ' ',
                    self::REQUIRED => false,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
