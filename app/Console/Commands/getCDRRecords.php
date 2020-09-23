<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Log;
use App\Models\Yealink\CDRRecord;
use App\Models\Yealink\Pbx;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class getCDRRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calls:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update call records';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pbx = Pbx::first();
        dd(Http::post($pbx->getFormattedApiUrl() . '/login',[
            'username' => $pbx->username,
            'password' => md5(Crypt::decryptString($pbx->password)),
            'port' => 0
        ])->body());
    }
}
