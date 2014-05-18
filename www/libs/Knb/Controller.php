<?php
/**
 * Class Knb_Controller
 */
class Knb_Controller extends Vbf_Mvc_Controller
{
    /**
     * @var bool
     */
    private $autoViewEnabled = TRUE;

    /**
     * @return bool
     */
    protected function getAutoViewEnabled()
    {
        return $this->autoViewEnabled;
    }

    /**
     * @param $value
     */
    protected function setAutoViewEnabled($value)
    {
        $this->autoViewEnabled = $value;
    }


    /**
     * @return Knb_ConnectedUser
     */
    protected function getConnectedUser()
    {
        return $this->getServiceContainer()->get('connectedUser');
    }

    /**
     * @return Zend_Db_Adapter_Mysqli
     */
    protected function getDatabaseConnection()
    {
        return $this->getServiceContainer()->get('database_user');
    }

    /**
     *
     */
    protected function disablePageTitle()
    {
        $this->setViewParameter('/__globalViews/main', 'noPageTitle', TRUE);
    }

    /**
     * @param $smallboxes
     */
    protected function setSmallBoxes($smallboxes)
    {
        $this->setViewParameter('/__globalViews/main', 'smallboxes', $smallboxes);
    }

    /**
     * @param $title
     */
    protected function setTitle($title)
    {
        $this->setViewParameter('/__globalViews/main', 'title', $title);
    }

    /**
     *
     */
    public function onBefore()
    {
        $this->setViewParameter('/__globalViews/main', 'title', '');
    }

    /**
     *
     */
    public function onAfter()
    {
        if ($this->getAutoViewEnabled()) {
            $this->setViewParameter('/__globalViews/main', 'body', $this->renderView(''));
            $this->setViewParameter('/__globalViews/main', 'submenu', $this->getSubMenu());
            $this->setViewParameter('/__globalViews/main', 'leftCol', $this->getLeftCol());
            $this->setViewParameter('/__globalViews/main', 'rightCol', $this->getRightCol());
            $this->setViewParameter('/__globalViews/main', 'connectedUser', $this->getServiceContainer()->get('connectedUser'));
            $this->displayView('/__globalViews/main');
        }
    }

    /**
     * @param $title
     * @param $value
     */
    protected function setMainViewParameter($title, $value)
    {
        $this->setViewParameter('/__globalViews/main', $title, $value);
    }

    /**
     * @return array
     */
    protected function getSubmenu()
    {
        return array();
    }

    /**
     * @return string
     */
    protected function getLeftCol()
    {
        return '';
    }

    /**
     * @return string
     */
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