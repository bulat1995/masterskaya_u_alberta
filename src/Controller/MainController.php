<?php
/**
* Контроллер отображающий главную страницу проекта
*
*/
namespace App\Controller;

use App\Entity\Setting;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class MainController extends AbstractController
{

	/**
	* @Route("/",name="default",)
	*/
	public function defaultPage(Request $request)
	{
		$setting=$this->getDoctrine()->getRepository(Setting::class)->findOneBy(['id'=>1]);
		return $this->render("default/index.html.twig",[
			'setting'=>$setting,
		]);
	}
}
