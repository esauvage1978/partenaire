<?php


namespace App\Repository;


use App\Dto\DtoInterface;

interface DtoRepositoryInterface
{
    public function findAllForDto(DtoInterface $dto,$page=null, $limit=null);

    public function countForDto(DtoInterface $dto);
}