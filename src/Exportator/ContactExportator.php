<?php


namespace App\Exportator;


use App\Dto\ContactDto;
use App\Repository\ContactDtoRepository;

class ContactExportator extends ExportatorAbstract implements ExportatorInterface
{
    public function __construct(
        ContactDtoRepository $contactDtoRepository,
        ContactDto $dto,
        string $route,
        string $title,
        string $complement
    ) {
        parent::__construct($contactDtoRepository, $dto, $route,$title,$complement);
    }
}