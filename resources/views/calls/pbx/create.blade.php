@extends('layouts.app')

@section('content')
<div id="calls-pbx-create" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('calls.pbx.create')
                </div>
                <div class="card-body">
                    <form method="POST" action={{Route('calls.pbx.store')}}>
                        @csrf
                        <input type="hidden" name="users" id="users" />
                        <div class="form-row mb-2 justify-content-center">
                            <div class="form-group col-md-6">
                                <label for="inputDesignation">@Lang('forms.fields.designation')</label>
                                <input type="text" name="designation" class="form-control @error('name') is-invalid @enderror" id="inputDesignation" value="" required>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputDelegation">@Lang('forms.fields.delegation')</label>
                                <select name="delegation" id="inputDelegation" class="form-control">
                                    @foreach($delegations as $delegation)
                                        <option value="{{ $delegation->id }}">{{ $delegation->designation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputProtocol">@Lang('forms.fields.protocol')</label>
                                <select name="protocol" id="inputProtocol" class="form-control">
                                    <option value="http">HTTP</option>
                                    <option value="https">HTTPS</option>
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="inputUrl">@Lang('forms.fields.url')</label>
                                <input type="text" name="url" class="form-control @error('url') is-invalid @enderror" id="inputUrl" value="" autocomplete="off">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputPort">@Lang('forms.fields.port')</label>
                                <input type="text" name="port" class="form-control @error('port') is-invalid @enderror" id="inputPort" value="" autocomplete="off">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputApiBase">@Lang('forms.fields.api_base_url')</label>
                                <input type="text" name="api_base" class="form-control @error('api_base') is-invalid @enderror" id="inputApiBase" value="" autocomplete="off">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputUsername">@Lang('forms.fields.username')</label>
                                <input type="text" name="api_username" class="form-control @error('username') is-invalid @enderror" id="inputUsername" value="" autocomplete="off">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword">@Lang('forms.fields.password')</label>
                                <input type="password" name="api_password" class="form-control @error('username') is-invalid @enderror" id="inputPassowrd" value="" autocomplete="off">
                                <a href="#" class="text-dark show-password">
                                    <i class="fa fa-eye"></i>
                                </a>
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

