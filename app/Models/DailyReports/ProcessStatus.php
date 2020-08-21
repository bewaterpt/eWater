<?php

namespace App\Models\DailyReports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DailyReports\Status;
use Auth;

class ProcessStatus extends Model
{
    protected $STATUS_EXTRA = 3;
    protected $STATUS_DB_SYNC = 6;
    protected $STATUS_FINISHED = 7;
    protected $STATUS_CANCELLED = 8;

    protected $EXCLUDED_STATUSES = [];

    protected $SELF_CONCLUDING_STATUSES = [];


    protected $dates = ['concluded_at'];

    protected $touches = ['report'];

    protected $table = 'process_status';

    public function __construct() {
        $this->STATUS_EXTRA = Status::where('slug', 'extra')->first()->id;
        $this->STATUS_DB_SYNC = Status::where('slug', 'database_sync')->first()->id;
        $this->STATUS_FINISHED = Status::where('slug', 'finish')->first()->id;
        $this->STATUS_CANCELLED = Status::where('slug', 'cancel')->first()->id;

        $this->EXCLUDED_STATUSES = [
            $this->STATUS_EXTRA,
            $this->STATUS_CANCELLED,
        ];

        $this->SELF_CONCLUDING_STATUSES = [
            $this->STATUS_FINISHED,
            $this->STATUS_CANCELLED,
        ];
    }

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
            $nextStatusId = $this->STATUS_EXTRA;
        } else if($status === 7) {
            $nextStatusId = $this->STATUS_FINISHED;
        } else if ($status === 8) {
            $nextStatusId = $this->STATUS_CANCELLED;
        } else {
            $nextStatusId = Status::whereNotIn('id', $this->EXCLUDED_STATUSES)->where('id', '>', $currentStatusId)->min('id');
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
        if(in_array($nextStatusId, $this->SELF_CONCLUDING_STATUSES)) {
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
        return $this->stepForward($this->STATUS_EXTRA);
    }

    public function conclude($user_id) {
        $this->concluded_at = Carbon::now();
        $this->user()->associate($user_id);
        $this->save();

        return true;
    }

    public function finish() {
        return $this->stepForward($this->STATUS_FINISHED);
    }

    public function cancel() {
        return $this->stepForward($this->STATUS_CANCELLED);
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
