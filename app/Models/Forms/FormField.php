<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Custom\Classes\Option;

class FormField extends Model
{
    use HasFactory;

    public function form() {
        return $this->belongsTo('App\Models\Forms\Form');
    }

    public function getOptions() {
        dd($this->options);
        return collect(unserialize($this->options))->map(function ($option) {
            return new Option($option);
        });;
    }

    public function getData() {

    }

    public function getClasses() {
        return $this->classes;
    }
 }
