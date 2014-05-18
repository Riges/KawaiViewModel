<?php
require_once('Knb/Controller.php');
require_once('fanficts_Model.php');

class Fanficts_Controller extends Knb_Controller
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $serviceContainer
     */
    public function __construct($serviceContainer) {
        parent::__construct($serviceContainer);
        Fanficts::setDatabase($this->getServiceContainer()->get('database'));
    }

	// GET knb.fr/fanficts/$user/$title
	public function getAction($user, $title)
	{
		$id = Fanficts::getId($user, $title);
		$this->setViewParameters(Fanficts::getOne($id));
	}
	
	// GET knb.fr/fanficts
	public function getSummaryAction()
	{
		$this->setParameter('last', $lastFicts = Fanficts::getLast(10));
		$this->setParameter('better', $betterFicts = Fanficts::getBetterVotes(10));
		$this->setParameter('mostActive', $mostActive = Fanficts::getMostActive(10));
	}
	
	// GET knb.fr/fanficts/$user
	public function getForuserAction($user)
	{
		
	}

	// GET knb.fr/fanficts/new
	public function getNewAction()
	{
		
	}
	
	// POST knb.fr/fanficts
	public function postAction()
	{
		
	}

	// GET knb.fr/fanficts/$user/$title/edit
	public function getEditAction($user, $title)
	{
		$this->setTitle('Modifier une fict');
		$this->setParameter('user', $user);
		$this->setParameter('title', $title);
	}
	
	// PUT knb.fr/fanficts/$user/$title
	// POST knb.fr/fanficts/$user/$title?_method=PUT
	public function putAction($user, $title)
	{
	}
	
	// DELETE knb.fr/fanficts/$user/$title
	// POST knb.fr/fanficts/$user/$title?_method=DELETE
	public function deleteAction($user, $title)
	{
		
	}
}

?>