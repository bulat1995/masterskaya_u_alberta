<?php
/**
* Контроллер для работы с клиентами
*
*/
namespace App\Controller;

use App\Entity\Client;
use App\Entity\Document;

use App\Entity\DocTemplate;

use App\Form\ClientType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClientController extends AbstractController
{

	/**
	* Добавление клиента
	* @Route("/lk/client/create",name="client.create", methods= { "GET", "POST"})
	*/
	public function create(Request $request,EntityManagerInterface $em)
	{
		$client=new Client();
		$form=$this->createForm(ClientType::class,$client);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em->persist($client);
			$em->flush();
			return $this->redirectToRoute('client.show',array('id'=>$client->getId()));
		}
		return $this->render('lk/client/create.html.twig',[
			'tree'=>[],
			'form'=>$form->createView(),

		]);
	}

	/**
	* Постраничное отображение клиентов
	*@Route("/lk/client/page/{page}",name="client.list", requirements={"page": "\d+"})
	*
	*/
	public function list(Request $request,PaginatorInterface $paginator, int $page=1)
	{
		$clients=$paginator->paginate($this->getDoctrine()->getRepository(Client::class)->findAll(),$page,20);
		return $this->render('lk/client/list.html.twig',[
			'tree'=>[],
			'clients'=>$clients,
		]);
	}

	/**
	* Отображение данных клиента по его идентификатору
	*  @Route("/lk/client/{id}", name="client.show", requirements= { "id" : "\d+" })
	*/
	public function show(Request $request, Client $client, EntityManagerInterface $em)
	{
		$form=$this->prepareFormDocument();
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$doc=$form->getData()['docTemplate'];
			return $this->redirectToRoute('document.create',array('id'=>$client->getId(),'docTemplate'=>$doc->getId()));
		}

		return $this->render('lk/client/show.html.twig',[
			'client'=>$client,
			'document'=>$form->createView(),
		]);

	}

	/**
	* Редактирование данных клиента по его идентификатору
	*@Route("/lk/client/edit/{id}",name="client.edit", methods= { "GET", "POST"}, requirements={ "id": "\d+" })
	*
	*/
	public function edit(Request $request,Client $client, EntityManagerInterface $em)
	{
		$form=$this->createForm(ClientType::class,$client);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em->persist($client);
			$em->flush();
			return $this->redirectToRoute('client.show',array('id'=>$client->getId()));
		}
		return $this->render('lk/client/edit.html.twig',[
			'form'=>$form->createView(),
		]);
	}


	/**
	* Подготовка формы выбора шаблона для последующей генерации документа
	*/
	private function prepareFormDocument()
	{
		return $this->createFormBuilder()
				->add('docTemplate',EntityType::class,[
							'class' => DocTemplate::class,
							'placeholder'=>'Выберите шаблон',
							'label'=>'catalog.forDocument',
							'choice_label' => function($device){
								return $device->getName();
							},
				])->getForm()
				;
	}


}
