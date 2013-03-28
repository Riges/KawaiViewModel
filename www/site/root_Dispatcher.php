<?php

require_once('Vbf/Mvc/Dispatcher.php');

class Root_Dispatcher extends Vbf_Mvc_Dispatcher
{
    public function getDispatchRules()
    {
        return array(
            array('type' => 'action', 'params' => 0, 'url' => '', 'name' => '', 'method' => 'GET'),
            array('type' => 'action', 'params' => 0, 'url' => 'testFullPage', 'name' => 'fullPage', 'method' => 'GET'),
            array('type' => 'action', 'params' => 0, 'url' => 'login', 'name' => 'login', 'method' => 'POST'),
            array('type' => 'action', 'params' => 0, 'url' => 'logout', 'name' => 'logout', 'method' => 'GET'),

            array('type' => 'module', 'params' => 0, 'url' => 'fanficts', 'name' => 'fanficts'),
            array('type' => 'module', 'params' => 0, 'url' => 'admin', 'name' => 'admin'),
            array('type' => 'module', 'params' => 0, 'url' => 'users', 'name' => 'users'),
            array('type' => 'module', 'params' => 0, 'url' => 'news', 'name' => 'news')
        );
    }
}

?>