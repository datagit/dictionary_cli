<?php
/**
 * Created by PhpStorm.
 * User: datdm
 * Date: 8/11/18
 * Time: 10:58 AM
 */

class NotHandingErrors
{

    /**
     * @param $value
     * @return array
     * @throws Exception
     */
    public static function parsePersonNameFromString($value)
    {
        if ( empty($value)) {
            throw new \Exception('Value must not empty.');
        }
        //https://regex101.com/
        //https://www.rexegg.com/regex-quickstart.html
        $pattern = '/(?<first_name>[\\s\\w]*),(?<last_name>[\\s\\w]*)/';
        if (preg_match($pattern, $value, $matches)) {
            return array($matches['first_name'], $matches['last_name']);
        }
        return false;
    }

    /**
     * @param $value
     * @param $display_type
     * @throws Exception
     */
    public function displayName($value, $display_type = 0)
    {
        $value = self::parsePersonNameFromString($value);
        $display = sprintf("Name is empty.\n");
        if ($value) {
            list($first_name, $last_name) = array_map('trim', $value);
            switch ($display_type) {
                case 1:
                    $display = sprintf("First name: %s\nLast name: %s\n", $first_name, $last_name);
                    break;
                case 2:
                    $display = sprintf("*First name: [%s]*Last name: [%s]\n", $first_name, $last_name);
                    break;
                default:
                    $display = sprintf("#First name: {%s}#Last name: {%s}\n", $first_name, $last_name);
                    break;
            }
        }
        echo $display;
    }
}

$n = new NotHandingErrors();
$n->displayName('  ');
$n->displayName('dat, dao');
$n->displayName('dat1, dao1', 1);
$n->displayName('dat2, dao2', 2);