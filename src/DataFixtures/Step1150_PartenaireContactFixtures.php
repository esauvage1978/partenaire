<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Partenaire;
use App\Helper\FixturesImportData;
use App\Repository\ContactRepository;
use App\Repository\PartenaireRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1150_PartenaireContactFixtures extends Fixture implements  FixtureGroupInterface
{
    CONST FILENAME='asr_box_rel_partenaire_contact';
    /**
     * @var FixturesImportData
     */
    private $importData;

    /**
     * @var Partenaire[]
     */
    private $partenaires;

    /**
     * @var Contact[]
     */
    private $contacts;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $importData,
        PartenaireRepository $partenaireRepository,
        ContactRepository $contatRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->importData=$importData;
        $this->partenaires=$partenaireRepository->findAll();
        $this->contacts=$contatRepository->findAll();
        $this->entityManagerInterface=$entityManagerI;
    }

    public function load(ObjectManager $manager)
    {

        $data=$this->importData->importToArray(self::FILENAME. ".json");

        for($i=0;$i<\count($data);$i++) {

            $partenaire = $this->getInstance($data[$i]['gauche'], $this->partenaires);

            $contact = $this->getInstance($data[$i]['droite'], $this->contacts);

            if( is_a($partenaire,Partenaire::class)
                &&
                is_a($contact,Contact::class)
            ) {
                $partenaire->addContact($contact);

                $this->entityManagerInterface->persist($partenaire);
            }
        }

        $this->entityManagerInterface->flush();
    }

    public function getInstance(string $id, $entitys)
    {
        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
    }

    public static function getGroups(): array
    {
        return ['step1150'];
    }

}
