<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use App\Models\Revision;

class Model extends BaseModel
{
    use HasFactory;

    public static function findOrCreate($id) {
        return static::find($id) ?: new static;
    }

    public static function getType() {
        $classParts = explode('\\', static::getClassName());
        return strtolower($classParts[sizeof($classParts) - 1]);
    }

    public static function getClassName() {
        return static::class;
    }

    public function getRevisions() {
        return Revision::where('class', $this->getClassName())->where('type', $this->getType())->where('reference_id', $this->id)->get();
    }
}
