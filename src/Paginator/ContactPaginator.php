<?php

namespace App\Paginator;

use App\Dto\ContactDto;
use App\Repository\ContactDtoRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContactPaginator extends PaginatorAbstract implements PaginatorInterface
{

    const GRID = 'contact.grid.limitShow';
    const VIGNETTE = 'contact.vignette.limitShow';

    public function __construct(
        ParameterBagInterface $bag,
        ContactDtoRepository $contactDtoRepository,
        ContactDto $dto,
        string $limitShowParam,
        ?string $page
    )
    {
        parent::__construct($bag, $contactDtoRepository, $dto, $limitShowParam, $page);
    }

}
