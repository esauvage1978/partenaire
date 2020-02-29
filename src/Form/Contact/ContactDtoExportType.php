<?php

namespace App\Form\Contact;

use App\Dto\ContactDto;
use App\Entity\Contact;
use App\Entity\Fonction;
use App\Entity\Civilite;
use App\Form\AppTypeAbstract;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactDtoExportType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add('wordSearch', TextType::class,
                [
                    self::LABEL => 'Mot à chercher',
                    self::REQUIRED => false,
                ])

        ;
    }

    public function getBlockPrefix()
    {
        return 'contact_export';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactDto::class,
        ]);
    }
}
