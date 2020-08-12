<?php
/**
* Контроллер для работы с услугами
*
*/
namespace App\Controller;

use App\Entity\Catalog;

use App\Entity\Device;
use App\Form\CatalogType;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\FormInterface;

use Doctrine\ORM\EntityManagerInterface;

class CatalogController extends AbstractController
{

	/**
	* Добавление новой услуги
	* @Route("/lk/catalog/create/{id}", name="catalog.create", methods={"GET","POST"}, requirements={"id"="\d+"})
	*
	*/
	public function create(Request $request,int $id=null, Device $device=null, EntityManagerInterface $em) : Response
	{
		$catalog=new Catalog();
		if($device==null && $id!==null){
			throw $this->createNotFoundException();
		}

		if(isset($device)){
			$catalog->setDevice($device);
			$id=$device->getId();
		}

		$form=$this->createForm(CatalogType::class,$catalog);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$catalog->setPublic(true);
			$em->persist($catalog);
			$em->flush();
			return $this->redirectToRoute('device.show', array('id'=>$id) );
		}
		$tree = $em->getRepository(Device::class)->getToRoot($id);
		return $this->render('lk/catalog/create.html.twig',[
				'form'=>$form->createView(),
				'device'=>$device,
				'tree'=>$tree,
				'id'=>$id,
		]);
	}

	/**
	* Редактирование услуги
	* @Route("/lk/catalog/edit/{id}", name="catalog.edit", methods={"GET","POST"}, requirements={"id"="\d+"})
	*/
	public function edit(Request $request,  Catalog $catalog=null, EntityManagerInterface $em) : Response
	{
		if($catalog==null){
			throw $this->createNotFoundException();
		}
		$id=($catalog->getDevice()==null)? null:$catalog->getDevice()->getId();

		$form=$this->createForm(CatalogType::class,$catalog);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{

			$em->persist($catalog);
			$em->flush();
			return $this->redirectToRoute('device.show', array('id'=>$id) );
		}
		$tree = $em->getRepository(Device::class)->getToRoot($id);
		$deleteForm=$this->createDeleteForm($catalog);
		return $this->render('lk/catalog/edit.html.twig',[
				'form'=>$form->createView(),
				'deleteForm'=>$deleteForm->createView(),
				'tree'=>$tree,
				'id'=>$id,
		]);
	}


    /**
     * Создание формы для удаления услуги
     *
     * @param Catalog $catalog
     *
     * @return FormInterface
     */
    private function createDeleteForm(Catalog $catalog) : FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('catalog.delete', ['id' => $catalog->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

	/**
     * Удаление услуги.
     * @Route("/lk/catalog/{id}/delete", name="catalog.delete", methods="DELETE", requirements={"id" = "\d+"})
     * @param Request $request
     * @param Catalog $catalog
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Request $request, Catalog $catalog, EntityManagerInterface $em) : Response
    {
        $form = $this->createDeleteForm($catalog);
        $form->handleRequest($request);
        $id=($catalog->getDevice()==null)? null:$catalog->getDevice()->getId();
        if ($form->isSubmitted() && $form->isValid()) {
        	$catalog->setPublic(false);
            $em->persist($catalog);
            $em->flush();
        }
		return $this->redirectToRoute('device.show', array('id'=>$id) );
    }
}
