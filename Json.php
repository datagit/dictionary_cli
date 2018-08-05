<?php
/**
 * Created by PhpStorm.
 * User: datdm
 * Date: 8/6/18
 * Time: 5:28 AM
 */

class Json
{
    const DATA_FILE = 'words_examples.json';
    private $_json_data = '';

    /**
     * Json constructor.
     */
    public function __construct()
    {
        $this->_json_data = file_get_contents(self::DATA_FILE);
    }
    public function toArray()
    {
        return json_decode($this->_json_data, true);
    }
    private function _updateJsonDataFrom($data_array)
    {
        $this->_json_data = $this->toJson($data_array);
        return $this;
    }
    public function save($data_array)
    {
        $this->_updateJsonDataFrom($data_array);
        if(!file_put_contents(self::DATA_FILE, $this->_json_data)) {
            return false;
        }
        return true;
    }
    public function toJson(array $data_array)
    {
        if (empty($data_array)) {
            return '';
        }
        return json_encode($data_array, JSON_PRETTY_PRINT);
    }
}