@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('general.interruptions.view')
                    <span class="float-right text-right">
                        <a class="text-primary edit px-1" href="{{ route('interruptions.edit', ['id' => $interruption->id]) }}" title="{{ __('general.edit') }}">
                            <i class="fas fa-edit"></i>
                        </a>
                    </span>
                </div>
                <div class="card-body">
                    <div class="form-row mb-5">
                        <div class="form-group col-md-3">
                            <input id="scheduled" type="radio" name="scheduled" disabled {{ $interruption->scheduled ? 'checked' : '' }}>
                            <label for="scheduled">@Lang('general.interruptions.is_scheduled')</label>
                            <br>
                            <input id="unscheduled" type="radio" name="scheduled" disabled {{ $interruption->scheduled ? '' : 'checked' }}>
                            <label for="unscheduled">@Lang('general.interruptions.is_unscheduled')</label>
                        </div>
                        <div class="form-group col-md-2">
                            <div><b>@Lang('general.interruptions.ref')</b></div>
                            <div>{{ $interruption->work_id }} </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div><b>@Lang('general.interruptions.start_date')</b></div>
                            <div>{{ $interruption->start_date }} </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div><b>@Lang('general.interruptions.reinstatement_date')</b></div>
                            <div>{{ $interruption->reinstatement_date }} </div>
                        </div>
                    </div>
                    <div class="form-row w-100">
                        <div class="form-group col-md-12">
                            <div>
                                <b>@Lang('general.interruptions.affected_area')</b>
                            </div>
                            <div>
                                {!! $interruption->affected_area !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

