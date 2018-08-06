<?php
/**
 * Created by PhpStorm.
 * User: datdm
 * Date: 8/6/18
 * Time: 11:26 PM
 */

class DictionaryCli
{
    public function add($params)
    {
        $params = array(
            'word' => $params['w'],
            'examples' => empty($params['e']) ? array() : array($params['e']),
            'favorite' => isset($params['f']) ? boolval($params['f']) : false,
        );
        $d = new Dictionary();
        return $d->addWord($params);
    }

    public function edit($params)
    {
        $params = array(
            'word' => $params['w'],
            'examples' => empty($params['e']) ? array() : array($params['e']),
            'favorite' => isset($params['f']) ? boolval($params['f']) : false,
        );
        $d = new Dictionary();
        return $d->editWord($params);
    }

    public function getMethod($method){
        $object = $this;
        return function() use($object, $method){
            $args = func_get_args();
            return call_user_func_array(array($object, $method), $args);
        };
    }
}