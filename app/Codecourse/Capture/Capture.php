<?php

namespace Codecourse\Capture;

use Codecourse\Views\View;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\Response;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Capture
{

	protected $view;
	protected $pdf;
	protected $fileType;

	public function __construct() 
	{
		$this->view = new View;
	}

	public function load($filename, array $data = [], $fileType = 'pdf')
	{
		$this->fileType = $fileType;
		$view = $this->view->render($filename, $data);

		$this->pdf = $this->captureImage($view);
	}

	protected function captureImage($view)
	{
		$path = $this->writeFile($view);

		$this->phantomProcess($path)->setTimeout(100)->mustRun();

		return $path;
	}

	protected function writeFile($view)
	{

		if($this->fileType == 'pdf') {

			file_put_contents($path = 'storage/' . md5(uniqid()) . '.pdf', $view);

		} else {

			file_put_contents($path = 'storage/' . md5(uniqid()) . '.doc', $view);
		}

		return $path;
	}

	protected function phantomProcess($path)
	{
		return new Process('phantomjs capture.js ' . $path);
	}

	public function respond($filename, $fileType = 'pdf') 
	{

		$this->fileType = $fileType;
		$contentType = 'application/msword';

		if($this->fileType == 'pdf') {
			$contentType = 'application/pdf';
		}
		$response = new Response(file_get_contents($this->pdf),  200, [
			'Content-Description' => 'File Transfer',
			'Content-Disposition' => 'attachment; filename="'. $filename .'"',
			'Content-Transfer-Encoding' => 'binary',
			'Content-Type' => $contentType,
		]);
		
		unlink($this->pdf);

		$response->send();

	}
}
