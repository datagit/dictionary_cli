<?php
/**
 * Created by PhpStorm.
 * User: datdm
 * Date: 8/4/18
 * Time: 4:24 PM
 */

require_once 'Json.php';
require_once 'Repository.php';
require_once 'Word.php';
require_once 'Dictionary.php';
require_once 'DictionaryCli.php';

$options = getopt("m:weft");
// Script example.php
$shortopts  = "";
$shortopts .= "m:";     // Required value
$shortopts .= "w:";     // Optional value
$shortopts .= "e::";    // Optional value
$shortopts .= "f::";    // Optional value
$shortopts .= "s::";    // Optional value
$shortopts .= "t::";    // Optional value


$longopts  = array(
    "required:",     // Required value
    "optional::",    // Optional value
    "option",        // No value
    "opt",           // No value
);
$options = getopt($shortopts, $longopts);

if (empty($options['m'])) {
    echo sprintf("Please help me input:-m{Method} -w{Word} -E{Examples}\n");
    die;
}

$test = new DictionaryCli();
$method = $test->getMethod($options['m']);
print_r($method($options));
echo "\n";