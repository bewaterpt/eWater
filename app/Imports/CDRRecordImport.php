<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Yealink\CDRRecord;

class CDRRecordImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

    }

    public function model(array $row) {
        return new CDRRecord([
            'callid' => $row[0],
            'timestart' => $row[1],
            'callfrom' => $row[2],
            'callto' => $row[3],
            'callduration' => $row[4],
            'talkduration' => $row[5],
            'waitduration' => $row[4] - $row[5],
            'srctrunkname' => $row[6],
            'dsttrunkname' => $row[7],
            'status' => $row[8],
            'type' => $row[9],
            'pincode' => $row[10],
            'recording' => $row[11],
            'didnumber' => $row[12],
            'sn' => $row[13]
        ]);
    }
}
