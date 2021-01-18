@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.motives.create')
                </div>
                <div class="card-body">
                    <form id="create-motive" method="POST" action={{Route('settings.motives.store')}}>
                        @csrf
                        <input type="hidden" name="roles" id="roles"/>
                        <input type="hidden" name="teams" id="teams"/>
                        <div class="form-row mb-2">
                            <div class="form-group col-md-6">
                                <label for="inputName">@Lang('forms.fields.name')</label>
                                <input type="text" name="name" class="form-control" id="inputName" value="{{$user->name ? $user->name : ''}}" placeholder="{{__('forms.placeholders.name')}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputEmail">@Lang('forms.fields.email')</label>
                                <input type="email" name="email" class="form-control-plaintext" readonly value="{{$user->email ? $user->email : __('forms.fields.no_value')}}" id="inputEmail" placeholder="{{__('forms.placeholders.email')}}">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputAccountable" class="mr-2">@Lang('forms.fields.accountable_bool')</label>
                                <input type="checkbox" name="accountable" class="form-control-checkbox" {{$user->accountable ? 'checked' : ''}} id="inputAccountable">
                            </div>
                            {{-- <div class="form-group col-md-12 text-left">
                                <label for="inputTeam" class="mr-2">@Lang('forms.fields.team')</label>
                                <select name="team" class="form-control selecpicker col-md-6" id="inputTeam">
                                    <option value="none">@lang('forms.values.none')</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ $user->teams()->exists() && $user->team()->first()->id === $team->id ? 'selected' : ''}}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>
                        <div class="mb-3">@Lang('general.roles')</div>
                        @include('components.multiselect_listbox', ['left' => $roles, 'right' => $user->roles()->get(), 'lField' => 'name', 'rField' => 'name', 'hiddenField' => 'roles'])
                        @include('components.multiselect_listbox', ['left' => $teams, 'right' => $user->teams()->get(), 'lField' => 'name', 'rField' => 'name', 'hiddenField' => 'teams'])
                        <button type="submit" class="btn btn-primary">@Lang('general.save')</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

