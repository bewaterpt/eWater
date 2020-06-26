@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.edit_user') {{ $user->name }}
                </div>
                <div class="card-body">
                    <form id="updateUser" method="POST" action={{Route('settings.users.update', ['id' => $user->id])}}>
                        @csrf
                        <input type="hidden" name="roles" id="roles"/>
                        <div class="form-row mb-5">
                            <div class="form-group col-md-6">
                                <label for="inputName">@Lang('forms.fields.name')</label>
                                <input type="text" name="name" class="form-control" id="inputName" value="{{$user->name ? $user->name : ''}}" placeholder="{{__('forms.placeholders.name')}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputEmail">@Lang('forms.fields.email')</label>
                                <input type="email" name="email" class="form-control-plaintext" readonly value="{{$user->email ? $user->email : __('settings.no_value')}}" id="inputEmail" placeholder="{{__('forms.placeholders.email')}}">
                            </div>
                        </div>
                        <div class="mb-3">@Lang('general.daily_reports.')</div>
                        @include('components.multiselect_listbox', ['left' => $roles, 'right' => $user->roles()->get(), 'lField' => 'name', 'rField' => 'name'])
                        <button type="submit" class="btn btn-primary">@Lang('general.save')</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

