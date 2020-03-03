<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Entity\Partenaire;
use App\Form\AppTypeAbstract;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->buildFormNameEnableContent($builder);
        $builder
            ->add('partenaires', EntityType::class, [
                self::CSS_CLASS => Partenaire::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->select('c')
                        ->orderBy('c.name', 'ASC');
                }]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
