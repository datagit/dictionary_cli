<?php
/**
 * Created by PhpStorm.
 * User: datdm
 * Date: 8/6/18
 * Time: 10:38 PM
 */
/*
"hash_id": {
        "index": 1,
        "hit": 1,
        "favorite": true,
        "c": "2018-08-05 23:00:00",
        "u": "2018-08-05 23:00:00",
        "word": "wwww",
        "example": [
            "e1",
            "e2"
        ],
    }

 $_word_info = array()
    init($params) return Word
    updateByParams($word_info, $params) return Word
 */
class Word
{
    private $_word_info = array();

    public function generateHashId($term)
    {
        if (empty(trim($term))) {
            throw new Exception('Please input term');
        }
        return md5(strtolower(trim($term)));
    }

    public function init(array $params)
    {
        $this->_word_info['hit'] = 1;
        $dt = new DateTime();
        $current_datetime = $dt->format('Y-m-d H:i:s');
        $this->_word_info['c'] = $current_datetime;
        $this->_word_info['u'] = $current_datetime;
        foreach ($params as $key => $value) {
            $this->_word_info[$key] = $value;
            if ($key == 'word') {
                $this->_word_info['hash_id'] = $this->generateHashId($value);
            }
        }
        return $this->_word_info;
    }

    public function mergeParams(array $word_info, array $params = array())
    {
        $word_info['hit']++;
        $dt = new DateTime();
        $current_datetime = $dt->format('Y-m-d H:i:s');
        $this->_word_info['u'] = $current_datetime;
        foreach ($params as $key => $value) {
            if ($key == 'word') {
                continue;
            }
            $word_info[$key] = $value;
            if ($key == 'examples') {
                $word_info['examples'] = array_unique(array_merge($word_info['examples'], $params['examples']));
            }
        }
        $this->_word_info = $word_info;
        return $this->_word_info;
    }
}