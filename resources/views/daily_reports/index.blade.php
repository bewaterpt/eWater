@extends('layouts.app')

<input class="form-control datepicker" placeholder="Select Date" name="date" type="text">

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">@Lang('settings.users.list')</div>
                <div class="card-body">
                    <table id="datatable-users" class="object-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>
                                    @Lang('general.daily_reports.launch_number')
                                </th>
                                <th>
                                    @Lang('general.article')
                                </th>
                                <th>
                                    @Lang('general.roles')
                                </th>
                                <th>
                                    @Lang('general.agent')
                                </th>
                                <th>
                                    @Lang('general.state')
                                </th>
                                <th>
                                    @Lang('general.actions')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                {{-- <tr>
                                    <td>
                                        <a href="{{Route('settings.users.view', ['id' => $user->id])}}">
                                            {{$user->name}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$user->username}}
                                    </td>
                                    <td>
                                        @foreach($user->roles()->get() as $role)
                                            {{$role->name}}
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($user->agent()->first())
                                        <a href="{{Route('settings.agent.view', ['id' => $user->agent()->first()->id])}}">
                                            {{$user->agent()->first()->name}}
                                        </a>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        @if($user->enabled)
                                            <i class="fas fa-check" style="color: limegreen"></i>
                                        @else
                                            <i class="fas fa-times" style="color: red"></i>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a class="" href="{{$user->id === Auth::user()->id ? Route('settings.users.edit_self') : Route('settings.users.edit', ['id' => $user->id])}}" title="@Lang('general.action_edit')"><i class="fas fa-user-edit"></i></a>
                                        @if($user->id !== Auth::user()->id)
                                            <a href="{{Route('settings.users.toggle_state', ['id' => $user->id])}}" class="{{ $user->enabled ? 'disable' : 'enable' }}" title="{{$user->enabled ? __('general.action_disable') : __('general.action_enable')}}">
                                                @if($user->enabled)
                                                    <i class="fas fa-user-times"></i>
                                                @else
                                                    <i class="fas fa-user-check"></i>
                                                @endif
                                            </a>
                                            {{-- @if($user['id'] !== 1)
                                                <a href="{{Route('settings.users.delete', ['id' => $user['id']])}}" class="delete" title="@Lang('general.action_delete')"><i class="fas fa-trash-alt"></i></a>
                                            @endif
                                        @endif
                                    </td>
                                </tr> --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

