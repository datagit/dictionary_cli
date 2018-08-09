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
	
	public function create(array $params)
	{
		if (empty($params)) {
			return array();
		}
		foreach($this->_fields as $key) {
			$fields[$key] = $params[$key];
		}
		return $fields;
	}
	
	public function update(array $fields, array $params)
	{
		if (empty($params)) {
			return array();
		}
		foreach($this->_fields as $key) {
			switch(gettype($params[$key])) {
				case 'array': 
					$fields[$key] = array_unique(array_merge($fields[$key], $params[$key]));
					break;
				case 'string':
				case 'integer':
				case 'NULL': 
				case 'boolean': 
				case 'double': 
					$fields[$key] = $params[$key];
					break;
			}
		}
		return $fields;
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

