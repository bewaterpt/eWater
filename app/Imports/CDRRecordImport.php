<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Yealink\CDRRecord;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Row;

class CDRRecordImport implements WithHeadingRow, WithChunkReading, OnEachRow, WithProgressBar
{

    use Importable;

    protected $pbxId;

    public function __construct(int $pbxId) {
        $this->pbxId = $pbxId;
    }

    public function onRow(Row $row) {

        // if (!isset($row['callid'])) {
        // }
        $row = $row->toArray();

        if (!isset($row['callid'])) {
            dd($row);
        }
        $cdr = new CDRRecord();
        $cdr->callid = $row['callid'];
        $cdr->timestart = $row['timestart'];
        $cdr->callfrom = $row['callfrom'];
        $cdr->callto = $row['callto'];
        $cdr->callduration = $row['callduraction'];
        $cdr->talkduration = $row['talkduraction'];
        $cdr->waitduration = $row['callduraction'] - $row['talkduraction'];
        $cdr->srctrunkname = $row['srctrunkname'];
        $cdr->dsttrunkname = $row['dsttrunkname'];
        $cdr->status = $row['status'];
        $cdr->type = $row['type'];
        $cdr->pincode = $row['pincode'];
        $cdr->recording = $row['recording'];
        $cdr->didnumber = $row['didnumber'];
        $cdr->sn = $row['sn'];
        $cdr->pbx()->associate($this->pbxId);
        $cdr->save();
    }

    public function chunkSize(): int {
        return 500;
    }
}
