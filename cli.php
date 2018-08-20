<?php
/**
 * Created by PhpStorm.
 * User: daomanhdat
 * Date: 8/7/18
 * Time: 4:00 PM
 */

require_once 'Json.php';
require_once 'Word.php';
require_once 'Dictionary.php';
require_once 'DictionaryCli.php';


//$method = readline("Please enter a your method [add, edit, get, find]:");
//
//if (in_array($method, array('add', 'a', 'edit', 'e'))) {
//    $word = readline("Please enter a your word:");
//    $example = readline("Please enter a your example:");
//    if ($method == 'edit' || $method == 'e') {
//        $favorite = readline("Please enter a your favorite[0,1]:");
//    }
//}elseif (in_array($method, array('get', 'g', 'find', 'f'))) {
//    $sort_type = readline("Please enter a your sort_type[1,2,3,4]:");
//    if($method == 'find' || $method == 'f') {
//        $word = readline("Please enter a your term:");
//    }
//}

//$options = getopt("m:w:e:f:s:");
$options = array(
    'm' => $method = 'f',
    'w' => $word = 'pe',
    'e' => $example = 'eeee',
    'f' => $favorite = '1',
    's' => $sort_type = 1,
);

$d = new DictionaryCli();
$r = call_user_func_array(array($d, $options['m']), array($options));
print_r($r);
echo "\n";
