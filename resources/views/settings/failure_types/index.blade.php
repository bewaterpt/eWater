@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">
                    @Lang('settings.failure_types.list')
                    <span class="float-right">
                        <a href="{{Route('settings.materials.create')}}"><i class="fas fa-plus"></i></a>
                    </span>
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable-users" class="object-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>
                                    @Lang('general.designation')
                                </th>
                                <th>
                                    @Lang('general.materials')
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
                            @foreach($failureTypes ?? '' as $type)
                                <tr>
                                    <td>
                                        {{$type->designation}}
                                    </td>
                                    <td>
                                        @foreach($type->materials()->get() as $material)
                                            {{$material->designation}}
                                        @endforeach
                                    </td>
                                    <td style="text-align: center">
                                        @if($type->enabled)
                                            <i class="fas fa-check" style="color: limegreen"></i>
                                        @else
                                            <i class="fas fa-times" style="color: red"></i>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{Route('settings.failure_types.edit', ['id' => $type->id])}}" title="@Lang('general.action_edit')"><i class="fas fa-edit"></i></a>
                                        <a href="{{Route('settings.failure_types.toggle_state', ['id' => $type->id])}}" class="{{ $type->enabled ? 'disable' : 'enable' }}" title="{{$type->enabled ? __('general.action_disable') : __('general.action_enable')}}">
                                            @if($type->enabled)
                                                <i class="fas fa-times"></i>
                                            @else
                                                <i class="fas fa-check"></i>
                                            @endif
                                        </a>
                                        <a href="{{Route('settings.failure_types.delete', ['id' => $type->id])}}" class="delete" title="{{__('general.action_delete')}}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
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

