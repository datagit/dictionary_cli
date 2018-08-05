<?php
/**
 * Created by PhpStorm.
 * User: datdm
 * Date: 8/4/18
 * Time: 7:11 PM
 */

class Dictionary extends Repository
{

    public function addWord($params)
    {
        $params = array(
            'word' => $params['w'],
            'examples' => empty($params['e']) ? '' : $params['e'],
            'favorite' => isset($params['f']) ? boolval($params['f']) : false,
        );
        $word_info = $this->add($params);
        return $this->toJson($word_info);
    }

    public function editWord($params)
    {
        //check validation here
        $params = array(
            'word' => $params['w'],
            'examples' => empty($params['e']) ? '' : $params['e'],
            'favorite' => isset($params['f']) ? boolval($params['f']) : false,
        );
        $word_info = $this->edit($params);
        return $word_info;
    }

    public function addFavourite($params)
    {
        //check validation here
        $params = array(
            'word' => $params['w'],
            'favorite' => true,
        );
        $word_info = $this->edit($params);
        return $word_info;
    }

    public function removeFavourite($params)
    {
        $params = array(
            'word' => $params['w'],
            'favorite' => false,
        );
        $word_info = $this->edit($params);
        return $word_info;
    }

    public function listAll($params)
    {
        $params = array(
            'sort_type' => $params['s'],
        );
        $data = $this->getList($params);
        return $data;
    }

    public function listByFavourite($params)
    {
        $params = array(
            'sort_type' => $params['s'],
        );
        $data = $this->getList($params);
        return $data;
    }

    public function listByRecent()
    {

    }

    public function listByNoteRecent()
    {

    }

    public function searchByTerm($params)
    {
        //like
        //word or examples
        $params = array(
            'sort_type' => $params['s'],
            'term' => $params['t'],
        );
        $data = $this->findByTerm($params);
        return $data;
    }


    public function get_method($method){
        $object = $this;
        return function() use($object, $method){
            $args = func_get_args();
            return call_user_func_array(array($object, $method), $args);
        };
    }
}