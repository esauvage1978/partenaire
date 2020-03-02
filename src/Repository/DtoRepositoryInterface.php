<?php


namespace App\Repository;


use App\Dto\DtoInterface;

interface DtoRepositoryInterface
{
    public function findAllForDtoPaginator(DtoInterface $dto,$page=null, $limit=null);

    public function findAllForDto(DtoInterface $dto);

    public function countForDto(DtoInterface $dto);
}