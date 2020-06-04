@extends('layouts.app')

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
                                    @Lang('general.daily_reports.report_number')
                                </th>
                                <th>
                                    @Lang('general.status')
                                </th>
                                <th>
                                    @Lang('general.created_by')
                                </th>
                                <th>
                                    @Lang('general.actions')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>
                                        <a href="{{Route('settings.reports.view', ['id' => $user->id])}}">
                                            {{ $report->id }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $report->getCurrentStatus()->first()->name}}
                                    </td>
                                    <td>
                                        {{ $report->getCreator() }}
                                    </td>
                                    <td class="actions">
                                        {{-- <a class="" href="{{$user->id === Auth::user()->id ? Route('settings.users.edit_self') : Route('settings.users.edit', ['id' => $user->id])}}" title="@Lang('general.action_edit')"><i class="fas fa-user-edit"></i></a>
                                        @if($user->id !== Auth::user()->id)
                                            <a href="{{Route('settings.users.toggle_state', ['id' => $user->id])}}" class="{{ $user->enabled ? 'disable' : 'enable' }}" title="{{$user->enabled ? __('general.action_disable') : __('general.action_enable')}}">
                                                @if($user->enabled)
                                                    <i class="fas fa-user-times"></i>
                                                @else
                                                    <i class="fas fa-user-check"></i>
                                                @endif
                                            </a>
                                            @if($user['id'] !== 1)
                                                <a href="{{Route('settings.users.delete', ['id' => $user['id']])}}" class="delete" title="@Lang('general.action_delete')"><i class="fas fa-trash-alt"></i></a>
                                            @endif
                                        @endif --}}
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

