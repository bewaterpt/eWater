@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.teams.edit'): {{ $team->name }}
                </div>
                <div class="card-body">
                    <form method="POST" action={{Route('settings.teams.update', ['id' => $team->id])}}>
                        @csrf
                        <div class="form-row mb-2 justify-content-center">
                            <div class="form-group col-md-12">
                                <label for="inputName">@Lang('forms.fields.name')</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="inputName" value="{{ $team->name }}" required>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
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

