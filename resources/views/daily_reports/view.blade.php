@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @Lang('general.daily_reports.report', ['number' => $report->id])
                    <span class="float-right">
                        <a href="{{ route('daily_reports.cancel', ['id' => $report->id]) }}" class="text-danger">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </span>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="report-creator col-md-6 float-left ">
                            @lang('general.created_by_user_at_time', [ 'name' => $report->creator()->first()->name, 'time' =>  $report->created_at->diffForHumans()])
                        </div>
                        <div class="report-created-at col-md-6 float-right">
                            <div class="col-md-12">
                                @Lang('general.updated_time_ago', ['time' => $report->updated_at->diffForHumans()])
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 mb-3">
                        @Lang('general.daily_reports.action_log')
                    </div>
                    <div class="border-top pt-3">
                        <table class="table table-striped table-bordered" style="width: 100%">
                            <thead class="thead-light">
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
                                    <tr>
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
                                            @if(!$reportRow->concluded_at && !$report->closed() && $reportRow->status()->userCanProgress())
                                                <a class="btn-link back text-danger" href="{{ route('daily_reports.back', ['id' => $reportRow->id]) }}"><i class="fas fa-arrow-alt-circle-left"></i></a>
                                                <a class="forward text-success" href="{{ route('daily_reports.forward', ['id' => $reportRow->id]) }}"><i class="fas fa-arrow-alt-circle-right"></i></a>
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
@endsection

