@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">@Lang('general.daily_reports.list')</div>
                <div class="card-body table-responsive">
                    <table id="reports" class="table table-sm table-striped table-bordered" style="width: 100%">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center px-0 actions sorting_disabled">
                                    <i class="fas fa-tools text-black "></i>
                                </th>
                                <th>
                                    @Lang('general.daily_reports.report_number')
                                </th>
                                <th>
                                    @Lang('general.daily_reports.status')
                                </th>
                                <th>
                                    @Lang('general.daily_reports.total_hr')
                                </th>
                                <th>
                                    @Lang('general.daily_reports.total_km')
                                </th>
                                <th>
                                    @Lang('general.team')
                                </th>
                                <th>
                                    @Lang('general.date')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td class="actions text-center">
                                        <a href="{{ route('daily_reports.view', ['id' => $report->id]) }}" class="text-info mr-1 view"><i class="fas fa-eye"></i></a>
                                        {{-- <a href="{{ route('daily_reports.edit', ['id' => $report->id]) }}" class="text-info edit"><i class="fas fa-edit"></i></a> --}}
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
                                    <td>
                                        {{-- <a href="{{Route('daily_reports.view', ['id' => $report->id])}}"> --}}
                                            {{ $report->id }}
                                        {{-- </a> --}}
                                    </td>
                                    <td>
                                        {{ $report->getCurrentStatus()->first()->name }}
                                    </td>
                                    <td>
                                        {{ $helpers->decimalToTimeValue($report->getTotalHours()) }}
                                        {{-- @if($report->getTotalHours() > 1)
                                            @Lang('general.hours')
                                        @else
                                            @Lang('general.hour')
                                        @endif --}}
                                    </td>
                                    <td>
                                        {{ $report->getTotalKm() }} @Lang('general.daily_reports.km')
                                    </td>
                                    <td>
                                        {{ $report->team()->first()->name }}
                                    </td>
                                    <td>
                                        {{ (new DateTime($report->lines()->first()->entry_date))->format('Y-m-d') }}
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

