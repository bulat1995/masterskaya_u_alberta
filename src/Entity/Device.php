<?php
/**
* Сущность устройства
* id<= catalog.device
*/
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
* @ORM\Table(name="devices")
* @ORM\HasLifecycleCallbacks()
*/
class Device 
{

		/**
		* @ORM\Column(type="integer")
		* @ORM\Id
		* @ORM\GeneratedValue(strategy="AUTO")
		*/
		private $id;

		/**
		* @var string
		*@ORM\Column(type="string", length=400)
		*
		*/
		private $name;

		/**
		* @ORM\Column(type="string", length=200, nullable=true)
		*
		*/
		private $logo;

		/**
		* @var int
		* @ORM\Column(type="integer")
		*/
		private $lft;

		/**
		* @var int
		* @ORM\Column(type="integer")
		*/
		private $rgt;

		/**
		* @var int
		* @ORM\Column(type="integer")
		*/
		private $lvl;

		/**
		* @var Catalog[] | ArrayCollection  
		* @ORM\OneToMany(targetEntity="Catalog", mappedBy="device")
		*/
		private $catalogs;

		public function __construct()
		{
			$this->catalogs=new ArrayCollection();
		}

		
		public function getId(): int
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

		public function getLft()
		{
			return $this->lft;
		}

		public function setLft(int $lft)
		{
			$this->lft=$lft;
		}

		public function getRgt()
		{
			return $this->rgt;
		}
		public function setRgt(int $rgt)
		{
			$this->rgt=$rgt;
		}

		public function getLvl()
		{
			return $this->lvl;
		}

		public function setLvl(int $lvl)
		{
			$this->lvl=$lvl;
		}

		public function getLogo()
		{
			return $this->logo;
		}

		public function setLogo($logo) : self
		{
			if(!empty($logo))
			{
				$this->logo=$logo;
			}
			return $this;
		}

		public function clearLogo()
		{
			$this->logo=null;
		}

		public function getCatalogs()
		{
			return $this->catalogs->filter(function(Catalog $catalog) {
            	return $catalog->isPublic();
        	});
		}

		public function addCatalogs(Catalog $catalog) : self
		{
			if(!$this->catalogs->contains($catalog))
			{
				$this->catalogs->add($catalog);
			}
			return $this;
		}

}