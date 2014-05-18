<?php

/**
 * Class Vbf_Guard
 */
class Vbf_Guard
{
    /**
     * @param $val
     * @param $func
     * @return mixed
     */
    public static function guardString($val, $func)
    {
        return call_user_func($func, $val);
    }

    /**
     * @param $obj
     * @param $func
     * @param bool $guardAny
     * @return Vbf_GuardProxy
     */
    public static function guardObject($obj, $func, $guardAny = true)
    {
        return new Vbf_GuardProxy($obj, $func, $guardAny);
    }

    /**
     * @param $array
     * @param $func
     * @param bool $guardAny
     * @return array
     */
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

    /**
     * @param $val
     * @param $func
     * @return array|mixed|Vbf_GuardProxy
     */
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

    /**
     * @param $val
     * @return array|mixed|Vbf_GuardProxy
     */
    public static function guardAnyHtml($val)
    {
        return self::guardAny($val, array('Vbf_Guard', 'htmlentities'));
    }

    /**
     * @param $val
     * @return array|mixed|Vbf_GuardProxy
     */
    public static function guardAnyXml($val)
    {
        return self::guardAny($val, array('Vbf_Guard', 'xmlentities'));
    }

    /**
     * @return array
     */
    public static function getHtmlentitiesFunc()
    {
        return array('Vbf_Guard', 'htmlentities');
    }

    /**
     * @return array
     */
    public static function getXmlentitiesFunc()
    {
        return array('Vbf_Guard', 'xmlentities');
    }

    /**
     * @param $s
     * @return string
     */
    public static function htmlentities($s)
    {
        return htmlentities($s, ENT_QUOTES);
    }

    /**
     * @param $s
     * @return mixed
     */
    public static function xmlentities($s)
    {
        return str_replace(
            array('&', '<', '>', "'", '"'),
            array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'),
            $s);
    }
}