<?php

require_once('Vbf/GuardProxy.php');

class Vbf_Guard
{
    public static function guardString($val, $func)
    {
        return call_user_func($func, $val);
    }

    public static function guardObject($obj, $func, $guardAny = true)
    {
        return new Vbf_GuardProxy($obj, $func, $guardAny);
    }

    public static function guardArray($array, $func, $guardAny = true)
    {
        $result = array();
        foreach ($array as $key => $val) {
            if ($guardAny) {
                $result[$key] = self::guardAny($val, $func);
            } else if (is_string($val)) {
                $result[$key] = self::guardString($val, $func);
            } else {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    public static function guardAny($val, $func)
    {
        if (is_string($val)) {
            return self::guardString($val, $func);
        } else if (is_array($val)) {
            return self::guardArray($val, $func);
        } else if (is_object($val)) {
            return self::guardObject($val, $func);
        } else {
            return $val;
        }
    }

    public static function guardAnyHtml($val)
    {
        return self::guardAny($val, array('Vbf_Guard', 'htmlentities'));
    }

    public static function guardAnyXml($val)
    {
        return self::guardAny($val, array('Vbf_Guard', 'xmlentities'));
    }

    public static function getHtmlentitiesFunc()
    {
        return array('Vbf_Guard', 'htmlentities');
    }

    public static function getXmlentitiesFunc()
    {
        return array('Vbf_Guard', 'xmlentities');
    }

    public static function htmlentities($s)
    {
        return htmlentities($s, ENT_QUOTES);
    }

    public static function xmlentities($s)
    {
        return str_replace(
            array('&', '<', '>', "'", '"'),
            array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'),
            $s);
    }
}

?>
