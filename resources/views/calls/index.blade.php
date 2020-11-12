@extends('layouts.app')

@section('content')
<div class="w-100 px-5" id="calls-dashboard">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header text-center text-bold">
                    @Lang('general.statistics')
                </div>
                <div class="card-body">
                    <div class="col-md-6 d-inline-block" width="100%" height="100%">
                        <canvas id="monthlyWaitTimeInfo">
                        </canvas>
                    </div>
                    <div class="col-md-6 d-inline-block float-right" width="100%" height="100%">
                        <canvas id="monthlyCallNumberInfo">
                        </canvas>
                    </div>
                    <div class="col-md-6 d-inline-block" width="100%" height="100%">
                        <canvas id="monthlyLostCallNumberInfo">
                        </canvas>
                    </div>
                    <div class="col-md-6 d-inline-block float-right" width="100%" height="100%">
                        <canvas id="placeholder">
                        </canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    @Lang('calls.pbx.list')
                    <div class="float-right">
                        <a class="text-primary float-left mr-3" id="reloadCallData" href="#" data-toggle="modal" data-target="#modalSpinner">
                            <i class="fas fa-sync"></i>
                        </a>
                        <div class="dropdown show float-right">
                            <a class="text-primary dropdown-toggle" id="exportSelector" href="#" data-backdrop="false" data-keyboard="false" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                <i class="fas fa-file-export"></i>
                            </a>
                            <div id="export" class="dropdown-menu min-w-0" aria-labelledby="exportSelector">
                                <a class="text-primary dropdown-item" href="{{ route('calls.export') }}" data-toggle="modal" data-target="#modalSpinner"><i class="fas fa-file-csv"></i> CSV</a>
                                <a class="text-primary dropdown-item" href="{{ route('calls.export', ['filetype' => 'xlsx']) }}" data-toggle="modal" data-target="#modalSpinner"><i class="fas fa-file-excel"></i> XLSX</a>
                                {{-- <a class="text-primary dropdown-item" href="{{ route('calls.export', ['filetype' => 'pdf']) }}" data-toggle="modal" data-target="#modalExport"><i class="fas fa-file-pdf"></i> PDF</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable-calls" class="object-table table table-sm table-striped" style="width: 100%">
                        <thead class="thead-light">
                            <tr>
                                {{-- <th class="actions text-center px-0">
                                    <i class="fas fa-tools text-black sorting_disabled"></i>
                                </th> --}}
                                <th>
                                    <div class="p-0 filter-col">
                                        {{-- <input type="date" class="form-control filter-col unstyled" data-col="timestart" data-onload="date"> --}}
                                    </div>
                                </th>
                                <th>
                                    <div class="p-0">
                                        <input type="text" class="form-control filter-col" data-col="callfrom">
                                    </div>
                                </th>
                                <th>
                                    <div class="p-0">
                                        <input type="text" class="form-control filter-col" data-col="callto">
                                    </div>
                                </th>
                                <th>
                                    <div class="p-0 filter-col">
                                    </div>
                                </th>
                                <th>
                                    <div class="p-0 filter-col">
                                    </div>
                                </th>
                                <th>
                                    <div class="p-0 filter-col">
                                    </div>
                                </th>
                                <th>
                                    <div class="p-0">
                                        <select class="form-control filter-col" data-col="status" style="-webkit-appearance: none;">
                                            <option value="">---</option>
                                            <option value="ANSWERED">@Lang('calls.answered')</option>
                                        </select>
                                    </div>
                                </th>
                                <th>
                                    <div class="p-0">
                                        <select class="form-control filter-col" data-col="type" style="-webkit-appearance: none;">
                                            <option value="">---</option>
                                            <option value="Inbound">@Lang('calls.inbound')</option>
                                            <option value="Transfer">@Lang('calls.transfer')</option>
                                        </select>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                {{-- <th class="actions text-center px-0">
                                    <i class="fas fa-tools text-black sorting_disabled"></i>
                                </th> --}}
                                <th>
                                    <div>
                                        @Lang('forms.fields.timestart')
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        @Lang('forms.fields.call_from')
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        @Lang('forms.fields.call_to')
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        @Lang('forms.fields.call_duration')
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        @Lang('forms.fields.talk_duration')
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        @Lang('forms.fields.wait_duration')
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        @Lang('forms.fields.status')
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        @Lang('forms.fields.type')
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        {{-- <tfoot>
                            <td class='dt-search'>
                                <input type="date" name='timestart' data-onload="date">
                            </td>
                            <td class='dt-search'>
                                <input type="number" name="callfrom">
                            </td>
                            <td class='dt-search'>
                                <input type="number" name="callto">
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class='dt-search'>
                                <select name="type" id="">
                                    <option value="null">@lang('general.select_one')</option>
                                    <option value="Inbound">@lang('calls.inbound')</option>
                                    <option value="Transfer">@lang('calls.transfer')</option>
                                </select>
                            </td>
                        </tfoot> --}}
                    </table>
                    {{-- {{ $cdrs->links() }} --}}
                    {{-- <hr class="mt-4 mb-3"> --}}

                </div>
            </div>
        </div>
    </div>
    @include('layouts.partials.modal_spinner')
    <div id="labels" class="d-none">
        <div id="averageMonthlyWaitTime">@Lang('charts.labels.average_wait_time_in_sec')</div>
        <div id="weightedAverageMonthlyWaitTime">@Lang('charts.labels.weighted_average_wait_time_in_sec')</div>
        <div id="maxMonthlyWaitTime">@Lang('charts.labels.max_wait_time_in_sec')</div>
        <div id="monthlyTotalCalls">@Lang('charts.labels.monthly_total_calls')</div>
        <div id="monthlyFrontOfficeCalls">@Lang('charts.labels.monthly_front_office_calls')</div>
        <div id="monthlyGenericCalls">@Lang('charts.labels.monthly_generic_calls')</div>
        <div id="monthlyInternalCalls">@Lang('charts.labels.monthly_internal_calls')</div>
        <div id="monthlyTotalLostCalls">@Lang('charts.labels.monthly_total_lost_calls')</div>
        <div id="monthlyFrontOfficeLostCalls">@Lang('charts.labels.monthly_front_office_lost_calls')</div>
        <div id="monthlyGenericLostCalls">@Lang('charts.labels.monthly_generic_lost_calls')</div>
        <div id="monthlyInternalLostCalls">@Lang('charts.labels.monthly_internal_lost_calls')</div>
    </div>
    <div id="titles" class="d-none">
        <div id="averageMonthlyWaitTime">@Lang('charts.titles.average_monthly_wait_time')</div>
        <div id="minMaxMonthlyWaitTime">@Lang('charts.titles.min_max_monthly_wait_time')</div>
        <div id="minMaxExternalMonthlyWaitTime">@Lang('charts.titles.min_max_monthly_wait_time_inbound')</div>
        <div id="averageExternalMonthlyWaitTime">@Lang('charts.titles.average_monthly_wait_time_inbound')</div>
        <div id="totalCallsByTypeAndMonthExcludeLost">@Lang('charts.titles.total_calls_type_month_not_lost')</div>
        <div id="totalLostCallsByTypeAndMonth">@Lang('charts.titles.total_lost_calls_type_month')</div>
    </div>
</div>
@endsection

