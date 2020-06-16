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
                        <table class="table table-striped table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>
                                        @Lang('general.work_number')
                                    </th>
                                    <th>
                                        @Lang('general.article')
                                    </th>
                                    <th>
                                        @Lang('general.quantity')
                                    </th>
                                    <th>
                                        @Lang('general.unit_price')
                                    </th>
                                    <th>
                                        @Lang('general.total')
                                    </th>
                                    <th>
                                        @Lang('general.date')
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report->lines()->get() as $line)
                                    <tr>
                                        <td>
                                            {{ $line->work_number }}
                                        </td>
                                        <td>
                                            {{ $line->getArticle()->descricao }}
                                        </td>
                                        <td>
                                            {{ $line->quantity }}
                                        </td>
                                        <td>
                                            {{ $line->unit_price }} €
                                        </td>
                                        <td>
                                            {{ $line->getTotal() }} €
                                        </td>
                                        <td>
                                            {{ $line->entry_date }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="float-right text-right col-md-6">
                            @Lang('general.total'): {{ $report->getTotalPrice() }} €
                        </div>
                        <div class="mt-5 mb-3">
                            @Lang('general.daily_reports.action_log')
                        </div>
                        <div class="border-top pt-3">
                            <table class="table table-sm table-striped" style="width: 100%">
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

