<?php


namespace App\Exportator;


use App\Dto\DtoInterface;
use App\Repository\ContactDtoRepository;
use App\Repository\DtoRepositoryInterface;

class ExportatorAbstract
{

    /**
     * @var ContactDtoRepository
     */
    protected $dtoRepository;

    /**
     * @var DtoInterface
     */
    protected $dto;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $complement;

    public function __construct(
        DtoRepositoryInterface $dtoRepository,
        DtoInterface $dto,
        string $route,
        string $title,
        string $complement
    ) {
        $this->dtoRepository = $dtoRepository;
        $this->dto = $dto;
        $this->route = $route;
        $this->title = $title;
        $this->complement = $complement;
    }

    public function getDatas()
    {
        return $this->dtoRepository->findAllForDto(
            $this->dto);
    }

    public function getParams()
    {
        return [
            'route' => $this->route,
            'title' => $this->title,
            'complement'=>$this->complement
        ];
    }
}