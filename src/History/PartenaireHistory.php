<?php


namespace App\History;


use App\Entity\Partenaire;
use App\Manager\HistoryManager;
use Symfony\Component\Security\Core\Security;

class PartenaireHistory extends HistoryAbstract
{
    public function __construct(
        HistoryManager $manager,
        Security $securityContext
    ) {
        parent::__construct($manager,$securityContext);
    }

    public function compare(Partenaire $partenaireOld, Partenaire $partenaireNew)
    {
        $this->history->setPartenaire($partenaireNew);
        $diffPresent=false;

        $this->compareField('Nom',$partenaireOld->getName(),$partenaireNew->getName()) &&$diffPresent=true;
        $this->compareFieldBool('Afficher',$partenaireOld->getEnable(),$partenaireNew->getEnable()) &&$diffPresent=true;
        $this->compareField('Mail principal',$partenaireOld->getMail1(),$partenaireNew->getMail1()) &&$diffPresent=true;
        $this->compareField('Mail secondaire',$partenaireOld->getMail2(),$partenaireNew->getMail2()) &&$diffPresent=true;
        $this->compareField('Téléphone principal',$partenaireOld->getPhone1(),$partenaireNew->getPhone1()) &&$diffPresent=true;
        $this->compareField('Téléphone secondaire',$partenaireOld->getPhone2(),$partenaireNew->getPhone2()) &&$diffPresent=true;
        $this->compareField('Rue',$partenaireOld->getAddComp1(),$partenaireNew->getAddComp1()) &&$diffPresent=true;
        $this->compareField('Compléménent d\'adresse',$partenaireOld->getAddComp2(),$partenaireNew->getAddComp2()) &&$diffPresent=true;
        $this->compareField('Code postal',$partenaireOld->getAddCp(),$partenaireNew->getAddCp()) &&$diffPresent=true;
        $this->compareFieldOneToOne('Ville','name',$partenaireOld->getAddCity(),$partenaireNew->getAddCity()) &&$diffPresent=true;
        $this->compareFieldOneToOne('Catégorie','name',$partenaireOld->getCategory(),$partenaireNew->getCategory()) &&$diffPresent=true;
        $this->compareField('Description',$partenaireOld->getContent(),$partenaireNew->getContent()) &&$diffPresent=true;

        if($diffPresent) {
            $this->save();
        }
    }
}