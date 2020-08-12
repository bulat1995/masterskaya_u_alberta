<?php
/**
* Сущность акта
*
* id<=work.acts
* id=>client.acts
*/
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Price;

use App\Entity\Client;

use Symfony\Component\Validator\Constraints as Assert;


/**
* @ORM\Entity(repositoryClass="App\Repository\ActRepository")
* @ORM\Table(name="acts")
*/
class Act
{
	use Price;
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id;


	/**
	* @var Catalog[] | ArrayCollection
	* @ORM\ManyToOne(targetEntity="Client", inversedBy="acts")
	*/
	private $client;

	/**
	* @var Catalog[] | ArrayCollection
	* @ORM\OneToMany(targetEntity="Work", mappedBy="act",cascade={"persist"})
	*/
	private $works;


	public function __construct()
	{
		$this->works=new ArrayCollection();

	}

	public function getId()
	{
		return $this->id;
	}


	public function getDateCreate()
	{
		return $this->works[0]->getDateCreate();
	}


	public function getDatePayed()
	{
		return $this->works[0]->getDatePayed();
	}


	public function getClient()
	{
		if($this->client instanceof Client)
		{
			return $this->client;
		}

	}

	public function setClient(Client $client)
	{
		$this->client=$client;
		foreach($this->works as $work)
		{
			$work->setClient($client);
		}
	}

	public function getWorks()
	{
		return $this->works;
	}

	public function addWork(Work $work)
	{
		if(!$this->works->contains($work))
		{
			$work->setAct($this);
			$this->works->add($work);
		}
		return $this;
	}

	public function getWorksForAct()
	{
		return $this->works->filter(function(Work $work){
			return !$work->isPayed();
		});
	}

	public function removeWork($null){}

	public function addWorksForAct($work)
	{
		if(!$this->works->contains($work))
		{
			$work->setAct($this);
			$this->works->add($work);
		}
		return $this;
	}
	public function removeWorksForAct($null){}


	public function getPrice()
	{
		$sum=0;
		foreach ($this->works as $key => $value) {
			$sum+=$this->works[$key]->getCount()*$this->works[$key]->getPrice();
		}
		return $sum;
	}

	public function getDebt()
	{
		$sum=0;
		foreach ($this->works as $key => $value) {
			if(!$this->works[$key]->isPayed()){
				$sum+=floatval($this->works[$key]->getCount()*$this->works[$key]->getPrice());
			}
		}
		return $sum;
	}

	public function isPayed()
	{
		$count=0;
		foreach ($this->works as $key => $value) {
			if($this->works[$key]->isPayed()){
				$count++;
			}
		}
		return ( $count >= sizeof($this->works));
	}

	public function getPriceForm()
	{
		$sum=0;
		foreach ($this->works as $key => $value) {
			$sum+=$this->works[$key]->getCount()*$this->works[$key]->getPrice();
		}
		return number_format($sum, 2, ',', ' ');
	}

	public function canDelete()
	{
		$status=true;
		foreach ($this->works as $key => $value) {
			if(!is_null($this->works[$key]->getDocument()))
			{
				$status=false;
			}
		}
		return $status;
	}





}
