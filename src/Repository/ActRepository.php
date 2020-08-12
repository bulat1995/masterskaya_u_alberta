<?php
namespace App\Repository;


use App\Entity\Act;

use Doctrine\ORM\EntityRepository;

class ActRepository extends EntityRepository
{

    public function getList()
    {
        $db=$this->createQueryBuilder('a');
			$db->where('a.id is not NULL');
            $db->orderBy('a.id','DESC');
		$acts=$db->getQuery()->getResult();
        foreach ($acts as $key => $value) {
            if($acts[$key]->isPayed())
            {
                unset($acts[$key]);
            }
        }
        return $acts;
    }
}
