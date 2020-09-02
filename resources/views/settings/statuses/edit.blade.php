@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.edit_status') {{ $status->name }}
                </div>
                <div class="card-body">
                    <form id="updateStatus" method="POST" action={{Route('settings.statuses.update', ['id' => $status->id])}}>
                        @csrf
                        <input type="hidden" name="roles" id="roles"/>
                        <div class="form-row mb-5">
                            <div class="form-group col-md-6">
                                <label for="inputName">@Lang('forms.fields.designation')</label>
                                <input type="text" name="name" class="form-control" id="inputName" value="{{$status->name }}" placeholder="{{ __('forms.placeholders.name') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputEmail">@Lang('forms.fields.slug')</label>
                                <input type="text" name="slug" class="form-control-plaintext" readonly value="{{ $status->slug }}" id="inputEmail" placeholder="{{ __('forms.placeholders.slug') }}">
                            </div>
                        </div>
                        <div class="mb-3">@Lang('general.daily_reports.')</div>
                        @include('components.multiselect_listbox', ['left' => $roles, 'right' => $status->roles()->get(), 'lField' => 'name', 'rField' => 'name', 'hiddenField' => 'roles'])
                        <button type="submit" class="btn btn-primary">@Lang('general.save')</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

