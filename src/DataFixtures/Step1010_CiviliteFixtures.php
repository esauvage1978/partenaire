<?php

namespace App\DataFixtures;

use App\Entity\Civilite;
use App\Helper\FixturesImportData;
use App\Repository\CiviliteRepository;
use App\Validator\CiviliteValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1010_CiviliteFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_contact';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var CiviliteValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    private $dataAdd;



    public function __construct(
        FixturesImportData $fixturesImportData,
        CiviliteValidator $validator,
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
            $instance = $this->initialise(new Civilite(), $data[$i]);
            if (!empty($instance)) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Civilite $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Civilite::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
            return;
        }
            var_dump('Validator : ' . $this->validator->getErrors($instance));

    }

    private function initialise(Civilite $instance, $data): ?Civilite
    {
        if (empty($data['civilite'])) {
            return null;
        }

        if(in_array($data['civilite'],$this->dataAdd)) {
            return null;
        }

        array_push( $this->dataAdd,$data['civilite']);

        $instance
            ->setName($data['civilite'])
            ->setEnable(true);

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1010'];
    }
}
