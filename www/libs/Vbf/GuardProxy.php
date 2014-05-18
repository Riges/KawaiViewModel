<?php

/**
 * Class Vbf_GuardProxy
 */
class Vbf_GuardProxy
{
    /**
     * @var
     */
    protected $obj;
    /**
     * @var
     */
    protected $func;
    /**
     * @var
     */
    protected $guardAny;

    /**
     * @param $obj
     * @param $func
     * @param $guardAny
     */
    public function __construct($obj, $func, $guardAny)
    {
        $this->obj = $obj;
        $this->func = $func;
        $this->guardAny = $guardAny;
    }

    /**
     * @param $var
     * @return array|mixed|Vbf_GuardProxy
     */
    public function __get($var)
    {
        $val = $this->obj->$var;
        if ($this->guardAny) {
            $val = Vbf_Guard::guardAny($val, $this->func);
        } else if (is_string($val)) {
            $val = Vbf_Guard::guardString($val, $this->func);
        }
        return $val;
    }

    /**
     * @param $var
     * @param $val
     * @throws Exception
     */
    public function __set($var, $val)
    {
        throw new Exception('Proxy objects are read only.');
    }

    /**
     * @param $name
     * @param $args
     * @throws Exception
     */
    public function __call($name, $args)
    {
        throw new Exception('No method call on proxy objects.');
    }

    /**
     * @param $var
     * @throws Exception
     */
    public function __unset($var)
    {
        throw new Exception('not supported');
    }

    /**
     * @param $var
     * @throws Exception
     */
    public function __isset($var)
    {
        throw new Exception('not supported');
    }
}