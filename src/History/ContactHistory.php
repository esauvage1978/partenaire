<?php


namespace App\History;


use App\Entity\Contact;
use App\Manager\HistoryManager;
use Symfony\Component\Security\Core\Security;

class ContactHistory extends HistoryAbstract
{
    public function __construct(
        HistoryManager $manager,
        Security $securityContext
    ) {
        parent::__construct($manager,$securityContext);
    }

    public function compare(Contact $contactOld, Contact $contactNew)
    {
        $this->history->setContact($contactNew);
        $diffPresent=false;

        $this->compareFieldOneToOne('Civilité','Name',$contactOld->getCivilite(),$contactNew->getCivilite()) &&$diffPresent=true;
        $this->compareField('Nom',$contactOld->getName(),$contactNew->getName()) &&$diffPresent=true;
        $this->compareFieldOneToOne('Fonction','Name',$contactOld->getFonction(),$contactNew->getFonction()) &&$diffPresent=true;
        $this->compareFieldBool('Afficher',$contactOld->getEnable(),$contactNew->getEnable()) &&$diffPresent=true;
        $this->compareField('Mail principal',$contactOld->getMail1(),$contactNew->getMail1()) &&$diffPresent=true;
        $this->compareField('Mail secondaire',$contactOld->getMail2(),$contactNew->getMail2()) &&$diffPresent=true;
        $this->compareField('Téléphone principal',$contactOld->getPhone1(),$contactNew->getPhone1()) &&$diffPresent=true;
        $this->compareField('Téléphone secondaire',$contactOld->getPhone2(),$contactNew->getPhone2()) &&$diffPresent=true;
        $this->compareField('Description',$contactOld->getContent(),$contactNew->getContent()) &&$diffPresent=true;

        if($diffPresent) {
            $this->save();
        }
    }
}