<?php

namespace App\Paginator;

use App\Dto\PartenaireDto;
use App\Repository\PartenaireDtoRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PartenairePaginator extends PaginatorAbstract implements PaginatorInterface
{

    const GRID = 'partenaire.grid.limitShow';
    const VIGNETTE = 'partenaire.vignette.limitShow';

    public function __construct(
        ParameterBagInterface $bag,
        PartenaireDtoRepository $partenaireDtoRepository,
        PartenaireDto $dto,
        string $limitShowParam,
        ?string $page
    ) {
        parent::__construct($bag, $partenaireDtoRepository, $dto, $limitShowParam, $page);
    }

}
