<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Helper\FixturesImportData;
use App\Repository\CategoryRepository;
use App\Validator\CategoryValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1050_CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_partenaire';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var CategoryValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    private $dataAdd;



    public function __construct(
        FixturesImportData $fixturesImportData,
        CategoryValidator $validator,
        EntityManagerInterface $entityManagerI
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->entityManagerInterface = $entityManagerI;
        $this->dataAdd=[];
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new Category(), $data[$i]);
            if (!empty($instance)) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Category $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Category::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance));

    }

    private function initialise(Category $instance, $data): ?Category
    {
        if (empty($data['categorie'])) {
            return null;
        }

        if(in_array($data['categorie'],$this->dataAdd)) {
            return null;
        }

        array_push( $this->dataAdd,$data['categorie']);

        $instance
            ->setName($data['categorie'])
            ->setEnable(true);

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1050'];
    }
}
