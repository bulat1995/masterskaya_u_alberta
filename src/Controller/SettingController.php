<?php
/**
* Контроллер для настроек проекта
*
*/
namespace App\Controller;

use App\Entity\Setting;

use App\Form\SettingType;
use Symfony\Component\Form\FormInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
* @Route("/lk/")
*/
class SettingController extends AbstractController
{

	/**
	* Редактирование настроек
	*@Route("/setting/", name="setting.edit")
	*/
	public function setting(Request $request,EntityManagerInterface $em)
	{
		$setting=$this->getDoctrine()->getRepository(Setting::class)->findOneBy(['id'=>1]);
		if(!$setting instanceof Setting)
		{
			$setting=new Setting();
		}
		$form=$this->createForm(SettingType::class,$setting);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em->persist($setting);
			$em->flush();
			$this->addFlash('success','Настройки успешно сохранены');
		}

		return $this->render('lk/setting/setting.html.twig',[
			'form'=>$form->createView(),
		]);
	}
}
