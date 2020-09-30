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
use Illuminate\Support\Carbon;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CDRRecordImport;

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
        $errors = config('app.yealink_error_codes');
        $pbx = Pbx::first();
        $cdrObj = new CDRRecord();
        $guzzleClient = new Client([
            'verify' => false,
        ]);
        $md5Password = md5(Crypt::decryptString($pbx->password));

        // dd(json_encode(['username' => $pbx->username,
        // 'password' => md5("B3jBf06d"),
        // 'port' => 30,
        // "version" => "1.0.2",
        // 'verify' => false,
        // "url" => "192.168.21.12:8083/REPORTS",
        // "urltag" => "1"]));
        $content = [
            'username' => $pbx->username,
            'password' => $md5Password,
            'port' => 0,
        ];

        $contentLength = strlen(json_encode($content));

        $response = $guzzleClient->request('POST', $pbx->getFormattedApiUrl() . 'login', [
            'json' => $content,
            'headers' => [
                'Content-Type' => 'application/json; charset=utf8',
                'Content-Length' => $contentLength,
            ]
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        if ($response['status'] === "Failed") {
            $this->error($response['errno'] . ': ' . $errors[$response['errno']]);
            die;
        }

        $token = $response['token'];

        $content = [
            'extid' => 'all',
            'starttime' => $cdrObj->latest('created_at')->first() ? $cdrObj->latest('created_at')->first() : Carbon::now()->subMonths(4)->format('Y-m-d H:i:s'),
            'endtime' => Carbon::now()->format('Y-m-d H:i:s'),
            'allowedip' => '172.16.69.240'
        ];

        $contentLength = strlen(json_encode($content));

        $response = $guzzleClient->request('POST', $pbx->getFormattedApiUrl() . 'cdr/get_random?token=' . $token, [
            'json' => $content,
            'headers' => [
                'Content-Type' => 'application/json; charset=utf8',
                'Content-Length' => $contentLength,
            ]
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        if ($response['status'] === "Failed") {
            $this->error($response['errno'] . ': ' . $errors[$response['errno']]);
            die;
        }

        $randomCdrId = $response['random'];
        $startTime = $response['starttime'];
        $endTime = $response['endtime'];

        $content = [

        ];

        $contentLength = strlen(json_encode($content));

        $response = $guzzleClient->request('POST', $pbx->getFormattedApiUrl() . 'cdr/download?extid=all&starttime=' . $startTime . '&endtime=' . $endTime . '&token=' . $token . '&random=' . $randomCdrId);


        $response = $response->getBody()->getContents();
        Excel::import(new CDRRecordImport, $response);
        dd(null);
        dd($response);
        // dd($pbx->getFormattedApiUrl() . 'login');
        // dd(md5(Crypt::decryptString($pbx->password)));
    }
}
