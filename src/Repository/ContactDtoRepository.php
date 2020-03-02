<?php


namespace App\Repository;


use App\Dto\ContactDto;
use App\Dto\DtoInterface;
use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ContactDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    const SELECT_ALL='select_all';
    const SELECT_PAGINATOR='select';

    const ALIAS = 'd';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function countForDto(DtoInterface $dto)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->initialise_selectCount();

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()->getSingleScalarResult();
    }

    public function findAllForDtoPaginator(DtoInterface $dto, $page = null, $limit = null,$select=self::SELECT_ALL)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        switch($select) {
            case self::SELECT_PAGINATOR:
                $this->initialise_select();
                break;
            default:
                $this->initialise_selectAll();
                break;

        }

        $this->initialise_where();

        $this->initialise_orderBy();

        if (empty($page)) {
            $this->builder
                ->getQuery()
                ->getResult();
        } else {
            $this->builder
                ->setFirstResult(($page - 1) * $limit)
                ->setMaxResults($limit);
        }

        return new Paginator($this->builder);
    }

    public function findAllForDto(DtoInterface $dto,$select=self::SELECT_ALL)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        switch($select) {
            case self::SELECT_PAGINATOR:
                $this->initialise_select();
                break;
            default:
                $this->initialise_selectAll();
                break;

        }

        $this->initialise_where();

        $this->initialise_orderBy();

       return $this->builder
            ->getQuery()
            ->getResult();

    }

    private function initialise_select()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CiviliteRepository::ALIAS,
                FonctionRepository::ALIAS
            )

            ->leftJoin(self::ALIAS . '.civilite', CiviliteRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.fonction', FonctionRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.roles', RoleRepository::ALIAS);
    }
    private function initialise_selectAll()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CiviliteRepository::ALIAS,
                FonctionRepository::ALIAS
            )
            ->leftJoin(self::ALIAS . '.civilite', CiviliteRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.fonction', FonctionRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.roles', RoleRepository::ALIAS);

        if (empty($this->dto->getRole())) {
            $this->builder->addSelect(RoleRepository::ALIAS);
        }
    }
    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(' . self::ALIAS . '.id)')
            ->leftJoin(self::ALIAS . '.civilite', CiviliteRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.fonction', FonctionRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.roles', RoleRepository::ALIAS);
    }

    private function initialise_where()
    {
        $this->params = [];
        $dto = $this->dto;

        $this->builder
            ->where(self::ALIAS . '.id>0');


        $this->initialise_where_civilite();

        $this->initialise_where_fonction();

        $this->initialise_where_enable();

        $this->initialise_where_role();

        $this->initialise_where_search();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }

    }

    private function initialise_where_civilite()
    {
        if (!empty($this->dto->getCivilite())) {
            $this->builder->andwhere(CiviliteRepository::ALIAS . '.id = :civiliteid');
            $this->addParams('civiliteid', $this->dto->getCivilite()->getId());
        }
    }

    private function initialise_where_fonction()
    {
        if (!empty($this->dto->getFonction())) {
            $this->builder->andwhere(FonctionRepository::ALIAS . '.id = :fonctionid');
            $this->addParams('fonctionid', $this->dto->getFonction()->getId());
        }
    }

    private function initialise_where_enable()
    {
        if (!empty($this->dto->getEnable())) {
            if($this->dto->getEnable()== ContactDto::TRUE) {
                $this->builder->andwhere(self::ALIAS . '.enable= true');
            } elseif($this->dto->getEnable()== ContactDto::FALSE) {
                $this->builder->andwhere(self::ALIAS . '.enable= false');
            }
        }
    }

    private function initialise_where_role()
    {
        if (!empty($this->dto->getRole())) {
            $this->builder->andwhere(RoleRepository::ALIAS . '.id = :roleid');
            $this->addParams('roleid', $this->dto->getRole()->getId());
        }
    }

    private function initialise_where_search()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        if (!empty($dto->getWordSearch())) {
            $builder
                ->andwhere(
                    self::ALIAS . '.content like :search' .
                    ' OR ' . self::ALIAS . '.name like :search' .
                    ' OR ' . self::ALIAS . '.phone1 like :search' .
                    ' OR ' . self::ALIAS . '.phone2 like :search' .
                    ' OR ' . self::ALIAS . '.mail1 like :search' .
                    ' OR ' . self::ALIAS . '.mail2 like :search' .
                    ' OR ' . CiviliteRepository::ALIAS . '.name like :search' .
                    ' OR ' . FonctionRepository::ALIAS . '.name like :search');

            $this->addParams('search', '%' . $dto->getWordSearch() . '%');
        }

    }

    private function initialise_orderBy()
    {
        $this->builder
            ->orderBy(self::ALIAS . '.name', 'ASC');
    }


}