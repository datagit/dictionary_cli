<?php
/**
 * Created by PhpStorm.
 * User: datdm
 * Date: 8/6/18
 * Time: 10:38 PM
 */
/*
"ab86a1e1ef70dff97959067b723c5c24": {
        "hit": 1,
        "c": "2018-08-07 10:09:11",
        "u": "2018-08-07 10:09:11",
        "word": "me",
        "hash_id": "ab86a1e1ef70dff97959067b723c5c24",
        "examples": [
            "m1"
        ],
        "favorite": true,
        "index": 2
    }

 $_word_info = array()
    init($params) return Word
    updateByParams($word_info, $params) return Word
 */
class Word
{
    private $_word_info = array();

    public function output($word_info)
    {
        return array(
            'w' => $word_info['word'],
            'e' => $word_info['examples'],
            'f' => $word_info['favorite'],
            'h' => $word_info['hit'],
            'c' => $word_info['c'],
            'u' => $word_info['u'],
            'i' => $word_info['index'],
        );
    }

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
        $word_info['u'] = $current_datetime;
        foreach ($params as $key => $value) {
            if ($key == 'word') {
                continue;
            }
            if ($key == 'examples') {
                $word_info[$key] = array_unique(array_merge($word_info[$key], $params[$key]));
            } else {
                $word_info[$key] = $value;
            }
        }
        $this->_word_info = $word_info;
        return $this->_word_info;
    }
}