<?php

namespace App\Dto;

use App\Entity\Civilite;

class ContactDto implements DtoInterface
{
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
     * @var ?String
     */
    private $actif;
    /**
     * @var ?String
     */
    private $page;
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
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     * @return ContactDto
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
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
}
