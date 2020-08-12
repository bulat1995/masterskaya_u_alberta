<?php
namespace App\DataFixtures;

use App\Entity\Device;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DevicesFixtures extends Fixture
{

	public function load( ObjectManager $manager): void
	{

		$pc=new Device();
		$pc->setName('Компьютер');
		$pc->setLvl(1);
		$pc->setLft(1);
		$pc->setRgt(8);
	
		$hdd=new Device();
		$hdd->setName('Жесткий диск');
		$hdd->setLvl(2);
		$hdd->setLft(2);
		$hdd->setRgt(3);


		$ram=new Device();
		$ram->setName('Оперативная память');
		$ram->setLvl(2);
		$ram->setLft(4);
		$ram->setRgt(7);

		$_1gb=new Device();
		$_1gb->setName('1 gb');
		$_1gb->setLvl(3);
		$_1gb->setLft(5);
		$_1gb->setRgt(6);


		$monitor=new Device();
		$monitor->setName('Монитор');
		$monitor->setLvl(1);
		$monitor->setLft(9);
		$monitor->setRgt(10);

		$manager->persist($pc);
		$manager->persist($ram);
		$manager->persist($_1gb);
		$manager->persist($hdd);
		$manager->persist($monitor);

		$manager->flush();

		$this->addReference('hdd-rem',$hdd);
	}
}