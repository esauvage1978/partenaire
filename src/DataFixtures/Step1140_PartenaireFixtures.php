<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Contact;
use App\Entity\Partenaire;
use App\Helper\FixturesImportData;
use App\Repository\CityRepository;
use App\Repository\ContactRepository;
use App\Validator\PartenaireValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1140_PartenaireFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_partenaire';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var PartenaireValidator
     */
    private $validator;

    /**
     * @var Contact[]
     */
    private $contacts;

    /**
     * @var City[]
     */
    private $cities;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        PartenaireValidator $validator,
        EntityManagerInterface $entityManagerI,
        ContactRepository $contactRepository,
        CityRepository $cityRepository
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->entityManagerInterface = $entityManagerI;
        $this->contacts = $contactRepository->findAll();
        $this->cities = $cityRepository->findAll();
    }


    public function load(ObjectManager $manager)
    {


        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < count($data); ++$i) {
            $instance = $this->initialise(new Partenaire(), $data[$i]);

            $this->checkAndPersist($instance);
        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Partenaire $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Partenaire::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : ' . $this->validator->getErrors($instance));
        }
    }


    public function getInstance(string $id, $entitys)
    {
        if ($id === '0') {
            return null;
        }

        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
    }
    public function getInstanceByName(?string $name, $entitys)
    {
        if (empty($name)) {
            return null;
        }

        foreach ($entitys as $entity) {
            if ($entity->getName() === $name) {
                return $entity;
            }
        }
    }

    private function initialise(Partenaire $instance, $data): Partenaire
    {
        /** @var Contact $contact */
        $contact = $this->getInstance($data['id_contact'], $this->contacts);

        /** @var City $city */
        $city = $this->getInstanceByName($data['adresse_ville'], $this->cities);


        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setEnable($data['afficher'])
            ->setContent($data['description'])
            ->setPhone1($data['telephone1'])
            ->setPhone2($data['telephone2'])
            ->setMail1($this->check_mail($data['mail1']))
            ->setMail2($this->check_mail($data['mail2']))
            ->setCirconscription($data['circonscription'])
            ->setAddComp1($data['adresse_ad1'])
            ->setAddComp2($data['adresse_ad2'])
            ->setAddCp($this->check_adr_cp($data['adresse_cp']));

        if (!empty($contact)) {
            $instance->setReferent($contact);
        }

        if (!empty($city)) {
            $instance->setAddCity($city);
        }

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1140'];
    }

    private function check_adr_cp(?string $cp)
    {
        if (empty($cp)) {
            return null;
        }
        return str_replace(' ', '', $cp);

    }

    private function check_mail(?string $mail)
    {
        if (empty($mail)) {
            return null;
        }
        $mailModif = trim($mail);
        $translit = array('Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ä' => 'A', 'Ã' => 'A', 'Å' => 'A', 'Ç' => 'C', 'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Í' => 'I', 'Ï' => 'I', 'Î' => 'I', 'Ì' => 'I', 'Ñ' => 'N', 'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Ö' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'á' => 'a', 'à' => 'a', 'â' => 'a', 'ä' => 'a', 'ã' => 'a', 'å' => 'a', 'ç' => 'c', 'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i', 'ñ' => 'n', 'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'ö' => 'o', 'õ' => 'o', 'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ÿ' => 'y');
        return strtr($mailModif, $translit);
    }
}
