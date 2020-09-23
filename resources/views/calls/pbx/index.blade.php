@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">
                    @Lang('calls.pbx.list')
                    <span class="float-right">
                        <a class="text-success" href="{{Route('calls.pbx.create')}}"><i class="fas fa-plus"></i></a>
                    </span>
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable-pbx" class="object-table table table-sm table-striped" style="width: 100%">
                        <thead class="thead-light">
                            <tr>
                                <th class="actions text-center px-0">
                                    <i class="fas fa-tools text-black sorting_disabled"></i>
                                </th>
                                <th>
                                    @Lang('general.designation')
                                </th>
                                <th>
                                    @Lang('general.url')
                                </th>
                                <th>
                                    @Lang('pbx.api_base_url')
                                </th>
                                <th>
                                    @Lang('general.username')
                                </th>
                                <th>
                                    @Lang('general.delegation')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pbxList as $pbx)
                                <tr>
                                    <td class="actions">
                                        <a class="" href="{{Route('calls.pbx.edit', ['id' => $pbx->id])}}" title="@Lang('general.action_edit')"><i class="fas fa-edit"></i></a>
                                        {{-- <a href="{{Route('settings.roles.toggle_state', ['id' => $role->id])}}" class="{{ $user->enabled ? 'disable' : 'enable' }}" title="{{$role->enabled ? __('general.action_disable') : __('general.action_enable')}}">
                                            @if($user->enabled)
                                                <i class="fas fa-user-times"></i>
                                            @else
                                                <i class="fas fa-user-check"></i>
                                            @endif
                                        </a> --}}
                                        {{-- <a href="{{Route('calls.pbx.delete', ['id' => $role->id])}}" class="delete" title="@Lang('general.action_delete')"><i class="fas fa-trash-alt"></i></a> --}}
                                    </td>
                                    <td>
                                        <a {{--href="{{Route('settings.users.view', ['id' => $user->id])}}" --}}>
                                            {{$pbx->designation}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$pbx->protocol . '://' . $pbx->url . ':' . $pbx->port}}
                                    </td>
                                    <td>
                                        {{ $pbx->api_base_uri }}
                                    </td>
                                    <td class="text-center">
                                        {{ $pbx->username }}
                                    </td>
                                    <td>
                                        {{ $pbx->delegation()->first()->designation }}
                                    </td>
                                    {{-- <td style="text-align: center">
                                        @if($user->enabled)
                                            <i class="fas fa-check" style="color: limegreen"></i>
                                        @else
                                            <i class="fas fa-times" style="color: red"></i>
                                        @endif
                                    </td> --}}
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

