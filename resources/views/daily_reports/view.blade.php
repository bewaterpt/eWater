@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @Lang('general.daily_reports.report', ['number' => $report->id]) {{ $report->closed() ? '(' . __('general.daily_reports.cancelled_by_user_at_time', [ 'name' => $report->creator()->first()->name, 'time' => $report->latestUpdate()->concluded_at->diffForHumans()]) . ')' : '' }}
                        <span class="float-right">
                            <a class="btn-link back text-danger" title="{{__('tooltips.daily_reports.prev')}}" data-toggle="modal" data-target="#modalPrevStatus" href="#">
                                <i class="fas fa-step-backward"></i>
                            </a>
                            <a class="btn-link branch text-primary" title="{{__('tooltips.daily_reports.extra')}}" data-toggle="modal" data-target="#modalExtraStatus" href="#">
                                <i class="fas fa-code-branch"></i>
                            </a>
                            <a class="btn-link forward text-success" title="{{__('tooltips.daily_reports.next')}}" data-toggle="modal" data-target="#modalNextStatus" href="#">
                                <i class="fas fa-step-forward"></i>
                            </a>
                            <a href="{{ route('daily_reports.cancel', ['id' => $report->id]) }}" class="text-danger {{ $report->closed() ? 'invisible' : '' }}">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="report-creator float-left ">
                            @lang('general.created_by_user_at_time', [ 'name' => $report->creator()->first()->name, 'time' =>  $report->created_at->diffForHumans()])
                        </div>
                        <div class="report-created-at float-right text-right">
                            <div class="">
                                @Lang('general.updated_time_ago', ['time' => $report->updated_at->diffForHumans()])
                            </div>
                        </div>

                        @foreach($report->lines()->get()->groupBy('work_number') as $workNumber => $rows)
                            <table class="table table-sm table-bordered border-top border-bottom accordion-toggle collapsed mb-0 mt-2" id="work-{{ $workNumber }}" data-toggle="collapse" data-parent="#work-{{ $workNumber }}" href="#collapse-{{ $workNumber }}">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="pl-3 border-0">{{ $workNumber }}</th>
                                        <th class="border-0">{{ $helpers->getWorkReportHours($workNumber) }}
                                            @if($helpers->getWorkReportHours($workNumber) > 1)
                                                @Lang('general.hours')
                                            @else
                                                @Lang('general.hour')
                                            @endif
                                        </th>
                                        <th class="border-0">{{ $helpers->getWorkReportKm($workNumber) }} @Lang('general.daily_reports.km')</th>
                                        <th class="chevron text-right pr-3 border-0"><i class="fas fa-chevron-up"></i></th>
                                    </tr>
                                </thead>
                            </table>
                            <table class="table table-sm table-bordered collapse in p-0" id="collapse-{{ $workNumber }}" style="position: relative; z-index: 20">
                                <thead>
                                    <tr class="hide-table-padding">
                                        <th>
                                            @Lang('general.worker')
                                        </th>
                                        <th>
                                            @Lang('general.article')
                                        </th>
                                        <th>
                                            @Lang('general.hours')
                                        </th>
                                        <th>
                                            @Lang('general.date')
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>
                                                {{ $row->worker }}
                                            </td>
                                            <td>
                                                {{ $row->getArticle()->descricao }}
                                            </td>
                                            <td>
                                                {{ $row->quantity }}
                                            </td>
                                            <td>
                                                {{ $row->entry_date }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                                    @foreach($reportRows as $reportRow)
                                        <tr class="{{ $reportRow->closed() ? 'bg-soft-danger' : ''}}">
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                {{ $reportRow->status()->first()->name }}
                                            </td>
                                            <td>
                                                {{ $reportRow->user()->first()->name }}
                                            </td>
                                            <td>
                                                @if($reportRow->concluded_at)
                                                    {{ $reportRow->concluded_at }}
                                                @else
                                                    @Lang('general.daily_reports.processing')
                                                @endif
                                            </td>
                                            <td>
                                                @if($reportRow->previous()->first())
                                                    {{ $reportRow->previous()->first()->status()->first()->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="actions text-center">
                                                @if(!$reportRow->concluded_at && !$report->closed() && $reportRow->status()->first()->userCanProgress())
                                                <a class="btn-link back text-danger" title="{{__('tooltips.daily_reports.prev')}}" data-toggle="modal" data-target="#modalPrevStatus" href="#">
                                                    <i class="fas fa-step-backward"></i>
                                                </a>
                                                <a class="btn-link branch text-primary" title="{{__('tooltips.daily_reports.extra')}}" data-toggle="modal" data-target="#modalExtraStatus" href="#">
                                                    <i class="fas fa-code-branch"></i>
                                                </a>
                                                <a class="btn-link forward text-success" title="{{__('tooltips.daily_reports.next')}}" data-toggle="modal" data-target="#modalNextStatus" href="#">
                                                    <i class="fas fa-step-forward"></i>
                                                </a>
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
        </div>
    </div>
    @include('daily_reports.partials.modal_prev', ['report' => $report])
    @include('daily_reports.partials.modal_next', ['report' => $report])
@endsection

