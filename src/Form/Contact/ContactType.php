<?php

namespace App\Form\Contact;

use App\Entity\Contact;
use App\Entity\Fonction;
use App\Entity\Civilite;
use App\Entity\Role;
use App\Form\AppTypeAbstract;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->buildFormNameEnableContent($builder);
        $builder
            ->add('fonction', EntityType::class, [
                self::CSS_CLASS => Fonction::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => false,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
            ])
            ->add('civilite', EntityType::class, [
                self::CSS_CLASS => Civilite::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => false,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
            ])
            ->add('roles', EntityType::class, [
                self::CSS_CLASS => Role::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
            ])
            ->add('mail1', EmailType::class,
                [
                self::LABEL => 'Principale',
                self::REQUIRED => false,
                ])
            ->add('mail2', EmailType::class,
                [
                    self::LABEL => 'Secondaire',
                    self::REQUIRED => false,
                ])
            ->add('phone1',TelType::class,
                [
                    self::LABEL => 'Principale',
                    self::REQUIRED => false,
                ])
            ->add('phone2',TelType::class,
                [
                    self::LABEL => 'Secondaire',
                    self::REQUIRED => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
