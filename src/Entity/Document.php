<?php
/**
* Сущность документа
*docTemplate->doctemplate.id
*client->client.id
* id<=works.id
*
*/
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Price;

/**
* @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
* @ORM\Table(name="documents")
*/
class Document
{
	use Price;
	/**
	* @var int
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id;
	/**
	* @ORM\Column(type="string")
	*/
	private $number;
	/**
	* @ORM\Column(type="date",nullable=true)
	*/
	private $dateOpen;
	/**
	* @ORM\Column(type="date",nullable=true)
	*/
	private $dateClosed;
	/**
	* @ORM\Column(type="datetime",nullable=true)
	*/
	private $datePayed;

	/**
	* @ORM\ManyToOne(targetEntity="DocTemplate", inversedBy="documents")
	* @ORM\JoinColumn(name="doc_template_id", referencedColumnName="id")
	*/
	private $docTemplate;

	/**
	* @var Works[] | ArrayCollection
	* @ORM\OneToMany(targetEntity="Work", mappedBy="document",cascade={"persist"})
	*/
	private $works;

	/**
	* @var Act[] | ArrayCollection
	* @ORM\ManyToOne(targetEntity="Client", inversedBy="documents")
	*/
	private $client;


	public function __construct()
	{
		$this->works=new ArrayCollection();

	}
	public function getId()
	{
		return $this->id;
	}

	public function getNumber()
	{
		return $this->number;
	}

	public function setNumber($value)
	{
		$this->number=$value;
	}

	public function getDateOpen()
	{
		return $this->dateOpen;
	}

	public function setDateOpen($value)
	{
		return $this->dateOpen=$value;
	}

	public function getDateClosed()
	{
		return $this->dateClosed;
	}

	public function setDateClosed($value)
	{
		$this->dateClosed=$value;
	}

	public function getDatePayed()
	{
		return $this->datePayed;
	}

	public function setDocTemplate($temp){
		$this->docTemplate=$temp;
	}

	public function addWork(Work $work)
	{
		if(!$this->works->contains($work))
		{
			$this->works->add($work);
			$work->setDocument($this);
		}
		return $this;
	}

	public function removeWork($null){}

	public function getWorks()
	{
		return $this->works;
	}
	public function setDatePayed($value)
	{
		$this->datePayed=$value;
		foreach($this->works as $key=>$val)
		{
			$this->works[$key]->setDatePayed($value);
		}
	}


	public function getClient()
	{
		return $this->client;
	}

	public function setClient($value)
	{
		$this->client=$value;
	}

	public function isPayed()
	{
		if(!empty($this->datePayed))
		{
			return true;
		}
		return false;
	}

	public function getPrice()
	{
		$price=0;
		foreach ($this->works as $key => $value) {
			$price+=$this->works[$key]->getPrice()*$this->works[$key]->getCount();
		}
		return $price;
	}




	public function getDocTemplate()
	{
		return $this->docTemplate;
	}
}
