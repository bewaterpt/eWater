@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.failure_types_create')
                </div>
                <div class="card-body">
                    <form method="POST" action={{Route('settings.failure_types.create')}}>
                        @csrf
                        <div class="form-row mb-2 justify-content-center">
                            <div class="form-group col-md-12">
                                <label for="inputDesignation">@Lang('forms.fields.designation')</label>
                                <input type="text" name="designation" class="form-control" id="inputDesignation" value="" placeholder="{{__('forms.placeholders.designation')}}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">@Lang('general.save')</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

