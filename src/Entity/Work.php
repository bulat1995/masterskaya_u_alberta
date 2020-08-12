<?php
/**
* Сущность Проведенной работы
* Связи сущностей
* client->clients.id
* act=>acts.id
* catalog=>catalog.id
*/
namespace App\Entity;

use App\Entity\Act;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="works")
*/
class Work
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id;

	/**
	* @ORM\ManyToOne(targetEntity="Catalog", inversedBy="works")
	* @ORM\JoinColumn(name="catalog_id", referencedColumnName="id")
	*/
	private $catalog;

	/**
	* @ORM\Column(type="integer")
	*/
	private $count;

	/**
	* @ORM\Column(type="float")
	*/
	private $price;

	/**
	* @ORM\Column(type="datetime")
	*/
	private $dateCreate;

	/**
	* @ORM\Column(type="datetime", nullable=true)
	*/
	private $datePayed;

	/**
	* @ORM\ManyToOne(targetEntity="Document", inversedBy="works")
	* @ORM\JoinColumn(name="document_id", referencedColumnName="id", nullable=true)
	*/
	private $document;

	/**
	* @ORM\ManyToOne(targetEntity="Act", inversedBy="works")
	* @ORM\JoinColumn(name="act_id", referencedColumnName="id", nullable=true)
	*/
	private $act;


	/**
	* @ORM\ManyToOne(targetEntity="Client", inversedBy="works")
	* @ORM\JoinColumn(name="client_id", referencedColumnName="id")
	*/
	private $client;

	public function __construct()
	{
		$this->dateCreate=new \DateTime();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getCatalog()
	{
		return $this->catalog;
	}

	public function setCatalog(Catalog $catalog)
	{
		$this->catalog=$catalog;
	}

	public function getCount()
	{
		return $this->count;
	}

	public function setCount($value)
	{
		$this->count=$value;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function setPrice($value)
	{
		$this->price=$value;
	}

	public function getFullPrice()
	{
		return $this->count*$this->price;
	}

	public function setClient($client)
	{
		$this->client=$client;
	}

	public function getClient()
	{
		return $this->client;
	}

	public function getDateCreate()
	{
		return $this->dateCreate;
	}

	public function setAct($value)
	{
		$this->act=$value;
	}

	public function getDocument()
	{
		return $this->document;
	}
	public function setDocument($doc)
	{
		$this->document=$doc;
	}

	public function isPayed()
	{
		return !is_null($this->datePayed);

	}

	public function getDatePayed()
	{
		return $this->datePayed;
	}

	public function setDatePayed($value)
	{
		$this->datePayed=$value;
	}

	public function getAct()
	{
		return $this->act;
	}


}
