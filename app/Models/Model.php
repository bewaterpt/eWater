<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use App\Models\Revision;
use Auth;

class Model extends BaseModel
{
    use HasFactory;

    private $hasUserTracking = false;

    private $hasRevisioning = false;

    public function __construct() {
        parent::__construct();
    }

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

    public function save(array $options = []) {

        if ($this->hasUserTracking) {
            if ($this->created_at) {
                $this->updated_by = Auth::user()->id;
            } else {
                $this->created_by = Auth::user()->id;
            }
        }

        if ($this->hasRevisioning) {
            $this->saveRevision($this);
        }

        return parent::save();
    }

    private function saveRevision($model) {
        $rev = new Revision();
        $rev->reference_id = $model->id;
        $rev->class = $model->getClassName();
        $rev->type = $model->getType();
        $rev->content = $model->toJson();
        $rev->save();
    }
}
