@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @Lang('general.daily_reports.create')
                </div>
                <div class="card-body">
                    <form class="" method="post" action={{Route('daily_reports.create')}}>
                        <table class="mb-4">
                            <thead>
                                <tr>
                                    <th>@Lang('forms.fields.work_number')</th>
                                    <th>@Lang('forms.fields.article')</th>
                                    <th>@Lang('forms.fields.unit_price')</th>
                                    <th>@Lang('forms.fields.quantity')</th>
                                    <th>@Lang('forms.fields.date')</th>
                                    <th>@Lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="number" name="work-number[]" min="0" class="form-control col-md-9" id="inputWorkNumber">
                                    </td>
                                    <td>
                                        <select name="article[]" class="form-control selectpicker col-md-11" id="inputArticle" data-dropup-auto="false">
                                            @foreach($articles as $descricao => $cod)
                                                <option value="{{ $cod }}">{{ $descricao }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="has-spinner">
                                        <input type="number" name="unit-price[]" step="any" class="form-control float-left col-md-7" min="0" value="0" id="inputUnitPrice">
                                        <div id="spinner float-left invisible" style="display:none">
                                            <img id="spinner-outer" src="{{ asset('/images/spinner-outer.png') }}">
                                            <img id="spinner-inner" src="{{ asset('/images/spinner-inner.png') }}">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="work-number[]" min="0" class="form-control col-md-6" id="inputQuantity">
                                    </td>
                                    <td>
                                        <input class="form-control datepicker col-md-11" placeholder="Select Date" name="date[]" type="date">
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">@Lang('forms.buttons.save')</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

