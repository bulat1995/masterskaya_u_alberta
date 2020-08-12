<?php
/**
* Сущность клиента 
* id<=document.client
* id <=act.client
* id <=work.client
*/
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\UserInfo;

use Doctrine\Common\Collections\ArrayCollection;
/**
* @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
* @ORM\Table(name="clients")
*/
class Client
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
	private $address;
	/**
	* @ORM\Column(type="string", length=200)
	*/
	private $bossName;
	/**
	* @ORM\Column(type="string", length=200)
	*/
	private $positionName;
	/**
	* @ORM\Column(type="string", length=800)
	*/
	private $bankInfo;
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
	private $rss;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $bik;

	/**
	* @ORM\Column(type="string", length=400)
	*/
	private $ks;

	/**
	* @ORM\Column(type="string", length=800)
	*/
	private $contacts;

	/**
	* @var Catalog[] | ArrayCollection
	* @ORM\OneToMany(targetEntity="Document", mappedBy="client")
	* @ORM\OrderBy({"id" = "DESC"})
	*/
	private $documents;

	/**
	* @var Catalog[] | ArrayCollection
	* @ORM\OneToMany(targetEntity="Act", mappedBy="client")
	*/
	private $acts;

	/**
	* @var Catalog[] | ArrayCollection
	* @ORM\OneToMany(targetEntity="Work", mappedBy="client")
	*/
	private $works;


	public function __construct()
	{
		$this->contracts=new ArrayCollection();
	}



	public function getDocuments()
	{
		return $this->documents;
	}

	public function getDebtByDoc()
	{
		$price=0;
		foreach ($this->documents as $key => $value) {
			if(!$this->documents[$key]->isPayed())
			{
				$price+=$this->documents[$key]->getPrice();
			}
		}
		return $price;
	}

	public function getDebtOther()
	{
		$price=0;
		foreach ($this->works as $key => $value) {
			if(!$this->works[$key]->isPayed() && (!$this->works[$key]->getDocument() instanceof Document))
			{
				$price+=$this->works[$key]->getPrice()*$this->works[$key]->getCount();
			}
		}
		return $price;
	}

}
