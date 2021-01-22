@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('forms.fields.motive')
                </div>
                <div class="card-body">
                    <form id="create-motive" method="POST" action={{Route('interruptions.motives.store')}}>
                        @csrf
                        <input type="hidden" name="roles" id="roles"/>
                        <input type="hidden" name="teams" id="teams"/>
                        <div class="form-row mb-2">
                            <div class="form-group col-md-6">
                                <label for="inputName">@Lang('general.interruptions.motive')</label>
                                <input type="text" name="name" class="form-control" id="inputName"  placeholder="{{__('forms.placeholders.motive')}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputEmail">@Lang('general.slug')</label>
                                <input type="text" name="slug" class="form-control"  id="inputSlug" placeholder="{{__('forms.placeholders.slug')}}">
                            </div>
                            <div class="form-group col-md-4 pt-4">
                                <input type="radio" name="scheduled" class="" id="inputScheduled1" value="true" >
                                <label for="inputScheduled1">@Lang('general.interruptions.is_scheduled')</label>
                                <br>
                                <input type="radio" name="scheduled" class="" id="inputScheduled2" value="false" >
                                <label for="inputScheduled2">@Lang('general.interruptions.is_unscheduled')</label>
                            </div>
                            {{-- <div class="form-group col-md-12">
                                <label for="inputAccountable" class="mr-2">@Lang('forms.fields.accountable_bool')</label>
                                <input type="checkbox" name="accountable" class="form-control-checkbox" id="inputAccountable">
                            </div> --}}
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
                        {{-- @include('components.multiselect_listbox', ['left' => $roles, 'right' => $user->roles()->get(), 'lField' => 'name', 'rField' => 'name', 'hiddenField' => 'roles'])
                        @include('components.multiselect_listbox', ['left' => $teams, 'right' => $user->teams()->get(), 'lField' => 'name', 'rField' => 'name', 'hiddenField' => 'teams']) --}}
                        <button type="submit" class="btn btn-primary">@Lang('general.save')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

