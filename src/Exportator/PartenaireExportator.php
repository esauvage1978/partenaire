<?php


namespace App\Exportator;


use App\Dto\PartenaireDto;
use App\Repository\PartenaireDtoRepository;

class PartenaireExportator extends ExportatorAbstract implements ExportatorInterface
{
    public function __construct(
        PartenaireDtoRepository $partenaireDtoRepository,
        PartenaireDto $dto,
        string $route,
        string $title,
        string $complement
    ) {
        parent::__construct($partenaireDtoRepository, $dto, $route,$title,$complement);
    }
}