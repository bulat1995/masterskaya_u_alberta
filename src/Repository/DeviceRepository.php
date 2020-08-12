<?php
namespace App\Repository;

use App\Entity\Device;
use Doctrine\ORM\EntityRepository;

class DeviceRepository extends EntityRepository
{

	//Вывод содержимого узла
	public function findDevicesById(int $deviceId=null)
	{
		//Вывод потомков узла по идентификатору
		if($deviceId!=null){
			$node=$this->find($deviceId);
			$db=$this->createQueryBuilder('n')
				->where('n.lft>:lft')->setParameter('lft',$node->getLft())
				->andWhere('n.rgt<:rgt')->setParameter('rgt',$node->getRgt())
				->andWhere('n.lvl=:lvl')->setParameter('lvl',$node->getLvl()+1)
				->orderBy('n.name','ASC');
		}
		//Вывод узлов Первого уровня
		else{
			$db=$this->createQueryBuilder('d')
				->orderBy('d.name','ASC')->where('d.lvl = 1');
		}
		return $db->getQuery()->getResult();
	}

	// поиск Родителей до корневого узла по идентификатору
	public function getToRoot(int $deviceId=null)
	{
		if($deviceId!=null){
			$node=$this->find($deviceId);
			$nd=$this->createQueryBuilder('n')
				->andWhere('n.lft<=:lft')
				->setParameter('lft',$node->getLft())
				->andWhere('n.rgt>=:rgt')
				->setParameter('rgt',$node->getRgt())
				->orderBy('n.lft','ASC');
			return $nd->getQuery()->getResult();
		}
		return;
	}

	//Поиск Родителя элемента
	public function getParent(Device $device)
	{
		$nd=$this->createQueryBuilder('n')
				->andWhere('n.lft<:lft')
				->setParameter('lft',$device->getLft())
				->andWhere('n.rgt>:rgt')
				->setParameter('rgt',$device->getRgt())
				->setMaxResults(1)
				->orderBy('n.lft','ASC');
			return $nd->getQuery()->getOneOrNullResult();
	}

	/**
	* Создание нового узла
	*/
	public function createNode($node=null,&$device)
	{
		$id=(isset($node)) ? $node->getId() : null;
		$lvl=(isset($node)) ? $node->getLvl()+1 : 1;
		$left=$this->findPlace($device->getName(),$id);
		$device->setLft($left+1);
		$device->setRgt($left+2);
		$device->setLvl($lvl);
		$this->shift($left,2);
	}

	// + Сдвиг узлов
	public function shift($left,$shift,$right=null)
	{
		$db=$this->createQueryBuilder('d')
			->update(Device::class, 'd')
			->where('d.rgt > :lft')->setParameter('lft',$left);
	        $db->set('d.rgt', 'd.rgt+'.$shift)->getQuery()->execute();
		$db=$this->createQueryBuilder('d')
			->update(Device::class, 'd')
			->where('d.rgt > :lft')->setParameter('lft',$left);
			$db->andWhere('d.lft > :lft')->setParameter('lft',$left);
	        $db->set('d.lft', 'd.lft+'.$shift)->getQuery()->execute();
        $this->clear();
	}

	// + Получение крайнего правого значения
	public function getRightMax() :int
	{
		$query = $this->createQueryBuilder('d');
	    $query->select('MAX(d.rgt) AS maxim');
	    $max=$query->getQuery()->getSingleResult();
	    return (int)$max['maxim'];
	}

	// + Подготовка запроса для поиска места в БД
	private function setFindQuery($name,$id,$invert=false)
	{
		$query = $this->createQueryBuilder('d');
		if(!$invert)
			$query->where('d.name > :name');
		else
			$query->where('d.name < :name');

		$query->setParameter('name',$name);
	    $level=1;
	    if(isset($id))
	    {
			$node=$this->find($id);
		    $query->andWhere('d.rgt < :rgt')->setParameter('rgt',$node->getRgt())
		    		->andWhere('d.rgt > 0')->andWhere('d.lft > 0')
		    		->andWhere('d.lft > :lft')->setParameter('lft',$node->getLft());
			$level=$node->getLvl()+1;
	    }
	    $query->andWhere('d.lvl = :lvl')->setParameter('lvl',$level);
	    if(!$invert)
			$query->orderBy('d.name', 'ASC');
		else
			$query->orderBy('d.name', 'DESC');
	    $query->setMaxResults(1);
	    return $query->getQuery()->getOneOrNullResult();
	}

	// + Поиск места для узла по имени узла
	public function findPlace($name,$id=null)
	{
		$invert=false;
	    $place=$this->setFindQuery($name,$id,$invert);
	    if(!isset($place))
	    {
			$invert=true;
		    $place=$this->setFindQuery($name,$id,$invert);
	    }
		if(isset($place))
			$curPlace=($invert) ? $place->getRgt() : $place->getLft()-1;
		elseif(isset($id))
			 $curPlace=$this->find($id)->getLft();
		else
			$curPlace=$this->getRightMax();
		return $curPlace;
	}

	//Перемешение узла
	//from - перемещаемый узел
	// to  - место помещения
	// @return true/false
	public function move($fromId,$toId)
	{
		$node=$this->find($fromId);
		if(isset($toId)) $placeTmp=$this->find($toId);
		$place=[
			'id'  => (isset($toId)) ? $placeTmp->getId()  : null,
			'lft' => (isset($toId)) ? $placeTmp->getLft() : 0,
			'rgt' => (isset($toId)) ? $placeTmp->getRgt() : $this->getRightMax(),
			'lvl' => (isset($toId)) ? $placeTmp->getLvl() : 0,
		];

		if(!(($node->getLft() < $place['lft']) && ($node->getRgt() > $place['rgt'])))
		{
			$level=($place['lvl']-$node->getLvl())+1;
			$width=($node->getRgt()-$node->getLft())+1;
			$this->nodeInvert($node);
			$this->shift($node->getLft(), -$width,);
			$newPlace=$this->findPlace($node->getName(),$place['id']);
			$step=($newPlace+1)-($node->getLft());
			$this->shift($newPlace,$width);
			$this->invertToNode($step,$level);
			return true;
		}
		return false;
	}

	/**
	* Инвертирование значений узла (скрытие)
	*/
	private function nodeInvert($node)
	{
		$db=$this->createQueryBuilder('d')
			->update(Device::class, 'd')
			->where('d.lft>=:lft')->setParameter('lft',$node->getLft())
			->andWhere('d.rgt<=:rgt')->setParameter('rgt',$node->getRgt())
	        ->set('d.rgt', "-d.rgt")->set('d.lft', "-d.lft")
	        ->getQuery()->execute();
	}

	//Возвращение узла из минусового диапазона со сдвигом положения и уровня
	//startPoint - Значение для смещения пложения узла
	//level -  Значение для смещения уровня узла
	private function invertToNode($startPoint,$level)
	{
		$db=$this->createQueryBuilder('d')
			->update(Device::class, 'd')
			->where('d.lft<:lft')->setParameter('lft',0)
	        ->set('d.rgt', "ABS(d.rgt)+$startPoint")
	        ->set('d.lft', "ABS(d.lft)+$startPoint")
	        ->set('d.lvl', "d.lvl+$level")
	        ->getQuery()->execute();
	}

	//Удаление узла с потомками по идентификатору
	public function deleteNode($id)
	{
		if(isset($id))
		{
			$node=$this->find($id);
			$width=($node->getRgt()-$node->getLft())+1;

			$db=$this->createQueryBuilder('d')
						->delete()
						->where('d.lft>=:lft')->setParameter('lft',$node->getLft())
						->andWhere('d.rgt<=:rgt')->setParameter('rgt',$node->getRgt())
						->getQuery()->execute();
						
		    $this->shift($node->getLft(),-$width);
		    return true;
		}
		return false;
	}

	//Поисковый запрос
	public function getDevicesByName($value){
		return $this->createQueryBuilder('d')
		->where('d.name LIKE :v')
		->setParameter('v',$value)
		->getQuery()->execute();
	}


}
