@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">@Lang('general.statuses')</div>
                <div class="card-body">
                    <table id="datatable-statuses" class="object-table table table-sm table-striped table-bordered" style="width: 100%">
                        <thead>
                            <tr>
                                <th>
                                    @Lang('general.designation')
                                </th>
                                <th>
                                    @Lang('general.slug')
                                </th>
                                <th>
                                    @Lang('general.roles')
                                </th>
                                <th>
                                    @Lang('general.actions')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statuses as $status)
                                <tr>
                                    <td>
                                        <a {{--href="{{Route('settings.users.view', ['id' => $user->id])}}" --}}>
                                            {{$status->name}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$status->slug}}
                                    </td>
                                    <td class="text-center">
                                        {{-- {{ dd($status->roles()->get()) }} --}}
                                        @foreach($status->roles()->get() as $role)
                                            {{ $role->name }}<br>
                                        @endforeach
                                    </td>
                                    {{-- <td style="text-align: center">
                                        @if($user->enabled)
                                            <i class="fas fa-check" style="color: limegreen"></i>
                                        @else
                                            <i class="fas fa-times" style="color: red"></i>
                                        @endif
                                    </td> --}}
                                    <td class="actions">
                                        <a class="" href="{{Route('settings.statuses.edit', ['id' => $status->id])}}" title="@Lang('general.action_edit')"><i class="fas fa-edit"></i></a>
                                        {{-- <a href="{{Route('settings.roles.toggle_state', ['id' => $role->id])}}" class="{{ $user->enabled ? 'disable' : 'enable' }}" title="{{$role->enabled ? __('general.action_disable') : __('general.action_enable')}}">
                                            @if($user->enabled)
                                                <i class="fas fa-user-times"></i>
                                            @else
                                                <i class="fas fa-user-check"></i>
                                            @endif
                                        </a> --}}
                                        <a href="{{Route('settings.statuses.delete', ['id' => $status->id])}}" class="delete" title="@Lang('general.action_delete')"><i class="fas fa-trash-alt"></i></a>
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

