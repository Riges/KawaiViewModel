<?php

class Vbf_GuardProxy
{
    protected $obj;
    protected $func;
    protected $guardAny;

    public function __construct($obj, $func, $guardAny)
    {
        $this->obj = $obj;
        $this->func = $func;
        $this->guardAny = $guardAny;
    }

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

    public function __set($var, $val)
    {
        throw new Exception('Proxy objects are read only.');
    }

    public function __call($name, $args)
    {
        throw new Exception('No method call on proxy objects.');
    }

    public function __unset($var)
    {
        throw new Exception('not supported');
    }

    public function __isset($var)
    {
        throw new Exception('not supported');
    }
}

?>