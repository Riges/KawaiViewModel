<?php
require_once('Knb/Error.php');
require_once('Knb/Controller.php');
require_once('news_Model.php');

class News_Controller extends Knb_Controller
{
	private static function getNewsAsArrayOr404($year, $month, $day, $url_title)
	{
		$newsArray = News::getNewsAsArray($year, $month, $day, $url_title);
		if ($newsArray === NULL) throw new Vbf_Mvc_Exception404("Unknown news : $url_title");
		return $newsArray;
	}
	
	// GET /users/
	public function getListAction()
	{
		$this->setTitle('News');
		$this->setParameter('news', News::getList(TRUE));
	}
	
	// GET /news/{year}/{month}/{day}/{url}/
	public function getOnenewsAction($year, $month, $day, $url_title)
	{
		$newsArray = self::getNewsAsArrayOr404($year, $month, $day, $url_title);
		$this->setTitle('News : '.$newsArray['news_title']);
		$this->setParameters($newsArray);
	}
	
	// GET /users/{username}/edit
	public function getEditAction($username)
	{
		$userArray = self::getUserAsArrayOr404($username);
		$this->setTitle("Modifier utilisateur : $username");
		$this->setParameters($userArray);
	}
	
	// GET /news/new
	public function getNewAction()
	{
		$this->getConnectedUser()->assertRight('news_create');
		
		$this->setTitle("Ajout d'une news");
	}
	
	// PUT /users/{username}
	public function putAction($user_login)
	{
		Users::updateOne(Users::getId($user_login), $_POST);
		$this->setParameter('user_login', $_POST['user_login']);
	}
	
	// POST /news
	public function postAction()
	{
		// Create the new news	
		$this->getConnectedUser()->assertRight('news_create');
		$_POST['news_title_url'] = urlencode($_POST['news_title']);
		if(!array_key_exists("news_date",$_POST))
		{
			$_POST["news_date"] = time();	
		}
		else
		{
			list($day, $month, $year) = split('[/.-]', $_POST["news_date"]);
			$_POST["news_date"] = mktime("0", "0", "0", $month, $day, $year);
			
		}
		if(!array_key_exists("news_publish",$_POST))
		{
			$_POST["news_publish"] = 0;
		}
		else
		{
			if($_POST["news_publish"] == "on")
			{
				$_POST["news_publish"]=1;
			}
			else
			{
				$_POST["news_publish"]=0;
			}
		}
		if (News::exists($_POST))
		{
			throw new Knb_Error("An news with the name ${$_POST['news_title']} already exists for date this date");
		}
		News::create($_POST);
		$this->setParameter("date", date("Y/m/d", $_POST["news_date"]));
		$this->setParameter("news_title_url", $_POST['news_title_url']);
	}
	
	// GET /users/{user_login}/delete
	public function getDeleteAction($user_login)
	{
		// Ask if we should _really_ remove it.
	}

	// DELETE /users/{user_login}
	public function deleteAction($user_login)
	{
		// Remove the user
	}
}

?>