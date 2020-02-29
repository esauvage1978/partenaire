<?php


namespace App\EntityListener;


use App\Entity\Contact;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ContactEntityListener
{

    /**
     * @param Contact $contact
     * @param LifecycleEventArgs $event
     */
    public function prePersist(Contact $contact, LifecycleEventArgs $event)
    {
        $this->checkEntity($contact);
    }

    /**
     * @param Contact $contact
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(Contact $contact, LifecycleEventArgs $event)
    {
        $this->checkEntity($contact);
    }

    private function checkEntity(Contact $contact)
    {
        $contact->setPhone1($this->checkPhone($contact->getPhone1()));
        $contact->setPhone2($this->checkPhone($contact->getPhone2()));
    }

    private function checkPhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        return  preg_replace('`[^0-9]`', ' ', $phone);
    }
}