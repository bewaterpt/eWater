@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.profile'): {{ $user->name."(". $user->username .")"}}

                    <span class="float-right">
                        <a href="{{Route('settings.users.edit', ['id' => $user->id])}}">
                            <i class="fas fa-user-edit"></i>
                        </a>
                        @if($user['id'] !== Auth::user()->id)
                        <a href="{{Route('settings.users.toggle_state', ['id' => $user['id']])}}" class="{{ $user['enabled'] ? 'disable' : 'enable' }}" title="{{$user['enabled'] ? __('general.action_disable') : __('general.action_enable')}}">
                            @if($user['enabled'])
                                <i class="fas fa-user-times"></i>
                            @else
                                <i class="fas fa-user-check"></i>
                            @endif
                        </a>
                        @if($user['id'] !== 1)
                            <a href="{{Route('setings.users.delete', ['id' => $user['id']])}}" class="delete" title="@Lang('general.action_delete')"><i class="fas fa-trash-alt"></i></a>
                        @endif
                    @endif
                    </span>
                </div>
                <div class="card-body">
                    <pre>{{print_r($user)}}</pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

