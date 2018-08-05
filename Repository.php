<?php
/**
 * Created by PhpStorm.
 * User: datdm
 * Date: 8/4/18
 * Time: 4:24 PM
 */

class Repository
{
    const LIMIT = 5;
    const DATA_FILE = 'words_examples.json';
    private $_words_examples = array();

    /**
     * Repository constructor
     */
    public function __construct()
    {
        $this->_words_examples = $this->_init();
    }

    private function _init()
    {
        $array = $this->_toArray(file_get_contents(self::DATA_FILE));
        if (empty($array)) {
            return array();
        }
        return $array;
    }

    private function _toArray($json_data)
    {
        return json_decode($json_data, true);
    }

    public function toJson(array $data)
    {
        if (empty($data)) {
            return '';
        }
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    private function _save()
    {
        $json_data = $this->toJson($this->_words_examples);
        if(!file_put_contents(self::DATA_FILE, $json_data)) {
            return false;
        }
        return true;
    }

    private function _update($id, $word_info)
    {
        $this->_words_examples['ids'][$word_info['hash_id']] = $id;
        unset($word_info['hash_id']);
        $this->_words_examples[$id] = $word_info;
        $this->_save();
        return $word_info;
    }

    private function generateHashId($term)
    {
        return md5(strtolower(trim($term)));
    }

    private function _getLastNumberId()
    {
        if (empty($this->_words_examples['ids'])) {
            return 1;
        }
        return (count($this->_words_examples['ids']) + 1);
    }

    private function _findNumberIdByHashId($hash_id)
    {
        if (empty($this->_words_examples['ids'][$hash_id])) {
            return 1;
        }
        return $this->_words_examples['ids'][$hash_id];
    }

    private function _findByHashId($hash_id)
    {
        if (!empty($this->_words_examples['ids'][$hash_id]) && !empty($this->_words_examples[$this->_words_examples['ids'][$hash_id]])) {
            $output = $this->_words_examples[$this->_words_examples['ids'][$hash_id]];
            $output['hash_id'] = $hash_id;
            return $output;
        }
        unset($this->_words_examples['ids'][$hash_id]);
        unset($this->_words_examples[$this->_words_examples['ids'][$hash_id]]);
        return array();
    }

    private function findExactWord($word)
    {
        $hash_id = $this->generateHashId($word);
        $word_info = $this->_findByHashId($hash_id);
        return array($hash_id, $word_info);
    }

    private function _createWordInfo($params = array(), $id = 0)
    {
        $word_info = array();
        if (empty($params)) {
            return $word_info;
        }
        if (empty($id)) {
            $id = $this->_getLastNumberId();
        }
        $word_info['favourite'] = false;
        $word_info['hit'] = 1;
        $dt = new DateTime();
        $current_datetime = $dt->format('Y-m-d H:i:s');
        $word_info['datetime_hit'] = $current_datetime;
        $word_info['datetime_created'] = $current_datetime;
        $word_info['datetime_updated'] = $current_datetime;
        foreach ($params as $key => $value) {
            $word_info[$key] = $value;
            if ($key == 'word') {
                $word_info['hash_id'] = $this->generateHashId($value);
            }
        }
        return array($id, $word_info);
    }

    private function _appendExample($examples, $new_example)
    {
        $examples = array_unique(array_merge($examples, array($new_example)));
        return $examples;
    }

    public function add($params = array())
    {
        //find by id
        list($hash_id, $word_info) = $this->findExactWord($params['word']);
        $id = 0;
        if (!empty($params['favourite'])) {
            $append_info['favourite'] = $params['favourite'];
        }
        if (empty($word_info)) {
            //add new word
            $append_info['word'] = $params['word'];
            if (!empty($params['examples'])) {
                $append_info['examples'] = array($params['examples']);
            }
        } else {
            //add more examples
            $id = $this->_findNumberIdByHashId($hash_id);
            if (!empty($params['examples'])) {
                $examples = $this->_appendExample($word_info['examples'], $params['examples']);
                $append_info['examples'] = $examples;
            }
            $append_info['hit'] = $word_info['hit'] + 1;
            $append_info = array_merge($word_info, $append_info);
        }
        list($id, $word_info) = $this->_createWordInfo($append_info, $id);
        //save
        $word_info = $this->_update($id, $word_info);
        //return this item
        return $word_info;
    }

    public function edit($params = array())
    {
        if (empty($params['word'])) {
            return array();
        }
        //find by id
        list($hash_id, $word_info) = $this->findExactWord($params['word']);
        if (empty($word_info)) {
            //create new word_info
            $word_info = $this->add($params['word'], $params['examples']);
        }
        //update fields
        if (!empty($params['examples'])) {
            $word_info['examples'] = $this->_appendExample($word_info['examples'], $params['examples']);
        }
        if (!empty($params['word'])) {
            $word_info['word'] = $params['word'];
        }
        if (isset($params['favourite'])) {
            $word_info['favourite'] = boolval($params['favourite']);
        }
        $word_info['hit'] = $word_info['hit'] + 1;
        //save
        $id = $this->_findNumberIdByHashId($hash_id);
        $word_info = $this->_update($id, $word_info);
        //return new word_info
        return $word_info;
    }

    public function findByTerm($params = array())
    {
        //search like
        $temp = $this->_words_examples;
        unset($temp['ids']);
        foreach ($temp as $id => $values) {
            $is_match = false;
            if (preg_match(sprintf('/%s/i', $params['term']), $values['word'])) {
                $is_match = true;
            } else {
                foreach ($values['examples'] as $example) {
                    if (preg_match(sprintf('/^%s/i', $params['term']), $example)) {
                        $is_match = true;
                        break;
                    }
                }
            }
            if (!$is_match) {
                unset($temp[$id]);
            }
        }
        $temp = $this->_sortBy($temp, $params['sort_type']);
        $temp = array_slice($temp, 0, self::LIMIT);
        return $temp;
    }



    public function getList($params = array())
    {
        $temp = $this->_words_examples;
        unset($temp['ids']);
        $temp = $this->_sortBy($temp, $params['sort_type']);
        $temp = array_slice($temp, 0, self::LIMIT);
        return $temp;
    }

    public function getFavourite($params = array())
    {
        $temp = $this->_words_examples;
        unset($temp['ids']);
        $temp = $this->_filterFavourite($temp);
        $temp = $this->_sortBy($temp, $params['sort_type']);
        $temp = array_slice($temp, 0, self::LIMIT);
        return $temp;
    }

    private function _filterFavourite($data)
    {
        foreach ($data as $id => $values) {
            if ($values['favourite'] == false) {
                unset($data[$id]);
            }
        }
        return $data;
    }

    private function _sortBy($data, $sort_type)
    {
        $favourite  = array_column($data, 'favourite');
        $hit  = array_column($data, 'hit');
        $word  = array_column($data, 'word');

        switch ($sort_type) {
            case 1:
                array_multisort($favourite, SORT_ASC, $hit, SORT_DESC, $word, SORT_ASC, $data);
                break;
            case 2:
                array_multisort($hit, SORT_DESC, $favourite, SORT_ASC, $word, SORT_ASC, $data);
                break;
            case 3:
                array_multisort($word, SORT_ASC, $favourite, SORT_ASC, $hit, SORT_DESC, $data);
                break;
        }
        return $data;
    }




}