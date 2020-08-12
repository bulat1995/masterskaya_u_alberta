<?php
namespace App\DataFixtures;
use App\Entity\Catalog;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CatalogsFixtures extends Fixture implements DependentFixtureInterface
{
	public function load( ObjectManager $manager): void
	{
		$remHdd=new Catalog();
		$remHdd->setDevice($manager->merge($this->getReference('hdd-rem')));
		$remHdd->setName('Ремонт жесткого диска');
		$remHdd->setPublic(true);
		$remHdd->setCountName('шт');
		$remHdd->setPrice(0);

		$manager->persist($remHdd);
		$manager->flush();

	}

	public function getDependencies(): array
	{
		return [
			DevicesFixtures::class
		];
	}
}
