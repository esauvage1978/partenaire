<?php

namespace App\Dto;

use App\Entity\Civilite;

class PartenaireDto implements DtoInterface
{
    const FALSE='false';
    const TRUE='true';

    /**
     * @var ?string
     */
    private $wordSearch;


    /**
     * @var ?String
     */
    private $page;
    /**
     * @var ?String
     */
    private $enable;
    /**
     * @var ?String
     */
    private $circonscription;

    /**
     * @return mixed
     */
    public function getCirconscription()
    {
        return $this->circonscription;
    }

    /**
     * @param mixed $circonscription
     * @return PartenaireDto
     */
    public function setCirconscription($circonscription)
    {
        $this->circonscription = $circonscription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return PartenaireDto
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    /**
     * @var ?City
     */
    private $city;

    /**
     * @return mixed
     */
    public function getWordSearch()
    {
        return $this->wordSearch;
    }

    /**
     * @param mixed $wordSearch
     * @return PartenaireDto
     */
    public function setWordSearch($wordSearch)
    {
        $this->wordSearch = $wordSearch;
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
     * @return PartenaireDto
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
     * @return PartenaireDto
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
        return $this;
    }
}
