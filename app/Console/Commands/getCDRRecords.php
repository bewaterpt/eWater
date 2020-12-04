<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Yealink\CDRRecord;
use App\Models\Yealink\Pbx;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;
use GuzzleHttp\Client;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory as REF;
use Exception;
use Storage;
use Cache;
use Illuminate\Support\Facades\Redis;

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
     * The Redis instance for this command
     *
     * @var Redis
     */
    private $redis;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function setCallRecordUpdateState($current, $first = false, $total = null) {
        $content = ['calls', 'updating', true, 'current', $current ];

        if ($first) {
            $content[] = 'total';
            $content[] = $total;
        }

        try {
            Redis::command('hset', $content);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function forgetCallRecordUpdateState() {

        try {
            foreach(Redis::hkeys('calls') as $key) {
                Redis::hdel('calls', $key);
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            Cache::forget('datatable_calls_records');
            Redis::hset('calls', 'updating', true);
            $this->setCallRecordUpdateState(0, true. 0);
            $this->info('Applying settings');
            $guzzleClient = new Client([
                'verify' => false,
            ]);

            $tempFile = storage_path('app').'/temp/yealinkcdr.csv';

            $errors = config('app.yealink_error_codes');
            $pbx = Pbx::first();
            $cdrObj = new CDRRecord();

            $this->comment('Done');
            $this->info('Authenticating with Yealink PBX');
            $content = [
                'username' => $pbx->username,
                'password' => md5(Crypt::decryptString($pbx->password)),
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
                $this->error($pbx->getFormattedApiUrl() . 'login: ' . $response['errno'] . ': ' . $errors[$response['errno']]);
                die;
            }

            $token = $response['token'];
            $this->comment('Done');

            $this->info('Getting random CDR file name');
            $content = [
                'extid' => 'all',
                'starttime' => $cdrObj->latest('timestart')->first() ? Carbon::parse($cdrObj->latest('timestart')->first()->timestart)->subHours(4)->format('Y-m-d H:i:s') : Carbon::now()->subMonths(12)->format('Y-m-d H:i:s'),
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

            $this->comment('Done');

            $this->info('Downloading CDR file');
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
            $this->comment('Done');

            $this->info('Counting records');
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $rowCount++;
                }
            }

            // Scrapped
            $this->setCallRecordUpdateState(0, true, $rowCount);

            $this->comment('Done');

            $this->info('Determining records for insertion in the database... (this might take a while)');

            foreach ($reader->getSheetIterator() as $sheet) {

                foreach ($sheet->getRowIterator() as $row) {
                    if ($index !== 0) {
                        $cells = $row->getCells();

                        // if (sizeOf($cdrs) === 10) {
                        //     CDRRecord::insert($cdrs);
                        //     unset($cdrs);
                        //     $cdrs = [];
                        // }

                        if (!CDRRecord::where('callid', $cells[0]->getValue())->where('timestart', $cells[1]->getValue())->where('type', $cells[9]->getValue())->first()) {
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
                        }

                        unset($cells, $row);
                        gc_collect_cycles();
                        $index++;
                    } else {
                        $index++;
                    }
                }
                $this->comment('Done');

                // dd($cdrs);
                $reader->close();


                $cdrs = collect($cdrs);

                if ($cdrs->count() > 0) {
                    $this->info('Inserting records in the database');
                    $bar = $this->output->createProgressBar($cdrs->count());
                    $bar->start();

                    foreach ($cdrs->chunk(200) as $items) {
                        CDRRecord::insert($items->toArray());
                        $bar->advance($items->count());

                        // Scrapped
                        $this->setCallRecordUpdateState($bar->getProgress());
                    }

                    $bar->finish();
                } else {
                    $this->info('Nothing to insert in the database');
                }
            }

            // Scrapped
            $this->forgetCallRecordUpdateState();

            Storage::delete($tempFile);

            $this->info("");
            $this->comment('Done. Bye Bye');
        } catch(\Exception $e) {
            Storage::delete($tempFile);

            throw new Exception($e->getMessage());
        }
    }
}
