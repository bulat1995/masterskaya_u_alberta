<?php
/**
* Трейт используемый в User и Client
*
*/
namespace App\Entity;

trait UserInfo
{

    public function getId()
	{
		return $this->id;
	}


    public function getDocName()
	{
		return $this->docName;
	}

	public function setDocName($value)
	{
		$this->docName=$value;
	}

    public function getContacts()
    {
        return $this->contacts;
    }

    public function setContacts($value)
    {
        $this->contacts=$value;
    }


    public function getFirstName()
	{
		$str=explode(' ',$this->getBossName());
		return ''.$str[1];
	}

	public function getSecondName()
	{
		$str=explode(' ',$this->getBossName());
		return $str[0];
	}


	public function getMiddleName()
	{
		$str=explode(' ',$this->getBossName());
		return $str[2];
	}



	public function getInn()
	{
		return $this->inn;
	}

	public function setInn($value)
	{
		$this->inn=$value;
	}

	public function getKpp()
	{
		return $this->kpp;
	}

	public function setKpp($value)
	{
		$this->kpp=$value;
	}


	public function getRss()
	{
		return $this->rss;
	}

	public function setRss($value)
	{
		$this->rss=$value;
	}

	public function getBik()
	{
		return $this->bik;
	}

	public function setBik($value)
	{
		$this->bik=$value;
	}

	public function getKs()
	{
		return $this->ks;
	}

	public function setKs($value)
	{
		$this->ks=$value;
	}


    public function  getBankInfo()
    {
        return $this->bankInfo;
    }

    public function setBankInfo($value)
    {
        $this->bankInfo=$value;
    }


    public function getBossName()
	{
		return $this->bossName;
	}

	public function setBossName($value)
	{
		$this->bossName=$value;
	}

    public function getPositionName()
    {
        return $this->positionName;
    }

    public function setPositionName($value)
    {
        $this->positionName=$value;
    }

    public function getCompanyName()
	{
		return $this->companyName;
	}

	public function setCompanyName($value)
	{
		$this->companyName=$value;
	}


    public function getInFace()
	{
		return $this->inFace;
	}

	public function setInFace($value)
	{
		$this->inFace=$value;
	}

    public function getAddress()
    {
        return $this->address;
    }
    
    public function setAddress($value)
    {
        $this->address=$value;
    }


}
