<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Helper\FixturesImportData;
use App\Validator\CityValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1040_CityFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_ville';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var CityValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        CityValidator $validator,
        EntityManagerInterface $entityManagerI
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->entityManagerInterface = $entityManagerI;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new City(), $data[$i]);

            $this->checkAndPersist($instance);

        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(City $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(City::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance));

    }

    private function initialise(City $instance, $data): City
    {
        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setContent($data['description'])
            ->setEnable($data['afficher']);

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1040'];
    }
}
