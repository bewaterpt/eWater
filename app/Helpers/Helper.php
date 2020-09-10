<?php
namespace App\Helpers;

use App\Models\DailyReports\ReportLine;
use App\Models\DailyReports\Report;
class Helper {

    protected function isAssoc(array $arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function sortArray($arr){
        if($this->isAssoc($arr)){
            ksort($arr);
        } else{
            asort($arr);
        }
        foreach ($arr as $a){
            if(is_array($a)){
                $a = $this->sortArray($a);
            }
        }

        return $arr;
    }

    /**
     * @method transliterate
     *
     * This method transliterates a string to match non special versions of special characters
     *
     * @param string $str
     *
     * @param integer $case - Defines the string case
     *      0 = No changes
     *      1 = Lower Case
     *      2+ = Upper Case
     */
    public function transliterate(String $str, $case = 0) {

        $newStr = '';
        $chars = str_split(trim(utf8_decode($str)));
        $chars = collect($chars)->map(function($char) {
            return utf8_encode($char);
        });

        $replacePairs = [
            '’' => '_', '“' => '_', '”' => '_',
            '«' => '_', '»' => '_', '–' => '_',
            '@' => '_', '(' => '', ')' => '_',
            '[' => '_', ']' => '_', '{' => '_',
            '}' => '', '/' => '_', '|' => '_',
            '\\' => '_', '#' => '_', '£' => '',
            '"' => '_', '!' => '', '.' => '_',
            '\'' => '_', '§' => '', '$' => '',
            '€' => '', '?' => '', '%' => '',
            '&' => '_', '=' => '_', '+' => '_',
            '*' => '_', '´' => '', '`' => '',
            'º' => '', 'ª' => '', '_' => '_',
            ':' => '_', ';' => '_', ',' => '',
            '<' => '', '>' => '', '¥' => '',
            '¤' => '', ' ' => '_', '-' => '_',
            '~' => '', '^' => '',

            'a' => 'a', 'ã' => 'a', 'á' => 'a', 'à' => 'a', 'â' => 'a', 'ä' => 'a',
            'A' => 'A', 'Ã' => 'A', 'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ä' => 'a',

            'b' => 'b',
            'B' => 'B',

            'c' => 'c', 'ç' => 'c',
            'C' => 'C', 'Ç' => 'C',

            'd' => 'd',
            'D' => 'D',

            'e' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'E' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',

            'f' => 'f',
            'F' => 'F',

            'g' => 'g',
            'G' => 'G',

            'h' => 'h',
            'H' => 'H',

            'í' => 'i', 'ï' => 'i',
            'Í' => 'I', 'Ï' => 'I',

            'j' => 'j',
            'J' => 'J',

            'k' => 'k',
            'K' => 'K',

            'l' => 'l',
            'L' => 'L',

            'm' => 'm',
            'M' => 'M',

            'n' => 'n',
            'N' => 'N',

            'õ' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
            'Õ' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Ö' => 'o',

            'p' => 'p',
            'P' => 'P',

            'q' => 'q',
            'Q' => 'Q',

            'r' => 'r',
            'R' => 'R',

            's' => 's',
            'S' => 'S',

            't' => 't',
            'T' => 'T',

            'ú' => 'u', 'ü' => 'u',
            'Ú' => 'U', 'Ü' => 'U',

            'v' => 'v',
            'V' => 'V',

            'w' => 'w',
            'W' => 'W',

            'x' => 'x',
            'X' => 'X',

            'y' => 'y',
            'Y' => 'Y',

            'z' => 'z',
            'Z' => 'Z'
        ];

        $prevChar = null;
        foreach ($chars as $index => $char){
            $prevChar = $char;

            if(in_array($char, [')', ']', '}']) && ($index + 1) == strlen($str)) {
                $newStr .= '';
            } else if (in_array($char, ['(', '[', '{']) && $prevChar = ''){
                $newStr .= '_';
            } else {
                $newStr .= strtr($char, $replacePairs);
            }
        }

        // Return string translated with replaced pairs

        if ($case === 1) {
            return mb_strtolower($newStr);
        } else if ($case > 1) {
            return mb_strtoupper($newStr);
        }

        return strtr($str, $replacePairs);
    }

    public function decimalToTimeValue($totalHours, $calculateDays = false, $calculateSeconds = false) {
        $sInDay = 86400;
        $sInHr = 3600;
        $sInMin = 60;
        $totalSeconds = 0;
        $days = 0;
        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        $totalSeconds = $totalHours * $sInHr;

        if($calculateDays) {
            if ($totalSeconds >= $sInDay) {
                $days = $totalSeconds / $sInDay;
                $totalSeconds = $totalSeconds % $sInDay;
            }
        }

        if ($totalSeconds >= $sInHr) {
            $hours = $totalSeconds / $sInHr;
            $totalSeconds = $totalSeconds % $sInHr;
        }

        if ($totalSeconds >= $sInMin) {
            $minutes = $totalSeconds / $sInMin;
            $totalSeconds = $totalSeconds % $sInMin;
        }

        $seconds = $totalSeconds;

        return ($calculateDays ? $days . "d, ": "") . ($hours < 10 ? "0" : "") . intval($hours) . ":" . ($minutes < 10 ? "0" : "") . intval($minutes) . ($calculateSeconds ? ":" . ($seconds < 10 ? "0" : "") . intval($seconds) : "");
    }
}

