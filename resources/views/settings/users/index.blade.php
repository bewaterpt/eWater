@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">@Lang('settings.users.list')</div>
                <div class="card-body">
                    <table id="datatable-users" class="object-table table table-sm table-striped" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="actions text-center px-0">
                                    <i class="fas fa-tools text-black sorting_disabled"></i>
                                </th>
                                <th>
                                    @Lang('general.name')
                                </th>
                                <th>
                                    @Lang('general.username')
                                </th>
                                <th>
                                    @Lang('general.roles')
                                </th>
                                <th>
                                    @Lang('general.teams')
                                </th>
                                <th class="text-center">
                                    @Lang('general.accountable')
                                </th>
                                <th class="text-center">
                                    @Lang('general.state')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="actions">
                                        {{-- {{$user->getAttributes()['id'] === Auth::user()->id ? Route('settings.users.edit_self') :  --}}
                                        <a class="" href="{{ Route('settings.users.edit', ['id' => $user->getAttributes()['id']]) }}" title="@Lang('general.action_edit')"><i class="fas fa-user-edit"></i></a>
                                        @if($user->getAttributes()['id'] !== Auth::user()->id)
                                            <a href="{{ Route('settings.users.toggle_state', ['id' => $user->getAttributes()['id']]) }}" class="{{ $user->enabled ? 'disable' : 'enable' }}" title="{{$user->enabled ? __('general.action_disable') : __('general.action_enable')}}">
                                                @if($user->enabled)
                                                    <i class="fas fa-user-times"></i>
                                                @else
                                                    <i class="fas fa-user-check"></i>
                                                @endif
                                            </a>
                                            {{-- @if($user['id'] !== 1)
                                                <a href="{{Route('settings.users.delete', ['id' => $user['id']])}}" class="delete" title="@Lang('general.action_delete')"><i class="fas fa-trash-alt"></i></a>
                                            @endif --}}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{Route('settings.users.view', ['id' => $user->getAttributes()['id']])}}">
                                            {{$user->name}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$user->username}}
                                    </td>
                                    <td>
                                        @if($user->username==="admbm")
                                        {{-- {{dd($user->roles())}} --}}
                                        @endif
                                        @foreach($user->roles()->get() as $role)
                                            {{ $role->name }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($user->teams()->get() as $team)
                                            {{ $team->name }}<br>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        @if($user->accountable)
                                            <i class="fas fa-check text-success"></i>
                                        @else
                                            <i class="fas fa-times text-danger"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->enabled)
                                            <i class="fas fa-check text-success"></i>
                                        @else
                                            <i class="fas fa-times text-danger"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

