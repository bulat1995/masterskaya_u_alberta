<?php
/**
* Сущность Настроек
*
*/
namespace App\Entity;

use App\Entity\UserInfo;
use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity()
* @ORM\Table(name="setting")
*/
class Setting
{
	use UserInfo;
	/**
	* @var int
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $companyName;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $positionName;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $positionNameShort;
	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $bossName;
	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $inFace;
	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $docName;
	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $inn;
	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $kpp;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $qrCode;
	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $qrCodeWidth;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $rss;
	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $address;
	/**
	* @ORM\Column(type="string", length=800)
	*/
	private $bankInfo;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $bik;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $ks;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $contacts;

	/**
	* @ORM\Column(type="string", length=200)
	*/
	private $sitePhone;

	/**
	* @ORM\Column(type="string", length=200)
	*/
	private $siteEmail;

	/**
	* @ORM\Column(type="string", length=200)
	*/
	private $schedule;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $siteAddress;

	/**
	* @ORM\Column(type="string", length=800)
	*/
	private $siteKeywords;
	
	/**
	* @ORM\Column(type="integer")
	*/
	private $clientCount;
	/**
	* @ORM\Column(type="integer")
	*/
	private $startWorkYear;

	/**
	* @ORM\Column(type="integer")
	*/
	private $countWork;

	public function getSitePhone()
	{
		return $this->sitePhone;
	}

	public function setSitePhone($value)
	{
		$this->sitePhone=$value;
	}

	public function getSiteEmail()
	{
		return $this->siteEmail;
	}

	public function setSiteEmail($value)
	{
		$this->siteEmail=$value;
	}

	public function getSchedule()
	{
		return $this->schedule;
	}
	public function setSchedule($value)
	{
		$this->schedule=$value;
	}

	public function getSiteAddress()
	{
		return $this->siteAddress;
	}

	public function setSiteAddress($value)
	{
		$this->siteAddress=$value;
	}

	public function getSiteKeywords()
	{
		return $this->siteKeywords;
	}

	public function setSiteKeywords($value)
	{
		$this->siteKeywords=$value;
	}


	public function getPositionNameShort()
	{
		return $this->positionNameShort;
	}

	public function setPositionNameShort($value)
	{
		$this->positionNameShort=$value;
	}

	public function getQrCode()
	{
		return $this->qrCode;
	}

	public function setQrCode($value)
	{
		$this->qrCode=$value;
	}

	public function getQrCodeWidth()
	{
		return $this->qrCodeWidth;
	}

	public function setQrCodeWidth($value)
	{
		$this->qrCodeWidth=$value;
	}
	public function setId($val)
	{
		$this->id=$val;
	}

	public function getClientCount()
	{
		return $this->clientCount;
	}
	public function setClientCount($value)
	{
		$this->clientCount=$value;
	}

	public function getStartWorkYear()
	{
		return $this->startWorkYear;
	}
	public function setStartWorkYear($value)
	{
		$this->startWorkYear=$value;
	}

}
