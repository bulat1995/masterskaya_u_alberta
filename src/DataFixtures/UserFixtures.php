<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
	public function  load(ObjectManager $manager)
	{
		$user=new User();
		$user->setUserName('albert');
		$user->setPlainPassword('albert');
		$user->setEmail('bulatkasymov@yandex.ru');
		$user->addRole('ROLE_SUPER_ADMIN');
		$user->setEnabled(true);
		$manager->persist($user);
		$manager->flush();

	}
}
