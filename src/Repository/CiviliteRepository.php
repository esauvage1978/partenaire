<?php

namespace App\Repository;


use App\Entity\Civilite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Civilite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Civilite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Civilite[]    findAll()
 * @method Civilite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiviliteRepository extends ServiceEntityRepository
{
    const ALIAS='c_c';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Civilite::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}