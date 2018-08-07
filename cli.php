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

$options = getopt("m:w:e:f:s:");

if (empty($options['m'])) {
    echo sprintf("Please help me input:-m{Method} -w{Word} -E{Example}\n");
    die;
}
$d = new DictionaryCli();
$r = call_user_func_array(array($d, $options['m']), array($options));
print_r($r);
echo "\n";