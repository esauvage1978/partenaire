<?php

namespace App\Manager;

use App\Dto\ContactDto;
use App\Entity\Partenaire;
use App\Entity\Role;
use App\Helper\ToolCollecion;
use App\Repository\ContactDtoRepository;
use App\Repository\RoleRepository;
use App\Validator\PartenaireValidator;
use App\Validator\RoleValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class PartenaireManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var PartenaireValidator
     */
    private $validator;




    public function __construct(
        EntityManagerInterface $manager,
        PartenaireValidator $validator
    ) {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function save(Partenaire $partenaire): bool
    {
        $this->initialise($partenaire);

        if (!$this->validator->isValid($partenaire)) {
            return false;
        }

        $this->manager->persist($partenaire);
        $this->manager->flush();

        return true;
    }

    public function initialise(Partenaire $partenaire)
    {


        return true;
    }



    public function getErrors(Partenaire $partenaire)
    {
        return $this->validator->getErrors($partenaire);
    }

    public function remove(Partenaire $partenaire)
    {
        $this->manager->remove($partenaire);
        $this->manager->flush();
    }

}
