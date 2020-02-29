<?php


namespace App\Paginator;


use App\Dto\ContactDto;
use App\Dto\DtoInterface;
use App\Repository\ContactDtoRepository;
use App\Repository\DtoRepositoryInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class PaginatorAbstract
{
    /**
     * @var ParameterBagInterface
     */
    protected $bag;

    /**
     * @var int
     */
    protected $nbrTotal;

    /**
     * @var int
     */
    protected $nbrLimitShow;

    /**
     * @var string
     */
    protected $page;

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
    private $limitShowParam;

    public function __construct(
        ParameterBagInterface $bag,
        DtoRepositoryInterface $dtoRepository,
        DtoInterface $dto,
        string $limitShowParam,
        ?string $page='1'
    )
    {
        $this->bag = $bag;
        $this->dtoRepository = $dtoRepository;
        $this->dto = $dto;
        $this->page=$page;
        $this->limitShowParam=$limitShowParam;

        $this->calculNbrTotal();
        $this->calculLimitShow();

        $this->checkPage();
    }

    protected function getNbrSheets()
    {
        return ceil($this->nbrTotal / $this->nbrLimitShow);
    }

    private function checkPage()
    {
        $nbrPages = $this->getNbrSheets();
        if (is_null($this->page) || $this->page < 1) {
            $this->page= 1;
        } else if ($this->page > $nbrPages) {
            $this->page = $nbrPages;
        }

    }

    public function getDatas()
    {
        return $this->dtoRepository->findAllForDto(
            $this->dto,
            $this->page,
            $this->nbrLimitShow);
    }

    public function getParams()
    {
        return ['nbrPages' => $this->getNbrSheets(),
            'currentPage' => $this->page];
    }

    private function calculNbrTotal()
    {
        $this->nbrTotal = $this->dtoRepository->countForDto($this->dto);
        empty($this->nbrTotal) && $this->nbrTotal=0;

    }

    private function calculLimitShow()
    {
        $this->nbrLimitShow = $this->bag->get($this->limitShowParam);
    }

}