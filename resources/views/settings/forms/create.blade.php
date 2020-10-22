@extends('layouts.app')

@section('content')
<div class="container" id="create-custom-form">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.forms.create')
                </div>
                <div class="card-body">
                    <form id="create-form" method="POST" action={{Route('settings.forms.store')}}>
                        @csrf
                        <div class="form-row mb-2 justify-content-center">
                            <div class="form-group col-md-12">
                                <label for="inputName">@Lang('forms.fields.name')</label>
                                <input type="text" name="form-name" class="form-control @error('form-name') is-invalid @enderror" id="inputName" value="" required>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <label for="inputDescription">@Lang('forms.fields.description')</label>
                                <input type="text" name="form-description" class="form-control @error('form-description') is-invalid @enderror" id="inputDescription" value="" autocomplete="off">
                            </div>
                            <div class="col-md-12">
                                <div class="header min-h-100 py-3 sticky-top" style="pointer-events:none">
                                    <span class="col-md-6 border-left border-top py-3 border-bottom float-left bg-white">
                                        @Lang('forms.fields.fields')
                                    </span>
                                    <span class="col-md-6 border-right border-top py-3 border-bottom float-right text-right bg-white" style="pointer-events:all">
                                        <div class="dropdown show">
                                            <a class="text-primary dropdown-toggle" id="exportSelector" href="#" data-backdrop="false" data-keyboard="false" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                                @Lang('general.add_field')
                                            </a>
                                            <div id="add-field" class="dropdown-menu min-w-0" aria-labelledby="exportSelector">
                                                <a class="text-black dropdown-item" href="#" data-type="text" data-tag="input">@Lang('forms.fields.text')</a>
                                                <a class="text-black dropdown-item" href="#" data-type="file" data-tag="input">@Lang('forms.fields.file')</a>
                                                <a class="text-black dropdown-item" href="#" data-type="select" data-tag="select">@Lang('forms.fields.select')</a>
                                                <a class="text-black dropdown-item" href="#" data-type="textarea" data-tag="textarea">@Lang('forms.fields.textarea')</a>
                                                <a class="text-black dropdown-item" href="#" data-type="checkbox" data-tag="input">@Lang('forms.fields.multiple_choice')</a>
                                                <a class="text-black dropdown-item" href="#" data-type="radio" data-tag="input">@Lang('forms.fields.unique_choice')</a>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div id="field-container" class="field-container px-4 row"></div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-inline-block">
                            <span class="btn-text">@Lang('forms.buttons.save')</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span id="spinner-text" class="loading-text d-none">@Lang('general.loading')</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.partials.forms_fields')
@endsection

