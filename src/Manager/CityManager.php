<?php

namespace App\Manager;

use App\Validator\CityValidator;
use Doctrine\ORM\EntityManagerInterface;

class CityManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, CityValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
