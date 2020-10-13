@extends('layouts.app')

@section('content')
<div id="daily-reports-create" class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <button type="submit" class="btn float-left btn-primary" form="report">@Lang('forms.buttons.save')</button>
                    @Lang('general.daily_reports.create')
                    <span class="float-right">
                        <a class="add-work btn btn-primary text-white" title="{{__('tooltips.daily_reports.add_work')}}" href="#"><i class="fas fa-plus"></i> @Lang('general.daily_reports.add_work')</a>
                    </span>
                </div>
                <div class="card-body">
                    <form id="report" class="" method="post" action={{Route('daily_reports.store')}}>
                        @csrf
                        <fieldset>
                            <div class="float-left col-md-2 p-0">
                                <div class="col-md-11 p-0">
                                    <label for="inputDatetime">@Lang('forms.fields.date')</label>
                                    <input id="inputDatetime" required class="form-control datepicker" placeholder="Select Date" name="datetime" type="date" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" required>
                                    <label for="inputTeam">@Lang('forms.fields.team')</label>
                                    <select id="inputTeam" required class="form-control selectpicker" placeholder="Select Date" name="team">
                                        @foreach($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="inputPlate">@Lang('general.daily_reports.plate')</label>
                                    <input type="text" id="inputPlate" required name="plate" class="form-control" pattern="([A-Z]{2}-[0-9]{2}-[0-9]{2}|[0-9]{2}-[A-Z]{2}-[0-9]{2}|[0-9]{2}-[0-9]{2}-[A-Z]{2})">
                                    <label for="inputKmDeparture">@Lang('general.daily_reports.km_departure')</label>
                                    <input type="number" id="inputKmDeparture" required name="km-departure" class="form-control">
                                    <label for="inputKmArrival">@Lang('general.daily_reports.km_arrival')</label>
                                    <input type="number" id="inputKmArrival" required name="km-arrival" class="form-control">
                                    <div id="total-km-holder" class="border mt-3 rounded p-1">
                                        <span id="title">@Lang('general.daily_reports.total_km'): </span>
                                        <span id="value"></span>
                                    </div>
                                    <div id="total-hour-holder" class="border mt-3 rounded p-1">
                                        <span id="title">@Lang('general.daily_reports.total_hr'): </span>
                                        <span id="value"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="float-right col-md-10">
                                <label for="inputComment">@Lang('general.daily_reports.comment')</label>
                                <textarea name="comment" rows="13" id="inputComment" class="form-control text-editor float-right mh-100"></textarea>
                            </div>
                        </fieldset>

                        <div id="original-work" class="work card mt-5 mb-4">
                            <div class="card-header col-md-12">
                                <span id="error-popover" class="d-inline" data-placement="bottom" data-trigger="focus">
                                    @Lang('general.daily_reports.work_number_x') <input type="number" required class="work-number form-control col-md-2 d-inline border-bottom"/>
                                    <div class="d-none popover popover-data">
                                        <span id="title" class="replace">
                                            @Lang('errors.error')
                                        </span>
                                        <span id="content" class="replace">
                                        </span>
                                    </div>
                                </span>
                                &nbsp;&nbsp;
                                <span class="d inline">
                                    @Lang('general.daily_reports.driven-km') <input type="number" required class="driven-km form-control col-md-1 d-inline border-bottom" name="driven_km"/>
                                </span>
                                <span class="float-right mt-1">
                                    <a id="addRow" tabindex="-1" class="text-success" title="{{__('tooltips.daily_reports.add_row')}}" href="#"><i class="fas fa-plus"></i></a>
                                    <a class="remove-work text-danger" title="{{__('tooltips.daily_reports.remove-work')}}" href="#"><i class="fas fa-trash-alt"></i></a>
                                </span>
                            </div>
                            <div class="card-body table-responsive">
                                <table id="report-lines" class="table table-sm table-borderless w-auto m-auto">
                                    <thead>
                                        <tr>
                                            <th>@Lang('general.actions')</th>
                                            <th>@Lang('forms.fields.worker')</th>
                                            <th>@Lang('forms.fields.article')</th>
                                            <th>@Lang('forms.fields.hours')</th>
                                            <th class="info"></th>
                                            {{-- <th>@Lang('forms.fields.date')</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="first">
                                            <td class="actions text-center">
                                                <a id="removeRow" href="#" class="text-danger"><i class="fas fa-times"></i></a>
                                            </td>
                                            <td>
                                                <select type="text" name="worker" required class="form-control" id="inputWorker" required>
                                                    @foreach ($workers as $worker)
                                                        <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="article_id" required class="form-control selectpicker" id="inputArticle" data-dropup-auto="false" required>
                                                    {{-- {{ dd($articles) }} --}}
                                                    @foreach($articles as $descricao => $cod)
                                                        <option value="{{ $cod }}">{{ $descricao }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="quantity">
                                                <input type="number" required name="quantity" min="0" value="0" step=".01" class="form-control" id="inputQuantity" required>
                                            </td>
                                            <td class="info">
                                                <a id="info" class="btn info-tooltip ri-information-line text-info ri-lg cursor-info" data-toggle="tooltip" data-placement="right" data-trigger="hover" title="{!! trans('info.hours_as_quantity') !!}"></a>
                                            </td>
                                            {{-- <td class="date">
                                                <input id="inputDatetime" required class="form-control datepicker" placeholder="Select Date" name="datetime" type="datetime-local" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}" required>
                                            </td> --}}
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-inline-block">
                            <span class="btn-text">@Lang('forms.buttons.save')</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span id="spinner-text" class="loading-text d-none">@Lang('general.loading')</span>
                        </button>
                        <div id="warnings" class="w-auto d-inline-block">
                            <div class="justify-content-center">
                                <div id="inferiorKmWarn" class="alert alert-warning m-0 d-none p-1">
                                    <ul class="mb-0">
                                        <li>@Lang('errors.inferior_km_warning')</li>
                                    </ul>
                                </div>
                                <div id="superiorKmErr" class="alert alert-danger m-0 d-none p-1">
                                    <ul class="mb-0">
                                        <li>@Lang('errors.superior_km_error')</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <span class="float-right d-inline-block">
                            <a class="add-work btn btn-primary text-white" title="{{__('tooltips.daily_reports.add_work')}}" href="#"><i class="fas fa-plus"></i> @Lang('general.daily_reports.add_work')</a>
                        </span>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('components.popover', ['id' => 'error', 'type' => 'error'])
@endsection
