<?php
/**
 * Class Knb_Error
 */
class Knb_Error extends Exception
{
    /**
     * @param string $msg
     */
    public function __construct($msg)
    {
        parent::__construct($msg);
    }
}