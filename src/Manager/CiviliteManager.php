<?php

namespace App\Manager;

use App\Validator\CiviliteValidator;
use Doctrine\ORM\EntityManagerInterface;

class CiviliteManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, CiviliteValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
