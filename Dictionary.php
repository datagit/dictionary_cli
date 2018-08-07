<?php
/**
 * Created by PhpStorm.
 * User: datdm
 * Date: 8/6/18
 * Time: 10:56 PM
 */
class Dictionary extends Json
{
    const LIMIT = 5;
    private $_word_info_list = array();

    /**
     * Dictionary constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_word_info_list = $this->toArray();
    }

    private function _isExisted($word_info)
    {
        if (empty($this->_word_info_list)) {
            return false;
        }
        return isset($this->_word_info_list[$word_info['hash_id']]);
    }

    private function _getByHashId($hash_id) {
        if (isset($this->_word_info_list[$hash_id])) {
            return $this->_word_info_list[$hash_id];
        }
        return false;
    }

    private function _getIndex()
    {
        if (empty($this->_word_info_list)) {
            return 1;
        }
        return count($this->_word_info_list) + 1;
    }

    private function _sortBySortType($data, $sort_type)
    {
        $favorite  = array_column($data, 'favorite');
        $hit  = array_column($data, 'hit');
        $word  = array_column($data, 'word');
        $index  = array_column($data, 'index');
        $updated  = array_column($data, 'u');

        switch ($sort_type) {
            case 1:
                array_multisort($favorite, SORT_ASC, $hit, SORT_DESC, $word, SORT_ASC, $index, SORT_ASC, $updated, SORT_ASC, $updated, SORT_ASC, $data);
                break;
            case 2:
                array_multisort($word, SORT_ASC, $favorite, SORT_ASC, $hit, SORT_DESC, $index, SORT_ASC, $updated, SORT_ASC, $updated, SORT_ASC, $data);
                break;
            case 3:
                array_multisort($index, SORT_ASC, $favorite, SORT_ASC, $hit, SORT_DESC, $word, SORT_ASC, $index, SORT_ASC, $updated, SORT_ASC, $data);
                break;
            case 4:
                array_multisort($updated, SORT_ASC,$favorite, SORT_ASC, $hit, SORT_DESC, $word, SORT_ASC, $index, SORT_ASC, $updated, SORT_ASC, $data);
                break;
            default:
                array_multisort($hit, SORT_DESC, $favorite, SORT_ASC, $word, SORT_ASC, $index, SORT_ASC, $updated, SORT_ASC, $updated, SORT_ASC, $data);
                break;
        }
        return $data;
    }


    public function editWord($params)
    {
        if (empty($params)) {
            return array();
        }
        $word = new Word();
        $hash_id = $word->generateHashId($params['word']);
        $word_info = $this->_getByHashId($hash_id);
        if (empty($word_info)) {
            return array();
        }
        $word_info['hash_id'] = $hash_id;
        $word_info = $word->mergeParams($word_info, $params);
        $this->_save($word_info);
        return $word->output($word_info);
    }

    private function _save($word_info)
    {
        $hash_id = $word_info['hash_id'];
        $this->_word_info_list[$hash_id] = $word_info;
        return $this->save($this->_word_info_list);
    }

    public function addWord($params)
    {
        $word = new Word();
        $word_info = $word->init($params);
        if (!$this->_isExisted($word_info)) {
            $word_info['index'] = $this->_getIndex();
        } else {
            $word_info = $word->mergeParams($word_info, $params);
        }
        $this->_save($word_info);
        return $word->output($word_info);
    }

    public function getList($sort_type)
    {
        $word_info_list = $this->_word_info_list;
        $word_info_list = $this->_sortBySortType($word_info_list, $sort_type);
        $word_info_list = array_slice($word_info_list, 0, self::LIMIT);
        $word = new Word();
        $output = array();
        foreach ($word_info_list as $word_info) {
            $output[] = $word->output($word_info);
        }
        return $output;
    }

    public function findByTerm($term, $sort_type)
    {
        //search like
        $word_info_list = $this->_word_info_list;
        foreach ($word_info_list as $id => $values) {
            $is_match = false;
            if (preg_match(sprintf('/%s/i', $term), $values['word'])) {
                $is_match = true;
            } else {
                foreach ($values['examples'] as $example) {
                    if (preg_match(sprintf('/^%s/i', $term), $example)) {
                        $is_match = true;
                        break;
                    }
                }
            }
            if (!$is_match) {
                unset($word_info_list[$id]);
            }
        }
        $word_info_list = $this->_sortBySortType($word_info_list, $sort_type);
        $word_info_list = array_slice($word_info_list, 0, self::LIMIT);
        $word = new Word();
        $output = array();
        foreach ($word_info_list as $word_info) {
            $output[] = $word->output($word_info);
        }
        return $output;
    }

}