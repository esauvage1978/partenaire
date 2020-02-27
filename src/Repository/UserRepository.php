<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    const ALIAS = 'u';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS)
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    /**
 * @return User[] Returns an array of User objects
 */
    public function findAllForContactAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS, AvatarRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.Avatar',AvatarRepository::ALIAS)
            ->Where(self::ALIAS.'.roles like :val1')
            ->setParameter('val1', '%ROLE_ADMIN%')
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    /**
     * @return User[] Returns an array of User objects
     */
    public function findAllForContactGestionnaire()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS, AvatarRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.Avatar',AvatarRepository::ALIAS)
            ->Where(self::ALIAS.'.roles like :val1')
            ->AndWhere(self::ALIAS.'.roles not like :val2')
            ->setParameter('val1', '%"ROLE_GESTIONNAIRE"%')
            ->setParameter('val2', '%ROLE_ADMIN%')
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

}
