<?php
/**
* Сущность пользователя системы
*
*/
namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
* @ORM\Entity()
* @ORM\Table(name="users")
*/
class User extends BaseUser
{

	public const rolesName=[
		'ROLE_USER' => 'Пользователь',
		'ROLE_DEVICE_EDIT' => '[Устройство] Редактирование',
		'ROLE_DEVICE_CREATE' => '[Устройство] Добавление',
		'ROLE_DEVICE_DELETE' => '[Устройство] Удаление',
		'ROLE_CATALOG_CREATE' => '[Услуга] Добавление',
		'ROLE_CATALOG_EDIT' => '[Услуга] Редактирование',
		'ROLE_CATALOG_DELETE' => '[Услуга] Удаление',
		'ROLE_CLIENT_CREATE' => '[Клиент] Добавление',
		'ROLE_CLIENT_SHOW' => '[Клиент] Просмотр',
		'ROLE_CLIENT_EDIT' => '[Клиент] Редактирование',
		'ROLE_CLIENT_DELETE' => '[Клиент] Удаление',
		'ROLE_DOCUMENT_SHOW' => '[Документ] Просмотр',
		'ROLE_DOCUMENT_CREATE' => '[Документ] Добавление',
		'ROLE_DOCUMENT_EDIT' => '[Документ] Редактирование',
		'ROLE_DOCUMENT_DELETE' => '[Документ] Удаление',
		'ROLE_ACT_SHOW' => '[Акт] Просмотр',
		'ROLE_ACT_CREATE' => '[Акт] Добавление',
		'ROLE_ACT_EDIT' => '[Акт] Редактирование',
		'ROLE_ACT_DELETE' => '[Акт] Удаление',
		'ROLE_TEMPLATE_SHOW' => '[Шаблон] Просмотр',
		'ROLE_TEMPLATE_CREATE' => '[Шаблон] Добавление',
		'ROLE_TEMPLATE_DELETE' => '[Шаблон] Удаление',
		'ROLE_TEMPLATE_EDIT' => '[Шаблон] Редактирование',
		'ROLE_SETTING_EDIT' => '[Настройки] Редактирование',
		'ROLE_SUPER_ADMIN' => 'Администратор [Полный доступ]',
	];


	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;


	public function getRolesName($key)
	{
		return self::rolesName[$key];
	}


}
