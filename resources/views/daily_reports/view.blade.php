@extends('layouts.app')

@section('content')
    <div id="daily-reports-view" class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @Lang('general.daily_reports.report', ['number' => $report->id]) {{ $report->closed() ? '(' . __('general.daily_reports.cancelled_by_user_at_time', [ 'name' => $report->creator()->first()->name, 'time' => $report->latestUpdate()->concluded_at->diffForHumans()]) . ')' : '' }}
                        <span class="float-right">
                            @if(!$report->closed() && $report->getCurrentStatus()->first()->userCanProgress())
                                <a class="btn-link back text-danger" title="{{__('tooltips.daily_reports.prev')}}" data-toggle="modal" data-target="#modalPrevStatus" href="#">
                                    <i class="fas fa-step-backward"></i>
                                </a>
                                {{-- <a class="btn-link branch text-primary" title="{{__('tooltips.daily_reports.extra')}}" data-toggle="modal" data-target="#modalExtraStatus" href="#">
                                    <i class="fas fa-code-branch"></i>
                                </a> --}}
                                <a class="btn-link forward text-success" title="{{__('tooltips.daily_reports.next')}}" data-toggle="modal" data-target="#modalNextStatus" href="#">
                                    <i class="fas fa-step-forward"></i>
                                </a>
                            @endif
                            @if($pmodel->can('daily_reports.cancel') && !$report->closed())
                                <a href="{{ route('daily_reports.cancel', ['id' => $report->id]) }}" class="text-danger">
                                    <i class="fas fa-ban"></i>
                                </a>
                            @endif
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 m-0 p-0 mb-2">
                            <span></span>
                            <div class="d-inline report-creator text-left">
                                @lang('general.created_by_user_at_time', [ 'name' => $report->creator()->first()->name, 'time' =>  $report->created_at->diffForHumans()])
                            </div>
                            <div class="d-inline report-created-at float-right text-right">
                                @Lang('general.updated_time_ago', ['time' => $report->updated_at->diffForHumans()])
                            </div>
                        </div>

                        @foreach($report->lines()->get()->groupBy('work_number') as $workNumber => $rows)
                            <div class="card mb-2 border-0">
                                <div class="card-header border accordion-toggle collapsed col-md-12" id="work-{{ $workNumber }}" data-toggle="collapse" data-parent="#work-{{ $workNumber }}" href="#collapse-{{ $workNumber }}">
                                    <div class="d-inline-block col-md-3">@Lang('general.daily_reports.work-number-x') {{ $workNumber }}</div>
                                    <div class="d-inline-block col-md-3">{{ $report->linesByWorkNumber($workNumber)->sum('quantity') }}
                                        @if($report->linesByWorkNumber($workNumber)->sum('quantity') > 1)
                                            @Lang('general.hours')
                                        @else
                                            @Lang('general.hour')
                                        @endif
                                    </div>
                                    <div class="d-inline-block col-md-3">{{ $report->linesByWorkNumber($workNumber)->sum('driven_km') }} @Lang('general.daily_reports.km')</div>
                                    <div class="d-inline col-md-2 chevron float-right text-right"><i class="fas fa-chevron-up"></i></div>
                                </div>
                                <div class="card-body p-0 collapse in" id="collapse-{{ $workNumber }}">
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
                                                        {{ $row->user()->first()->username }}
                                                    </td>
                                                    <td>
                                                        {{ $row->getArticle()->descricao }}
                                                    </td>
                                                    <td>
                                                        {{ $row->quantity }}
                                                        @if($row->quantity > 1)
                                                            @Lang('general.hours')
                                                        @else
                                                            @Lang('general.hour')
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $row->entry_date }}
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
                        <div class="">
                            <table id="report-process-status" class="table table-sm" style="width: 100%">
                                <thead class="">
                                    <tr>
                                        <th>
                                            #
                                        </th>
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
                                        <th class="text-center">
                                            @Lang('general.actions')
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($processStatuses as $processStatus)
                                        <tr class="{{ $processStatus->closed() ? 'bg-soft-danger' : ''}}">
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                {{ $processStatus->status()->first()->name }}
                                            </td>
                                            <td>
                                                {{ $processStatus->user()->first()->name }}
                                            </td>
                                            <td>
                                                @if($processStatus->concluded_at)
                                                    {{ $processStatus->concluded_at }}
                                                @else
                                                    @Lang('general.daily_reports.processing')
                                                @endif
                                            </td>
                                            <td>
                                                @if($processStatus->previous()->first())
                                                    {{ $processStatus->previous()->first()->status()->first()->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="actions text-center">
                                                @if(!$report->closed())
                                                    @if(!$processStatus->concluded_at && $processStatus->status()->first()->userCanProgress())
                                                        <a class="btn-link back text-danger" title="{{ __('tooltips.daily_reports.prev') }}" data-toggle="modal" data-target="#modalPrevStatus" href="#">
                                                            <i class="fas fa-step-backward"></i>
                                                        </a>
                                                        {{-- <a class="btn-link branch text-primary" title="{{ __('tooltips.daily_reports.extra') }}" data-toggle="modal" data-target="#modalExtraStatus" href="#">
                                                            <i class="fas fa-code-branch"></i>
                                                        </a> --}}

                                                        <a class="btn-link forward text-success" title="{{ __('tooltips.daily_reports.next') }}" data-toggle="modal" data-target="#modalNextStatus" href="#">
                                                            <i class="fas fa-step-forward"></i>
                                                        </a>
                                                    @else
                                                        @if (!$processStatus->hasComment())
                                                            -
                                                        @endif
                                                    @endif
                                                @endif
                                                @if ($processStatus->hasComment())
                                                    <a class="btn-link comment {{ $processStatus->error ? 'text-danger ri-alert-line' : 'ri-information-line text-info' }} ri-lg" title="{{ __('tooltips.daily_reports.view_comment') }}" data-toggle="modal" data-id="{{ $processStatus->id }}" data-target="#modalComment" href="#">
                                                    </a>
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
        </div>
    </div>
    @include('daily_reports.modals.modal_prev', ['report' => $report])
    @include('daily_reports.modals.modal_next', ['report' => $report])
    @include('daily_reports.modals.modal_comment')
    {{-- @include('daily_reports.partials.modal_extra', ['report' => $report]) --}}
@endsection

