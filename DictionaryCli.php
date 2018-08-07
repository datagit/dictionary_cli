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
        //check validation here
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
        //check validation here
        $params = array(
            'word' => $params['w'],
            'examples' => empty($params['e']) ? array() : array($params['e']),
            'favorite' => isset($params['f']) ? boolval($params['f']) : false,
        );
        $d = new Dictionary();
        return $d->editWord($params);
    }

    public function get($params)
    {
        //check validation here
        $d = new Dictionary();
        return $d->getList($params['s']);
    }

    public function find($params)
    {
        //check validation here
        $d = new Dictionary();
        return $d->findByTerm($params['w'], $params['s']);
    }
}