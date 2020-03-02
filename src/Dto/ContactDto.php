<?php

namespace App\Dto;

use App\Entity\Civilite;

class ContactDto implements DtoInterface
{
    const FALSE='false';
    const TRUE='true';

    /**
     * @var ?string
     */
    private $wordSearch;
    /**
     * @var ?Civilite
     */
    private $civilite;
    /**
     * @var ?Fonction
     */
    private $fonction;
    /**
     * @var ?Role
     */
    private $role;

    /**
     * @var ?String
     */
    private $page;
    /**
     * @var ?String
     */
    private $enable;


    /**
     * @return mixed
     */
    public function getWordSearch()
    {
        return $this->wordSearch;
    }

    /**
     * @param mixed $wordSearch
     * @return ContactDto
     */
    public function setWordSearch($wordSearch)
    {
        $this->wordSearch = $wordSearch;
        return $this;
    }

    /**
     * @return null | Civilite
     */
    public function getCivilite():?Civilite
    {
        return $this->civilite;
    }

    /**
     * @param mixed $civilite
     * @return ContactDto
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * @param mixed $fonction
     * @return ContactDto
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     * @return ContactDto
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     * @return ContactDto
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param mixed $enable
     * @return ContactDto
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
        return $this;
    }
}
