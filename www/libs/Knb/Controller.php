<?php

require_once('Vbf/Mvc/Controller.php');

class Knb_Controller extends Vbf_Mvc_Controller
{
	private $autoViewEnabled = TRUE;
	
	protected function getAutoViewEnabled() { return $this->autoViewEnabled; }
	protected function setAutoViewEnabled($value) { $this->autoViewEnabled = $value; }
	
	protected function getConnectedUser()
	{
		global $g_user;
		return $g_user;
	}
	
	protected function getDatabaseConnection()
	{
		global $g_database;
		return $g_database;
	}

	protected function disablePageTitle()
	{
		$this->setViewParameter('/__globalViews/main', 'noPageTitle', TRUE);
	}
	
	protected function setSmallBoxes($smallboxes)
	{
		$this->setViewParameter('/__globalViews/main', 'smallboxes', $smallboxes);
	}
	
	protected function setTitle($title)
	{
		$this->setViewParameter('/__globalViews/main', 'title', $title);
	}
	
	public function onBefore()
	{
		$this->setViewParameter('/__globalViews/main', 'title', '');
	}

	public function onAfter()
	{
		if ($this->getAutoViewEnabled())
		{
			$this->setViewParameter('/__globalViews/main', 'body', $this->renderView(''));
			$this->setViewParameter('/__globalViews/main', 'submenu', $this->getSubMenu());
			$this->setViewParameter('/__globalViews/main', 'leftCol', $this->getLeftCol());
			$this->setViewParameter('/__globalViews/main', 'rightCol', $this->getRightCol());
			$this->displayView('/__globalViews/main');
		}
	}

	protected function setMainViewParameter($title, $value)
	{
		$this->setViewParameter('/__globalViews/main', $title, $value);
	}
	
	protected function getSubmenu()
	{
		return array();
	}
	
	protected function getLeftCol()
	{
		return '';
	}
	
	protected function getRightCol()
	{
		
		/*if ($this->getExtension() != 'html')
			return '';
		Zend_Loader::loadClass('Zend_View');
		$view = new Zend_View();
		$views = new Vbf_Mvc_View($this->getFrontController(), ROOT_PATH.'site/rightColView.html.php');
		$view->addScriptPath(ROOT_PATH.'site/');
		$view->views = $views;
		$view->keywords = $this->getKeywords();
		return $view->render('rightColView.html.php');*/
		return '';
	}
}

?>
