@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.teams.create')
                </div>
                <div class="card-body">
                    <form method="POST" action={{Route('settings.teams.store')}}>
                        @csrf
                        <div class="form-row mb-2 justify-content-center">
                            <div class="form-group col-md-12">
                                <label for="inputName">@Lang('forms.fields.name')</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="inputName" value="" required>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <label for="inputColor">@Lang('forms.fields.color')</label>
                                <div id="teams-colorpicker" class="input-group">
                                    <input type="text" name="color" class="form-control @error('color') is-invalid @enderror" id="inputColor" value="" autocomplete="off">
                                    <span class="input-group-append">
                                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                    </span>
                                </div>
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

