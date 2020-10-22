<?php

namespace App\Custom\Classes;

use App\Custom\Classes\Option;
use App\Models\Forms\FormField;
use Illuminate\Support\Collection;

class FieldSet extends Collection
{
    public function __construct($set) {

        foreach($set as $fields) {
            $formField = new FormField();
            foreach($fields as $field => $value) {
                $formField->{$field} = $value;
            }

            $this->push($formField);
        }

        return $this;
    }
}
