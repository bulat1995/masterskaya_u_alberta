<?php
namespace App\Repository;

use App\Entity\Document;
use App\Entity\Work;
use App\Entity\Catalog;

use Doctrine\ORM\EntityRepository;

class DocumentRepository extends EntityRepository
{
	public function getApplication(Document $document)
    {
        return $this->createQueryBuilder('d')
            ->select('DISTINCT w.price as price, COUNT(d.id) as count,cat.name as name, cat.countName as countName, (w.price * COUNT(d.id)) as fullPrice')
            ->from(Work::class,'w')
            ->where('w.document=:document')
            ->andWhere('w.catalog=cat.id')
            ->leftJoin('w.catalog','cat')
            ->groupBy('w.price')
            ->setParameter('document',$document->getId())
            ->getQuery()->getResult();

    }
}
