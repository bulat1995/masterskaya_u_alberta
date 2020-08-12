<?php
namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SettingFixtures extends Fixture
{

	public function load( ObjectManager $manager): void
	{
		$setting=new Setting();
		$setting->setId(1);
		$setting->setBossName('Абдрафиков Альберт Хасбуллаевич');
		$setting->setInFace('Абдрафиков Альберт Хасбуллаевич');
		$setting->setCompanyName('ИП Абдрафиков Альберт Хасбуллаевич');
		$setting->setPositionName('Индивидуальный предприниматель');
		$setting->setPositionNameShort('ИП');
		$setting->setInn('Инн');
		$setting->setKpp('Инн');
		$setting->setQrCode('http://192.168.1.1/act/');
		$setting->setQrCodeWidth('15');
		$setting->setRss('rss');
		$setting->setBik('bik');
		$setting->setKs('ks');
		$setting->setContacts('kontakty');
		$setting->setDocName('свидетельства о государственной регистрации ОГРН 315028000126787');
		$setting->setAddress('Тут должен быть юр адрес');
		$setting->setBankInfo('Банковские данные');
		$manager->persist($setting);
		$manager->flush();
	}
}
