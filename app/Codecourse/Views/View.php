<?php

namespace Codecourse\Views;

use Twig_Environment;
use Twig_Loader_Filesystem;

class View 
{

	protected $twig;
	public function __construct() 
	{
		$this->twig = new Twig_Environment(
			new Twig_Loader_Filesystem('views')
			);
	}

	public function render($view, array $data = []) 
	{
		return $this->twig->render($view, $data);
	}
}