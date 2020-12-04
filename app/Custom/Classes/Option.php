<?php

namespace App\Custom\Classes;

class Option
{
    public $label;
    public $value;

    public function __construct($option) {
        $this->label = $option['label'];
        $this->value = $option['value'];

        return $this;
    }
}
