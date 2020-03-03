<?php

namespace App\Helper;

use App\Dto\PartenaireDto;
use App\Entity\User;
use App\Repository\PartenaireDtoRepository;
use Symfony\Component\Security\Core\Security;

class PartenaireFilter
{
    /**
     * @var PartenaireDtoRepository
     */
    private $repository;

    /**
     * @var PartenaireDto
     */
    private $dto;

    private $security;

    public function __construct(
        PartenaireDtoRepository $repository,
        PartenaireDto $dto,
        Security $security
    ) {
        $this->repository = $repository;
        $this->dto = $dto;
        $this->security = $security;
    }

    public function getData(?string $filter): array
    {
        $resultRepo = null;
        $complement = '';

        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        switch ($filter) {
            case 'partenaire':
                $this->dto
                    ->setEnable(PartenaireDto::TRUE);

                $resultRepo = $this->repository->countForDto($this->dto);
                break;

            case 'without_jalon_writer':
                break;
        }

        return [
            'actions' => $resultRepo,
            'complement' => $complement,
            'nbr' => $resultRepo,
        ];
    }
}
