<?php

namespace App\Manager;

use App\Dto\PartenaireDto;
use App\Entity\City;
use App\Helper\ToolCollecion;
use App\Repository\PartenaireDtoRepository;
use App\Repository\CityRepository;
use App\Validator\CityValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CityManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var CityValidator
     */
    private $validator;

    /**
     * @var PartenaireDtoRepository
     */
    private $partenaireRepository;


    public function __construct(
        EntityManagerInterface $manager,
        CityValidator $validator,
        PartenaireDtoRepository $partenaireRepository
    )
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->partenaireRepository = $partenaireRepository;
    }

    public function save(City $city): bool
    {
        $this->initialise($city);

        if (!$this->validator->isValid($city)) {
            return false;
        }

        $this->manager->persist($city);
        $this->manager->flush();

        return true;
    }

    public function initialise(City $city)
    {

        if (!empty($city->getId())) {
            $dto = new PartenaireDto();
            $dto->setCity($city);
            $this->setRelation(
                $city,
                $this->partenaireRepository->findAllForDto($dto),
                $city->getPartenaires()->toArray()
            );
        }

        return true;
    }

    public function setRelation(City $city, $entitysOld, $entitysNew)
    {
        $em = new ToolCollecion($entitysOld, $entitysNew);

        foreach ($em->getDeleteDiff() as $entity) {
            $entity->setAddCity(null);
        }

        foreach ($em->getInsertDiff() as $entity) {
            $entity->setAddCity($city);
        }
    }


    public function getErrors(City $entity)
    {
        return $this->validator->getErrors($entity);
    }

    public function remove(City $entity)
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

}
