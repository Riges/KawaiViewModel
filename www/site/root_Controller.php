<?php

require_once('Knb/Controller.php');
require_once('Knb/ConnectedUser.php');

class Root_Controller extends Knb_Controller
{
    public function getAction()
    {
        header("Location: " . ROOT_URL . "news/");
        $this->setTitle('Title for first page');
        $this->disablePageTitle();
        $this->setSmallBoxes(array(
            array('title' => 'Title', 'text' => 'Hello i am a <b>super</b> smallbox !'),
            array('title' => 'Second small', 'text' => 'Hello i am a <b>super</b> smallbox !')
        ));
    }

    public function getFullPageAction()
    {
        $this->setTitle('Full page test');
    }

    public function postLoginAction()
    {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $this->setParameter('error', null);
        try {
            Knb_ConnectedUser::login($login, $password);
            if ($this->getExtension() == 'html') $this->back();
        } catch (Exception $error) {
            $this->setParameter('error', $error);
        }
    }

    public function getLogoutAction()
    {
        $this->getConnectedUser()->logout();

        $this->setAutoViewEnabled(false);
        if ($this->getExtension() == 'html') $this->back();
    }
}

?>
