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
                    <form id="updateTeam" method="POST" action={{Route('settings.teams.update', ['id' => $team->id])}}>
                        @csrf
                        <input type="hidden" name="users" id="users" />
                        <div class="form-row mb-2 justify-content-center">
                            <div class="form-group col-md-12">
                                <label for="inputName">@Lang('forms.fields.name')</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="inputName" value="{{ $team->name }}" required>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <label for="inputColor">@Lang('forms.fields.color')</label>
                                <div id="teams-colorpicker" class="input-group">
                                    <input type="text" name="color" class="form-control @error('color') is-invalid @enderror" id="inputColor" value="{{ $team->color }}" autocomplete="off">
                                    <span class="input-group-append">
                                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                    </span>
                                </div>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3 mb-3">@Lang('general.users')</div>
                        @include('components.multiselect_listbox', ['left' => $users, 'right' => $team->users()->get(), 'lField' => 'name', 'rField' => 'name', 'hiddenField' => 'users'])
                        <button type="submit" class="btn btn-primary float-right">@Lang('general.save')</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

