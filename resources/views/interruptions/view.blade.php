@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('general.interruptions.view')
                </div>
                <div class="card-body">
                    <div class="form-row mb-5">
                        <div class="form-group col-md-6">
                            <p>@Lang('general.interruptions.ref')</p>
                            <div>{{ $interruption->work_id }} </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputEmail">@Lang('forms.fields.email')</label>
                            <input type="email" name="email" class="form-control-plaintext" readonly value="{{$user->email ? $user->email : __('settings.no_value')}}" id="inputEmail" placeholder="{{__('forms.placeholders.email')}}">
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary float-right" value="{{__('general.save')}}"/>
                    <div class="mb-3">Reset Password</div>
                    <fieldset class="border-top mb-3">
                        <div class="form-row">
                            <div class="form-group pt-2 col-md-6">
                                <label for="inputCurrentPassword">@Lang('forms.fields.current_password')</label>
                                <input type="password" name="currentPass" readonly class="form-control" id="inputCurrentPassword">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputNewPassword">@Lang('forms.fields.new_password')</label>
                                <input type="password" name="newPass" readonly class="form-control" id="inputNewPassword">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputNewPasswordRepeat">@Lang('forms.fields.new_password_repeat')</label>
                                <input type="password" name="newPassRepeat" class="form-control" id="inputNewPasswordRepeat">
                            </div>
                        </div>
                    </fieldset>
                    <input type="submit" class="btn btn-primary float-right" value="{{__('general.reset_password')}}"/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

