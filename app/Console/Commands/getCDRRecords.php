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
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory as REF;

class GetCDRRecords extends Command
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

        $tempFile = storage_path().'/temp/yealinkcdr.csv';

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

        unset($md5Password);

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
            $this->error($pbx->getFormattedApiUrl() . 'login: ' . $response['errno'] . ': ' . $errors[$response['errno']]);
            die;
        }

        $token = $response['token'];

        $content = [
            'extid' => 'all',
            'starttime' => $cdrObj->latest('created_at')->first() ? $cdrObj->latest('created_at')->first()->created_at->format('Y-m-d H:i:s') : Carbon::now()->subMonths(4)->format('Y-m-d H:i:s'),
            'endtime' => Carbon::now()->format('Y-m-d H:i:s'),
            // 'allowedip' => '172.16.69.240'
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
            $this->error($pbx->getFormattedApiUrl() . 'cdr/get_random?token=' . $token . ': ' . $response['errno'] . ': ' . $errors[$response['errno']]);
            die;
        }

        $randomCdrId = $response['random'];
        $startTime = $response['starttime'];
        $endTime = $response['endtime'];

        $content = [

        ];

        $contentLength = strlen(json_encode($content));

        $response = $guzzleClient->request('POST', $pbx->getFormattedApiUrl() . 'cdr/download?extid=all&starttime=' . $startTime . '&endtime=' . $endTime . '&token=' . $token . '&random=' . $randomCdrId);
        file_put_contents($tempFile, trim($response->getBody()->getContents()));
        unset($response, $content, $contentLength, $randomCdrId, $startTime, $endTime, $guzzleClient, $cdrObj);
        $reader = REF::createReaderFromFile($tempFile);
        $reader->open($tempFile);
        $index = 0;
        $rowCount = 0;
        $cdrs = [];
        // (new CDRRecordImport($pbx->id))->withOutput($this->output)->import($tempFile);

        $this->info('Counting Rows');
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowCount++;
            }
        }

        foreach ($reader->getSheetIterator() as $sheet) {

            $rows = $sheet->getRowIterator();
            // dd($rows);
            $bar = $this->output->createProgressBar($rowCount);
            $bar->start();

            foreach ($rows as $row) {
                if ($index !== 0) {
                    // do stuff with the row
                    $cells = $row->getCells();

                    if(sizeOf($cdrs) === 500) {
                        CDRRecord::insert($cdrs);
                        $cdrs = [];
                    }

                    $cdrs[] = [
                        'callid' => $cells[0]->getValue(),
                        'timestart' => $cells[1]->getValue(),
                        'callfrom' => $cells[2]->getValue(),
                        'callto' => $cells[3]->getValue(),
                        'callduration' => $cells[4]->getValue(),
                        'talkduration' => $cells[5]->getValue(),
                        'waitduration' => $cells[4]->getValue() - $cells[5]->getValue(),
                        'srctrunkname'=> $cells[6]->getValue(),
                        'dsttrunkname' => $cells[7]->getValue(),
                        'status' => $cells[8]->getValue(),
                        'type' => $cells[9]->getValue(),
                        'pincode' => $cells[10]->getValue(),
                        'recording' => $cells[11]->getValue(),
                        'didnumber' => $cells[12]->getValue(),
                        'sn' => $cells[13]->getValue(),
                        'pbx_id' => $pbx->id
                    ];

                    $bar->advance();
                    unset($cells, $row);
                    gc_collect_cycles();
                } else {
                    $index++;
                }
            }
            $reader->close();
            CDRRecord::insert($cdrs);
            $bar->finish();
        }

        // dd();
        // dd(CDRRecord::all());
        // dd($response);
        // dd($pbx->getFormattedApiUrl() . 'login');
        // dd(md5(Crypt::decryptString($pbx->password)));
    }
}
