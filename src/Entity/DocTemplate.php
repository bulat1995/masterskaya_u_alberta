<?php
/**
* Сущность шаблона
* id<-document.doctemplate
* id<=catalog.id
*/
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
* @ORM\Entity()
* @ORM\Table(name="DocTemplate")
*/
class DocTemplate
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id;
	/**
	* @ORM\Column(type="string", length=200)
	*/
	private $name;

	/**
	* @var Catalog[] | ArrayCollection
	* @ORM\ManyToMany(targetEntity="Catalog", mappedBy="docTemplate")
	*/
	private $catalogs;

	/**
	* @var Catalog[] | ArrayCollection
	* @ORM\OneToMany(targetEntity="Document", mappedBy="docTemplate")
	*/
	private $documents;

	/**
	* @ORM\Column(type="string")
	*/
	private $document;
	/**
	* @ORM\Column(type="string")
	*/
	private $subDoc;

	/**
	* @ORM\Column(type="boolean")
	*/
	private $active;

	public function __construct()
	{
		$this->documents=new ArrayCollection();
		$this->catalogs=new ArrayCollection();
	}
	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($value)
	{
		$this->name=$value;
	}

	public function getDocument()
	{
		return $this->document;
	}

	public function setDocument($value)
	{
		if(!empty($value))
		{
			$this->document=$value;
		}
		return $this;
	}

	public function getSubDoc()
	{
		return $this->subDoc;
	}

	public function setSubDoc($value)
	{
		if(!empty($value))
		{
			$this->subDoc=$value;
		}
		return $this;
	}

	public function isActive()
	{
		return $this->active;
	}

	public function setActive($value)
	{
		$this->active=$value;
	}

	public function addDocument($doc)
	{
		if(!$this->documents->contains($doc))
		{
			$this->documents->add($doc);
		}
	}

	public function getDocuments()
	{
		return $this->documents;
	}

	public function getCatalogs()
	{
		return $this->catalogs;
	}
}
