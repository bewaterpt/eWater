@extends('layouts.app')

@section('content')
    <div id="daily-reports-view" class="container">
        <div class="row justify-content-center">
            <div class="change-report col-md-12 px-0 py-3">
                <a href="{{ route('daily_reports.previous_report', ['id' => $report->id]) }}" class="btn btn-primary">
                    <i class="fas fa-chevron-left"></i> @Lang('general.daily_reports.previous_report')
                </a>
                <a href="{{ route('daily_reports.next_report', ['id' => $report->id]) }}" class="btn btn-primary float-right">
                    @Lang('general.daily_reports.next_report') <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @Lang('general.daily_reports.report', ['number' => $report->id]) {{ $report->closed() ? '(' . __('general.daily_reports.closed_by_user_at_time', [ 'name' => $report->latestUpdate()->user()->first()->name, 'time' => $report->latestUpdate()->concluded_at->diffForHumans()]) . ')' : '' }}
                        <span class="float-right">
                            @if(!$report->closed() && $report->getCurrentStatus->userCanProgress() && !$report->synced)
                                <a href="{{ route('daily_reports.edit', ['id' => $report->id]) }}" class="text-info edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($report->getCurrentStatus->slug !== $statusObj->where('enabled', true)->where('id', '!=', $statusObj->first()->id)->orderBy('id')->first()->slug)
                                    <a class="btn-link back text-danger" title="{{__('tooltips.daily_reports.prev')}}" data-toggle="modal" data-target="#modalPrevStatus" href="#">
                                        <i class="fas fa-step-backward"></i>
                                    </a>
                                @endif
                                {{-- <a class="btn-link branch text-primary" title="{{__('tooltips.daily_reports.extra')}}" data-toggle="modal" data-target="#modalExtraStatus" href="#">
                                    <i class="fas fa-code-branch"></i>
                                </a> --}}
                                <a class="btn-link forward text-success" title="{{__('tooltips.daily_reports.next')}}" data-toggle="modal" data-target="#modalNextStatus" href="#">
                                    <i class="fas fa-step-forward"></i>
                                </a>
                            @endif
                            @include('layouts.partials.info_box', ['id' => 1])

                            @if($pmodel->can('daily_reports.cancel') && !$report->closed())
                                <span class="mx-2 text-secondary">l</span>
                                <a id="cancel-report" href="{{ route('daily_reports.cancel', ['id' => $report->id]) }}" class="btn btn-link text-white bg-danger font-weight-bold" style="font-size: 11px;">
                                    @Lang('general.daily_reports.cancel')
                                </a>
                            @endif
                            @if($report->closed() && in_array($report->latestUpdate()->status()->first()->slug, ['cancel', 'finish']) && $pmodel->can('daily_reports.restore'))
                                <span class="mx-2 text-secondary">l</span>
                                <a id="restore-report" href="{{ route('daily_reports.restore', ['id' => $report->latestUpdate()->id]) }}" class="text-danger">
                                    <i class="fas fa-history"></i>
                                </a>
                            @endif
                        </span>
                    </div>
                    <div class="card-body">
                        @if ($report->inferiorKm())
                            <div id="warnings" class="w-100 mb-3">
                                <div class="row">
                                    <div class="col"></div>
                                    <div id="inferiorKmWarn" class="text-center col-md-10 alert alert-warning m-0 p-1">
                                        <span>@Lang('errors.inferior_km_warning')</span>
                                    </div>
                                    <div class="col"></div>
                                </div>
                            </div>
                        @endif
                        @if($report->comment)
                            <label for="comment">@Lang('general.daily_reports.comment')</label>
                            <div id="comment" class="comment border rounded mh-25 pt-2 px-2 mb-3">
                                {!! $report->comment !!}
                            </div>
                        @endif
                        <div class="col-md-12 m-0 p-0 mb-2">
                            <div class="d-inline report-creator mr-3 text-left">
                                @lang('general.created_by_user_at_time', [ 'name' => $report->creator()->first()->name, 'time' =>  $report->created_at->diffForHumans()])
                            </div>
                            <div class="d-inline report-created-at text-right">
                                @Lang('general.updated_time_ago', ['time' => $report->updated_at->diffForHumans()])
                            </div>
                            <div class="float-right">
                                <div class="d-inline report-creator mr-3 text-left">
                                    @Lang('general.daily_reports.km_departure'): {{ $report->km_departure }} Km
                                </div>
                                <div class="d-inline report-creator mr-3 text-left">
                                    @Lang('general.daily_reports.km_arrival'): {{ $report->km_arrival }} Km
                                </div>
                                <div class="d-inline report-created-at text-right">
                                    @Lang('general.daily_reports.total_km'): {{ $report->getTotalKm() }} Km
                                </div>
                            </div>
                        </div>

                        @foreach($report->lines()->get()->groupBy('work_number') as $workNumber => $rows)
                            <div class="card mb-2 border-0">
                                <div class="card-header border accordion-toggle collapsed col-md-12" id="work-{{ $workNumber }}" data-toggle="collapse" data-parent="#work-{{ $workNumber }}" href="#collapse-{{ $workNumber }}">
                                    <div class="d-inline-block mr-3" title="{{ trans('general.daily_reports.work_number') }}"># {{ $workNumber }}</div>
                                    @if($workObject->getById($workNumber))
                                        <div class="d-inline-block mr-3" title="{{ trans('general.daily_reports.work_type') }}">{{ $workObject->getById($workNumber)->getWorkType()->first()->descricao }}</div>
                                    @endif
                                    <div class="d-inline-block mr-3" title="{{ trans('general.daily_reports.work_hr', ['id' => $workNumber]) }}">{{ $helpers->decimalHoursToTimeValue($report->linesByWorkNumber($workNumber)->sum('quantity')) }}
                                        {{-- @if($report->linesByWorkNumber($workNumber)->sum('quantity') > 1 || $report->linesByWorkNumber($workNumber)->sum('quantity') === 0)
                                            @Lang('general.hours')
                                        @else
                                            @Lang('general.hour')
                                        @endif --}}
                                    </div>
                                    <div class="d-inline-block mr-3" title="{{ trans('general.daily_reports.work_km', ['id' => $workNumber]) }}">{{ $report->linesByWorkNumber($workNumber)->first()->driven_km }} @Lang('general.daily_reports.km')</div>
                                    @if($workObject->getById($workNumber))
                                        <div class="d-inline-block" title="{{ trans('general.daily_reports.address') }}">{{ trim(implode(' ', $workObject->getById($workNumber)->getStreet()->select('ART_TIPO', 'ART_TITULO', 'ART_DESIG', 'ART_LOCAL')->first()->toArray()))}}, </div>
                                    @endif
                                    @if($workObject->getById($workNumber))
                                        <div class="d-inline-block" title="{{ trans('general.daily_reports.address') }}">
                                            {{ $workObject->getById($workNumber)->getStreet()->first()->getLocality()->first()->desig }}
                                        </div>
                                    @endif
                                    <div class="d-inline chevron float-right text-right"><i class="fas fa-chevron-up"></i></div>
                                </div>
                                <div class="card-body p-0 collapse in table-responsive" id="collapse-{{ $workNumber }}">
                                    <table class="table table-sm table-bordered p-0 m-0" style="position: relative; z-index: 20">
                                        <thead>
                                            <tr class="hide-table-padding">
                                                <th class="border-1">
                                                    @Lang('general.worker')
                                                </th>
                                                <th class="border-1">
                                                    @Lang('general.article')
                                                </th>
                                                <th class="border-1">
                                                    @Lang('general.hours')
                                                </th>
                                                <th class="border-1">
                                                    @Lang('general.date')
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rows as $row)
                                                <tr>
                                                    <td>
                                                        {{ $row->user()->first()->name }}
                                                    </td>
                                                    <td>
                                                        {{ $row->article()->first()->designation }}
                                                    </td>
                                                    <td>
                                                        {{ $helpers->decimalHoursToTimeValue($row->quantity) }}
                                                        {{-- @if($row->quantity > 1)
                                                            @Lang('general.hours')
                                                        @else
                                                            @Lang('general.hour')
                                                        @endif --}}
                                                    </td>
                                                    <td>
                                                        {{ (new DateTime($row->entry_date))->format('Y-m-d') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-5 mb-3">
                            @Lang('general.daily_reports.action_log')
                        </div>
                        <div class="table-responsive">
                            <table id="report-process-status" class="table table-sm" style="width: 100%">
                                <thead class="">
                                    <tr>
                                        <th class="text-center px-0">
                                            {{-- @Lang('general.actions') --}}
                                            <i class="fas fa-tools text-black sorting_disabled"></i>
                                        </th>
                                        {{-- <th>
                                            #
                                        </th> --}}
                                        <th>
                                            @Lang('general.daily_reports.status')
                                        </th>
                                        <th>
                                            @Lang('general.daily_reports.processed_by')
                                        </th>
                                        <th>
                                            @Lang('general.daily_reports.concluded_at')
                                        </th>
                                        <th>
                                            @Lang('general.daily_reports.previous_status')
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($processStatuses as $processStatus)
                                        <tr class="{{ $processStatus->closed() ? 'bg-soft-danger' : ''}}">
                                            <td class="actions text-center">
                                                @if(!$report->closed())
                                                    @if(!$processStatus->concluded_at && $processStatus->status->userCanProgress())
                                                        @if($processStatus->status->slug !== $statusObj->where('enabled', true)->where('id', '!=', $statusObj->first()->id)->orderBy('id')->first()->slug)
                                                            <a class="btn-link back text-danger" title="{{ __('tooltips.daily_reports.prev') }}" data-toggle="modal" data-target="#modalPrevStatus" href="#">
                                                                <i class="fas fa-step-backward"></i>
                                                            </a>
                                                        @endif

                                                        {{-- <a class="btn-link branch text-primary" title="{{ __('tooltips.daily_reports.extra') }}" data-toggle="modal" data-target="#modalExtraStatus" href="#">
                                                            <i class="fas fa-code-branch"></i>
                                                        </a> --}}
                                                        <a class="btn-link forward text-success" title="{{ __('tooltips.daily_reports.next') }}" data-toggle="modal" data-target="#modalNextStatus" href="#">
                                                            <i class="fas fa-step-forward"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                                @if ($processStatus->hasComment())
                                                    <a class="btn-link comment {{ $processStatus->error ? 'text-danger ri-alert-line' : 'ri-information-line text-info' }} ri-lg" title="{{ __('tooltips.daily_reports.view_comment') }}" data-toggle="modal" data-id="{{ $processStatus->id }}" data-target="#modalComment" href="#"></a>
                                                @endif
                                            </td>
                                            {{-- <td>
                                                {{ $loop->index + 1 }}
                                            </td> --}}
                                            <td>
                                                {{ $processStatus->status->name }}
                                            </td>
                                            <td>
                                                @if($processStatus->user)
                                                    {{ $processStatus->user->name }}
                                                @else
                                                    @Lang('general.daily_reports.processing')
                                                @endif
                                            </td>
                                            <td>
                                                @if($processStatus->concluded_at)
                                                    {{ $processStatus->concluded_at }}
                                                @else
                                                    @Lang('general.daily_reports.processing')
                                                @endif
                                            </td>
                                            <td>
                                                @if($processStatus->previous)
                                                    {{ $processStatus->previous->status->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="change-report col-md-12 px-0 py-3">
                <a href="{{ route('daily_reports.previous_report', ['id' => $report->id]) }}" class="btn btn-primary">
                    <i class="fas fa-chevron-left"></i> @Lang('general.daily_reports.previous_report')
                </a>
                <a href="{{ route('daily_reports.next_report', ['id' => $report->id]) }}" class="btn btn-primary float-right">
                    @Lang('general.daily_reports.next_report') <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    @include('daily_reports.modals.modal_prev', ['report' => $report])
    @include('daily_reports.modals.modal_next', ['report' => $report])
    @include('daily_reports.modals.modal_comment')
    @include('layouts.partials.modal_info', ['id' => 1, 'content' => __('info.view_report'), 'subject' => __('general.daily_reports.daily_reports')])
    {{-- @include('daily_reports.partials.modal_extra', ['report' => $report]) --}}
    <div id="prompts" class="d-none">
        <span class="cancel-report">@Lang("prompts.cancel-report")</span>
    </div>
@endsection

