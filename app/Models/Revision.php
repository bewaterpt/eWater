<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class Revision extends Model
{
    use HasFactory;
    
    private $hasUserTracking = true;
}
