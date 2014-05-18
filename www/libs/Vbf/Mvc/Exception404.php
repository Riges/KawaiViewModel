<?php

/**
 * Class Vbf_Mvc_Exception404
 */
class Vbf_Mvc_Exception404 extends Exception
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}