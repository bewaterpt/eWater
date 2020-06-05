@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @Lang('general.daily_reports.create')
                    <span class="float-right">
                        <a id="addRow" class="text-success" href="#"><i class="fas fa-plus"></i></a>
                    </span>
                </div>
                <div class="card-body">
                    <form class="" method="post" action={{Route('daily_reports.create')}}>
                        @csrf
                        <table id="insert-reports" class="mb-4">
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
                                <tr class="first">
                                    <td>
                                        <input type="number" name="work-number[]" min="0" class="form-control col-md-9" id="inputWorkNumber" required>
                                    </td>
                                    <td>
                                        <select name="article[]" class="form-control selectpicker col-md-11" id="inputArticle" data-dropup-auto="false" required>
                                            @foreach($articles as $descricao => $cod)
                                                <option value="{{ $cod }}">{{ $descricao }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="has-spinner">
                                        <input type="number" name="unit-price[]" step="any" class="form-control float-left col-md-7" min="0" value="0" id="inputUnitPrice" required>
                                        <div id="spinner float-left invisible" style="display:none">
                                            <img id="spinner-outer" src="{{ asset('/images/spinner-outer.png') }}">
                                            <img id="spinner-inner" src="{{ asset('/images/spinner-inner.png') }}">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" min="0" value="0" class="form-control col-md-6" id="inputQuantity" required>
                                    </td>
                                    <td>
                                        <input class="form-control datepicker col-md-11" placeholder="Select Date" name="datetime]" type="datetime-local" required>
                                    </td>
                                    <td class="actions text-center">
                                        <a id="removeRow" href="#" class="text-danger"><i class="fas fa-times"></i></a>
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
