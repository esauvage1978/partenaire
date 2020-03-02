<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact implements EntityInterface
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fonction", inversedBy="contacts",fetch="EAGER")
     */
    private $fonction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Civilite", inversedBy="contacts")
     */
    private $civilite;

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
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="contacts")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="History", mappedBy="contact")
     */
    private $histories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partenaire", mappedBy="referent")
     */
    private $referentPartenaires;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Partenaire", mappedBy="contacts")
     */
    private $partenaires;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->referentPartenaires = new ArrayCollection();
        $this->partenaires = new ArrayCollection();
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

    public function getFullname(): ?string
    {
        $name='';
        if(!empty($this->getCivilite())) {
            $name=$this->getCivilite()->getName().' ';
        }
        $name=$name.$this->name;
        if(!empty($this->getFonction())) {
            $name=$name. ' ['.$this->getFonction()->getName().']';
        }
        return  $name;
    }


    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getFonction(): ?Fonction
    {
        return $this->fonction;
    }

    public function setFonction(?Fonction $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getCivilite(): ?Civilite
    {
        return $this->civilite;
    }

    public function setCivilite(?Civilite $civilite): self
    {
        $this->civilite = $civilite;

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

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

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
            $history->setContact($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getContact() === $this) {
                $history->setContact(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Partenaire[]
     */
    public function getReferentPartenaires(): Collection
    {
        return $this->referentPartenaires;
    }

    public function addReferentPartenaire(Partenaire $referentPartenaire): self
    {
        if (!$this->referentPartenaires->contains($referentPartenaire)) {
            $this->referentPartenaires[] = $referentPartenaire;
            $referentPartenaire->setReferent($this);
        }

        return $this;
    }

    public function removeReferentPartenaire(Partenaire $referentPartenaire): self
    {
        if ($this->referentPartenaires->contains($referentPartenaire)) {
            $this->referentPartenaires->removeElement($referentPartenaire);
            // set the owning side to null (unless already changed)
            if ($referentPartenaire->getReferent() === $this) {
                $referentPartenaire->setReferent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Partenaire[]
     */
    public function getPartenaires(): Collection
    {
        return $this->partenaires;
    }

    public function addPartenaire(Partenaire $partenaire): self
    {
        if (!$this->partenaires->contains($partenaire)) {
            $this->partenaires[] = $partenaire;
            $partenaire->addContact($this);
        }

        return $this;
    }

    public function removePartenaire(Partenaire $partenaire): self
    {
        if ($this->partenaires->contains($partenaire)) {
            $this->partenaires->removeElement($partenaire);
            $partenaire->removeContact($this);
        }

        return $this;
    }


}
