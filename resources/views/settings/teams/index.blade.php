@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">
                        @Lang('general.teams')
                        <span class="float-right">
                            <a class="text-success" href="{{Route('settings.teams.create')}}"><i class="fas fa-plus"></i></a>
                        </span>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="datatable-teams" class="object-table table table-sm table-bordered table-striped" style="width: 100%">
                            <thead class="thead-light">
                                <tr>
                                    <th class="actions text-center px-0 sorting_disabled">
                                        {{-- @Lang('general.actions') --}}
                                        <i class="fas fa-tools text-black"></i>
                                    </th>
                                    <th>
                                        @Lang('general.team')
                                    </th>
                                    <th>
                                        @Lang('general.user_count')
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teams as $team)
                                    <tr>
                                        <td class="actions">
                                            <a class="" href="{{Route('settings.teams.edit', ['id' => $team->id])}}" title="@Lang('general.action_edit')"><i class="fas fa-edit"></i></a>
                                            <a data-toggle="modal" data-id="{{ $team->id }}" data-target="#modalTeamUsers" href="#" class="btn-link team-users" id="showTeamUsers" title="@Lang('general.action_show_users')"><i class="fas fa-users-cog"></i></a>
                                            <a href="{{Route('settings.teams.delete', ['id' => $team->id])}}" class="delete" title="@Lang('general.action_delete')"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                        <td>
                                            <a {{--href="{{Route('settings.users.view', ['id' => $user->id])}}" --}}>
                                                {{$team->name}}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{ $team->users()->count() }}
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
    @include('settings.teams.modals.modal_team_users')
@endsection

