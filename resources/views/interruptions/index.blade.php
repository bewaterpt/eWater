@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @Lang('general.interruptions.interruptions')
                    <span class="float-right">
                        <a class="btn-link text-success" href="{{ route('interruptions.create') }}"><i class="fas fa-plus"></i></a>
                    </span>
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable-interruptions" class="table table-sm table-striped table-bordered order-column" style="width: 100%">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center px-3 sorting-disabled">
                                    <i class="fas fa-tools p-0 text-black"></i>
                                </th>
                                <th>
                                    @Lang('general.work_number')
                                </th>
                                <th>
                                    @Lang('general.start_date')
                                </th>
                                <th class="limit-w-45">
                                    @Lang('general.interruptions.affected_area')
                                </th>
                                <th class="limit-w-15">
                                    @Lang('general.interruptions.reinstatement_date')
                                </th>
                                {{-- <th>
                                    @Lang('general.coordinates')
                                </th> --}}
                                <th class="text-center ">
                                    @Lang('general.type')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($interruptions as $interruption)
                                <tr>
                                    <td>
                                        {{-- <a href="{{Route('daily_reports.view', ['id' => $report->id])}}">
                                            {{ $interruption->work_id }}
                                        {{-- </a>
                                    </td>
                                    <td>
                                        {{ $interruption->affected_area }}
                                    </td>
                                    <td>
                                        {{ $interruption->start_date }}
                                    </td>
                                    <td>
                                        {{ $interruption->reinstatement_date }}
                                    </td>
                                    <td>
                                        {{ $interruption->user() }}
                                    </td>
                                    <td class="actions text-center">
                                        <a class="view" href="{{ route('interruptions.view', ['id' => $interruption->id]) }}"><i class="fas fa-eye"></i></a>
                                        @if($pmodel->can('interruptions.edit'))
                                            <a href="{{ route('interruptions.edit', ['id' => $interruption->id]) }}" class="edit"><i class="fas fa-edit"></i></a>
                                        @endif
                                        @if($pmodel->can('interruptions.delete'))
                                            <a href="{{ route('interruptions.delete', ['id' => $interruption->id]) }}" class="delete"><i class="fas fa-trash-alt"></i></a>
                                        @endif
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
                                        @endif
                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

