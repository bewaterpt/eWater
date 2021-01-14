<?php

namespace App\Models\Yealink;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class Pbx extends Model
{
    use HasFactory;

    protected $table = 'pbx';

    public function delegation() {
        return $this->belongsTo('App\Models\Delegation');
    }

    public function getFormattedApiUrl() {
        return $this->protocol . '://' . $this->url . ':' . $this->port . $this->api_base_uri;
    }
}
