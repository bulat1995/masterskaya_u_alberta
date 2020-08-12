<?php
namespace App\Repository;

use App\Entity\Client;
use App\Entity\Work;
use App\Entity\Catalog;

use Doctrine\ORM\EntityRepository;

class ClientRepository extends EntityRepository
{
	public function getClient($query=null)
    {
        return $this->createQueryBuilder('c')
			->where('CONCAT(c.companyName,\' \',c.address,\' \',c.positionName,\' \',c.bossName,\' \',c.bankInfo,\' \',c.inFace,\' \',c.contacts) LIKE :q')
            ->setParameter('q',$query)
            ->getQuery()->getResult();
    }
}
