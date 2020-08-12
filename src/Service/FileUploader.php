<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
	private  $directory;

	public function __construct($directory)
	{
		$this->directory=$directory;
	}

	public function changeDirectory($value)
	{
		$this->directory=$value;
	}

	public function getDirectory()
	{
		return $this->directory;
	}


	public function upload(UploadedFile $file) : string
	{
		$fileName=\bin2hex(\random_bytes(10)).'.'.$file->guessExtension();
		$file->move($this->directory,$fileName);
		return $fileName;
	}

	public function uploadNew(UploadedFile $file)
	{
		return $this->upload($file);
	}
}
