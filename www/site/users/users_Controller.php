<?php
require_once('Knb/Error.php');
require_once('Knb/Controller.php');
require_once('users_Model.php');

class Users_Controller extends Knb_Controller
{
	private static function getUserAsArrayOr404($username)
	{
		$userArray = Users::getUserAsArray($username);
		if ($userArray === NULL) throw new Vbf_Mvc_Exception404("Unknown user : $username");
		return $userArray;
	}
	
	// GET /users/
	public function getListAction()
	{
		$this->setTitle('Utilisateurs');
		$this->setParameter('users', Users::getList());
		$count = Users::getCount();
	}
	
	// GET /users/{username}/
	public function getOneuserAction($username)
	{
		$userArray = self::getUserAsArrayOr404($username);
		
		$this->setTitle("Utilisateur {$userArray['user_full_name']}");
		$this->setParameters($userArray);
		
		$this->setMainViewParameter('noFullText', TRUE);
	}
	
	// GET /users/{username}/edit
	public function getEditAction($username)
	{
		$this->getConnectedUser()->assertRight('user_edit');
		
		$userArray = self::getUserAsArrayOr404($username);
		$this->setTitle("Modifier l'utilisateur {$userArray['user_full_name']}");
		$this->setParameters($userArray);
	}
	
	// GET /users/new
	public function getNewAction()
	{
		$this->getConnectedUser()->assertRight('user_create');
	}
	
	// PUT /users/{username}
	public function putAction($user_login)
	{
		$this->getConnectedUser()->assertRight('user_edit');
		
		if ( ($_POST['user_password_1'] == '') && ($_POST['user_password_2'] == '') )
		{
			Users::updateOneWithoutPassword(Users::getId($user_login), $_POST);			
		}
		else if ($_POST['user_password_1'] == $_POST['user_password_2'])
		{
			Users::updateOneWithPassword(Users::getId($user_login), $_POST, $_POST['user_password_1']);
		}
		else
		{
			//FIXME: Trouver un moyen pour que ça fasse un retour sur la page précédente en mettant l'erreur en haut et en gardant les valeurs entrées par l'utilisateur.
			throw new Knb_Error('The two provided passwords should match.');
		}
		$this->setParameter('user_login', $_POST['user_login']);
	}
	
	// POST /users
	public function postAction()
	{
		$this->getConnectedUser()->assertRight('user_create');
		if (Users::exists($_POST['user_login']))
		{
			throw new Knb_Error("An user with the name ${$_POST['user_login']} already exists");
		}
		Users::create($_POST);
	}
	
	// GET /users/{user_login}/delete
	public function getDeleteAction($user_login)
	{
		$this->getConnectedUser()->assertRight('user_delete');
		// TODO: Ask if we should _really_ remove it.
	}

	// DELETE /users/{user_login}
	public function deleteAction($user_login)
	{
		$this->getConnectedUser()->assertRight('user_delete');

		Users::deleteOne(Users::getId($user_login));
	}
	
	public function getSubmenu()
	{
		global $g_user;
		$links = array();
		if ($g_user->haveRight('user_create')) $links['Ajouter'] = 'new';
		$links['Voir la liste'] = '';

		return array('Options' => $links);
	}
}

?>
