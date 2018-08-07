<?php
/**
 * Created by PhpStorm.
 * User: datdm
 * Date: 8/6/18
 * Time: 11:26 PM
 */

class DictionaryCli
{
    public function a($params)
    {
        return $this->add($params);
    }

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

    public function e($params)
    {
        return $this->edit($params);
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

    public function g($params)
    {
        return $this->get($params);
    }

    public function get($params)
    {
        //check validation here
        $d = new Dictionary();
        return $d->getList($params['s']);
    }

    public function f($params)
    {
        return $this->find($params);
    }

    public function find($params)
    {
        //check validation here
        $d = new Dictionary();
        return $d->findByTerm($params['w'], $params['s']);
    }
}