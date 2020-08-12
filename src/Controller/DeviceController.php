<?php
/**
* Контроллер для работы с устройствами
*
*/
namespace App\Controller;

use App\Entity\Device;
use App\Entity\Catalog;
use App\Form\DeviceType;
use App\Service\FileUploader;


use Doctrine\ORM\EntityManagerInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Form\FormInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
* @Route("/lk/")
*/
class DeviceController extends AbstractController
{

	/**
	* Отображение корневого узла 
	* если есть идентификатор узла то отображение его потомков
	* @Route("{id}", name="device.show", methods={"GET"}, requirements={"id" = "\d+"})
	*/
	public function show(int $id=null,Device $device=null, EntityManagerInterface $em) : Response
	{
		if(!$em->getRepository(Device::class)->findOneBy( array('id' => $id )) && !is_null($id)){
			throw $this->createNotFoundException('The device does not exist');
		}
		if($device instanceof Device){
			$id=$device->getId();
			$catalogs=$device->getCatalogs();
		}
		else{
			$catalogs=$em->getRepository(Catalog::class)->getCatalogsByRoot();
		}
		$devices = $em->getRepository(Device::class)->findDevicesById($id);
		$tree = $em->getRepository(Device::class)->getToRoot($id);
		return $this->render('lk/device/list.html.twig',[
			'device'=>$device,
			'tree'=>$tree,
			'devices' =>$devices,
			'catalogs'=>$catalogs,
			'id'=>$id,
		]);
	}

	/**
	* Создание узла устройства
	* @Route("device/create",name="device.create", methods={"POST","GET"})
	*/
	public function create(Request $request, EntityManagerInterface $em,FileUploader $fileUploader) : Response
	{
		$device= new Device();
		$form=$this->createForm(DeviceType::class,$device);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$fileLogo=$form->get('logo')->getData();
			if($fileLogo instanceof FileUploader)
			{
				$device->setLogo($fileUploader->upload($fileLogo));
			}
			$node=$form->get('category')->getData();
			$em->getRepository(Device::class)->createNode($node,$device);
			$em->persist($device);
			$em->flush();
			return $this->redirectToRoute('device.show',array('id'=>$device->getId()));
		}
		return $this->render('lk/device/create.html.twig',[
			'form'=>$form->createView(),
		]);
	}


	/**
	* Редактирование узла
	* @Route("device/{id}/edit", name="device.edit", requirements = {"id" = "\d+"}, methods = {"GET", "POST"})
	* @param Request $request
	* @param Device $device
	* @param EntityManagerInterface $em
	*/
	public function edit(Request $request, Device $device, EntityManagerInterface $em,FileUploader $fileUploader) : Response
	{
		$form=$this->createForm(DeviceType::class, $device);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$node=$form->get('category')->getData();

			if($form->get('deleteLogo')->getData())
			{
				$device->clearLogo();
			}
			$fileLogo=$form->get('logo')->getData();
			if($fileLogo instanceof FileUploader)
			{
				$device->setLogo($fileUploader->upload($fileLogo));
			}
			$em->persist($device);
			$em->flush();
			$em->getRepository(Device::class)->move($device,$node);
			return $this->redirectToRoute('device.show',array('id'=>$device->getId()));
		}
		//Форма удаления узла
		$delete=$this->createDeleteForm($device);

		return $this->render('lk/device/edit.html.twig',[
			'form'=>$form->createView(),
			'tree'=>$em->getRepository(Device::class)->getToRoot($device->getId()),
			'deleteForm'=>$delete->createView(),
			'title'=>'Редактирование устройства',
		]);
	}

	/**
	* удаление узла вместе с потомками
	* @Route("device/{id}/delete", name="device.delete", requirements={"id": "\d+"}, methods={"POST","DELETE"})
	*/
	public function delete(Request $request, Device $device, EntityManagerInterface $em) : Response
	{
		$form = $this->createDeleteForm($device);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        	$repo=$em->getRepository(Device::class);
        	$parent=$repo->getParent($device);
        	$repo->deleteNode($device->getId());
            if(method_exists($parent,"getId")){
				return $this->redirectToRoute('device.show', array('id'=>$parent->getId()) );
            }
			return $this->redirectToRoute('device.show');
        }
		return $this->redirectToRoute('device.show', array('id'=>$device->getId()));
	}

    /**
     * Создание формы для удаления узла 
     *
     * @param Device $device
     *
     * @return FormInterface
     */
    private function createDeleteForm(Device $device) : FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('device.delete', ['id' => $device->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

}
