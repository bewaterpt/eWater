<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    public function __construct($name = null, $description = null) {
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function fields() {
        return $this->hasMany('App\Models\Forms\FormField');
    }
}
