<?php

namespace App\Manager;

use App\Validator\FonctionValidator;
use Doctrine\ORM\EntityManagerInterface;

class FonctionManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, FonctionValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
