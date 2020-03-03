<?php

namespace App\Manager;

use App\Dto\PartenaireDto;
use App\Entity\Category;
use App\Helper\ToolCollecion;
use App\Repository\PartenaireDtoRepository;
use App\Repository\CategoryRepository;
use App\Validator\CategoryValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var CategoryValidator
     */
    private $validator;

    /**
     * @var PartenaireDtoRepository
     */
    private $partenaireRepository;


    public function __construct(
        EntityManagerInterface $manager,
        CategoryValidator $validator,
        PartenaireDtoRepository $partenaireRepository
    )
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->partenaireRepository = $partenaireRepository;
    }

    public function save(Category $category): bool
    {
        $this->initialise($category);

        if (!$this->validator->isValid($category)) {
            return false;
        }

        $this->manager->persist($category);
        $this->manager->flush();

        return true;
    }

    public function initialise(Category $category)
    {

        if (!empty($category->getId())) {
            $dto = new PartenaireDto();
            $dto->setCategory($category);
            $this->setRelation(
                $category,
                $this->partenaireRepository->findAllForDto($dto),
                $category->getPartenaires()->toArray()
            );
        }

        return true;
    }

    public function setRelation(Category $category, $entitysOld, $entitysNew)
    {
        $em = new ToolCollecion($entitysOld, $entitysNew);

        foreach ($em->getDeleteDiff() as $entity) {
            $entity->setCategory(null);
        }

        foreach ($em->getInsertDiff() as $entity) {
            $entity->setCategory($category);
        }
    }


    public function getErrors(Category $entity)
    {
        return $this->validator->getErrors($entity);
    }

    public function remove(Category $entity)
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

}
