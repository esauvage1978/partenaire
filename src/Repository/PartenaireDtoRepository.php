<?php


namespace App\Repository;


use App\Dto\DtoInterface;
use App\Dto\PartenaireDto;
use App\Entity\Partenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PartenaireDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    const SELECT_ALL = 'select_all';
    const SELECT_PAGINATOR = 'select';

    const ALIAS = 'p';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partenaire::class);
    }

    public function countForDto(DtoInterface $dto)
    {
        /**
         * var PartenaireDto
         */
        $this->dto = $dto;

        $this->initialise_selectCount();

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()->getSingleScalarResult();
    }

    public function findAllForDtoPaginator(DtoInterface $dto, $page = null, $limit = null, $select = self::SELECT_ALL)
    {
        /**
         * var PartenaireDto
         */
        $this->dto = $dto;

        switch ($select) {
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

    public function findAllForDto(DtoInterface $dto, $select = self::SELECT_ALL)
    {
        /**
         * var PartenaireDto
         */
        $this->dto = $dto;

        switch ($select) {
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
                CityRepository::ALIAS
            )
            ->leftJoin(self::ALIAS . '.add_city', CityRepository::ALIAS);
    }

    private function initialise_selectAll()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CityRepository::ALIAS
            )
            ->leftJoin(self::ALIAS . '.add_city', CityRepository::ALIAS);
    }

    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(' . self::ALIAS . '.id)')
            ->leftJoin(self::ALIAS . '.add_city', CityRepository::ALIAS);
    }

    private function initialise_where()
    {
        $this->params = [];
        $dto = $this->dto;

        $this->builder
            ->where(self::ALIAS . '.id>0');

        $this->initialise_where_enable();

        $this->initialise_where_circonscription();

        $this->initialise_where_city();

        $this->initialise_where_search();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }

    }

    private function initialise_where_enable()
    {
        if (!empty($this->dto->getEnable())) {
            if ($this->dto->getEnable() == PartenaireDto::TRUE) {
                $this->builder->andwhere(self::ALIAS . '.enable= true');
            } elseif ($this->dto->getEnable() == PartenaireDto::FALSE) {
                $this->builder->andwhere(self::ALIAS . '.enable= false');
            }
        }
    }

    private function initialise_where_circonscription()
    {
        if (!empty($this->dto->getCirconscription())) {
            if ($this->dto->getCirconscription() == PartenaireDto::TRUE) {
                $this->builder->andwhere(self::ALIAS . '.circonscription= true');
            } elseif ($this->dto->getCirconscription() == PartenaireDto::FALSE) {
                $this->builder->andwhere(self::ALIAS . '.circonscription= false');
            }
        }
    }

    private function initialise_where_city()
    {
        if (!empty($this->dto->getCity())) {
            $this->builder->andwhere(CityRepository::ALIAS . '.id = :cityid');
            $this->addParams('cityid', $this->dto->getCity()->getId());
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
                    ' OR ' . self::ALIAS . '.add_comp1 like :search' .
                    ' OR ' . self::ALIAS . '.add_comp2 like :search' .
                    ' OR ' . self::ALIAS . '.add_cp like :search' .
                    ' OR ' . self::ALIAS . '.name like :search' .
                    ' OR ' . self::ALIAS . '.phone1 like :search' .
                    ' OR ' . self::ALIAS . '.phone2 like :search' .
                    ' OR ' . self::ALIAS . '.mail1 like :search' .
                    ' OR ' . self::ALIAS . '.mail2 like :search');

            $this->addParams('search', '%' . $dto->getWordSearch() . '%');
        }

    }

    private function initialise_orderBy()
    {
        $this->builder
            ->orderBy(self::ALIAS . '.name', 'ASC');
    }


}