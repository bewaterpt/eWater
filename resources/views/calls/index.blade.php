@extends('layouts.app')

@section('content')
<div class="container" id="calls-dashboard">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @Lang('calls.pbx.list')
                    {{-- <span class="float-right">
                        <div class="dropdown show">
                            <a class="text-info dropdown-toggle" id="exportSelector" href="#" target="_blank" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                <i class="fas fa-file-export"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="exportSelector">
                                <a class="text-info dropdown-item" href="{{Route('calls.export')}}">CSV <i class="fas fa-file-csv"></i></a>
                                <a class="text-info dropdown-item" href="#"></a>
                                <a class="text-info dropdown-item" href="#"></a>
                            </div>
                        </div>
                    </span> --}}
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable-calls" class="object-table table table-sm table-striped" style="width: 100%">
                        <thead class="thead-light">
                            <tr>
                                {{-- <th class="actions text-center px-0">
                                    <i class="fas fa-tools text-black sorting_disabled"></i>
                                </th> --}}
                                <th>
                                    @Lang('forms.fields.call_from')
                                </th>
                                <th>
                                    @Lang('forms.fields.call_to')
                                </th>
                                <th>
                                    @Lang('forms.fields.timestart')
                                </th>
                                <th>
                                    @Lang('forms.fields.call_duration')
                                </th>
                                <th>
                                    @Lang('forms.fields.talk_duration')
                                </th>
                                <th>
                                    @Lang('forms.fields.wait_duration')
                                </th>
                                <th>
                                    @Lang('forms.fields.status')
                                </th>
                                <th>
                                    @Lang('forms.fields.type')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    {{-- {{ $cdrs->links() }} --}}
                    <hr class="mt-4 mb-3">
                    <div class="col-md-12 text-center text-bold mb-4">
                        @Lang('general.statistics')
                    </div>
                    <div class="col-md-12 float-left" width="100%" height="100%">
                        <canvas id="monthlyWaitTimeInfo">
                        </canvas>
                    </div>
                    <div class="col-md-12 float-left" width="100%" height="100%">
                        <canvas id="monthlyCallNumberInfo">
                        </canvas>
                    </div>
                    <div class="col-md-12 float-left" width="100%" height="100%">
                        <canvas id="monthlyLostCallNumberInfo">
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="labels" class="d-none">
        <div id="averageMonthlyWaitTime">@Lang('charts.labels.average_wait_time_in_sec')</div>
        <div id="maxMonthlyWaitTime">@Lang('charts.labels.max_wait_time_in_sec')</div>
        <div id="minMonthlyWaitTime">@Lang('charts.labels.min_wait_time_in_sec')</div>
    </div>
    <div id="titles" class="d-none">
        <div id="averageMonthlyWaitTime">@Lang('charts.titles.average_monthly_wait_time')</div>
        <div id="minMaxMonthlyWaitTime">@Lang('charts.titles.min_max_monthly_wait_time')</div>
        <div id="minMaxExternalMonthlyWaitTime">@Lang('charts.titles.min_max_monthly_wait_time_inbound')</div>
        <div id="averageExternalMonthlyWaitTime">@Lang('charts.titles.average_monthly_wait_time_inbound')</div>
    </div>
</div>
@endsection

