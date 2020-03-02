<?php

namespace App\Manager;

use App\Validator\HistoryValidator;
use Doctrine\ORM\EntityManagerInterface;

class HistoryManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, HistoryValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
