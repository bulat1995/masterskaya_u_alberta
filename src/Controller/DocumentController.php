<?php
/**
* Контроллер для работы с документами
*
*/

namespace App\Controller;


use App\Entity\Document;
use App\Entity\DocTemplate;

use App\Entity\Client;
use App\Entity\Setting;
use App\Form\DocumentType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
* @Route("/lk/")
*/
class DocumentController extends AbstractController
{
	/**
	* Генерирование документа
	* @Route("document/create/{id}/{docTemplate}", name="document.create", methods={"GET", "POST"}, requirements={"id": "\d+", "docTemplate": "\d+"})
	*/
	public function create(Request $request, Client $client, DocTemplate $docTemplate, EntityManagerInterface $em)
	{

		$document=new Document();
		$document->setClient($client);
		$document->setDocTemplate($docTemplate);
		$form=$this->createForm(DocumentType::class,$document);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$document->setDateClosed(new \DateTime("NOW"));
			$em->persist($document);
			$em->flush();
			return $this->redirectToRoute('document.show',array('id'=>$document->getId()));
		}
		return $this->render('lk/document/create.html.twig',[
			'document'=>$document,
			'form'=>$form->createView(),
		]);
	}


	/**
	* Отображение документа
	* @Route("document/{id}", name="document.show", requirements={"id": "\d+"})
	*/
	public function show(Request $request,Document $document)
	{
		return $this->render('lk/document/show.html.twig',[
			'document'=>$document,
			'application'=>$this->getDoctrine()->getRepository(Document::class)->getApplication($document),
		]);
	}


	/**
	* Редактирование документа
	* @Route("document/edit/{id}",name="document.edit",requirements={"id": "\d+"})
	*/
	public function edit(Request $request, Document $document,EntityManagerInterface $em)
	{
		if($document->isPayed())
		{
			return $this->redirectToRoute('document.show',array('id'=>$document->getId()));
		}
		$form=$this->createForm(DocumentType::class,$document);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$document->setDateClosed(new \DateTime("NOW"));
			$em->persist($document);
			$em->flush();
			return $this->redirectToRoute('document.show',array('id'=>$document->getId()));
		}
		return $this->render('lk/document/edit.html.twig',[
			'document'=>$document,
			'form'=>$form->createView(),
		]);
	}


	/**
	* Установка статуса документа "Оплачено"
	* @Route("document/pay/{id}", name="document.pay", requirements={ "id" : "\d+"})
	*/
	public function pay(Request $request, Document $document,EntityManagerInterface $em)
	{
		$document->setDatePayed(new \DateTime('now'));
		$em->persist($document);
		$em->flush();
		return $this->redirectToRoute('document.show',array('id' => $document->getId() ));
	}

	/**
	* Просмотреть сгенерированый  документ в браузере
	* @Route("/document/{id}/watch" , name="document.watch", requirements= { "id" : "\d+"})
	*/
	public function watch(Document $document,EntityManagerInterface $em)
	{

		return $this->render("docTemplate/main.html.twig",[
			'document'=>$document,
			'application'=>$em->getRepository(Document::class)->getApplication($document),
			'docTemplate'=>'@path1'.$document->getDocTemplate()->getDocument(),
			'client'=>$document->getClient(),
			'my'=>$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1)),
		]);
	}

	/**
	* Скачать сформированный документ
	* @Route("/document/{id}/download" , name="document.download", requirements= { "id" : "\d+"})
	*/
	public function download(Document $document,EntityManagerInterface $em)
	{
		$response=new Response();
	    $response->headers->set('Content-type', 'application/octet-stream');
	    $name=$document->getDocTemplate()->getName().' №'.$document->getNumber().' от '.$document->getDateOpen()->format('d.m.Y').' '.$document->getClient()->getCompanyName().'[Основной].doc';
	    $response->headers->set('Content-Disposition','attachment; filename="'.$name.'"');
	    $response->setContent(
				$this->renderView('@path1'.$document->getDocTemplate()->getDocument(),[
					'document'=>$document,
					'application'=>$em->getRepository(Document::class)->getApplication($document),
					'docTemplate'=>'@path1'.$document->getDocTemplate()->getDocument(),
					'client'=>$document->getClient(),
					'my'=>$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1)),
				])
		);
	    $response->setStatusCode(200);
	    $response->headers->set('Content-Transfer-Encoding', 'binary');
	    $response->headers->set('Pragma', 'no-cache');
	    $response->headers->set('Expires', '0');
	    return $response;
	}


	/**
	* Просмотреть приложения документа
	* @Route("/document/{id}/application" , name="document.acts", requirements= { "id" : "\d+"})
	*/
	public function acts(Document $document,EntityManagerInterface $em)
	{
		return $this->render('lk/docTemplate/main.html.twig',[
			'document'=>$document,
			'docTemplate'=>'@path1'.$document->getDocTemplate()->getSubDoc(),
			'application'=>$em->getRepository(Document::class)->getApplication($document),
			'client'=>$document->getClient(),
			'my'=>$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1)),
		]);

	}

	/**
	* Скачать приложения документа
	* @Route("/document/{id}/application/download" , name="document.application.download", requirements= { "id" : "\d+"})
	*/
	public function actsDownloads(Document $document,EntityManagerInterface $em)
	{
		$response=new Response();
	    $response->headers->set('Content-type', 'application/octet-stream');
	    $document->getDocTemplate()->getName().' №'.$document->getNumber().' от '.$document->getDateOpen()->format('d.m.Y').' '.$document->getClient()->getCompanyName().'[Приложение].doc';
		$response->headers->set('Content-Disposition','attachment; filename="'.$name.'"');
	    $response->setContent(
				$this->renderView('docTemplate/main.html.twig',[
					'document'=>$document,
					'docTemplate'=>'@path1'.$document->getDocTemplate()->getSubDoc(),
					'application'=>$em->getRepository(Document::class)->getApplication($document),
					'client'=>$document->getClient(),
					'my'=>$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1)),
				])
		);
	    $response->setStatusCode(200);
	    $response->headers->set('Content-Transfer-Encoding', 'binary');
	    $response->headers->set('Pragma', 'no-cache');
	    $response->headers->set('Expires', '0');
	    return $response;
	}

}
