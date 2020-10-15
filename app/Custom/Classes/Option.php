<?php

namespace App\Custom\Classes;

use Illuminate\Console\Command;
use App\Models\DailyReports\Report;
use App\Models\Connectors\OutonoObrasCC as ObrasCC;
use App\Models\Article;
use Illuminate\Support\Carbon;
use DB;
USE Log;

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
