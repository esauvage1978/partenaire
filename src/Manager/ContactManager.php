<?php

namespace App\Manager;

use App\Validator\ContactValidator;
use Doctrine\ORM\EntityManagerInterface;

class ContactManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, ContactValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
