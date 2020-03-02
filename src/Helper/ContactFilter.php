<?php

namespace App\Helper;

use App\Dto\ContactDto;
use App\Entity\User;
use App\Repository\ContactDtoRepository;
use App\Repository\ContactRepository;
use Symfony\Component\Security\Core\Security;

class ContactFilter
{
    /**
     * @var ContactDtoRepository
     */
    private $repository;

    /**
     * @var ContactDto
     */
    private $contactDto;

    private $security;

    public function __construct(
        ContactDtoRepository $repository,
        ContactDto $contactDto,
        Security $security
    ) {
        $this->repository = $repository;
        $this->contactDto = $contactDto;
        $this->security = $security;
    }

    public function getData(?string $filter): array
    {
        $resultRepo = null;
        $complement = '';

        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        switch ($filter) {
            case 'contact':
                $this->contactDto
                    ->setEnable(ContactDto::TRUE);

                $resultRepo = $this->repository->countForDto($this->contactDto);
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
