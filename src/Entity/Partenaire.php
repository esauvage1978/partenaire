<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 */
class Partenaire implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $add_comp1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $add_comp2;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $add_cp;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone1;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $circonscription;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\History", mappedBy="partenaire")
     */
    private $histories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="referentPartenaires")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $referent;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Contact", inversedBy="partenaires")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $contacts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="partenaires")
     */
    private $add_city;

    public function __construct()
    {
        $this->histories = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    public function getAddComp1(): ?string
    {
        return $this->add_comp1;
    }

    public function setAddComp1(?string $add_comp1): self
    {
        $this->add_comp1 = $add_comp1;

        return $this;
    }

    public function getAddComp2(): ?string
    {
        return $this->add_comp2;
    }

    public function setAddComp2(?string $add_comp2): self
    {
        $this->add_comp2 = $add_comp2;

        return $this;
    }

    public function getAddCp(): ?string
    {
        return $this->add_cp;
    }

    public function setAddCp(?string $add_cp): self
    {
        $this->add_cp = $add_cp;

        return $this;
    }

    public function getPhone1(): ?string
    {
        return $this->phone1;
    }

    public function setPhone1(?string $phone1): self
    {
        $this->phone1 = $phone1;

        return $this;
    }

    public function getPhone2(): ?string
    {
        return $this->phone2;
    }

    public function setPhone2(?string $phone2): self
    {
        $this->phone2 = $phone2;

        return $this;
    }

    public function getMail1(): ?string
    {
        return $this->mail1;
    }

    public function setMail1(?string $mail1): self
    {
        $this->mail1 = $mail1;

        return $this;
    }

    public function getMail2(): ?string
    {
        return $this->mail2;
    }

    public function setMail2(?string $mail2): self
    {
        $this->mail2 = $mail2;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCirconscription(): ?bool
    {
        return $this->circonscription;
    }

    public function setCirconscription(bool $circonscription): self
    {
        $this->circonscription = $circonscription;

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setPartenaire($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getPartenaire() === $this) {
                $history->setPartenaire(null);
            }
        }

        return $this;
    }

    public function getReferent(): ?Contact
    {
        return $this->referent;
    }

    public function setReferent(?Contact $referent): self
    {
        $this->referent = $referent;

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }

        return $this;
    }

    public function getAddCity(): ?City
    {
        return $this->add_city;
    }

    public function setAddCity(?City $add_city): self
    {
        $this->add_city = $add_city;

        return $this;
    }
}
