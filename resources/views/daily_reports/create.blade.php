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
                        <a class="add-work btn btn-info text-white" title="{{__('tooltips.daily_reports.add_work')}}" href="#"><i class="fas fa-plus"></i> @Lang('general.daily_reports.add_work')</a>
                    </span>
                </div>
                <div class="card-body">
                    <form id="report" class="" method="post" action={{Route('daily_reports.create')}}>
                        @csrf
                        <fieldset>
                            <div class="float-left col-md-2">
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
                            </div>
                            <div class="float-right col-md-10">
                                <label for="inputComment">@Lang('general.daily_reports.comment')</label>
                                <textarea name="comment" rows="7" id="inputComment" class="form-control float-right mh-100"></textarea>
                            </div>
                        </fieldset>

                        <div id="original-work" class="work card mt-5 mb-4">
                            <div class="card-header col-md-12">
                                <span class="d-inline">
                                    @Lang('general.daily_reports.work-number-x') <input type="number" required class="work-number form-control col-md-2 d-inline border-bottom"/>
                                </span>
                                &nbsp;&nbsp;
                                <span class="d inline">
                                    @Lang('general.daily_reports.driven-km') <input type="number" required class="driven-km form-control col-md-1 d-inline border-bottom" name="driven-km"/>
                                </span>
                                <span class="float-right mt-1">
                                    <a id="addRow" tabindex="-1" class="text-success" title="{{__('tooltips.daily_reports.add_row')}}" href="#"><i class="fas fa-plus"></i></a>
                                    <a class="remove-work text-danger" title="{{__('tooltips.daily_reports.remove-work')}}" href="#"><i class="fas fa-trash-alt"></i></a>
                                </span>
                            </div>
                            <div class="card-body">
                                <table id="report-lines" class="table table-sm table-borderless">
                                    <thead>
                                        <tr>
                                            <th>@Lang('forms.fields.worker')</th>
                                            <th>@Lang('forms.fields.article')</th>
                                            <th>@Lang('forms.fields.hours')</th>
                                            <th>@Lang('forms.fields.date')</th>
                                            <th>@Lang('general.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="first">
                                            <td>
                                                <input type="text" name="worker" required class="form-control col-md-9" id="inputWorkNumber" required>
                                            </td>
                                            <td>
                                                <select name="article" required class="form-control selectpicker col-md-12" id="inputArticle" data-dropup-auto="false" required>
                                                    @foreach($articles as $descricao => $cod)
                                                        <option value="{{ $cod }}">{{ $descricao }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" required name="quantity" min="0" value="0" class="form-control col-md-6" id="inputQuantity" required>
                                            </td>
                                            <td>
                                                <input id="inputDatetime" required class="form-control datepicker col-md-10" placeholder="Select Date" name="datetime" type="datetime-local" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}" required>
                                            </td>
                                            <td class="actions text-center">
                                                <a id="removeRow" href="#" class="text-danger"><i class="fas fa-times"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span class="btn-text">@Lang('forms.buttons.save')</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span id="spinner-text" class="loading-text d-none">@Lang('general.loading')</span>
                        </button>
                        <span class="float-right">
                            <a class="add-work btn btn-info text-white" title="{{__('tooltips.daily_reports.add_work')}}" href="#"><i class="fas fa-plus"></i> @Lang('general.daily_reports.add_work')</a>
                        </span>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="errors" class="d-none">
    <span id="differentKm"> @Lang('errors.different_km') </span>
</div>
@endsection
