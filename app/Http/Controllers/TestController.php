<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReports\Report;
use App\Models\Connectors\OutonoArtigos as Artigos;
use App\Models\Connectors\OutonoObrasCC as ObrasCC;
use Illuminate\Support\Carbon;
use Log;
use DB;
use Storage;
use App\Models\Article;
class TestController extends Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function index(Request $request) {
        $stg = new Storage();
        dd(Storage::disk('local')->files('temp'));
    }
}
