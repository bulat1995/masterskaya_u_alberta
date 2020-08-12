<?php 
namespace App\Repository;

use App\Entity\Catalog;

use Doctrine\ORM\EntityRepository;

class CatalogRepository extends EntityRepository
{
	//Услуги для корневого раздела устройства
	public function getCatalogsByRoot()
	{
		$db=$this->createQueryBuilder('c')
			->orderBy('c.name','ASC');
			$db->where('c.device is NULL')->andWhere('c.public is not NULL');
		return $db->getQuery()->getResult();
	}

	public function getCatalogByName($query){
		$db=$this->createQueryBuilder('c')
		->orderBy('c.name')->where('c.name LIKE :name')
		->andWhere('c.public is not NULL')
		->setParameter(':name',$query);
		return $db->getQuery()->getResult();
	}
}
