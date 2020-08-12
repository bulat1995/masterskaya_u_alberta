<?php
namespace App\EventListener;

use App\Entity\Device;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class DeviceUploadListener
{
	private $uploader;
	private $params;

	public function __construct(FileUploader $uploader,ParameterBagInterface $params)
	{
		$this->uploader=$uploader;
		$this->params=$params;
	}

	public function preRemove(LifecycleEventArgs $args)
	{
		$filesystem = new Filesystem();	
		$entity=$args->getEntity();
		if(!$entity instanceof Device)
		{
			return;
		}
		$filesystem->remove($entity->getLogo());
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$entity=$args->getEntity();
		$this->uploadFile($entity);
		$this->fileToString($entity);
	}

	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity=$args->getEntity();
		$this->uploadFile($entity);
		$this->fileToString($entity);
	}

	private function uploadFile($entity)
	{
		if(!$entity instanceof Device)
		{
			return;
		}

		$logoFile=$entity->getLogo();
		if($logoFile instanceof UploadedFile)
		{
			$this->uploader->changeDirectory($this->params->get('device_img_folder'));
			$fileName=$this->uploader->upload($logoFile);
			$entity->setLogo($fileName);
		}
	}

	public function postLoad(LifecycleEventArgs $args)
	{
		$entity=$args->getEntity();
		$this->stringToFile($entity);
	}

	private function stringToFile($entity)
	{
		if(!$entity instanceof Device)
		{
			return;
		}

		if($fileName=$entity->getLogo())
		{
			$this->uploader->changeDirectory($this->params->get('device_img_folder'));
			$entity->setLogo(new File($this->uploader->getDirectory().'/'.$fileName));
		}
	}

	private function fileToString($entity)
	{
		if(!$entity instanceof Device)
		{
			return;
		}
		$logoFile=$entity->getLogo();

		if($logoFile instanceof File)
		{
			$entity->setLogo($logoFile->getFileName());
		}
	}

}
