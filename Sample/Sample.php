<?php
#/usr/bin/php
#/usr/bin/php /Users/daomanhdat/Desktop/my_temp/test.php

class Sample 
{
	private $_fields = array(
		'id',		//integer
		'name',		//string
		'email',	//string
		'sex',		//boolean
		'habits',	//array
		'birthday',	//datatetime
		'salary',	//double
		'created',	//datetime
		'updated',	//datetime
	);

	private $_data = array();
	
	public function create(array $params)
	{
		if (empty($params)) {
			return array();
		}
		foreach($this->_fields as $key) {
            $this->_data[$key] = $params[$key];
		}
		return $this->_data;
	}
	
	public function update(array $fields, array $params)
	{
		if (empty($params)) {
			return array();
		}
		foreach($this->_fields as $key) {
			switch(gettype($params[$key])) {
				case 'array':
				    //append
                    $this->_data[$key] = array_unique(array_merge($fields[$key], $params[$key]));
					break;
				case 'string':
				case 'integer':
				case 'NULL': 
				case 'boolean': 
				case 'double':
                $this->_data[$key] = $params[$key];
					break;
			}
		}
		return $this->_data;
	} 
}

$s = new Sample();
var_dump($s->create(
	array(
	'id'	=> 1,		//integer
	'name'	=> 'nnnnnnn',		//string
	'email' => 'email@eamil.email',	//string
	'sex'	=> true,		//boolean
	'habits'	=> array("beer", 'football', 'grils'),	//array
	'birthday' => DateTime::createFromFormat('j-M-Y', '15-Feb-2009'),	//datatetime
	'salary'	=> 1234.98,	//double
	'created'	=> new DateTime('NOW'),	//datetime
	'updated'	=> new DateTime('NOW'),	//datetime
	'abc'		=> 'abc',
	)
));

