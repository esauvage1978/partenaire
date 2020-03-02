<?php

namespace App\Manager;

use App\Dto\ContactDto;
use App\Entity\Role;
use App\Helper\ToolCollecion;
use App\Repository\ContactDtoRepository;
use App\Repository\RoleRepository;
use App\Validator\RoleValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class RoleManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var RoleValidator
     */
    private $validator;

    /**
     * @var ContactDtoRepository
     */
    private $contactRepository;


    public function __construct(
        EntityManagerInterface $manager,
        RoleValidator $validator,
        ContactDtoRepository $contactRepository
    )
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->contactRepository = $contactRepository;
    }

    public function save(Role $role): bool
    {
        $this->initialise($role);

        if (!$this->validator->isValid($role)) {
            return false;
        }

        $this->manager->persist($role);
        $this->manager->flush();

        return true;
    }

    public function initialise(Role $role)
    {

        if (!empty($role->getId())) {
            $dto = new ContactDto();
            $dto->setRole($role);
            $this->setRelation(
                $role,
                $this->contactRepository->findAllForDto($dto),
                $role->getContacts()->toArray()
            );
        }

        return true;
    }

    public function setRelation(Role $role, $entitysOld, $entitysNew)
    {
        $em = new ToolCollecion($entitysOld, $entitysNew);

        foreach ($em->getDeleteDiff() as $entity) {
            $entity->removeRole($role);
        }

        foreach ($em->getInsertDiff() as $entity) {
            $entity->addRole($role);
        }
    }


    public function getErrors(Role $entity)
    {
        return $this->validator->getErrors($entity);
    }

    public function remove(Role $entity)
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

}
