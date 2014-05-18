<?php
/**
 * Class Knb_FrontController
 */
class Knb_FrontController extends Vbf_Mvc_FrontController
{
    /**
     *
     */
    public function __construct($serviceContainer, $debug)
    {
        parent::__construct(ROOT_PATH . 'site/', ROOT_URL, "utf-8", $debug, $serviceContainer);
    }

    /**
     *
     */
    public function on404()
    {
        header("HTTP/1.0 404 Not Found", true, 404);
        require_once(ROOT_PATH . '/error_docs/404.php');
    }
}