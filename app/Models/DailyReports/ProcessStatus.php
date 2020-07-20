<?php

namespace App\Models\DailyReports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\DailyReports\Status;
use Auth;

class ProcessStatus extends Model
{
    const STATUS_EXTRA = 3;
    const STATUS_DB_SYNC = 6;
    const STATUS_FINISHED = 7;
    const STATUS_CANCELLED = 8;

    const EXCLUDED_STATUSES = [
        self::STATUS_EXTRA,
        self::STATUS_CANCELLED,
        // self::STATUS_FINISHED
    ];

    const SELF_CONCLUDING_STATUSES = [
        self::STATUS_FINISHED,
        self::STATUS_CANCELLED
    ];


    protected $dates = ['concluded_at'];

    protected $touches = ['report'];

    protected $table = 'process_status';

    public function report() {
        return $this->belongsTo('App\Models\DailyReports\Report', 'process_id');
    }

    public function status() {
        return $this->belongsTo('App\Models\DailyReports\Status');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function previous() {
        return $this->belongsTo('App\Models\DailyReports\ProcessStatus', 'previous_status');
    }

    public function stepForward($status = false) {
        $user = Auth::user();
        $currentStatusId = $this->status()->first()->id;

        $nextStatusId = 0;

        if($status === 3) {
            $nextStatusId = self::STATUS_EXTRA;
        } else if($status === 7) {
            $nextStatusId = self::STATUS_FINISHED;
        } else if ($status === 8) {
            $nextStatusId = self::STATUS_CANCELLED;
        } else {
            $nextStatusId = Status::whereNotIn('id', self::EXCLUDED_STATUSES)->where('id', '>', $currentStatusId)->min('id');
        }

        if ($currentStatusId === 3) {
            $nextStatusId === $this->previous()->first();
        }

        $this->conclude($user->id);

        $nextProcessStatus = new self();
        $nextProcessStatus->user()->associate($user->id);
        $nextProcessStatus->report()->associate($this->report()->first()->id);
        $nextProcessStatus->status()->associate($nextStatusId);
        $nextProcessStatus->previous()->associate($this->id);
        if(in_array($nextStatusId, self::SELF_CONCLUDING_STATUSES)) {
            $nextProcessStatus->conclude($user->id);
        }
        $nextProcessStatus->save();

        return $nextProcessStatus;
    }

    public function stepBack() {
        $user = Auth::user();
        $currentStatusId = $this->status()->first()->id;
        $prevStatusId = $this->previous()->first()->status()->first()->id;

        $this->conclude($user->id);

        $nextProcessStatus = new self();
        $nextProcessStatus->user()->associate($user->id);
        $nextProcessStatus->report()->associate($this->report()->first()->id);
        $nextProcessStatus->status()->associate($prevStatusId);
        $nextProcessStatus->previous()->associate($this->id);
        $nextProcessStatus->save();

        return $nextProcessStatus;
    }

    public function stepExtra() {
        return $this->stepForward(self::STATUS_EXTRA);
    }

    public function conclude($user_id) {
        $this->concluded_at = Carbon::now();
        $this->user()->associate($user_id);
        $this->save();

        return true;
    }

    public function finish() {
        return $this->stepForward(self::STATUS_FINISHED);
    }

    public function cancel() {
        return $this->stepForward(self::STATUS_CANCELLED);
    }

    public function closed() {
        if ($this->status()->first()->slug === 'cancel') {
            return true;
        }

        if($this->status()->first()->slug === 'finish') {
            return true;
        }

        return false;
    }

    public function isExtra() {
        return $this->status()->first()->id === 3 ? true : false;
    }

    public function hasComment() {
        return $this->comment !== null ? true : false;
    }
}
