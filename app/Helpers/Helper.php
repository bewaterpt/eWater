<?php
namespace App\Helpers;

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
}
