<?php
/**
* Контроллер для работы с шаблонами
*
*/
namespace App\Controller;

use App\Entity\DocTemplate;
use App\Entity\Setting;

use App\Form\DocTemplateType;
use App\Service\FileUploader;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DocTemplateController extends AbstractController
{

	/**
	* Генерирование шаблона
	* @Route("/lk/doc-template/create", name="template.create", methods={"GET","POST"})
	*/
	public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader)
	{
		$docTemplate= new DocTemplate();
		$form=$this->createForm(DocTemplateType::class,$docTemplate);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em->persist($docTemplate);
			$em->flush();
			return $this->redirectToRoute('template.show',['id'=>$docTemplate->getId()]);
		}
		return $this->render('lk/docTemplate/create.html.twig',['form'=>$form->createView()]);
	}


	/**
	* отображение всех шаблонов
	* @Route("/lk/doc-template", name="template.list", methods={"GET"}, requirements={"id" = "\d+"})
	*/
	public function list()
	{
		$docTemplates=$this->getDoctrine()->getRepository(DocTemplate::class)->findAll();
		return $this->render('lk/docTemplate/list.html.twig',[
			'docTemplates'=>$docTemplates,
		]);

	}


	/**
	* отображение шаблона
	* @Route("/lk/doc-template/{id}", name="template.show", requirements={"id":"\d+"})
	*/
	public function show(DocTemplate $docTemplate)
	{
		return $this->render('lk/docTemplate/show.html.twig',[
			'docTemplate'=>$docTemplate,
		]);

	}


	/**
	* Редактирование шаблона
	* @Route("/lk/doc-template/{id}/edit", name="template.edit", requirements={"id": "\d+"},methods = {"GET", "POST"})
	*/
	public function edit(Request $request, DocTemplate $docTemplate, EntityManagerInterface $em)
	{
		$form=$this->createForm(DocTemplateType::class,$docTemplate);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em->persist($docTemplate);
			$em->flush();
			return $this->redirectToRoute('template.show',['id'=>$docTemplate->getId()]);
		}
		return $this->render('lk/docTemplate/edit.html.twig',[
			'docTemplate'=>$docTemplate,
			'form'=>$form->createView()
		]);

	}

	/**
	* Отображение статической страницы о работе с псевдотегами
	* @Route("/lk/doc-template/psevdo", name="template.psevdo")
	*/
	public function getPsevdo()
	{
		return $this->render('lk/docTemplate/psevdo.html.twig',[
			'my'=>$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1)),
		]);
	}

}
