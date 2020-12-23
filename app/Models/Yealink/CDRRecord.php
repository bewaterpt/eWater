<?php

namespace App\Models\Yealink;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class CDRRecord extends Model
{
    use HasFactory;

    protected $fillable = ['*'];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $table = 'cdr_records';

    public function pbx() {
        return $this->belongsTo('App\Models\Yealink\Pbx');
    }
}
