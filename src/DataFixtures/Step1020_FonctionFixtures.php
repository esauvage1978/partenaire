<?php

namespace App\DataFixtures;

use App\Entity\Fonction;
use App\Helper\FixturesImportData;
use App\Validator\FonctionValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1020_FonctionFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_contact';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var FonctionValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    private $dataAdd;



    public function __construct(
        FixturesImportData $fixturesImportData,
        FonctionValidator $validator,
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
            $instance = $this->initialise(new Fonction(), $data[$i]);
            if (!empty($instance)) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Fonction $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Fonction::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
            return;
        }
            var_dump('Validator : ' . $this->validator->getErrors($instance));

    }

    private function initialise(Fonction $instance, $data): ?Fonction
    {
        if (empty($data['fonction'])) {
            return null;
        }

        if(in_array($data['fonction'],$this->dataAdd)) {
            return null;
        }

        array_push( $this->dataAdd,$data['fonction']);

        $instance
            ->setName($data['fonction'])
            ->setEnable(true);

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1020'];
    }
}
