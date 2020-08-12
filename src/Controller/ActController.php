<?php
/**
* Контроллер для работы с услугами
*
*/
namespace App\Controller;

use App\Entity\Act;
use App\Entity\Work;
use App\Entity\Catalog;
use App\Entity\Client;
use App\Entity\Setting;


use Doctrine\Common\Collections\ArrayCollection;

use App\Form\ActType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;

class ActController extends AbstractController
{

	/**
	* Постраничное отображение сформированных актов
	* @Route("/lk/act/page/{page}" , name="act.list", requirements= { "page": "\d+"})
	*/
	public function list(Request $request,PaginatorInterface $paginator, int $page=1)
	{
		$acts=$paginator->paginate($this->getDoctrine()->getRepository(Act::class)->getList(),$page,20);
		return $this->render('lk/act/list.html.twig',[
			'acts'=>$acts,
		]);
	}

	/**
	* Формирование акта
	* @Route("/lk/act/create/",name="act.create")
	*/
	public function create(Request $request,EntityManagerInterface $em)
	{
		$this->denyAccessUnlessGranted('ROLE_ACT_CREATE');
		$act=new Act();
		$form=$this->createForm(ActType::class,$act);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$act->setClient($act->getClient());
			$em->persist($act);
			$em->flush();
			return $this->redirectToRoute('act.show',array('id'=>$act->getId()));
		}

		return $this->render('lk/act/create.html.twig',[
				'form'=>$form->createView(),
				'setting'=>$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1)),
				'act'=>$act
		]);
	}

	/**
	* Редактирование акта
	* @Route("/lk/act/edit/{id}",name="act.edit",requirements={"id": "\d+"})
	*/
	public function edit(Request $request,Act $act,EntityManagerInterface $em)
	{
		$this->denyAccessUnlessGranted('ROLE_ACT_EDIT');
		$origWorks = new ArrayCollection();
		foreach ($act->getWorks() as $work) {
			$origWorks->add($work);
		}
		$form=$this->createForm(ActType::class,$act);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			foreach ($origWorks as $work) {
				if (!$act->getWorks()->contains($work)) {
					if(!$work->isPayed())
					{
						$em->remove($work);
					}
				}
			}
			$act->setClient($act->getClient());
			$em->persist($act);
			$em->flush();
			return $this->redirectToRoute('act.show',array('id'=>$act->getId()));
		}

		return $this->render('lk/act/edit.html.twig',[
				'act'=>$act,
				'setting'=>$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1)),
				'form'=>$form->createView(),
		]);
	}


	/**
	* Просмотр акта
	* @Route("/lk/act/{id}" , name="act.show", requirements= { "id" : "\d+"})
	*/
	public function show(Act $act)
	{
		return $this->render('lk/act/show.html.twig',[
			'act'=>$act,
		]);
	}

	/**
	* Просмотр сформированного акта
	* @Route("/lk/act/{id}/watch" , name="act.watch", requirements= { "id" : "\d+"})
	*/
	public function watch(Act $act)
	{
		return $this->render('lk/act/act.html.twig',[
			'act'=>$act,
			'my'=>$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1)),
		]);
	}


	/**
	* Загрузка сформированного акта
	* @Route("/lk/act/{id}/download" , name="act.download", requirements= { "id" : "\d+"})
	*/
	public function download(Act $act)
	{
	    $response=new Response();
	    $response->headers->set('Content-type', 'application/octet-stream');
	    $name='Акт №'.$act->getNumber().' от '.$act->getDateCreate()->format('d.m.Y').' '.$act->getClient()->getCompanyName().'.doc';
	    $response->headers->set('Content-Disposition','attachment; filename="'.$name.'"');
	    $response->setContent(
	    	$this->renderView('act/act.html.twig',[
			'act'=>$act,
					'my'=>$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1)),
		]));
	    $response->setStatusCode(200);
	    $response->headers->set('Content-Transfer-Encoding', 'binary');
	    $response->headers->set('Pragma', 'no-cache');
	    $response->headers->set('Expires', '0');
	    return $response;
	}



	/**
	* Обработка qr кода и генерирование нового акта
	* @Route("/lk/act/{command}",name="act.generate",requirements={"command": "([\d|\:|\*|\,|\-]+\d$)"})
	*/
	public function generate(Request $request,$command=null,EntityManagerInterface $em)
	{
		$this->denyAccessUnlessGranted('ROLE_ACT_CREATE');
		$expl=explode(':',$command);
		//Клиент
		$client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(array('id'=>(int)$expl[0]));
		//Формирование акта
		$act=new Act();
		$catalogs=explode(',',$expl[1]);
		$i=0;
		foreach($catalogs as $key=>$value)
		{
			$currentWork=explode('*', $catalogs[$key]);
			$catalog=$this->getDoctrine()->getRepository(Catalog::class)->findOneBy(array('id'=>(int)$currentWork[0]));
			$work[$i]=new Work();
			$work[$i]->setCatalog($catalog);
			$work[$i]->setPrice($catalog->getPrice());
			$count=(isset($currentWork[1]))?$currentWork[1]:1;
			$work[$i]->setCount($count);
			$act->addWork($work[$i]);
			$i++;
		}
		$act->setClient($client);
		$form=$this->createForm(ActType::class,$act);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em->persist($act);
			$em->flush();
			return $this->redirectToRoute('act.show',array('id'=>$act->getId()));
		}

		return $this->render('lk/act/create.html.twig',[
				'form'=>$form->createView(),
				'setting'=>$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1)),
				'act'=>$act
		]);
	}


	/**
	* отображение qr кода для сформированного акта
	* @Route("/lk/act/show-qr/{command}",name="act.show.qr",requirements={"command": "([\d|\:|\*|\,|\-]+\d$)"})
	*/
	public function showQr(Request $request,$command=null,EntityManagerInterface $em)
	{
		$expl=explode(':',$command);
		$setting=$this->getDoctrine()->getRepository(Setting::class)->findOneBy(array('id'=>1));
		$client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(array('id'=>(int)$expl[0]));
		$act=new Act();
		$catalogs=explode(',',$expl[1]);
		$i=0;
		// Поиск услуг по команде и формирование новой выполненной работы
		foreach($catalogs as $key=>$value)
		{
			$currentWork=explode('*', $catalogs[$key]);
			$catalog=$this->getDoctrine()->getRepository(Catalog::class)->findOneBy(array('id'=>(int)$currentWork[0]));
			$work[$i]=new Work();
			$work[$i]->setCatalog($catalog);
			$work[$i]->setPrice($catalog->getPrice());
			$count=(isset($currentWork[1]))?$currentWork[1]:1;
			$work[$i]->setCount($count);
			$act->addWork($work[$i]);
			$i++;
		}
		$act->setClient($client);
		return $this->render('lk/act/showQr.html.twig',[
				'act'=>$act,
				'setting'=>$setting,
				'command'=>$command
		]);
	}

	/**
	* Удаление акта 
	* @Route("/lk/act/delete/{id}",name="act.delete", requirements={"id": "\d+"})
	*/
	public function delete(Act $act, EntityManagerInterface $em)
	{
		$this->denyAccessUnlessGranted('ROLE_ACT_EDIT');
		if($act->canDelete())
		{
			$em->remove($act);
			$em->flush();
		}
		return $this->redirectToRoute('act.list');

	}

}
