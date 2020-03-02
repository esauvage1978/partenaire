<?php

namespace App\DataFixtures;

use App\Entity\Civilite;
use App\Entity\Contact;
use App\Entity\Fonction;
use App\Helper\FixturesImportData;
use App\Repository\CiviliteRepository;
use App\Repository\FonctionRepository;
use App\Validator\ContactValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1030_ContactFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_contact';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var ContactValidator
     */
    private $validator;

    /**
     * @var \App\Entity\Civilite[]
     */
    private $civilites;

    /**
     * @var \App\Entity\Fonction[]
     */
    private $fonctions;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        ContactValidator $validator,
        CiviliteRepository $civiliteRepository,
        FonctionRepository $fonctionRepository,
        EntityManagerInterface $entityManagerI
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->civilites = $civiliteRepository->findAll();
        $this->fonctions = $fonctionRepository->findAll();
        $this->entityManagerInterface = $entityManagerI;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < count($data); ++$i) {
            $instance = $this->initialise(new Contact(), $data[$i]);

            $this->checkAndPersist($instance);

        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Contact $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Contact::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : ' . $this->validator->getErrors($instance));
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

    private function initialise(Contact $instance, $data): Contact
    {
        /** @var Civilite $civilite */
        $civilite = $this->getInstanceByName($data['civilite'], $this->civilites);

        /** @var Fonction $fonction */
        $fonction = $this->getInstanceByName($data['fonction'], $this->fonctions);

        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setEnable($data['afficher'])
            ->setContent($data['description'])
            ->setPhone1($data['telephone1'])
            ->setPhone2($data['telephone2'])
            ->setMail1($this->check_mail( $data['mail1']))
            ->setMail2($this->check_mail($data['mail2']));

        if (!empty($civilite)) {
            $instance->setCivilite($civilite);
        }

        if (!empty($fonction)) {
            $instance->setFonction($fonction);
        }

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1030'];
    }

    private function check_mail(?string $mail)
    {
        if (empty($mail)) {
            return null;
        }
        $mailModif=trim($mail);
        $translit = array('Á'=>'A','À'=>'A','Â'=>'A','Ä'=>'A','Ã'=>'A','Å'=>'A','Ç'=>'C','É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E','Í'=>'I','Ï'=>'I','Î'=>'I','Ì'=>'I','Ñ'=>'N','Ó'=>'O','Ò'=>'O','Ô'=>'O','Ö'=>'O','Õ'=>'O','Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','á'=>'a','à'=>'a','â'=>'a','ä'=>'a','ã'=>'a','å'=>'a','ç'=>'c','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ó'=>'o','ò'=>'o','ô'=>'o','ö'=>'o','õ'=>'o','ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ý'=>'y','ÿ'=>'y');
        return  strtr($mailModif, $translit);
    }
}
