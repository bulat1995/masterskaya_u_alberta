<?php
/**
* Сущность услуги
* id<=doctemplate.catalog
* device<- device.id
* work=>work.catalogs
*/

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
* @ORM\Entity(repositoryClass="App\Repository\CatalogRepository")
* @ORM\Table(name="catalogs")
*/
class Catalog
{
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
	private $name;

	/**
	* @ORM\Column(type="float",nullable=true)
	*/
	private $price;

	/**
	* @ORM\ManyToOne(targetEntity="Device", inversedBy="catalogs")
	* @ORM\JoinColumn(name="device_id", referencedColumnName="id", nullable=true)
	*/
	private $device;

	/**
	* @ORM\ManyToMany(targetEntity="DocTemplate", inversedBy="catalogs")
	* @ORM\JoinColumn(name="doc_template_id", referencedColumnName="id", nullable=true)
	*/
	private $docTemplate;

	/**
	* @var CatalogsArchive[] | ArrayCollection
	* @ORM\OneToMany(targetEntity="Work", mappedBy="catalog")
	*/
	private $works;

	/**
	* @ORM\Column(type="string")
	*/
	private $countName;

	/**
	* @ORM\Column(type="boolean")
	*/
	private $public;

	public function __construct()
	{
		$this->works=new ArrayCollection();
		$this->docTemplate=new ArrayCollection();

	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName(string $name)
	{
		$this->name=$name;
	}


	public function getPrice() : ?float
	{
		return $this->price;
	}

	public function setPrice(float $price=null )
	{
		$this->price=$price;
	}

	public function getDevice(): ?Device
	{
		return $this->device;
	}

	public function setDevice(Device $device) :self
	{
		$this->device=$device;
		return $this;
	}

	public function getCountName()
	{
		return $this->countName;
	}

	public function setCountName($value)
	{
		$this->countName=$value;
	}

	public function isPublic()
	{
		return $this->public;
	}
	public function setPublic($value)
	{
		$this->public=$value;
	}

	public function getDocTemplate()
	{
		return $this->docTemplate;
	}

	public function addDocTemplate($doc)
	{
		if($this->docTemplate->contains($doc))
		{
			$this->docTemplate->add($doc);
		}
		return $this;
	}

	public function removeDocTemplate($null){}

}
